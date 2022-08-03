<?php

namespace Properos\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Base\Classes\Api;
use Properos\Cms\Models\Document;

class DocumentController extends Controller
{
    public function documents()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            return view('be.cms.list-document');
        }
    }

    public function create()
    {
        return view('be.cms.create-document');
    }

    public function edit($id)
    {
        if ($id > 0) {
            $document = Document::with('category')->find($id);
            if ($document) {
                return view('be.cms.create-document', [
                    'editable_document' => $document
                ]);
            }
        }
    }

    public function getDocument($type, $id)
    {
        $document = Document::find($id);
        if ($document) {
            if ($document->visibility == 'public') {
                if ($type == 'download') {
                    return response()->download(storage_path('/app/public/' . $document->path), $document->filename);
                } else {
                    return response()->file(storage_path('/app/public/' . $document->path));
                }
            } else {
                if (\Auth::check()) {
                    $user = \Auth::user()->load('units');
                    if ($document) {
                        if ($document->owner_id > 0) {
                            if ($user->isRole('owner')) {
                                if ($user->units->count() > 0) {
                                    foreach ($user->units as $key => $unit) {
                                        if ($unit->id == $document->owner_id) {
                                            if ($type == 'download') {
                                                return response()->download(storage_path('/app/' . $document->path), $document->filename);
                                            } else {
                                                return response()->file(storage_path('/app/' . $document->path));
                                            }
                                        }
                                    }
                                }
                                abort(403);
                            } else if ($user->hasRole('admin') || $user->hasRole('director') || $user->isRole('manager')) {
                                if ($type == 'download') {
                                    return response()->download(storage_path('/app/' . $document->path), $document->filename);
                                } else {
                                    return response()->file(storage_path('/app/' . $document->path));
                                }
                            } else {
                                abort(403);
                            }
                        } else {
                            if ($type == 'download') {
                                return response()->download(storage_path('/app/public/' . $document->path), $document->filename);
                            } else {
                                return response()->file(storage_path('/app/public/' . $document->path));
                            }
                        }
                    } else {
                        abort(404);
                    }
                } else {
                    abort(403);
                }
            }
        } else {
            abort(404);
        }
    }

    public function listCategories()
    {
        return view('be.cms.list-document-categories');
    }

    public function catDocuments($category_slug)
    {
        $category = DocumentCategory::where('slug', $category_slug)->first();
        if ($category) {
            return view('themes.'. ((\Properos\Base\Classes\Theme::get()) ? \Properos\Base\Classes\Theme::get() : 'default') . '.documents')->with([
                'theme' => \Properos\Base\Classes\Theme::get(),
                'category_id' => $category ? $category->id : 0
            ]);
        }
        abort(404);
    }

    public function public_documents()
    {
        return view('themes.'. ((\Properos\Base\Classes\Theme::get()) ? \Properos\Base\Classes\Theme::get() : 'default') . '.documents')->with([
            'theme' => \Properos\Base\Classes\Theme::get(),
            'visibility' => 'public'
        ]);
    }

    public function myDocuments()
    {
        if (Auth::check()) {
            $user = Auth::user()->load('units');
            $units = collect([]);

            if ($user->units->count() > 0) {
                foreach ($user->units as $key => $unit) {
                    $units = $units->push($unit->id);
                }
                return view('themes.'. ((\Properos\Base\Classes\Theme::get()) ? \Properos\Base\Classes\Theme::get() : 'default') . '.documents')->with([
                    'theme' => \Properos\Base\Classes\Theme::get(),
                    'units' => $units,
                    'owner_type' => 'unit'
                ]);
            }
        }
    }
}
