<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbWorkDocument extends Model
{
    use HasFactory;
    protected $table = 'cb_work_document';
    // protected $primaryKey = ['personal_cd', 'personal_data_cd'];

    public $incrementing = false;
    protected $keyType = 'string';

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';
}
