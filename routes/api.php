<?php

use App\Http\Controllers\DivisionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadFileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('upload-files', UploadFileController::class);
Route::post('/upload-files/publish-to-user-storage', [UploadFileController::class, 'publishToUserStorageExample']);

Route::apiResource('division', DivisionController::class);

