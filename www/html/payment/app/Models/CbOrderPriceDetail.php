<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbOrderPriceDetail extends Model
{
    use HasFactory;
    protected $table = 'cb_order_price_detail';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';
    
    public function estimateNo(){
        return $this->belongsTo(TsEstimate::class,  'estimate_no', 'estimate_no');
    }
    public function orderNo(){
        return $this->belongsTo(TsOrder::class,  'order_no', 'order_no');
    }
}
