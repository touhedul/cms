<?php

namespace Properos\Cms\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Properos\Base\Classes\Base;
use Properos\Base\Exceptions\ApiException;
use Properos\Cms\Classes\CFile;
use Properos\Cms\Models\Page;
use Properos\Cms\Models\File;


class CPages extends Base
{
	private $cFile;
	function __construct( CFile $cFile)
	{
		parent::__construct(Page::class, 'Page');  
		$this->cFile = $cFile; 
	}

	public function create(array $data)
	{
		$check_url = Page::where('m_url',str_slug($data['m_url']))->first();
		if($check_url){
			throw new ApiException('Url already exist','006',$data['m_url']);
		}
			
		if(isset($data['file'])){
			$data['picture'] = $data['file'];
			$data['owner_id'] = (Page::max('id') + 1);
			$file_path = $this->cFile->uploadFile($data);
			if ($file_path) {
				$data['header_media_path'] = $file_path->path;
			}
		}
		$data['user_id'] = \Auth::user()->id;
		$data['m_url'] = str_slug($data['m_url']);
		
		return $this->createModel($data);
	}


	public function update(array $data)
	{
		$check_page = Page::find($data['id']);
		if ($check_page) {
			$check_url = Page::where([['m_url',str_slug($data['m_url'])],['id','<>',$data['id']]])->first();
			if($check_url){
				throw new ApiException('Url already exist','006',$data['m_url']);
			}
			$files = File::where([['owner_type','page'],['owner_id',$data['id']]])->get();
			
			if(isset($data['file'])){
				$data['picture'] = $data['file'];
				$data['owner_id'] = $data['id'];
				$file_path = $this->cFile->uploadFile($data);
				if ($file_path) {
					$data['header_media_path'] = $file_path->path;
				}
				if($files){
					foreach($files as $file){
						$this->cFile->deleteFile($file->id);
					}
				}
			}else if(isset($data['header_media_path'])){
				$data['header_media_path'] = $data['header_media_path'];
			}else{
				$data['header_media_path'] = null;
				if($files){
					foreach($files as $file){
						$this->cFile->deleteFile($file->id);
					}
				}
			}
			
			
			$data['user_id'] = \Auth::user()->id;
			$data['m_url'] = str_slug($data['m_url']);
			return $this->updateModel($data);
		}
		throw new ApiException('Page doesn\'t exist','006',$data);
	}
}