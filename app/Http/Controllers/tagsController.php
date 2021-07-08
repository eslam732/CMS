<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class tagsController extends Controller
{
    public function getTags()
    {
        return response()->json(['Tags'=>Tag::all()],200);
    }

    public function createTag()
    {
        $validation=Validator::make(request()->all(),[
            'name'=>'required|unique:tags'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),200);
        }
        $data=request()->all();
        $tag=Tag::create($data);
        return response()->json($tag, 200);
    }

    public function editTag($tagId)
    {
        $validation=Validator::make(request()->all(),[
            'name'=>'required|unique:tags'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(),200);
        }
        $tag=Tag::find($tagId);
        if(!$tag){
            return response("could not finde tag",404); 
        }
        $tag->name=request()['name'];
        $tag->save();
        return response("updated",200); 

    }
    public function deleteTag($tagId)
    {
        $tag=Tag::find($tagId);
        if(!$tag){
            return response("could not finde tag",404); 
        } 
        if($tag->posts->count()>0){
            return response("cant delete this cat",404); 
        }
        $tag->delete();
        return response("deleted",200);
    }


    
}
