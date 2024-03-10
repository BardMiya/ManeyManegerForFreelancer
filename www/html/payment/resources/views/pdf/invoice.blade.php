<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>請求書：{{sprintf('%09d', $data->invoice_no)}}</title>
    <style>
        @page{
            margin: 15mm 19mm;
        }
        @media print{
            body, h1, h2, h3, h4, h5, h6, p, th, td, caption, li, dt, dd, sub, sup{
                font-size: 10.5pt;
                color: #000;
                font-weight: normal;
                font-family: "游ゴシック体", YuGothic, "游ゴシック", "Yu Gothic", Meiryo, "Hiragino Kaku Gothic ProN";
            }
            sub, sup, span{
                font-size: 9pt;
            }
            h1{
                font-size: 22pt;
                font-weight: bold;
            }
            h2{
                font-size: 18pt;
                width: 115mm;
            }
            h2 sub{
                font-size: 14pt;
            }
            .document-main .main-top th{
                font-size: 12pt;
                padding: 3mm 5mm 3mm 2mm;
            }
            ul{
                list-style-type: none;
                list-style-position: inside;
            }
            .back-blue{
                background-color: #0070C0;
                color: #FFF;
            }
            tr.back-blue th{
                color: #FFF;
            }
            .left{
                text-align: left;
            }
            .center{
                text-align: center;
            }
            .right{
                text-align: right;
            }
            table.table{
                border-collapse: collapse;
            }
            .table td,
            .table th{
                border: 1px solid #000;
                height: 8mm;
            }
            .box{
                border: 1px solid #000;
                padding-left: 3mm;
                padding-right: 3mm;
            }
            .document-title .left-section{
                width: 95mm;
                padding-right: 10mm;
                vertical-align: bottom;
                border-bottom: 1px solid #0070C0;
            }
            .document-title .spacer{
                width: 35mm;
            }
            .document-title .right-section{
                text-align: left;
                vertical-align: top;
            }
            .document-main .left-section{
                width: 85mm;
                vertical-align: middle;
                float: left;
                padding-top: 15mm;
            }
            .document-main .left-section p{
                margin-bottom: 0;
            }
            .document-main .left-section table{
                margin-top: 0;
            }
            .document-main .main-top table th{
                font-weight: bold;
            }
            .document-main .main-top table td{
                font-size: 16pt;
                width: 112pt;
            }
            .document-main .right-seiction{
                width: 87mm;
            }
            .document-main .right-seiction ul{
                font-size: 9pt;
                list-style-type: none;
            }
            .document-main .right-seiction ul li strong{
                font-weight: normal;
                font-size: 12pt;
            }
            #sign{
                position: absolute;
                top: 50mm;
                right: 30mm;
            }
            .document-details table td{
                padding: 2mm 2mm;
            }
            .document-details table th{
                padding: 2mm 2mm;
            }
            .document-details table.detail{
                width: 100%;
            }
            .document-details th span{
                font-size: 9pt;
            }
            .document-details table.detail th{
                padding: 2mm 1.5mm;
            }
            .document-details table.detail td{
                height: 12.1mm;
            }
            .document-details .total-table{
                float: right;
                width: 65mm;
            }
            .document-details .total-table table{
                width: 100%;
            }
            .document-details .total-table th{
                width: 37.5mm;
                font-weight: bold;
            }
            .document-details .tax-table{
                margin-top: 8mm;
            }
            .document-details .tax-table th{
                font-weight: bold;
            }
            .document-remark{
                margin-top: 1.5em;
            }
            .document-remark table{
                width: 100%;
            }
            .document-remark table td{
                height: 15mm;
            }
            .document-remark table th{
                font-weight: bold;
            }
        }
    </style>
    </head>
    <body>
        <h1 class="back-blue center">ご請求書</h1>
        <table class="document-title">
            <tr>
            <td class="left-section">
                <h2>{{ $data->order->orderer->name }}　<span style="font-size: 14pt;">{{$honorific}}</span></h2>
            </td>
            <td class="spacer"></td>
            <td class="right-section">
                <table>
                    <tr><th>発行日</th><td>{{(new DateTime($data->invoice_date))->format('Y/m/d')}}</td></tr>
                    <tr><th>請求番号</th><td>{{sprintf('%09d', $data->invoice_no)}}</td></tr>
                </table>
            </td>
            </tr>
        </table>
        <figure id="sign" class="right"><img src='{{public_path('img/VeAYEjLJAl.png')}}' alt="印影" /></figure>
        <section class="document-main">
                <div class="left-section main-top">
                    <p>下記の通り、ご請求申し上げます。</p>
                    <table class="table">
                        <tr><th class="back-blue">ご請求金額（税込）</th><td class="right">￥{{number_format($data->total)}}-</td></tr>
                    </table>
                </div>
                <div class="right-seiction">
                    <ul>
                        <li>
                            {{$servicer['pro']}} <strong>{{$servicer['name']}}</strong>
                        </li>
                        <li>登録番号　{{$servicer['ivn']}}</li>
                        <li>〒{{$servicer['zip']}}　{{$servicer['address']}}</li>
                        <li>TEL　{{$servicer['tel']}}</li>
                        <li>{{$servicer['email']}}</li>
                        <li class="box">【取引銀行】{!! str_replace("\n", "<br />", $servicer['bnk']) !!}</li>
                    </ul>
                </div>
        </section>
        <section class="document-details">
            <table class="detail table">
                <thead>
                    <tr class="back-blue">
                        <th style="width: 10mm;">No.</th>
                        <th style="width: 14.9mm;">日付</th>
                        <th>内容</th>
                        <th style="width: 12.5mm;">数量</th>
                        <th style="width: 12.5mm;">単位</th>
                        <th style="width: 21.7mm;">単価<span>(税抜)</span></th>
                        <th style="width: 12.5mm;">税率</th>
                        <th style="width: 23.3mm;">金額<span>(税抜)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 8; $i++)
                        <tr>
                            @php
                                $detail = $data->details->get($i);
                            @endphp
                            <td class="right">{{$detail == null ? "" : $detail->detail_no}}</td>
                            <td class="center">{{$detail == null ? "" : (new DateTime($detail->work->completed_date))->format('m/d')}}</td>
                            <td>{{$detail == null ? "" : $detail->work->description}}</td>
                            <td class="right">{{$detail == null ? "" : $detail->work->quantity}}</td>
                            <td>{{$detail == null ? "" : $detail->work->product->quantifier}}</td>
                            <td class="right">{{$detail == null ? "" : '￥'.number_format($detail->unit_price)}}</td>
                            <td class="right">{{$detail == null ? "" : ($taxRate[$detail->tax_type] * 100).'%'}}</td>
                            <td class="right">{{$detail == null ? "" : '￥'.number_format($detail->price)}}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            <div class="total-table">
                <table class="table">
                    <tbody>
                        <tr>
                            <th class="back-blue left">小計</th>
                            <td class="right">￥{{number_format($data->sub_total)}}</td>
                        </tr>
                        <tr>
                            <th class="back-blue left">消費税</th>
                            <td class="right">￥{{number_format($data->tax)}}</td>
                        </tr>
                        <tr>
                            <th class="back-blue left">合計</th>
                            <td class="right">￥{{number_format($data->total)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tax-table">
                @php
                    $tax10 = $data->taxDetails->filter(function($v, $k){
                        return $v->type == 1;
                    })->push(['tax' => 0, 'taxation_summary' => 0])->first();
                    $tax8 = $data->taxDetails->filter(function($v, $k){
                        return $v->type == 2;
                    })->push(['tax' => 0, 'taxation_summary' => 0])->first();
                @endphp
                <table class="table">
                    <thead>
                        <tr class="back-blue">
                            <th style="width: 24.9mm;" class="center">税率区分</th>
                            <th style="width: 28.6mm;" class="center">消費税</th>
                            <th style="width: 36.4mm;" class="center">金額<span>(税抜)</span></th>
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <td>10%対象</td><td class="right">￥{{number_format($tax10['tax'])}}</td><td class="right">￥{{number_format($tax10['taxation_summary'])}}</td>
                        </tr>
                        <tr>
                            <td>8%対象</td><td class="right">￥{{number_format($tax8['tax'])}}</td><td class="right">￥{{number_format($tax8['taxation_summary'])}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="document-remark">
            <table class="table">
                <thead>
                    <tr><th class="back-blue left">備考</th></tr>
                </thead>
                <tbody>
                    <tr><td>{{$data->remark}}</td></tr>
                </tbody>
            </table>
        </section>
    </body>
</html>