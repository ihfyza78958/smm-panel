<?php

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public V2 Adapter API — rate limited to 60 requests/minute per IP
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/v2', [ApiController::class, 'handle']);
    Route::get('/v2', [ApiController::class, 'handle']);
});
