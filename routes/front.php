<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;


Route::get('/', [PageController::class, 'welcomePage'])->name('welcome');
Route::get('/imei-check', [PageController::class, 'imeiCheck'])->name('imei.check');
Route::post('/imei-check', [PageController::class, 'imeiCheckSubmit'])->name('imei.check.submit');
Route::get('/imei-register/{device}', [PageController::class, 'imeiRegister'])->name('imei.register');
Route::post('/imei-register/{device}', [PageController::class, 'imeiRegisterSubmit'])->name('imei.register.submit');
Route::get('/payment/{device}', [PageController::class, 'paymentPage'])->name('imei.payment');
Route::post('/payment/{device}', [PageController::class, 'paymentSubmit'])->name('imei.payment.submit');
Route::get('/payment-success/{device}', [PageController::class, 'paymentSuccess'])->name('imei.payment.success');
