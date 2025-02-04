<?php

use App\Http\Controllers\Api\V1\OptAuthCodeController;
use App\Http\Controllers\Api\V1\TemplateController;
use App\Http\Middleware\EnsureOtpMiddleware;
use App\Models\Api\V1\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('templates', TemplateController::class)->except('store');
Route::apiResource('otp', OptAuthCodeController::class);

Route::post('/templates', [TemplateController::class, 'store']);
// ->middleware([EnsureOtpMiddleware::class]);

// Route::post('/index', [OptAuthCodeController::class, 'store']);

Route::get('/requestOtp', [OptAuthCodeController::class, 'requestOTP']);

Route::post('/requestTemplate', [TemplateController::class, 'requestTemplate']);


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage linked successfully!';
});


//once i hit this endpoint the middlewa re will check if the otp as been requeted or avialable in the database and create an otp, sends the otp to the email provided.
// Route::post('/storeOtp', [OptAuthCodeController::class, 'Api\OptAuthCodeController@store']);