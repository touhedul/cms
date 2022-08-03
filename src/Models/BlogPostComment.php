<?php

namespace Properos\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class BlogPostComment extends Model
{

    protected $fillable = [
        'blog_post_id', 'user_id', 'comment'
    ];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
