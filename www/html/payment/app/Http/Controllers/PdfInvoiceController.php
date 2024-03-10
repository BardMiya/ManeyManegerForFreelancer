<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TsInvoice;
use App\Models\CbInvoiceDetail;
use App\Models\TsWorkPrice;
use App\Models\TsTaxDetail;
use App\Const\AppConst;
use App\Util\AppUtil;
use PDF;

class PdfInvoiceController extends Controller
{
    public function viewPdf($no)
    {
        $data = TsInvoice::with([
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
            'details.work' => function($query){
                $query->select([
                    'work_cd',
                    'description',
                    'product_cd',
                    'quantity',
                    'started_date',
                    'completed_date'
                ])->whereNotNull('ts_work.'.AppConst::VALID);
            },
            'details.work.product' => function($query){
                $query->select([
                    'product_cd',
                    'quantifier',
                ])->whereNotNull('mt_product.'.AppConst::VALID);
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
                    'servicer_cd'
                ])->whereNotNull('ts_order.'.AppConst::VALID);
            },
            'order.orderer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                ])->whereNotNull('mt_client.'.AppConst::VALID);
            },
            'order.orderer.mtPersonal.personalInfo' => function($query){
                $query->select([
                    'mt_personal_info.personal_data_cd',
                    'type_cd',
                    'personal_data',
                ])->where('type_cd', '=', 'PTY')->whereNotNull('mt_personal_info.'.AppConst::VALID);
            },
            'order.servicer' => function($query){
                $query->select([
                    'personal_cd',
                    'name',
                    'email',
                    'zip',
                    'address',
                    'tel'
                ])->whereNotNull('mt_servicer.'.AppConst::VALID);
            },
            'order.servicer.mtPersonal.personalInfo' => function($query){
                $query->select([
                    'mt_personal_info.personal_data_cd',
                    'type_cd',
                    'personal_data',
                ])->whereNotNull('mt_personal_info.'.AppConst::VALID);
            }
        ])->where('invoice_no','=',$no)->whereNotNull(AppConst::VALID)->first();

        /*
        * PDF生成
        */
        // パラメータ成型
        $servicer = $data->order->servicer;
        $params = [
            'data' => $data,
            'honorific' => $data->order->orderer
                            ->mtPersonal->personalInfo->first()
                            ->personal_data == '1' ? '様' : '御中',
            'servicer' => [
                'name' => $servicer->name,
                'email' => $servicer->email,
                'zip' => $servicer->zip,
                'address' => $servicer->address,
                'tel' => $servicer->tel,
                'pro' => $servicer->mtPersonal->personalInfo->where('type_cd', '=', 'PRO')->first()->personal_data,
                'bnk' => $servicer->mtPersonal->personalInfo->where('type_cd', '=', 'BNK')->first()->personal_data,
                'ivn' => $servicer->mtPersonal->personalInfo->where('type_cd', '=', 'IVN')->first()->personal_data,
            ],
            'taxRate' => AppConst::$taxRate
        ];
        $pdf = PDF::loadView('pdf.invoice', $params);
        $datestring = date('YmdHis');
        $orderer = $data->order->orderer_cd;
        return $pdf->stream("{$datestring}_{$orderer}_invoice.pdf"); 
    }
}
