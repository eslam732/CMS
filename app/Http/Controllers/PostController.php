<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public $postValidation;
    public function postValidator($request)
    {
        $rules = array(
            "title" => "required|unique:posts",
            "description" => "required",
            "content" => "required",
            "description" => "required",
            "image" => "required",
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            $this->postValidation = $validator->errors();
            return 1;
        }
    }
    public function createPost()
    {
        if ($this->postValidator(request()->all)) {
            return response()->json($this->postValidation);
        }
        $image = request()['image']->store('posts');
        $allData = request()->all();
        $post =   Post::create([
            'title' => $allData['title'],
            'description' => $allData['description'],
            'content' => $allData['content'],
            'image' => $image,
            

        ]);
        // $post->save();

        return response()->json(["post" => $post], 201);
    }
    public function getPosts()
    {
        $posts = Post::all();
        return response()->json(["posts" => $posts], 200);
    }
    public function editPost($postId)
    {    
        $rules = array(
            "title" => "required|unique:posts",
            "description" => "required",
            "content" => "required",
            "description" => "required",
            
        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            
            return response()->json($validator->errors());
        }
        $post = Post::find($postId);
        if (!$post) {
            return response()->json("Post Not Found");
        }
        if(request()['image']){
            $post->deleteImage();
            $image = request()['image']->store('posts');
        }
       else{
        $image = $post->image;
       }
      
        $allData = request()->all();
        $post->title = $allData['title'];
        $post->description = $allData['description'];
        $post->content = $allData['content'];
        $post->image = $image;
        $post->save();
        return response()->json("Updated", 200);
    }
    public function deletePost($postId)
    {
        $post = Post::withTrashed()->find($postId);
        if (!$post) {
            return response()->json("Post Not Found");
        }
        if ($post->trashed()) {
            $post->deleteImage();
            $post->forceDelete();
            return response()->json("Deleted Permenantly", 200);
        } else {
            $post->delete();
            return response()->json("Moved TO Trash", 200);
        }
    }
    public function getTrashed()
    {
        $trashed = Post::onlyTrashed()->get();

        return response()->json($trashed);
    }
    public function restorePost($postId)
    {
        $post=Post::onlyTrashed()->find($postId);
        if (!$post) {
            return response()->json("Post Not Found");
        }
       $restoredPost= $post->restore();
        if($restoredPost){
            return response()->json("Post Restored");
        }
        else{
            return response()->json("SomeThing Went Wrong");
        }
    }

}
