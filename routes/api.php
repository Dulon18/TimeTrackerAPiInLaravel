<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function()
{
    Route::post('/register',[UserController::class,'register']);
    Route::post('/login',[UserController::class,'login']);

    Route::middleware('auth:sanctum')->group(function(){
        //logout
        Route::post('logout',[UserController::class,'logout']);

        // client manage routes
        Route::get('/client/list',[ClientController::class,'list']);
        Route::get('/client/show/{id}',[ClientController::class,'show']);
        Route::post('/client/create',[ClientController::class,'store']);
        Route::put('/client/update/{id}',[ClientController::class,'update']);
        Route::post('/client/delete/{id}',[ClientController::class,'delete']);

        // project routes
        Route::get('/project/list',[ProjectController::class,'list']);
        Route::get('/project/show/{id}',[ProjectController::class,'show']);
        Route::post('/project/create',[ProjectController::class,'store']);
        Route::put('/project/update/{id}',[ProjectController::class,'update']);
        Route::post('/project/delete/{id}',[ProjectController::class,'delete']);

        // Time Log routes
        Route::post('/timelog/{project_id}/start', [TimeLogController::class, 'start']);
        Route::post('/timelog/{project_id}/stop', [TimeLogController::class, 'stop']);

        //report
        Route::get('/report', [ReportController::class, 'index']);
        Route::get('/report/summery', [ReportController::class, 'summary']);

        //pdf export route
        Route::get('/logs/export-pdf', [TimeLogController::class, 'exportPdf']);

    });
});


