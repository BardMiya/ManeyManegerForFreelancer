<?php

namespace App\Http\Controllers;

use App\Const\AppConst;
use App\Util\AppUtil;
use App\Models\TsOrder;
use App\Models\MtServicer;
use App\Models\MtClient;
use App\Models\MtPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const ROUTE = '/orders';
    const ORDERER = 'orderer_no';
    const DESCRIPTION = 'description';
    const STARTING = 'starting_date';
    const FINiSHING = 'finishing_date';
    const STARTED = 'started_date';
    const FINISHED = 'finished_date';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //ts_order
        $getQry = $request->query();
        $where = [];
        if(array_key_exists(self::ORDERER, $getQry)){
            array_push($where, [self::ORDERER, 'LIKE', '%'.$getQry[self::ORDERER].'%']);
        }
        if(array_key_exists(self::DESCRIPTION, $getQry)){
            array_push($where, [self::DESCRIPTION, 'LIKE', '%'.$getQry[self::DESCRIPTION].'%']);
        }
        if(array_key_exists(self::STARTING, $getQry)) array_push($where, [self::STARTING, '>=', $getQry[self::STARTING]]);
        if(array_key_exists(self::FINiSHING, $getQry)) array_push($where, [self::FINiSHING, '<=', $getQry[self::FINiSHING]]);
        if(array_key_exists(self::STARTED, $getQry)) array_push($where, [self::STARTED, '>=', $getQry[self::STARTED]]);
        if(array_key_exists(self::FINISHED, $getQry)) array_push($where, [self::FINISHED, '>=', $getQry[self::FINISHED]]);

        $records = TsOrder::with([
            'servicer' => function($query){
                $query->select(['personal_cd', 'name', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'orderer' => function($query){
                $query->select(['personal_cd', 'name', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
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
        // insert to ts_order
        //  \DB::enableQueryLog();
        $record = new TsOrder();
        $record->order_no = $request->order_no;
        $record->servicer_cd = $request->servicer_cd;
        $record->orderer_cd = $request->orderer_cd;
        $record->description = $request->description;
        $record->contract_date = $request->contract_date;
        $record->starting_date = $request->starting_date;
        $record->finishing_date = $request->finishing_date;
        $record->started_date = $request->started_date;
        $record->finished_date = $request->finished_date;
        $record->contracte_type = $request->contracte_type;
        $record->valid_flg = 1;
        $record->create_user = AppUtil::who($request);
        $record->update_user = AppUtil::who($request);
        $record->save();
        // dd(\DB::getQueryLog());

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
        // ts_order 唯一抽出
         $records = TsOrder::with([
            'servicer' => function($query){
                $query->select(['personal_cd', 'name', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'orderer' => function($query){
                $query->select(['personal_cd', 'name', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'tsWork' => function($query){
                $query->select(['ts_work.work_cd', 'product_cd', 'description', 'quantity', 'started_date', 'completed_date', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'tsWork.product' => function($query){
                $query->select(['product_cd', 'description', 'regular_price', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'tsDeliver' => function($query){
                $query->select(['ts_deliver.deliver_no', 'destination_name', 'destination_zip', 'destination_address', 'destination_tel', 'way_type', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'tsEstimate' => function($query){
                $query->select(['ts_estimate.estimate_no', 'zip', 'address', 'tel', 'total', 'sub_total', 'tax', 'estimated_date', 'quotation_flg', 'expiration_date', 'quotation_term', 'remark', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'tsDocument' => function($query){
                $query->select(['ts_document.doc_no', 'doc_date', 'description', 'uri', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            },
            'tsInvoice' => function($query){
                $query->select(['ts_invoice.invoice_no', 'order_no', 'invoice_date', 'zip', 'address', 'tel', 'total', 'sub_total', 'tax', 'issuance_date', 'issuance_flg', AppConst::VALID])
                    ->whereNotNull(AppConst::VALID);
            }
        ])->where('order_no', '=', $id)->whereNotNull(AppConst::VALID)->get();

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
        // update ts_order
        $record = TsOrder::where('order_no', '=', $id)
            ->whereNotNull(AppConst::VALID)->first();
        $update = new TsOrder();
        DB::transaction(function() use($request, $record, $update, $id){
            $record->valid_flg = null;
            $record->update_user = AppUtil::who($request);
            $record->save();

            $update->order_no = $id;
            $update->servicer_cd = $request->servicer_cd ?? $record->servicer_cd;
            $update->orderer_cd = $request->orderer_cd ?? $record->orderer_cd;
            $update->description = $request->description ?? $record->description;
            $update->contract_date = $request->contract_date ?? $record->contract_date;
            $update->starting_date = $request->starting_date ?? $record->starting_date;
            $update->finishing_date = $request->finishing_date ?? $record->finishing_date;
            $update->started_date = $request->started_date ?? $record->started_date;
            $update->finished_date = $request->finished_date ?? $record->finished_date;
            $update->contracte_type = $request->contracte_type ?? $record->contracte_type;
            $update->valid_flg = 1;
            $update->create_user = AppUtil::who($request);
            $update->update_user = AppUtil::who($request);
            $update->save();
            return $update;
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
        //Delete from ts_order
        $record = TsOrder::where('order_no', '=', $id)
            ->whereNotNull(AppConst::VALID)->first();
            $record->valid_flg = null;
            $record->update_user = AppUtil::who($request);

            $record->save();
            return redirect(self::ROUTE);
    }
}
