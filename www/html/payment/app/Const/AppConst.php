<?php

namespace App\Const;

class AppConst
{
    public const APPCONFIG = 'paymaster';
    public const VALID = 'valid_flg';
    public const UPDATE_USER = 'update_user';

    public static $taxRate = array( 
        0 => 0, 
        1 => 0.1, 
        2 => 0.08, 
        'RAW' => 'CASE tax_type WHEN 0 THEN 0 WHEN 1 THEN 0.1 WHEN 2 THEN 0.08 END'
    );

    public static function SYSUSER()
    {
        return config(AppConst::APPCONFIG.'.app-system-user');
    }
}