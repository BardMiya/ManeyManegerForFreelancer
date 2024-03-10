<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtPersonalInfo extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mt_personal_info';
    protected $primaryKey = 'personal_data_cd';

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function cbPersonalData(){
        return $this->hasMany(CbPersonalData::class, 'personal_data_cd', 'personal_data_cd');
    }
    public function dataType(){
        return $this->belongsTo(MtPersonalDataType::class, 'type_cd', 'type_cd');
    }
}
