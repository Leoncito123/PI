<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::get('/sectors', [MapController::class, 'getSectors']);
Route::get('/types', [MapController::class, 'getTypes']);
Route::get('/ubications', [MapController::class, 'getUbications']);
Route::get('/sensors', [MapController::class, 'getSensors']);

