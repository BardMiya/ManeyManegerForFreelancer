<?php

namespace App\Util;

use App\Const\AppConst;
use Illuminate\Http\Request;

class AppUtil
{
    public static function who(Request $request)
    {
        return $request->user() ?? AppConst::SYSUSER();
    }
}