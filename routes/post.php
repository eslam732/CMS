<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;

Route::post('/createpost',[PostController::class,'createPost']);
Route::get('/posts',[PostController::class,'getPosts']);
Route::get('/posts/{catId}',[PostController::class,'getPostsForCategory']);
Route::get('/postsfortag/{tagId}',[PostController::class,'getPostsForTag']);
Route::post('/editpost/{postId}',[PostController::class,'editPost']);
Route::delete('/deletepost/{postId}',[PostController::class,'deletePost']);
Route::get('/trashedposts',[PostController::class,'getTrashed']);
Route::post('/restorepost/{postId}',[PostController::class,'restorePost']);