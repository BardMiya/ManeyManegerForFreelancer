<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PersonalDataTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DeriveryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServicerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PeliodEndCloseController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// 人格情報リソース
Route::get('/personal', [PersonalController::class,'index']);
Route::get('/personal/{id}', [PersonalController::class,'show']);
Route::apiResource('/client', ClientController::class);
Route::apiResource('/servicer', ServicerController::class);
Route::apiResource('/supplier', SupplierController::class);
// 人格情報種別リソース
Route::apiResource('/personal-data-type', PersonalDataTypeController::class);
// 注文情報リソース
Route::apiResource('/orders',OrderController::class);
// 業務情報リソース
Route::apiResource('/works',WorkController::class);
Route::post('/works/{work}/detail', [WorkController::class, 'addDetail']);
Route::put('/works/{work}/detail/{priceNo}', [WorkController::class, 'updateDetail']);
Route::delete('/works/{work}/detail/{priceNo}', [WorkController::class, 'deleteDetail']);
// 見積情報リソース
Route::apiResource('/estimates',EstimateController::class);
// 請求情報リソース
Route::apiResource('/invoices',InvoiceController::class);
// 取引仕分け情報リソース
Route::apiResource('/transactions',TransactionController::class);
// 文書リソース
Route::apiResource('/documents',DocumentController::class);
// 納品情報リソース
Route::apiResource('/derivery',DeriveryController::class);
// 商品情報リソース
Route::apiResource('/products',ProductController::class);
// 科目マスタ
Route::apiResource('/account',AccountController::class);
// 損益計算書
Route::get('/period-end/statement/{year}/{servicer?}',[PeliodEndCloseController::class, 'statement']);
// 貸借対照表
Route::get('/period-end/balance/{year}/{servicer?}',[PeliodEndCloseController::class, 'balance']);
// 繰越処理
Route::post('/period-end/close/{year}/{servicer}',[PeliodEndCloseController::class, 'periodClose']);
// Route::apiResource('',Controller::class);
// Route::apiResource('',Controller::class);
// Route::apiResource('',Controller::class);
// Route::apiResource('',Controller::class);
// Route::apiResource('',Controller::class);
