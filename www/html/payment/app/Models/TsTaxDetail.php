<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsTaxDetail extends Model
{
    use HasFactory;
    protected $table = 'ts_tax_detail';
    protected $primaryKey = ['invoice_no', 'type'];

    public $incrementing = false;

    public const CREATED_AT = 'create_datetime';
    public const UPDATED_AT = 'update_datetime';
}
