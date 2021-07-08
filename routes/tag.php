<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tagsController;


Route::post('/createtag',[tagsController::class,'createTag']);
Route::get('/gettags',[tagsController::class,'getTags']);
Route::post('/edittag/{tagId}',[tagsController::class,'editTag']);
Route::delete('/deletetag/{tagId}',[tagsController::class,'deleteTag']);