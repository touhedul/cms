<?php

namespace Properos\Cms\Helpers;
use Properos\Cms\Models\DocumentCategory;

class CustomView
{
    public function document_categories()
    {
        return DocumentCategory::all();
    }
}
