<?php

namespace Properos\Cms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/public/images' => public_path('/images'),
        ], 'cms');
        $this->publishes([
            __DIR__.'/properos_cms.php' => config_path('properos_cms.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/cms-routes.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->publishes([
            __DIR__.'/resources/assets/be' => resource_path('assets/js/be/modules/cms'),
        ], 'cms');
        $this->publishes([
            __DIR__.'/resources/assets/fe' => resource_path('assets/js/fe/modules/cms'),
        ], 'cms');

        $this->publishes([
            __DIR__.'/resources/views/be/blog' => resource_path('views/be/cms'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/pages' => resource_path('views/be/cms'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/documents' => resource_path('views/be/cms'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/blog.blade.php' => resource_path('views/themes/default/blog.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/pages.blade.php' => resource_path('views/themes/default/pages.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/documents.blade.php' => resource_path('views/themes/default/documents.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/post.blade.php' => resource_path('views/themes/default/post.blade.php'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/fe/layouts/pages' => resource_path('views/themes/default/layouts/pages'),
        ]);
        $this->publishes([
            __DIR__.'/resources/views/be/layouts/menu' => resource_path('views/be/layouts/menu'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Properos\Cms\Controllers\BlogController');
        $this->app->make('Properos\Cms\Controllers\ApiBlogController');
        $this->app->make('Properos\Cms\Controllers\PagesController');
        $this->app->make('Properos\Cms\Controllers\ApiPagesController');
        $this->app->make('Properos\Cms\Controllers\DocumentController');
        $this->app->make('Properos\Cms\Controllers\ApiDocumentController');
        $this->app->make('Properos\Cms\Controllers\ApiCategoryController');
    }
}
