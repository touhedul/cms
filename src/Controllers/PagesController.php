<?php

namespace Properos\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Properos\Cms\Models\Page;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Models\Setting;

class PagesController extends Controller
{

	public function show($slug)
	{
        $settings = Setting::where('key', 'homepage')->first();
		$keywords = '';
		if($settings){
			$settings->data = json_decode($settings->data, true);
			if(isset($settings->data['keywords'])){
				$keywords = $settings->data['keywords'];
			}
		}
        $page = Page::where([['m_url',$slug],['visibility',1]])->first();
		if($page){
			return view('themes.'.Theme::get().'.layouts.pages.'.$page->template)->with(['page' => $page, 'keywords'=>$keywords, 'theme' => Theme::get()]);
		}

		return abort(404);
	}

    public function pages()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $pages = Page::get();
            return view('be.cms.pages')->with(['pages' => $pages]);
        }
    }

    public function createPage()
    {
        return view('be.cms.add-page');
    }

    public function editPage($id)
    {
        if ($id > 0) {
            $page = Page::find($id);
            if ($page) {
                return view('be.cms.add-page')->with(['editable_page' => $page]);
            } else {
                return view('404');
            }
        }
    }
}