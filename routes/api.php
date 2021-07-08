<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\tagsController;

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

Route::middleware('auth:api')->get('/try',[UserController::class,'try']);
Route::get('/login',[UserController::class,'login'])->name('login');
Route::post('/register',[UserController::class,'registration']);
//Route::get('/try',[UserController::class,'try']);
Route::post('/login',[UserController::class,'login']);
Route::middleware('auth:api')->get('/allusers',[UserController::class,'getUsers']);
Route::middleware('auth:api')->post('/makeadmin/{userId}',[UserController::class,'makeAdmin']);
Route::middleware('auth:api')->get('/userprofile',[UserController::class,'getUserProfile']);
Route::middleware('auth:api')->post('/editprofile',[UserController::class,'editUserProfile']);


