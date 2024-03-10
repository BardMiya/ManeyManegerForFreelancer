<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtClient extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mt_client';
    protected $primaryKey = 'personal_cd';

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    function mtPersonal(){
        return $this->belongsTo(MtPersonal::class, 'personal_cd', 'personal_cd');
    }
    function tsOrder(){
        return $this->hasMany(TsOrder::class, 'orderer_cd', 'personal_cd');
    }
}
