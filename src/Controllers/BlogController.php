<?php

namespace Properos\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Cms\Models\BlogPost;
use Illuminate\Support\Facades\Auth;
use Properos\Cms\Classes\CBlog;
use Properos\Cms\Models\BlogPostComment;
use Properos\Base\Classes\Theme;

class BlogController extends Controller
{
	private $blog_post;
	private $blog_post_comment;

	public function __construct(
		BlogPost $blog_post,
		BlogPostComment $blog_post_comment
	) {
		$this->blog_post = $blog_post;
		$this->blog_post_comment = $blog_post_comment;
	}

	public function index()
	{
			$posts = $this->blog_post->where('active', 1)->with('author')->with('comments.user')->orderBy('created_at', 'DESC')->get();
		    return view('themes.'.Theme::get().'.blog')->with(['posts' => $posts,'theme' => Theme::get()]);
	}

	public function postDetails($slug)
	{
		$settings = null;
		// $settings = Properos\Commerce\Models\Setting::where('key', 'homepage')->first();
		$keywords = '';
		if($settings){
			$settings->data = json_decode($settings->data, true);
			if(isset($settings->data['keywords'])){
				$keywords = $settings->data['keywords'];
			}
		}
		if (Auth::check()) {
			$user = Auth::user();
		}else{
			$user = null;
		}
		if(is_numeric($slug) && $slug >0){
			$post = $this->blog_post->where('active',1)->with('author')->with('comments.user')->find($slug);
			if(!$post){
				$post = $this->blog_post->where('active',1)->with('author')->with('comments.user')->where('slug',$slug)->first();
			}
		}else{
			$post = $this->blog_post->where('active',1)->with('author')->with('comments.user')->where('slug',$slug)->first();
		}

		if ($post) {
			return view('themes.'.Theme::get().'.post')->with(['post' => $post, 'current_user' => $user, 'keywords'=>$keywords, 'theme' => Theme::get()]);
		} else {
			return abort(404);
		}
	}

	public function postTest($slug)
	{
		$settings = null;
		$settings = Properos\Commerce\Models\Setting::where('key', 'homepage')->first();
		$keywords = '';
		if($settings){
			$settings->data = json_decode($settings->data, true);
			if(isset($settings->data['keywords'])){
				$keywords = $settings->data['keywords'];
			}
		}
		if (Auth::check()) {
			$user = Auth::user();
		}else{
			$user = null;
		}
		if(is_numeric($slug) && $slug >0){
			$post = $this->blog_post->with('author')->with('comments.user')->find($slug);
			if(!$post){
				$post = $this->blog_post->with('author')->with('comments.user')->where('slug',$slug)->first();
			}
		}else{
			$post = $this->blog_post->with('author')->with('comments.user')->where('slug',$slug)->first();
		}

		if ($post) {
			return view('themes.'.Theme::get().'.post')->with(['post' => $post, 'current_user' => $user,'keywords'=>$keywords,'theme'=>Theme::get()]);
		} else {
			return abort(404);
		}
	}


	//Authenticated functions
	public function blog()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $posts = $this->blog_post->select('id', 'title', 'summary', 'header_media_type', 'header_media_path', 'slug','active')->orderBy('created_at', 'DESC')->paginate(8)->toJson();
            return view('be.cms.blog')->with(['posts' => $posts]);
        }
	}
	
	public function createPost()
    {
        return view('be.cms.add-blog-post');
	}
	
	public function editPost($id)
    {
        if ($id > 0) {
            $post = $this->blog_post->find($id);
            if ($post) {
                return view('be.cms.add-blog-post')->with(['editable_post' => $post]);
            } else {
                return view('404');
            }
        }
    }
}