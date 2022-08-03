<?php

namespace Properos\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $morphClass = "page";

    protected $fillable = [
        'user_id','p_title', 'p_name', 'p_content', 'm_title', 'm_description', 'm_keywords', 'm_url', 'visibility','header_media_path'
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }
}
