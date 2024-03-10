<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TsEstimate;
use App\Models\CbEstimateDetail;
use App\Models\TsWorkPrice;
use App\Const\AppConst;
use App\Util\AppUtil;
use Illuminate\Support\Facades\DB;

class EstimateController extends Controller
{
    const ROUTE = '/estimate';
    const DATE_FROM = 'dateFrom';
    const DATE_TO = 'dateTo';
    const DATE = 'estimated_date';
    const CLIENT = 'orderer_cd';
    const SERVICER = 'servicer_cd';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getQry = $request->query();
        $where = [];
        if(array_key_exists(self::SERVICER, $getQry)){
            array_push($where, [self::SERVICER, '=', $getQry[self::SERVICER]]);
        }
        if(array_key_exists(self::CLIENT, $getQry)){
            array_push($where, [self::CLIENT, '=', $getQry[self::CLIENT]]);
        }
        if(array_key_exists(self::DATE_FROM, $getQry)){
            array_push($where, [self::DATE, '>=', $getQry[self::DATE_FROM]]);
        }
        if(array_key_exists(self::DATE_TO, $getQry)){
            array_push($where, [self::DATE, '<=', $getQry[self::DATE_TO]]);
        }
        // ts_estimate
        $records = TsEstimate::with([
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
                ->whereNotNull('cb_estimate_detail.'.AppConst::VALID);
            },
            'details.work' => function($query){
                $query->select([
                    'work_cd',
                    'quantity',
                    'ts_work.'.AppConst::VALID])
                ->whereNotNull('ts_work.'.AppConst::VALID);
            },
            'order' => function($query){
                $query->select([
                    'ts_order.order_no',
                    'description',
                    'ts_order.'.AppConst::VALID
                ])->whereNotNull('ts_order.'.AppConst::VALID);
            },
            'orderer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_client.'.AppConst::VALID);
            },
            'servicer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_servicer.'.AppConst::VALID);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // ts_estimate
        $records = TsEstimate::with([
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
                ->whereNotNull('cb_estimate_detail.'.AppConst::VALID);
            },
            'details.work' => function($query){
                $query->select([
                    'work_cd',
                    'quantity',
                    'ts_work.'.AppConst::VALID])
                ->whereNotNull('ts_work.'.AppConst::VALID);
            },
            'order' => function($query){
                $query->select([
                    'ts_order.order_no',
                    'description',
                    'ts_order.'.AppConst::VALID
                ])->whereNotNull('ts_order.'.AppConst::VALID);
            },
            'orderer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_client.'.AppConst::VALID);
            },
            'servicer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_servicer.'.AppConst::VALID);
            }
        ])->where('estimate_no', '=', $id)->whereNotNull(AppConst::VALID)->get();

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
