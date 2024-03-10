<?php

namespace App\Http\Controllers;

use App\Util\AppUtil;
use App\Const\AppConst;
use App\Models\MtAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    private const TYPE = 'type';
    private const ACCOUNT_CD = 'account_cd';
    const ROUTE = '/account';
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getQry = $request->query();
        $where = [];
        $whereType = [];
        if(array_key_exists(self::TYPE, $getQry)){
            $whereType = explode(",", $getQry[self::TYPE]);
        }
        if(array_key_exists(self::ACCOUNT_CD, $getQry)){
            array_push($where, [self::ACCOUNT_CD, 'LIKE', "%{$getQry[self::NO_TO]}%"]);
        }
        if(count($whereType) == 0 ){
            $records = MtAccount::whereNotNull(AppConst::VALID)
                ->where($where)
                ->get();
            return response()->json(
                $records, 200
            );
        }else{
            $records = MtAccount::whereNotNull(AppConst::VALID)
                ->where($where)
                ->whereIn(self::TYPE, $whereType)
                ->get();
            return response()->json(
                $records, 200
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $record = new MtAccount();
        $record->account_cd = $request->account_cd;
        $record->name = $request->name;
        $record->type = $request->type;
        $record->valid_flg = 1;
        $record->create_user = AppUtil::who($request);
        $record->update_user = AppUtil::who($request);
        $record->save();
        
        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // mt_personal_data_typeの1件抽出
        $record = MtAccount::where('account_cd', '=', $id)
            ->whereNotNull('valid_flg')
            ->first();
            return response()->json(
                $record, 200
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $update = new MtAccount();
        DB::transaction(function() use ($request, $update, $id){
            // 現レコードを無効化
            $record = MtAccount::where('account_cd', '=', $id)
                ->whereNotNull('valid_flg')
                ->first();
            $record->valid_flg = null;
            $record->save();

            // 新規レコード登録
            $update->account_cd = $id;
            $update->name = $request->name;
            $update->type = $request->type;
            $update->valid_flg = 1;
            $update->create_user = AppUtil::who($request);
            $update->update_user = AppUtil::who($request);
            $update->save();
        });

        return redirect(self::ROUTE);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 論理削除
        $record = MtAccount::where('account_cd', '=', $id)
            ->whereNotNull('valid_flg')
            ->first();
        $record->valid_flg = null;
        $update->update_user = AppUtil::who($request);
        $record->save();
    }
}
