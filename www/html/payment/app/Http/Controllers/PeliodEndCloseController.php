<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TsTransaction;
use App\Models\TsTransactionDetail;
use App\Const\AppConst;
use App\Util\AppUtil;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\PeliodEndCloseRepository;
use App\Http\Services\PeliodEndCloseService;

class PeliodEndCloseController extends Controller
{
    /**
     * peliod end closing
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function periodClose($year, $servicer)
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
     * Calculation of profit and loss statement
     *
     * @param  int  $year
     * @param  string  $servicer
     * @return \Illuminate\Http\Response
     */
    public function statement($year, $servicer = null)
    {
        // ts_transaction
        $repository = new PeliodEndCloseRepository();
        $detailResult = $repository->statement($year, $servicer);
        $balanceResult = $repository->balance($year, $servicer);
        $service = new PeliodEndCloseService();
        $response = $service->statement($detailResult, $balanceResult);
        return response()->json(
            $response, 200
        );
    }
    /**
     * Calculation of Balance sheeet
     *
     * @param  int  $year
     * @param  string  $servicer
     * @return \Illuminate\Http\Response
     */
    public function balance($year, $servicer = null)
    {
        // ts_transaction
        $repository = new PeliodEndCloseRepository();
        $balanceResult = $repository->balance($year, $servicer);
        $service = new PeliodEndCloseService();
        $response = $service->balance($balanceResult);
        return response()->json(
            $response, 200
        );
    }
}
