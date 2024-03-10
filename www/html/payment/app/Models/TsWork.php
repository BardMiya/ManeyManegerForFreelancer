<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsWork extends Model
{
    use HasFactory;
    protected $table = 'ts_work';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function orderDetail()
    {
        return $this->belongsToMany(TsOrderDetail::class, 'ts_order_detail', 'work_cd', 'order_no', 'work_cd', 'order_no');
    }
    public function product()
    {
        return $this->belongsTo(MtProduct::class, 'product_cd', 'product_cd');
    }
    public function workPrice()
    {
        return $this->hasMany(TsWorkPrice::class, 'work_cd', 'work_cd');
    }
    public function personal()
    {
        return $this->belongsTo(MtServicer::class, 'assignee', 'personal_cd');
    }
}
