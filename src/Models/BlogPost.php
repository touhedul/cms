<?php

namespace Properos\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class BlogPost extends Model
{
    protected $fillable = [
        'slug','user_id', 'title', 'header_media_path', 'header_media_type', 'content', 'summary','active','keywords'
    ];

    public function comments()
    {
        return $this->hasMany(BlogPostComment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
