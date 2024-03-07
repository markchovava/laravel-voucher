<?php

use App\Http\Controllers\AppInfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GeneratedVoucherController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\VoucherPriceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('app-info')->group(function() {
    Route::get('/', [AppInfoController::class, 'view']);
});

Route::prefix('campaign')->group(function() {
    Route::get('/', [CampaignController::class, 'index']);
    Route::get('/{id}', [CampaignController::class, 'view']);
});



Route::prefix('claim')->group(function() {
    Route::get('/', [ClaimController::class, 'index']);
    Route::get('/by-user', [ClaimController::class, 'indexUserId']);
    Route::post('/', [ClaimController::class, 'store']);
    Route::get('/{id}', [ClaimController::class, 'view']);
    Route::get('/test', [ClaimController::class, 'test']);
});


Route::prefix('program')->group(function() {
    Route::get('/', [ProgramController::class, 'index']);
    Route::post('/', [ProgramController::class, 'store']);
    Route::get('/{id}', [ProgramController::class, 'view']);
});


Route::prefix('role')->group(function() {
    Route::get('/', [RoleController::class, 'index']);
    Route::get('/{id}', [RoleController::class, 'view']);
});

Route::prefix('generated-voucher')->group(function() {
    Route::get('/', [GeneratedVoucherController::class, 'index']);
    Route::get('/voucher-search', [GeneratedVoucherController::class, 'voucherSearch']);
});

Route::prefix('voucher-price')->group(function() {
    Route::get('/', [VoucherPriceController::class, 'view']);
});




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

