<?php

namespace Properos\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Cms\Classes\CDocumentsCategory;
use Properos\Base\Classes\Api;

class ApiCategoryController extends Controller
{
    public function search(Request $request)
    {
        $cDocumentsCategory = new CDocumentsCategory();
        $options = $cDocumentsCategory->standardize_search($request);
        $categories = $cDocumentsCategory->find($options);

        return Api::success('Categories search result.', $categories, $cDocumentsCategory->get_paginator()->toArray());
    }
}
