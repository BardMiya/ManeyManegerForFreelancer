<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsOrder extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ts_order';
    // protected $primaryKey = ['order_no', 'servicer_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function servicer(){
        return $this->belongsTo(MtServicer::class, 'servicer_cd', 'personal_cd');
    }
    public function orderer(){
        return $this->belongsTo(MtClient::class, 'orderer_cd', 'personal_cd');
    }
    public function tsWork(){
        return $this->belongsToMany(TsWork::class, 'ts_order_detail', 'order_no', 'work_cd', 'order_no', 'work_cd');
    }
    public function tsDeliver(){
        return $this->belongsToMany(TsDeliver::class, 'cb_order_deliver', 'order_no', 'deliver_no', 'order_no', 'deliver_no');
    }
    public function tsEstimate(){
        return $this->belongsToMany(TsEstimate::class, 'cb_order_price_detail', 'order_no', 'estimate_no', 'order_no', 'estimate_no');
    }
    public function tsDocument(){
        return $this->belongsToMany(TsDocument::class, 'cb_order_document', 'order_no', 'doc_no', 'order_no', 'doc_no');
    }
    public function tsInvoice(){
        return $this->hasMany(TsInvoice::class, 'order_no', 'order_no');
    }
}
