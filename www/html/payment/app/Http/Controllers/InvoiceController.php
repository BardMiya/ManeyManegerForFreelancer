<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TsInvoice;
use App\Models\CbInvoiceDetail;
use App\Models\TsWorkPrice;
use App\Models\TsTaxDetail;
use App\Const\AppConst;
use App\Util\AppUtil;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    const ROUTE = '/invoices';
    const ORDER = 'order';
    const DATE_FROM = 'dateFrom';
    const DATE_TO = 'dateTo';
    const ORDER_CD = 'order_no';
    const DATE = 'invoice_date';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getQry = $request->query();
        $where = [];
        if(array_key_exists(self::ORDER, $getQry)){
            array_push($where, [self::ORDER_CD, '=', $getQry[self::ORDER]]);
        }
        if(array_key_exists(self::DATE_FROM, $getQry)){
            array_push($where, [self::DATE, '>=', $getQry[self::DATE_FROM]]);
        }
        if(array_key_exists(self::DATE_TO, $getQry)){
            array_push($where, [self::DATE, '<=', $getQry[self::DATE_TO]]);
        }
        // ts_invoice
        $records = TsInvoice::with([
            'details' => function($query){
                $query->select([
                    'ts_work_price.work_price_no',
                    'work_cd',
                    'detail_no',
                    'unit_price',
                    'price',
                    'tax_type',
                    'tax_amount',
                    'ts_work_price.'.AppConst::VALID])
                ->whereNotNull('ts_work_price.'.AppConst::VALID)
                ->whereNotNull('cb_invoice_detail.'.AppConst::VALID);
            },
            'taxDetails' => function($query){
                $query->select([
                    'invoice_no',
                    'type',
                    'tax',
                    'taxation_summary'
                ]);
            },
            'order' => function($query){
                $query->select([
                    'order_no',
                    'orderer_cd',
                ])->whereNotNull('ts_order.'.AppConst::VALID);
            },
            'order.orderer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_client.'.AppConst::VALID);
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
        // tax_detail, cb_invoice_detailを同時登録
        DB::transaction(function() use($request){
            
            // 新規invoice_noを取得
            $invoice_no = (DB::table('ts_invoice')->max('invoice_no')) + 1;

            // // ts_work_priceからtax_type（消費税率）ごとの合計金額算出
            // $workPrice = TsWorkPrice::whereIn('work_price_no', $request->work_price_no)
            //                 ->select('tax_type')
            //                 ->selectRaw("SUM(tax_amount) as 'tax'")
            //                 ->selectRaw("SUM(price) as 'price'")
            //                 ->groupBy('tax_type')
            //                 ->orderBy('tax_type')
            //                 ->get();
            // // 請求金額の小計
            // $subTotal = $workPrice->map(fn($rec) => $rec->price )->sum();
            // // 請求金額の消費税
            // $taxTotal = $workPrice->map(fn($rec) => $rec->tax)->sum();
            // // 請求金額の総計
            // $total = $subTotal + $taxTotal;

            $workPrice = $this->getPrices($request->work_price_no, $subTotal, $taxTotal,  $total);
            //ts_invoice
            $record = new TsInvoice();
            $record->invoice_no = $invoice_no;
            $record->order_no = $request->order_no;
            $record->invoice_date = $request->invoice_date;
            $record->zip = $request->zip;
            $record->address = $request->address;
            $record->tel = $request->tel;
            $record->total = $total;
            $record->sub_total = $subTotal;
            $record->tax = $taxTotal;
            $record->issuance_date = $request->issuance_date;
            $record->issuance_flg = $request->issuance_flg;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();

            // cb_invoice_detail
            foreach($request->work_price_no as $workPriceNo){
                $relation = new CbInvoiceDetail();
                $relation->invoice_no = $invoice_no;
                $relation->work_price_no = $workPriceNo;
                $relation->valid_flg = 1;
                $relation->create_user = AppUtil::who($request);
                $relation->update_user = AppUtil::who($request);
                $relation->save();
            }

            // ts_tax_detail
            foreach($workPrice as $taxSummary){
                $taxDetail = new TsTaxDetail();
                $taxDetail->invoice_no = $invoice_no;
                $taxDetail->type = $taxSummary->tax_type;
                $taxDetail->tax = $taxSummary->tax;
                $taxDetail->taxation_summary = $taxSummary->price;
                $taxDetail->create_user = AppUtil::who($request);
                $taxDetail->update_user = AppUtil::who($request);
                $taxDetail->save();
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
        //
        $records = TsInvoice::with([
            'details' => function($query){
                $query->select([
                    'ts_work_price.work_price_no',
                    'work_cd',
                    'detail_no',
                    'unit_price',
                    'price',
                    'tax_type',
                    'tax_amount',
                    'ts_work_price.'.AppConst::VALID
                 ])->whereNotNull('ts_work_price.'.AppConst::VALID)
                    ->whereNotNull('cb_invoice_detail.'.AppConst::VALID);
            },
            'taxDetails' => function($query){
                $query->select([
                    'invoice_no',
                    'type',
                    'tax',
                    'taxation_summary'
                ]);
            },
            'order' => function($query){
                $query->select([
                    'order_no',
                    'orderer_cd',
                ])->whereNotNull('ts_order.'.AppConst::VALID);
            },
            'order.orderer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_client.'.AppConst::VALID);
            }
        ])->where('invoice_no','=',$id)->whereNotNull(AppConst::VALID)->get();

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
        //アップデートはts_invoiceの更新のみ
        DB::transaction(function() use($request, $id){
            // 明細、各種金額の取得
            $workPrice = $this->getPrices($request->work_price_no, $subTotal, $taxTotal,  $total);

            // update ts_invoice 論理削除
            $update = TsInvoice::where('invoice_no', '=', $id)
            ->whereNotNull(AppConst::VALID)->first();
            $update->valid_flg = null;
            $update->update_user = AppUtil::who($request);
            $update->save();

            // cb_invoice_detail 論理削除
            CbInvoiceDetail::where('invoice_no', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                AppConst::UPDATE_USER => AppUtil::who($request)
            ]);
            
            // ts_tax_detail 物理削除
            TsTaxDetail::where('invoice_no', '=', $id)->delete();

            //ts_invoice
            $record = new TsInvoice();
            $record->invoice_no = $id;
            $record->order_no = $request->order_no;
            $record->invoice_date = $request->invoice_date;
            $record->zip = $request->zip;
            $record->address = $request->address;
            $record->tel = $request->tel;
            $record->total = $total;
            $record->sub_total = $subTotal;
            $record->tax = $taxTotal;
            $record->issuance_date = $request->issuance_date;
            $record->issuance_flg = $request->issuance_flg;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();

            // cb_invoice_detail
            foreach($request->work_price_no as $workPriceNo){
                $relation = new CbInvoiceDetail();
                $relation->invoice_no = $id;
                $relation->work_price_no = $workPriceNo;
                $relation->valid_flg = 1;
                $relation->create_user = AppUtil::who($request);
                $relation->update_user = AppUtil::who($request);
                $relation->save();
            }

            // ts_tax_detail
            foreach($workPrice as $taxSummary){
                $taxDetail = new TsTaxDetail();
                $taxDetail->invoice_no = $id;
                $taxDetail->type = $taxSummary->tax_type;
                $taxDetail->tax = $taxSummary->tax;
                $taxDetail->taxation_summary = $taxSummary->price;
                $taxDetail->create_user = AppUtil::who($request);
                $taxDetail->update_user = AppUtil::who($request);
                $taxDetail->save();
            }
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
        // 請求情報削除
        //アップデートはts_invoiceの更新のみ
        DB::transaction(function() use($request, $id){
            TsInvoice::where('invoice_no', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                AppConst::UPDATE_USER => AppUtil::who($request)
            ]);

            // cb_invoice_detail 物理削除
            CbInvoiceDetail::where('invoice_no', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                AppConst::UPDATE_USER => AppUtil::who($request)
            ]);
            
            // ts_tax_detail 物理削除
            TsTaxDetail::where('invoice_no', '=', $id)->delete();
        });
    }
    /**
     * ts_work、小計、総計、消費税合計の取得
     */
    private function getPrices($workPriceNumbers, &$subTotal, &$taxTotal, &$total){
        // ts_work_priceからtax_type（消費税率）ごとの合計金額算出
        $workPrice = TsWorkPrice::whereIn('work_price_no', $workPriceNumbers)
                        ->select('tax_type')
                        ->selectRaw("SUM(tax_amount) as 'tax'")
                        ->selectRaw("SUM(price) as 'price'")
                        ->groupBy('tax_type')
                        ->orderBy('tax_type')
                        ->get();
        // 請求金額の小計
        $subTotal = $workPrice->map(fn($rec) => $rec->price )->sum();
        // 請求金額の消費税
        $taxTotal = $workPrice->map(fn($rec) => $rec->tax)->sum();
        // 請求金額の総計
        $total = $subTotal + $taxTotal;

        return $workPrice;
    }
}
