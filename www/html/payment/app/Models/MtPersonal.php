<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtPersonal extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mt_personal';
    protected $primaryKey = 'personal_cd';

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function mtPersonalInfo(){
        return $this->belongsToMany(MtPersonalInfo::class, 'cb_personal_data', 'personal_cd', 'personal_data_cd');
    }
    public function client(){
        return $this->hasOne(MtClient::class, 'personal_cd', 'personal_cd');
    }
    public function servicer(){
        return $this->hasOne(MtServicer::class, 'personal_cd', 'personal_cd');
    }
    public function supplier(){
        return $this->hasOne(MtSupplier::class, 'personal_cd', 'personal_cd');
    }
    function personalInfo(){
        return $this->belongsToMany(MtPersonalInfo::class, 'cb_personal_data', 'personal_cd', 'personal_data_cd', 'personal_cd', 'personal_data_cd');
    }
}
