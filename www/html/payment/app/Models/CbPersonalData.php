<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbPersonalData extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'cb_personal_data';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';

    public function mtPersonalInfo(){
        return $this->belongsTo(MtPersonalInfo::class, 'personal_data_cd', 'personal_data_cd');
    }
    public function personal(){
        return $this->belongTo(MtPersonal::class, 'personal_cd', 'personal_cd');
    }
}
