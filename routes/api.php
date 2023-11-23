<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/backups/create', [BackupController::class, 'store']);
    Route::get('/backups/{instanceId}/latest', [BackupController::class, 'showLatest']);
    Route::get('/backups/{instanceId}', [BackupController::class, 'index']);
    Route::get('/backups/{instanceId}/{date}', [BackupController::class, 'showByDate']);
    Route::delete('/backups/{instanceId}/{backupId}', [BackupController::class, 'forget']);

    // Route::get('/monitor/status', 'MonitorController@status');
    // Route::get('/monitor/backups', 'MonitorController@backups');

    // Route::post('/admin/backups/cleanup', 'AdminController@cleanup');
    // Route::post('/admin/backups/retry/{backupId}', 'AdminController@retry');
});
