<?php

namespace Properos\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Cms\Classes\CDocuments;
use Properos\Base\Classes\Api;
use Validator;
use Properos\Cms\Models\Document;
use Properos\Base\Exceptions\ApiException;
use Properos\Cms\Classes\CDocumentsCategory;

class ApiDocumentController extends Controller
{
    public function search(Request $request)
    {
        $cDocumentss = new CDocuments();
        $options = $cDocumentss->standardize_search($request);
        $documents = $cDocumentss->find($options);

        return Api::success('Documents search result.', $documents, $cDocumentss->get_paginator()->toArray());
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'label' => 'required|string',
            'category_id' => 'required|numeric',
            'document' => 'required',
            'visibility' => 'required',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->passes()) {
            $cDocuments = new CDocuments();
            $document = $cDocuments->create($data);

            return Api::success('Document created successfully', $document);
        }

        return Api::error('Error creating document', $validation->errors());

    }

    public function update(Request $request)
    {
        $data = $request->all();

        $rules = [
            'label' => 'required|string',
            'category_id' => 'required|numeric',
            'visibility' => 'required',
        ];
        $validation = Validator::make($data, $rules);
        if ($validation->passes()) {
            $cDocuments = new CDocuments();
            $has_file = $request->hasFile('file') ? true : false;
            $data['has_file'] = $has_file;
            $document = $cDocuments->update($data);
            if ($document != null) {
                return Api::success("Document updated successfully", $document);
            } else {
                throw new ApiException("Error updating the document", "002", []);
            }
        }

        return Api::error('Error updating document', $validation->errors());
    }

    public function remove($id)
    {
        if ($id > 0) {
            $document = Document::find($id);
            if ($document) {
                if ($document->owner_id > 0) {
                    \Storage::disk('local')->delete($document->path);
                } else {
                    \Storage::disk('public')->delete($document->path);
                }
                if ($document->delete()) {
                    return Api::success("document removed successfully", $document->id);
                } else {
                    throw new ApiException("Error while removing the document", "002", []);
                }
            }
        }
        throw new ApiException("document not found", "404", []);
    }

    public function searchCategories(Request $request)
    {
        $cCategories = new CDocumentsCategory();
        $options = $cCategories->standardize_search($request);
        $categories = $cCategories->find($options);

        return Api::success('Categories search result.', $categories, $cCategories->get_paginator()->toArray());
    }

    public function storeCategory(Request $request)
    {
        $data = $request->all();

        $rules = [
            'name' => 'required|string',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->passes()) {

            $slug = str_replace(' ', '-', strtolower($data['name']));
            $data['slug'] = $slug;

            $cCategory = new CDocumentsCategory();
            $category = $cCategory->createModel($data);

            return Api::success('Category created successfully', $category);
        }

        return Api::error('Error creating category', $validation->errors());
    }

    public function updateCategory(Request $request)
    {
        $data = $request->all();

        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->passes()) {

            $slug = str_replace(' ', '-', strtolower($data['name']));
            $data['slug'] = $slug;

            $cCategory = new CDocumentsCategory();
            $category = $cCategory->updateModel($data);

            return Api::success('Category updated successfully', $category);
        }

        return Api::error('Error creating category', $validation->errors());
    }

    public function deleteCategory($id)
    {
        if ($id > 0) {
            $cCategory = new CDocumentsCategory();
            return Api::success('Category deleted successfully', $cCategory->deleteModel($id));
        }
        throw new ApiException("document not found", "404", []);
    }
}
