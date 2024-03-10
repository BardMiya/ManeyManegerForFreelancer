<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'ts_order_detail';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function work(){
        return $this->belongsTo(TsWork::class, 'work_cd', 'work_cd');
    }
    public function order(){
        return $this->belongTo(TsOrder::class, 'order_no', 'order_no');
    }
}
