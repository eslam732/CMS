<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\DB;



class CategoryController extends Controller

{
    public function getCategories()
    {
       // $categories=Category::first()->posts;
        
       //dd(Category::first()->posts()->get());
       return response()->json(['categories'=>Category::all()],200);

    }
    

      
    public function createCategory()
    {
        $validation=Validator::make(request()->all(),[
            'name'=>'required|unique:categories'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),200);
        }
        $data=request()->all();
        $category=Category::create($data);
        return response()->json($category, 200);
    }
    
    public function editcategory($categoryId)
    {
        $validation=Validator::make(request()->all(),[
            'name'=>'required|unique:categories'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),200);
        }
        $category=Category::find($categoryId);
        if(!$category){
            return response("could not finde category",404); 
        }
        $category->name=request()['name'];
        $category->save();
        return response("updated",200); 

    }
    public function deletecategory($categoryId)
    {
        $category=Category::find($categoryId);
        
        if(!$category){
            return response("could not finde category",404); 
        }
        if($category->posts->count()>0){
            return response("cant delete this cat",404); 
        }
        $category->delete();
        return response("deleted",200);
    }
}
