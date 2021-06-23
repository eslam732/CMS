<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

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
Route::post('/createcategory',[CategoryController::class,'createCategory']);
Route::post('/editcategory/{categoryId}',[CategoryController::class,'editcategory']);
Route::delete('/deletecategory/{categoryId}',[CategoryController::class,'deletecategory']);
Route::post('/createpost',[PostController::class,'createPost']);
Route::get('/posts',[PostController::class,'getPosts']);
Route::post('/editpost/{postId}',[PostController::class,'editPost']);
Route::delete('/deletepost/{postId}',[PostController::class,'deletePost']);
Route::get('/trashedposts',[PostController::class,'getTrashed']);
Route::post('/restorepost/{postId}',[PostController::class,'restorePost']);
