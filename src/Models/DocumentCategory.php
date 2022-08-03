<?php

namespace Properos\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = [
        'name', 'slug'
    ];

    public $searchable = [
        'name', 'slug'
    ];

    public function toSearchableArray()
    {
        return array_flip($this->searchable);
    }
    
    public function getSearchableArray()
    {
        return $this->searchable;
    }
}
