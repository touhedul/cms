<?php

namespace Properos\Cms\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;
use Properos\Base\Classes\Base;
use Properos\Cms\Models\DocumentCategory;

class CDocumentsCategory extends Base
{
	function __construct()
	{
		parent::__construct(DocumentCategory::class, 'DocumentCategory');
	}


}