<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtPersonalDataType extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'mt_personal_data_type';
    protected $primaryKey = 'type_cd';

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function personalInfo(){
        $this->hasMany(MtPersonalInfo::class, 'type_cd', 'type_cd');
    }
}
