<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
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

// Route::resource('products', ProductController::class);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/email/verification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/email/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('auth:sanctum')->name('verification.verify');

Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordController::class, 'reset']);

Route::get('/products/prices', [ProductController::class, 'oneArray']);
Route::get('/products/prices_name', [ProductController::class, 'twoArray']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/search/{name}', [ProductController::class, 'search']);

Route::get('/products/random/{count}', [ProductController::class, 'storeRandom']);
Route::get('/users/random/{count}', [UserController::class, 'storeRandom']);
Route::get('/superadmin/random/{count}', [UserController::class, 'storeAdminRandom']);


Route::get('/localfiles', [FileController::class, 'indexLocal']);
Route::post('/localfiles', [FileController::class, 'uploadLocal']);
Route::get('/localfiles/{filename}', [FileController::class, 'showLocal']);
Route::delete('/localfiles/{filename}', [FileController::class, 'destroyLocal']);

Route::get('/files', [FileController::class, 'indexDb']);
Route::post('/files', [FileController::class, 'uploadDb']);
Route::get('/files/{id}', [FileController::class, 'showDb']);
Route::put('/files/{id}', [FileController::class, 'updateDb']);
Route::delete('/files/{id}', [FileController::class, 'destroyDb']);


// Protected route
Route::group(['middleware'=> ['auth:sanctum', 'verified']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/products', [ProductController::class, 'store'])->middleware('role:superadmin,admin');;
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware('role:superadmin,admin');;
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('role:superadmin,admin');;

    Route::get('/users', [UserController::class, 'index'])->middleware('role:superadmin,admin');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('role:superadmin,admin');;
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('role:superadmin,admin');;

    Route::put('/users/{id}/userlevel', [UserController::class, 'updateUserLevel'])->middleware('role:superadmin');;

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('role:superadmin');;
    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});