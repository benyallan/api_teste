<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'store'])
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [\App\Http\Controllers\Auth\AuthController::class, 'destroy'])
        ->name('logout');
    Route::apiResource(
        'users',
        \App\Http\Controllers\UserController::class,
        ['except' => ['create', 'edit']]
    );
    Route::get(
        'users/{user}/expenses',
        [\App\Http\Controllers\UserController::class, 'expenses']
    )->name('users.expenses');
    Route::apiResource(
        'companies',
        \App\Http\Controllers\CompanyController::class,
        ['except' => ['create', 'edit']]
    );
    Route::apiResource(
        'expenses',
        \App\Http\Controllers\ExpenseController::class,
        ['except' => ['create', 'edit']]
    );
    Route::post(
        'expenses/{expense}/approve',
        [\App\Http\Controllers\ExpenseController::class, 'approve']
    )->name('expenses.approve');
    Route::post(
        'expenses/{expense}/reject',
        [\App\Http\Controllers\ExpenseController::class, 'reject']
    )->name('expenses.reject');
    Route::patch('users/{user}/{company}/associate', [\App\Http\Controllers\UserController::class, 'addCompany'])
        ->name('users.companies.associate');
});
