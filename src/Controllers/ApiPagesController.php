<?php

namespace Properos\Cms\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Base\Classes\Api;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Properos\Cms\Classes\CPages;
use Properos\Cms\Models\Page;

class ApiPagesController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | ApiPagesController
    |--------------------------------------------------------------------------
    | 
	 */
	private $cPage;

	public function __construct(CPages $cPage)
	{
		$this->cPage = $cPage;
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        //
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
        //
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function storePage(Request $request)
	{
		$data = $request->all();

		$rules = [
			'p_title' => 'required|string',
			'p_content' => 'required|string',
			'm_title' => 'nullable|string|max:70',
			'm_description' => 'nullable|string|max:350',
			'm_url' => 'required|string',
		];

		$validation = Validator::make($data, $rules);

		if ($validation->passes()) {
			$page = $this->cPage->create($data);
			
			return Api::success('Page created successfully', $page);
		}

		return Api::error('Error creating page', $validation->errors());

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
        //
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
        //
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function updatePage(Request $request, $id)
	{
		$data = $request->all();
		$data['id'] = $id;
		$rules = [
			'id' => 'required|numeric|min:1',
			'p_title' => 'required|string',
			'p_content' => 'required|string',
			'm_title' => 'nullable|string|max:70',
			'm_description' => 'nullable|string|max:350',
			'm_url' => 'required|string',
		];

		$validation = Validator::make($data, $rules);

		if ($validation->passes()) {
			$page = $this->cPage->update($data);
			
			return Api::success('Page updated successfully', $page);
		}

		return Api::error('Error creating page', $validation->errors());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroyPage($id)
	{
		if ($id > 0) {
			$page = Page::find($id);
			// $image_path = $post->header_media_path;
			if ($page) {
				if ($page->delete()) {
					// $removed_path = Storage::disk('public')->delete($image_path);
					return Api::success('Page removed successfully', []);
				}
				return Api::error('Error removing page', []);
			}
			return Api::error('Page not exist', []);
		}
	}

}