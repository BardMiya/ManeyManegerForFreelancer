<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TsTransaction;
use App\Models\TsTransactionDetail;
use App\Const\AppConst;
use App\Util\AppUtil;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    const ROUTE = '/api/transactions';

    private const NO = 'transaction_no';
    private const DATE = 'transaction_date';
    private const DATE_FROM = 'dateFrom';
    private const DATE_TO = 'dateTo';
    private const NO_FROM = 'transactionNoFrom';
    private const NO_TO = 'transactionNoTo';
    private const ACCOUNT_CD = 'account_CD';
    private const WHO = 'transactioner';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getQry = $request->query();
        $where = [];
        $detailHas = function($query){ $query; };
        if(array_key_exists(self::NO_FROM, $getQry)){
            array_push($where, [self::NO, '>=', $getQry[self::NO_FROM]]);
        }
        if(array_key_exists(self::NO_TO, $getQry)){
            array_push($where, [self::NO, '<=', $getQry[self::NO_TO]]);
        }
        if(array_key_exists(self::DATE_FROM, $getQry)){
            array_push($where, [self::DATE, '>=', $getQry[self::DATE_FROM]]);
        }
        if(array_key_exists(self::DATE_TO, $getQry)){
            array_push($where, [self::DATE, '<=', $getQry[self::DATE_TO]]);
        }
        if(array_key_exists(self::WHO, $getQry)){
            array_push($where, [self::WHO, '=', $getQry[self::WHO]]);
        }
        if(array_key_exists(self::ACCOUNT_CD, $getQry)){
            $detailHas = function($query) use($getQry){ 
                $query->where(self::ACCOUNT_CD, $getQry[self::ACCOUNT_CD])
                    ->whereNotNull(AppConst::VALID);
            };
        }
        // ts_transaction
        $records = TsTransaction::with([
            'details' => function($query){
                $query->select([
                    'transaction_no',
                    'detail_no',
                    'type',
                    'price',
                    'account_CD',
                    'paper_no',
                    AppConst::VALID
                ])->whereNotNull(AppConst::VALID);
            },
            'details.account' => function($query){
                $query->select([
                    'account_cd',
                    'name',
                    'type',
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
        ])->where($where)
        ->whereHas('details',$detailHas)
        ->whereNotNull(AppConst::VALID)
        ->orderBy('transaction_date')
        ->get();

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
        // insert to ts_transaction
         DB::transaction(function() use($request){
            // 取引No取得
            $transaction_no = (DB::table('ts_transaction')->max('transaction_no')) + 1;

            $record = new TsTransaction();
            $record->transaction_no = $transaction_no;
            $record->transactioner = $request->transactioner;
            $record->transaction_date = $request->transaction_date;
            $record->remark = $request->remark;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();

            $i = 1;
            foreach($request->details as $detail){
                $transactionDetail = new TsTransactionDetail();
                $transactionDetail->transaction_no = $transaction_no;
                $transactionDetail->detail_no = $i;
                $transactionDetail->type = $detail['type'];
                $transactionDetail->price = $detail['price'];
                $transactionDetail->account_CD = $detail['account_CD'];
                $transactionDetail->paper_no = $detail['paper_no'];
                $transactionDetail->valid_flg = 1;
                $transactionDetail->create_user = AppUtil::who($request);
                $transactionDetail->update_user = AppUtil::who($request);
                $transactionDetail->save();
                $i++;
            }
        });
        return response()->json(
            [], 200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // ts_transaction
        $records = TsTransaction::with([
            'details' => function($query){
                $query->select([
                    'transaction_no',
                    'detail_no',
                    'type',
                    'price',
                    'account_CD',
                    'paper_no',
                    AppConst::VALID])
                ->whereNotNull(AppConst::VALID);
            },
            'details.account' => function($query){
                $query->select([
                    'account_cd',
                    'name',
                    'type',
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
        ])->where('transaction_no', '=', $id)->whereNotNull(AppConst::VALID)->get();

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
         // update to ts_transaction
         DB::transaction(function() use($request, $id){
            TsTransaction::where('transaction_no', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                'update_user' => AppUtil::who($request)
            ]);
            

            if(count($request->details ?? []) > 0){
                TsTransactionDetail::where('transaction_no', '=', $id)
                ->whereNotNull(AppConst::VALID)
                ->update([
                    AppConst::VALID => null,
                    'update_user' => AppUtil::who($request)
                ]);
            }

            $record = new TsTransaction();
            $record->transaction_no = $id;
            $record->transactioner = $request->transactioner;
            $record->transaction_date = $request->transaction_date;
            $record->remark = $request->remark;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();

            $i = 1;
            foreach($request->details ?? [] as $detail){
                $transactionDetail = new TsTransactionDetail();
                $transactionDetail->transaction_no = $id;
                $transactionDetail->detail_no = $i;
                $transactionDetail->type = $detail['type'];
                $transactionDetail->price = $detail['price'];
                $transactionDetail->account_CD = $detail['account_CD'];
                $transactionDetail->paper_no = $detail['paper_no'];
                $transactionDetail->valid_flg = 1;
                $transactionDetail->create_user = AppUtil::who($request);
                $transactionDetail->update_user = AppUtil::who($request);
                $transactionDetail->save();
                $i++;
            }
        });
        return response()->json(
            [], 200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
         // update to ts_transaction
         DB::transaction(function() use($request, $id){
            TsTransaction::where('transaction_no', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                'update_user' => AppUtil::who($request)
            ]);
            
            TsTransactionDetail::where('transaction_no', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->update([
                AppConst::VALID => null,
                'update_user' => AppUtil::who($request)
            ]);
        });
        return response()->json(
            [], 200
        );
    }
}
