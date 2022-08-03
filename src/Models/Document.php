<?php

namespace Properos\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'label', 'user_id', 'filename', 'category_id', 'owner_id_id','owner_type', 'amendment', 'extension', 'path','size'
    ];

    public function category(){
        return $this->hasOne(DocumentCategory::class, 'id', 'category_id');
    }

    public $searchable = [
        'label', 'filename','extension'
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
