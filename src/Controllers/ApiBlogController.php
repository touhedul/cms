<?php

namespace Properos\Cms\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Cms\Models\BlogPost;
use Properos\Cms\Models\BlogPostComment;
use Properos\Cms\Classes\CBlog;
use Properos\Base\Classes\Api;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ApiBlogController extends Controller
{
	private $cBlog;
	private $blog_post;
	private $blog_post_comment;
	/*
    |--------------------------------------------------------------------------
    | ApiBlogController
    |--------------------------------------------------------------------------
    | 
	 */

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(CBlog $cBlog, BlogPost $blog_post, BlogPostComment $blog_post_comment)
	{
		$this->cBlog = $cBlog;
		$this->blog_post = $blog_post;
		$this->blog_post_comment = $blog_post_comment;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function blogs()
	{
        $blogs = $this->blog_post->orderBy('created_at', 'DESC')->paginate(8)->toJson();
        return $blogs;
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
	public function storePost(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'required',
			'content' => 'required',
			'file' => 'required',
			'header_media_type' => 'required',
		]);

		if ($validator->fails()) {
			return Api::error('Error creating post', $validator->errors());
		}

		$post_result = $this->cBlog->storePost($request->all());
		if ($post_result) {
			return Api::success('Post created successfully', $post_result);
		}
		return Api::error('Error creating post', []);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function storeComment(Request $request)
	{
		$validatedData = $request->validate([
			'post_id' => 'required',
			'content' => 'required'
		]);

		$comment_result = $this->cBlog->storeComment($request->all());
		if ($comment_result) {
			return Api::success('Comment created successfully', $comment_result);
		}
		return Api::error('Error creating comment', []);
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
	public function updatePost(Request $request, $id)
	{
		$validatedData = $request->validate([
			'title' => 'required',
			'content' => 'required',
			'header_media_type' => 'required',
		]);
		$post_result = $this->cBlog->updatePost($request->all(), $id);
		if ($post_result) {
			return Api::success('Post updated successfully', $post_result);
		}
		return Api::error('Error updating post', []);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroyPost($id)
	{
		if ($id > 0) {
			$post = $this->blog_post->find($id);
			$image_path = $post->header_media_path;
			if ($post) {
				if ($post->delete()) {
					$removed_path = Storage::disk('public')->delete($image_path);
					return Api::success('Post removed successfully', []);
				}
				return Api::error('Error removing post', []);
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroyComment($id)
	{
		if ($id > 0) {
			if (Auth::check()) {
				$user = Auth::user();
				$comment = $this->blog_post_comment->find($id);
				if ($comment) {
					if ($comment->user_id == $user->id) {
						if ($comment->delete()) {
							return Api::success('Comment removed successfully', $id);
						}
					}
				} else {
					return abort(404);
				}
			} else {
				return abort(403);
			}
		}
		return Api::error('Error removing post', []);
	}

}