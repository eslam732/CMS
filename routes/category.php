<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;


Route::post('/createcategory',[CategoryController::class,'createCategory']);
Route::get('/getcategories',[CategoryController::class,'getCategories']);
Route::post('/editcategory/{categoryId}',[CategoryController::class,'editcategory']);
Route::delete('/deletecategory/{categoryId}',[CategoryController::class,'deletecategory']);