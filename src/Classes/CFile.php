<?php

namespace Properos\Cms\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Properos\Cms\Models\File as MFile;
use Properos\Base\Exceptions\ApiException;
use Validator;
use Properos\Cms\Models\Page;
use Properos\Cms\Models\BlogPost;

class CFile
{
	private $file;

	function __construct(MFile $file)
	{
		$this->file = $file;
	}

	public function uploadFile(array $data)
	{
		$validator = Validator::make($data, [
			'picture' => 'sometimes|required|file|image|dimensions:min_width=640,min_height=480',
			'picture_base_64' => 'sometimes|required',
			'owner_id' => 'sometimes|required|integer',
			'owner_type' => 'required|alpha|in:post,page'
		]);
		if (!$validator->fails()) {
			/* try { */
			ini_set('memory_limit', '512M');
			if (isset($data['picture'])) {
				//$image = Storage::disk('public')->putFile('pictures', $data['picture']);
				$img = \Image::make($data['picture'])->orientate();
			} else if (isset($data['picture_base_64'])) {
				$img = \Image::make($data['picture_base_64'])->orientate();
			} else {
				throw new ApiException('File format not supported');
			}
			$owner_type = $data['owner_type'];

			switch ($data['owner_type']) {
				case 'post':
				case 'page':
					$stream_image = (string)$img->resize(1920, 720, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode('jpg', 100);

					$data['path'] = 'pictures/' . $owner_type . '/bigs/' . Str::random(40) . '.jpg';
					Storage::disk('public')->put($data['path'], $stream_image);
					break;
				default:
					break;
			}
			//Storage::disk('public')->delete($image);
			if ($data['owner_type'] == 'post') {
				return $data['path'];
			} else {
				$file = $this->file->store($data);
				if ($file != null) {
					return $file;
				} else {
					return false;
				}
			}
			/* } catch (\Exception $e) {
				throw new ApiException('Error saving file', '001', $data);
			} */
		} else {
			throw new ApiException($validator->errors());
		}
	}

	public function deleteFile($id)
	{
		try {
			if ($id) {
				$file = $this->file->findOrFail($id);
				if ($file) {

					$path = $file->path;
					if ($file->thumb_path) {
						$thumb_path = $file->thumb_path;

					}
					$owner_type = $file->owner_type;
					$removed = $file->delete();
					if ($removed) {
						$removed_path = Storage::disk('public')->delete($path);
						if ($removed_path) {
							if ($thumb_path) {
								$removed_thumb = Storage::disk('public')->delete($thumb_path);
							}
							if ($owner_type == 'post') {
								return new BlogPost();
							} else if ($owner_type == 'page') {
								return new Page();
							}
						} else {
							return null;
						}
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}
	}

}