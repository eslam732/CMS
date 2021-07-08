<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'category_id',
        'creator_id'
    ];

    public function deleteImage()
    {
       Storage::delete($this->image);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function scopeSearched($query)
    {
        $search = request()->query('search');
        if(!$search){
            return $query;
        }   
        return $query->where('title', 'LIKE', "%{$search}%");
    }
    public static function categoryPosts($catId)
    {
       return DB::table('posts')->where('category_id', '=', "$catId")->paginate(1);
    }
}
