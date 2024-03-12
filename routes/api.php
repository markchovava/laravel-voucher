<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignCompanyController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ClaimedVoucherController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GeneratedVoucherController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramVoucherController;
use App\Http\Controllers\RedeemVoucherController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherPriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::prefix('profile')->group(function() {
        Route::get('/', [AuthController::class, 'view']);
        Route::get('/{id}', [AuthController::class, 'checkUserById']);
        Route::post('/', [AuthController::class, 'update']);
        Route::post('/password', [AuthController::class, 'password']);
    });

    Route::prefix('app-info')->group(function() {
        Route::get('/', [AppInfoController::class, 'view']);
        Route::post('/', [AppInfoController::class, 'store']);
    });

    Route::prefix('campaign')->group(function() {
        Route::get('/', [CampaignController::class, 'index']);
        Route::get('/active', [CampaignController::class, 'indexActive']);
        Route::post('/', [CampaignController::class, 'store']);
        Route::get('/{id}', [CampaignController::class, 'view']);
        Route::post('/{id}', [CampaignController::class, 'update']);
        Route::post('/status/{id}', [CampaignController::class, 'update_status']);
        Route::delete('/{id}', [CampaignController::class, 'delete']);
    });

    Route::prefix('generated-voucher')->group(function() {
        Route::get('/', [GeneratedVoucherController::class, 'index']);
        Route::get('/by-id/{id}', [GeneratedVoucherController::class, 'indexById']);
        Route::post('/', [GeneratedVoucherController::class, 'store']);
        Route::get('/exist/{id}', [GeneratedVoucherController::class, 'checkIfExists']);
        Route::get('/voucher-search', [GeneratedVoucherController::class, 'voucherSearch']);
    });
    
    Route::prefix('program')->group(function() {
        Route::get('/', [ProgramController::class, 'index']);
        Route::get('/by-user-id', [ProgramController::class, 'indexByUserId']);
        Route::post('/', [ProgramController::class, 'store']);
        Route::get('/{id}', [ProgramController::class, 'view']);
        Route::post('/store-by-amount', [ProgramController::class, 'storeByAmount']);
        Route::get('/program-campaign', [ProgramController::class, 'searchByProgramCampaign']);
    });

    Route::prefix('program-voucher')->group(function() {
        Route::get('/by-program/{id}', [ProgramVoucherController::class, 'indexByProgramId']);
        Route::get('/by-program-user/{id}', [ProgramVoucherController::class, 'indexByProgramUserId']);
    });

    Route::prefix('redeem-voucher')->group(function() {
        Route::get('/', [RedeemVoucherController::class, 'index']);
        Route::get('/search', [RedeemVoucherController::class, 'searchView']);
        Route::get('/by-program-id', [RedeemVoucherController::class, 'indexByProgramId']);
        Route::get('/check-by-id', [RedeemVoucherController::class, 'checkIfExists']);
        Route::post('/', [RedeemVoucherController::class, 'store']);
        Route::get('/{id}', [RedeemVoucherController::class, 'view']);
    });

    Route::prefix('claimed-voucher')->group(function() {
        Route::get('/', [ClaimedVoucherController::class, 'index']);
        Route::post('/', [ClaimedVoucherController::class, 'store']);
        Route::get('/{id}', [ClaimedVoucherController::class, 'view']);
    });

    
    Route::prefix('role')->group(function() {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/all', [RoleController::class, 'indexAll']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'view']);
        Route::post('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'delete']);
    });
    
    Route::prefix('user')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'view']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    Route::prefix('voucher-price')->group(function() {
        Route::get('/', [VoucherPriceController::class, 'view']);
        Route::post('/', [VoucherPriceController::class, 'store']);
    });

});
