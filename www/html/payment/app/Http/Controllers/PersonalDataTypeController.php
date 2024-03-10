<?php

namespace App\Http\Controllers;

use App\Util\AppUtil;
use App\Models\MtPersonalDataType;
use Illuminate\Http\Request;

class PersonalDataTypeController extends Controller
{
    const ROUTE = '/personal-data-type';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = MtPersonalDataType::all();
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
        $record = new MtPersonalDataType();
        $record->type_cd = $request->type_cd;
        $record->type = $request->type;
        $record->lank = $request->lank;
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
        $record = MtPersonalDataType::where('type_cd', '=', $id)
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

        $update = new MtPersonalDataType();
        DB::transaction(function() use ($request, $update, $id){
            // 現レコードを無効化
            $record = MtPersonalDataType::where('type_cd', '=', $id)
                ->whereNotNull('valid_flg')
                ->first();
            $record->valid_flg = null;
            $record->save();

            // 新規レコード登録
            $update->type_cd = $id;
            $update->type = $request->type ?? $record->type;
            $update->lank = $request->lank ?? $record->lank;
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
        $record = MtPersonalDataType::where('type_cd', '=', $id)
            ->whereNotNull('valid_flg')
            ->first();
        $record->valid_flg = null;
        $update->update_user = AppUtil::who($request);
        $record->save();
    }
}
