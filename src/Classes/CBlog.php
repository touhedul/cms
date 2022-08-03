<?php

namespace Properos\Cms\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Properos\Cms\Models\User;
use Properos\Cms\Models\BlogPost;
use Properos\Cms\Models\BlogPostComment;
use Properos\Cms\Classes\CFile;
use Illuminate\Support\Facades\Storage;


class CBlog
{
	private $blog_post;
	private $blog_post_comment;
	private $cFile;

	function __construct(BlogPost $blog_post, BlogPostComment $blog_post_comment, CFile $cFile)
	{
		$this->blog_post = $blog_post;
		$this->blog_post_comment = $blog_post_comment;
		$this->cFile = $cFile;
	}

	public function storePost(array $data)
	{
		if (Auth::check()) {
			$user = Auth::user();
			$blog_post = new BlogPost();

			$blog_post->slug = str_slug($data['title']);
			$getBlog = $blog_post->where('slug',$blog_post->slug)->first();
			if($getBlog){
				$max = $blog_post->max('id');
				$blog_post->slug .='-'.($max+1);
			}

			$blog_post->title = $data['title'];
			$blog_post->header_media_type = $data['header_media_type'];
			if ($data['header_media_type'] == 'video') {
				$blog_post->header_media_path = $data['file'];
			} else if ($data['header_media_type'] == 'picture') {
				
				$data['picture'] = $data['file'];
				$file_path = $this->cFile->uploadFile($data);
				if ($file_path) {
					$blog_post->header_media_path = $file_path;
				}
			}
			$blog_post->summary = substr(strip_tags($data['content']), 0, 150) . '...';
			$blog_post->content = $data['content'];
			$blog_post->user_id = $user->id;
			$blog_post->active = $data['active'];
			$blog_post->keywords = (isset($data['keywords'])) ? $data['keywords'] : null;
			$blog_post->save();
			return $blog_post;
		}
	}

	public function storeComment(array $data)
	{
		if (Auth::check()) {
			$user = Auth::user();
			$post = $this->blog_post->find($data['post_id']);
			if ($post) {
				$blog_post_comment = new BlogPostComment();
				$blog_post_comment->user_id = $user->id;
				$blog_post_comment->comment = $data['content'];
				if ($post->comments()->save($blog_post_comment)) {
					return $blog_post_comment;
				}
			}
		}
	}

	public function updatePost(array $data, $id)
	{
		if (Auth::check()) {
			$user = Auth::user();
			$blog_post = $this->blog_post->find($id);
			if ($blog_post) {
				$blog_post->slug = str_slug($data['title']);
				$getBlog = $blog_post->where([['slug',$blog_post->slug],['id','<>',$id]])->first();
				if($getBlog){
					$blog_post->slug .='-'.$id;
				}
				$blog_post->title = $data['title'];
				$blog_post->header_media_type = $data['header_media_type'];
				if ($data['header_media_type'] == 'video') {
					$blog_post->header_media_path = $data['file'];
				} else if ($data['header_media_type'] == 'picture') {
					if (isset($data['file'])) {
						$old_path = $blog_post->header_media_path;
						$data['picture'] = $data['file'];
						$file_path = $this->cFile->uploadFile($data);
						if ($file_path) {
							$removed_path = Storage::disk('public')->delete($old_path);
							$blog_post->header_media_path = $file_path;
						}
					}
				}
				$blog_post->summary = substr(strip_tags($data['content']), 0, 150) . '...';
				$blog_post->content = $data['content'];
				$blog_post->user_id = $user->id;
				$blog_post->active = $data['active'];
				$blog_post->keywords = (isset($data['keywords'])) ? $data['keywords'] : null;
				$blog_post->save();
				return $blog_post;
			}
		}
	}
}