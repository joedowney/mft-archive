<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::middleware(['guest'])->post('login', [AuthenticatedSessionController::class, 'store']);
Route::middleware(['auth.session'])->post('logout', [AuthenticatedSessionController::class, 'destroy']);
