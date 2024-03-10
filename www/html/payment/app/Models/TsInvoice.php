<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsInvoice extends Model
{
    use HasFactory;
    protected $table = 'ts_invoice';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function details(){
        return $this->belongsToMany(TsWorkPrice::class, 'cb_invoice_detail', 'invoice_no', 'work_price_no', 'invoice_no', 'work_price_no');
    }
    public function taxDetails(){
        return $this->hasMany(TsTaxDetail::class, 'invoice_no', 'invoice_no');
    }
    public function order(){
        return $this->belongsTo(TsOrder::class, 'order_no', 'order_no');
    }
}
