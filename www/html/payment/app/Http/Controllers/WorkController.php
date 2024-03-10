<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TsWork;
use App\Models\TsWorkPrice;
use App\Const\AppConst;
use App\Util\AppUtil;
use Illuminate\Support\Facades\DB;

class WorkController extends Controller
{
    const ROUTE = '/works';

    private const DESCRIPTION = 'description';
    private const DATE_FROM = 'dateFrom';
    private const DATE_TO = 'dateTo';
    private const NO_FROM = 'transactionNoFrom';
    private const NO_TO = 'transactionNoTo';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 抽出条件
        $getQry = $request->query();
        $where = [];
        if(array_key_exists(self::DESCRIPTION, $getQry)){
            array_push($where, [self::DESCRIPTION, 'LIKE', '%'.$getQry[self::DESCRIPTION].'%']);
        }

        // ts_work
        $records = TsWork::with([
            'workPrice' => function($query){
                $query->select([
                    'work_price_no',
                    'work_cd',
                    'detail_no',
                    'unit_price',
                    'price',
                    'tax_type',
                    'tax_amount',                 
                    AppConst::VALID
                ])->whereNotNull(AppConst::VALID);
            },
            'personal' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                    AppConst::VALID
                ])->whereNotNull(AppConst::VALID);
            }
        ])->where($where)->whereNotNull(AppConst::VALID)->get();

        return response()->json(
            $records, 200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // insert to ts_work
        // 明細情報もINSERT
        DB::transaction(function() use($request){
            $workPriceNo = (DB::table('ts_work_price')->max('work_price_no'));

            $record = new TsWork();
            $record->work_cd = $request->work_cd;
            $record->assignee = $request->assignee;
            $record->description = $request->description;
            $record->product_cd = $request->product_cd;
            $record->quantity = $request->quantity;
            $record->started_date = $request->started_date;
            $record->completed_date = $request->completed_date;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();

            $i = 1;
            foreach($request->details as $detail){
                // 小計、消費税額算出
                $prices = $this->calcuratPrice($request->quantity, $detail['unit_price'], $detail['tax_type']);

                $price = new TsWorkPrice();
                $price->work_price_no = $workPriceNo + $i;
                $price->work_cd = $request->work_cd;
                $price->detail_no = $i;
                $price->unit_price = $detail['unit_price'];
                $price->description = $detail['description'];
                $price->price = $prices[0];
                $price->tax_type = $detail['tax_type'];
                $price->tax_amount = $prices[1];
                $price->valid_flg = 1;
                $price->create_user = AppUtil::who($request);
                $price->update_user = AppUtil::who($request);
                $price->save();
                $i++;
            }
        });
        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // ts_work
        $records = TsWork::with([
            'workPrice' => function($query){
                $query->select([
                    'work_price_no',
                    'work_cd',
                    'detail_no',
                    'unit_price',
                    'price',
                    'tax_type',
                    'tax_amount',                 
                    AppConst::VALID])
                ->whereNotNull(AppConst::VALID);
            },
            'personal' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                    AppConst::VALID
                ])->whereNotNull(AppConst::VALID);
            }
        ])->where('work_cd', '=', $id)->whereNotNull(AppConst::VALID)->first();

        return response()->json(
            $records, 200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // update to ts_work
        // 明細は数量に応じた価格の再計算のみ
        DB::transaction(function() use($request, $id){
            TsWork::where('work_cd', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                'update_user' => AppUtil::who($request)
            ]);
    
            $record = new TsWork();
            $record->work_cd = $id;
            $record->assignee = $request->assignee;
            $record->description = $request->description;
            $record->product_cd = $request->product_cd;
            $record->quantity = $request->quantity;
            $record->started_date = $request->started_date;
            $record->completed_date = $request->completed_date;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();

            TsWorkPrice::where('work_cd', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                'price' =>  DB::raw('unit_price * '.$request->quantity),
                'tax_amount' =>  DB::raw('unit_price * '.$request->quantity.' * '.AppConst::$taxRate['RAW']),
                'update_user' => AppUtil::who($request)
            ]);
            
        });
        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Workを無効化
        TsWork::where('work_cd', '=', $id)
        ->whereNotNull(AppConst::VALID)
        ->update([
            AppConst::VALID => null,
            'update_user' => AppUtil::who($request)
        ]);
    }
    /**
     * Insert the price detail record
     * @param Request $request
     * @param string $work
     * @return \Illuminate\Http\Response
     */
    public function addDetail(Request $request, $work){
        DB::transaction(function() use($request){
            $workPriceNo = (DB::table('ts_work_price')->max('work_price_no'));
            $detailNo = TsWorkPrice::where('work_cd', '=', $work)
            ->whereNotNull(AppConst::VALID)
            ->max('detail_no');

            $work = TsWork::where('work_cd', '=', $work)
            ->whereNotNull(AppConst::VALID)
            ->select('quantity')->first();
           
            $prices = $this->calcuratPrice($work->quantity, $request->unit_price, $request->tax_type);

            $price = new TsWorkPrice();
            $price->work_price_no = ++$workPriceNo;
            $price->work_cd = $work;
            $price->detail_no = ++$detailNo;
            $price->unit_price = $request->unit_price;
            $price->description = $request->description;
            $price->price = $prices[0];
            $price->tax_type = $request->tax_type;
            $price->tax_amount = $prices[1];
            $price->valid_flg = 1;
            $price->create_user = AppUtil::who($request);
            $price->update_user = AppUtil::who($request);
            $price->save();
        });
    }
    /**
     * Update the price detail record
     * @param Request $request
     * @param string $work
     * @param int $priceNo
     * @return \Illuminate\Http\Response
     */
    public function updateDetail(Request $request, $work, $priceNo){
        DB::transaction(function() use($request, $id){
            TsWorkPrice::where([
                ['work_cd', '=', $work],
                ['work_price_no', '=', $priceNo]
            ])
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                'update_user' => AppUtil::who($request)
            ]);
           

            $work = TsWork::where('work_cd', '=', $work)
            ->whereNotNull(AppConst::VALID)
            ->select('quantity')->first();
           
            $prices = $this->calcuratPrice($work->quantity, $request->unit_price, $request->tax_type);

            $price = new TsWorkPrice();
            $price->work_price_no = $priceNo;
            $price->work_cd = $work;
            $price->detail_no = $request->detail_no;
            $price->unit_price = $request->unit_price;
            $price->description = $request->description;
            $price->price = $prices[0];
            $price->tax_type = $request->tax_type;
            $price->tax_amount = $prices[1];
            $price->valid_flg = 1;
            $price->create_user = AppUtil::who($request);
            $price->update_user = AppUtil::who($request);
            $price->save();
           
        });
    }
    /**
    * Invalid the price detail record
    * @param Request $request
    * @param string $work
    * @param int $priceNo
    * @return \Illuminate\Http\Response
    */
   public function deleteDetail(Request $request, $work, $priceNo){
        TsWorkPrice::where([
            ['work_cd', '=', $work],
            ['work_price_no', '=', $priceNo],
        ])->whereNotNull(AppConst::VALID)
         ->update([
            AppConst::VALID => null,
            'update_user' => AppUtil::who($request)
        ]);
    }
    /**
     * 小計、消費税計算
     */
    private function calcuratPrice($quantity, $unitPrice, $taxType)
    {
        return array(
            $unitPrice * $quantity, 
            $unitPrice * $quantity * AppConst::$taxRate[$taxType]
        );
    }
}
