<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DnsController;
use App\Http\Controllers\JsonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DebugController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',function(){
    return view('index');
});
Route::post('/UserVerify', [UserController::class,'login']);
Route::middleware('WebToken')->group(function () {
    Route::get('/users', [UserController::class,'returnUser']);
    Route::post('/users', [UserController::class,'addUser']);
    Route::put('/users/{id}/state/{state}', [UserController::class,'updateUser']);
    Route::put('/users/{id}', [UserController::class,'editUser']);
    Route::delete('/users/{id}', [UserController::class,'deleteUser']);
    Route::get('/dnsInfos', [DnsController::class,'returnDnsData']);
    Route::post('/genFiles', [DnsController::class,'makeFiles']);
    Route::get('/wwwInfos', [DnsController::class,'returnWWWData']);
    Route::get('/soaInfos', [DnsController::class,'returnSOAData']);
    Route::get('/jsonInfos', [JsonController::class,'jsonShow']);
    Route::post('/genJson', [JsonController::class,'genJSONFile']);
    Route::post('/testPage', [DnsController::class,'testPage']);
    Route::post('/testDNS', [DnsController::class,'testDNS']);
    Route::get('/debugInfos', [DebugController::class,'getDebugInfo']);
    Route::put('/publish/{type}', [DnsController::class,'PublishGit']);
});

