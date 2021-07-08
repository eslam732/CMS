<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
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
            'category_id' => 'required',
            'creator_id' => 'required',
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


        $cat = Category::find($allData['category_id']);
        if (!$cat) {

            return response()->json(['message' => 'category with this id is not found'], 404);
        }
        $post =   Post::create([
            'title' => $allData['title'],
            'description' => $allData['description'],
            'content' => $allData['content'],
            'image' => $image,
            'category_id' => $allData['category_id'],
            'creator_id' => (int)($allData['creator_id'])

        ]);
        if (isset($allData['tags'])) {
            $post->tags()->attach($allData['tags']);
        }


        return response()->json(["post" => $post], 201);
    }
    public function getPosts()
    {


        return response()->json(["posts" => Post::searched()->Paginate(3), 'total'], 200);
    }
    public function getPostsForCategory($catId)
    {



        $posts = Post::categoryPosts($catId);



        return response()->json(["posts" => $posts], 200);
    }
    public function getPostsForTag($tagId)
    {
        $tag = Tag::find($tagId);
        if (!$tag) {
            return response()->json(['error' => 'Tag not found'], 401);
        }

        //$posts = DB::table('posts')->;
        $posts = DB::select(DB::raw("
        SELECT *
        FROM posts
        WHERE id In
    (
        SELECT post_id FROM post_tag
        WHERE tag_id=$tagId
    );"));



        return response()->json(["posts" => $posts], 200);
    }

    public function editPost($postId)
    {
        $rules = array(
            "title" => "required|unique:posts",
            "description" => "required",
            "content" => "required",
            "description" => "required",
            'category_id' => 'required'

        );
        $validator = Validator::make(request()->all(), $rules);
        if ($validator->fails()) {

            return response()->json($validator->errors());
        }
        $post = Post::find($postId);
        if (!$post) {
            return response()->json("Post Not Found");
        }

        if (request()['image']) {
            $post->deleteImage();
            $image = request()['image']->store('posts');
        } else {
            $image = $post->image;
        }

        $allData = request()->all();
        $cat = Category::find($allData['category_id']);
        if (!$cat) {

            return response()->json(['message' => 'category with this id is not found'], 404);
        }
        $post->title = $allData['title'];
        $post->description = $allData['description'];
        $post->content = $allData['content'];
        $post->category_id = $allData['category_id'];
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
        $post = Post::onlyTrashed()->find($postId);
        if (!$post) {
            return response()->json("Post Not Found");
        }
        $restoredPost = $post->restore();
        if ($restoredPost) {
            return response()->json("Post Restored");
        } else {
            return response()->json("SomeThing Went Wrong");
        }
    }
}
