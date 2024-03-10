<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsEstimate extends Model
{
    use HasFactory;
    protected $table = 'ts_estimate';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    // public $incrementing = false;

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function order(){
        return $this->belongsToMany(TsOrder::class, 'cb_order_price_detail', 'estimate_no', 'order_no', 'estimate_no', 'order_no');
    }
    public function details(){
        return $this->belongsToMany(TsWorkPrice::class, 'cb_estimate_detail', 'estimate_no', 'estimate_no');
    }
    public function orderer(){
        return $this->belongsTo(MtClient::class, 'orderer_cd', 'personal_cd');
    }
    public function servicer(){
        return $this->belongsTo(MtServicer::class, 'servicer_cd', 'personal_cd');
    }
}
