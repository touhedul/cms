<?php

namespace Properos\Cms\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;
use Properos\Base\Classes\Base;
use Properos\Cms\Models\Document;
use Properos\Base\Exceptions\ApiException;

class CDocuments extends Base
{
	function __construct()
	{
		parent::__construct(Document::class, 'Document');
	}

	public function create(array $data)
	{
		if (!isset($data['owner_id'])) {
			$data['owner_id'] = 0;
		}
		if (!isset($data['owner_type'])) {
			$data['owner_type'] = 'admin';
		}
		$check_filename = Document::where([
			['filename', $data['document']->getClientOriginalName()],
			['owner_id', $data['owner_id']],
			['owner_type', $data['owner_type']],
		])->first();
		$document = new Document();
		if ($check_filename) {
			$document->save();
			$data['filename'] = $document->id . '-' . $data['document']->getClientOriginalName();
		} else {
			$data['filename'] = $data['document']->getClientOriginalName();
		}

		if (isset($data['document'])) {
			$document->path = $this->uploadDocument($data);
		}
		if (isset($data['visibility'])) {
			$document->visibility = $data['visibility'];
		}
		$document->label = $data['label'];
		$document->category_id = $data['category_id'];
		$document->filename = $data['filename'];
		$document->extension = $data['document']->getClientOriginalExtension();
		$document->size = $data['document']->getClientSize();
		$document->owner_id = $data['owner_id'];
		$document->owner_type = $data['owner_type'];
		$document->user_id = \Auth::user()->id;

		$document->save();

		return $document;
	}


	public function update(array $data)
	{
		if (isset($data['id']) && $data['id'] > 0) {
			$validator = Validator::make($data, [
				'label' => 'required|max:255',
				'category_id' => 'required|numeric',
			]);
			if ($validator->passes()) {
				$document = Document::find($data['id']);

				if ($document) {
					$document->label = $data['label'];
					$document->category_id = $data['category_id'];
					$document->amendment = $data['amendment'];
					$document->user_id = \Auth::user()->id;
					if (isset($data['visibility'])) {
						$document->visibility = $data['visibility'];
					}
					if ($data['has_file'] == true && isset($data['document'])) {
						if ($document->path != null) {
							$current_file = $document->path;
						}
						$path = $this->uploadDocument($data);
						if ($path) {
							$document->path = $path;
						}
						if (isset($current_file)) {
							\Storage::disk('public')->delete($current_file);
						}
						$document->filename = $data['document']->getClientOriginalName();
						$document->extension = $data['document']->getClientOriginalExtension();
						$document->size = $data['document']->getClientSize();
					}
					$document->save();
					return $document;
				} else {
					throw new ApiException("This Document is already registered", '003');
				}

			} else {
				throw new ApiException($validator->errors(), '004', $data);
			}

		}
		throw new ApiException("Document not found", '404', []);
	}

	public function uploadDocument(array $data)
	{
		$validator = Validator::make($data, [
			'document' => 'sometimes|required',
			'picture_base_64' => 'sometimes|required',
			'owner_id' => 'required|integer',
			'owner_type' => 'required'
		]);
		if (!$validator->fails()) {
			ini_set('memory_limit', '512M');

			$extension = $data['document']->getClientOriginalExtension();
			$owner_type = $data['owner_type'];

			if (in_array($extension, ['jpg', 'png', 'jpeg'])) {
				if (isset($data['document'])) {
					//$image = Storage::disk('public')->putFile('pictures', $data['picture']);
					$img = \Image::make($data['document'])->orientate();
				} else if (isset($data['picture_base_64'])) {
					$img = \Image::make($data['picture_base_64'])->orientate();
				} else {
					throw new ApiException('Document format not supported');
				}
				$storageName = Str::random(40);
				switch ($data['owner_type']) {
					case 'unit':
					case 'admin':
						$stream_image = (string)$img->resize(1920, 720, function ($constraint) {
							$constraint->aspectRatio();
							$constraint->upsize();
						})->encode('jpg', 100);
						$data['path'] = 'pictures/' . $owner_type . '/bigs/' . Str::random(40) . '.jpg';
						if ($data['owner_id'] > 0) {
							Storage::disk('local')->put($data['path'], $stream_image);
						} else {
							Storage::disk('public')->put($data['path'], $stream_image);
						}
						break;
				}
				return $data['path'];
			} else {
				$url = 'documents/' . $owner_type;
				if ($data['owner_id'] > 0) {
					$storagePath = Storage::disk('local')->putFile($url, $data['document']);
				} else {
					$storagePath = Storage::disk('public')->putFile($url, $data['document']);
				}
				$storageName = basename($storagePath);
				return $url . '/' . $storageName;
			}
		} else {
			throw new ApiException($validator->errors());
		}
	}

	public function deleteDocument($id)
	{
	}
}