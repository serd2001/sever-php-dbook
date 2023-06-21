<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\impBookController;
use App\Http\Controllers\orderDetailController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
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




Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/categories', [CategoryController::class, 'create']);
Route::put('/categories', [CategoryController::class, 'update']);
Route::put('/categories', [CategoryController::class, 'delete']);
Route::get('/categories', [CategoryController::class, 'show']);

Route::get('/books', [BookController::class, 'index']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);
Route::get('/search', [BookController::class, 'search']);

Route::post('/supplies', [SupplyController::class, 'create']);
Route::put('/supplies', [SupplyController::class, 'update']);
Route::delete('/supplies', [SupplyController::class, 'delete']);

Route::post('/users', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/order-detail', [orderDetailController::class, 'order']);

Route::get('/user', [AuthController::class, 'user']);
Route::post('/impbook', [impBookController::class, 'impbook']);



Route::group(['middleware' => ['auth:api']], function () {

    Route::post('/check-out', [CheckoutController::class, 'checkout']);
    Route::get('/payments', [PaymentController::class, 'show']);
    Route::post('/payments', [PaymentController::class, 'payment']);
    // Route::get('/details', [PassportAuthController::class, 'details']);
    // Route::put('/logout', [PassportAuthController::class, 'logout']);

    // //topping
    // Route::resource('/topping', ToppingController::class);
    // Route::resource('/banner', BannerController::class);

    //checkout
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
