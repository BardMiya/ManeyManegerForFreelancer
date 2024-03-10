<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsTransaction extends Model
{
    use HasFactory;
    protected $table = 'ts_transaction';
    protected $primaryKey = 'transaction_no';

    public $incrementing = false;

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function details(){
        return $this->hasMany(TsTransactionDetail::class, 'transaction_no', 'transaction_no');
    }
    public function personal(){
        return $this->belongsTo(MtServicer::class, 'transactioner', 'personal_cd');
    }
}
