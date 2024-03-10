<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsTransactionDetail extends Model
{
    use HasFactory;
    protected $table = 'ts_transaction_detail';
    protected $primaryKey = ['transaction_no', 'detail_no'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function transaction(){
        return $this->belongsTo(MtTransaction::class, 'transanction_no', 'transaction_no');
    }
    public function account(){
        return $this->belongsTo(MtAccount::class, 'account_CD', 'account_cd');
    }
}
