<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MtPersonal;

class PersonalController extends Controller
{
    const ROUTE = '/personal';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // mt_personal及び、
        // 配下のmt_client, mt_servicer, mt_supplier, 
        // 紐づくmt_personal_infoの全権抽出
        // \DB::enableQueryLog();
        $records = MtPersonal::with([
            'mtPersonalInfo' => function($query){
                $query->select(['type_cd', 'personal_data', 'valid_flg'])
                    ->whereNotNull('valid_flg');
            }, 
            'mtPersonalInfo.dataType' => function($query){
                $query->select(['type_cd', 'type', 'valid_flg'])
                    ->whereNotNull('valid_flg');
            },
            'client' => function($query){
                $query->whereNotNull('valid_flg');
            }, 
            'servicer' => function($query){
                $query->whereNotNull('valid_flg');
            },
            'supplier' => function($query){
                $query->whereNotNull('valid_flg');
            }])
            ->select('personal_cd')->get();
        // dd(\DB::getQueryLog());
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
    // public function store(Request $request)
    // {
    //     $records = Personal::all();

    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // mt_personal及び、
        // 配下のmt_client, mt_servicer, mt_supplier, 
        // 紐づくmt_personal_infoのpersonal_cd指定抽出
        $record = MtPersonal::with([
            'mtPersonalInfo' => function($query){
                $query->select(['type_cd', 'personal_data', 'valid_flg'])
                    ->whereNotNull('valid_flg');
            }, 
            'mtPersonalInfo.dataType' => function($query){
                $query->select(['type_cd', 'type', 'valid_flg'])
                    ->whereNotNull('valid_flg');
            },
            'client' => function($query){
                $query->whereNotNull('valid_flg');
            }, 
            'servicer' => function($query){
                $query->whereNotNull('valid_flg');
            },
            'supplier' => function($query){
                $query->whereNotNull('valid_flg');
            }])
            ->where('personal_cd', '=', $id)
            ->first('personal_cd');
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
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
