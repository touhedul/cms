<?php

use Illuminate\Support\Arr;

Route::group([config('properos_cms.router.default.options'), 'middleware' => 'web'], function(){
    //BLOG
    $middleware = config('properos_cms.router.default.middleware');
    $namespace = 'Properos\Cms\Controllers';

    Route::group(['prefix' => 'blog', 'middleware' => Arr::get($middleware, 'public', [])], function () use($namespace) {
        Route::get('/', $namespace.'\BlogController@index');
        Route::get('/post/{slug}', $namespace.'\BlogController@postDetails');
        Route::get('/test/{slug}', $namespace.'\BlogController@postTest');
    });

    Route::group(['prefix' => 'api/blog', 'middleware' => Arr::get($middleware, 'api/blog', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::post('/post/store', $namespace.'\ApiBlogController@storePost');
        Route::post('/post/update/{id}', $namespace.'\ApiBlogController@updatePost');
        Route::delete('/post/remove/{id}', $namespace.'\ApiBlogController@destroyPost');
        Route::post('/comment/store', $namespace.'\ApiBlogController@storeComment');
        Route::delete('/comment/remove/{id}', $namespace.'\ApiBlogController@destroyComment');
    });


    Route::group(['prefix' => 'api/admin', 'middleware' => Arr::get($middleware, 'api/admin', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::get('blogs', $namespace.'\ApiBlogController@blogs');

        //documents
        Route::group(['prefix' => '/documents'], function () use($namespace) {
    
            Route::post('/store', $namespace.'\ApiDocumentController@store');
            Route::post('/update', $namespace.'\ApiDocumentController@update');
            Route::delete('/remove/{id}', $namespace.'\ApiDocumentController@remove');
            Route::post('/categories/search', $namespace.'\ApiDocumentController@searchCategories');
            Route::post('/categories/store', $namespace.'\ApiDocumentController@storeCategory');
            Route::post('/categories/update', $namespace.'\ApiDocumentController@updateCategory');
            Route::get('/categories/{id}/delete', $namespace.'\ApiDocumentController@deleteCategory');
        });
    });

    Route::group(['prefix' => 'admin', 'middleware' => Arr::get($middleware, 'admin', Arr::get($middleware, 'private', []))], function () use($namespace) {
        //Blog
        Route::get('/blog', $namespace.'\BlogController@blog');
        Route::get('/add-blog-post', $namespace.'\BlogController@createPost');
        Route::get('/edit-blog-post/{id}', $namespace.'\BlogController@editPost');

        //Pages
        Route::get('/pages', $namespace.'\PagesController@pages');
        Route::get('/add-page', $namespace.'\PagesController@createPage');
        Route::get('/edit-page/{id}', $namespace.'\PagesController@editPage');

        Route::group(['prefix' => '/documents'], function () use($namespace) {
            Route::get('/', $namespace.'\DocumentController@documents');
            Route::get('/create', $namespace.'\DocumentController@create');
            Route::get('/edit/{id}', $namespace.'\DocumentController@edit');
            Route::get('/categories', $namespace.'\DocumentController@listCategories');
    
        });
    });

    Route::group(['prefix' => 'pages', 'middleware' => Arr::get($middleware, 'public', [])], function () use($namespace) {
        Route::get('/{slug}', $namespace.'\PagesController@show');
    });
    
    Route::group(['prefix' => 'api/pages', 'middleware' => Arr::get($middleware, 'api/pages', Arr::get($middleware, 'private', []))], function () use($namespace) {
        Route::post('/store', $namespace.'\ApiPagesController@storePage');
        Route::post('/update/{id}', $namespace.'\ApiPagesController@updatePage');
        Route::delete('/remove/{id}', $namespace.'\ApiPagesController@destroyPage');
    });

    Route::post('api/admin/documents/search', $namespace.'\ApiDocumentController@search');

    Route::get('/documents/my-documents', $namespace.'\DocumentController@myDocuments')->middleware('auth')->name('myFiles');
    Route::get('/documents/public', $namespace.'\DocumentController@public_documents')->name('public_documents');
    Route::get('/documents/{category_slug?}', $namespace.'\DocumentController@catDocuments')->name('documents');
    Route::get('/documents/{type}/{id}', $namespace.'\DocumentController@getDocument')->where([
        'type' => '(view|download)'
    ]);
});


