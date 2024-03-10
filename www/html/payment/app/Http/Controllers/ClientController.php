<?php

namespace App\Http\Controllers;

use App\Util\AppUtil;
use App\Const\AppConst;
use App\Models\MtClient;
use App\Models\MtPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    const ROUTE = '/client';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $records = $request->query('invalid') ? MtClient::all() 
            : MtClient::whereNotNull(AppConst::VALID)->get();
        return response()->json(
            $records, 200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            if(!MtPersonal::where('personal_cd', '=', $request->personal_cd)->exists())
            {
                $master = new MtPersonal();
                $master->personal_cd = $request->personal_cd;
                $master->create_user = AppUtil::who($request);
                $master->update_user = AppUtil::who($request);
                $master->save();
            }
            $record = new MtClient();
            $record->personal_cd = $request->personal_cd;
            $record->name = $request->name;
            $record->zip = $request->zip;
            $record->address = $request->address;
            $record->tel = $request->tel;
            $record->email = $request->email;
            $record->valid_flg = 1;
            $record->create_user = AppUtil::who($request);
            $record->update_user = AppUtil::who($request);
            $record->save();
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollBack();
        }

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
        $record = MtClient::where('personal_cd', '=', $id)
            ->whereNotNull(AppConst::VALID)
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
        DB::beginTransaction();
        try{
            // 現レコードを無効化
            $record = MtClient::where('personal_cd', '=', $id)
                ->whereNotNull(AppConst::VALID)
                ->first();
            $record->valid_flg = null;
            $record->update_user = AppUtil::who($request);
            $record->save();

            // 新規レコード登録
            $update = new MtClient();
            $update->personal_cd = $id;
            $update->name = $request->name ?? $record->name;
            $update->zip = $request->zip ?? $record->zip;
            $update->address = $request->address ?? $record->address;
            $update->tel = $request->tel ?? $record->tel;
            $update->email = $request->email ?? $record->email;
            $update->valid_flg = 1;
            $update->create_user = AppUtil::who($request);
            $update->update_user = AppUtil::who($request);
            $update->save();
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollBack();
        }

        return redirect(self::ROUTE);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // 論理削除
        $record = MtClient::where('personal_cd', '=', $id)
            ->whereNotNull(AppConst::VALID)
            ->first();
        $record->valid_flg = null;
        $record->update_user = AppUtil::who($request);
        $record->save();
    }
}
