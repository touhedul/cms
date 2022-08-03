## Properos CMS

CMS package.

**Required intervention/image package**
composer require intervention/image
Configuration => http://image.intervention.io/getting_started/introduction

**Required moment.js**
npm install moment 

**Required Vue YouTube Embed**
npm i -S vue-youtube-embed
https://github.com/kaorun343/vue-youtube-embed

**Add on resources/assets/bootstrap.js if not exist**
```js
    import Helpers from './misc/helpers'
    import VueYouTubeEmbed from 'vue-youtube-embed';

    window.moment = require('moment')
    window.Vue = require('vue');
    window.Helpers = Helpers;
    Vue.use(VueYouTubeEmbed)
```

**Create env.js**

**Add on config/app.php**
```php
    'providers' => [
        '...',
        Properos\Cms\CmsServiceProvider::class,
        '...'
    ]
```

**Register provider on composer.json**
```json
    "autoload": {
    "...": {},
        "psr-4": {
            "App\\": "app/",
            "Properos\\Cms\\": "packages/properos/properos-cms/src"
        }
    },
```

**Run**
```php
    composer dump
    php artisan vendor:publish 
    Select -> Properos\Cms\CmsServiceProvider  
    php artisan storage:link
    ```

**Add on webpack.mix.js**
.js('resources/assets/js/be/modules/cms/js/cms.js', 'public/be/js/modules/cms.js')
.js('resources/assets/js/fe/modules/cms/js/cms.js', 'public/fe/js/modules/cms.js')

**config/properos_cms.php file**
Set the middleware for the routes.

**How to use a Model**
\Properos\Cms\Models\Model-Name


**Modify config/database.php**
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => 'Innodb',
],
```

**Run migrations**
```php
php artisan migrate
    create  blog_posts table
            blog_post_comments table
            pages table
```

**Add on AppServiceProvider**
```php
use Illuminate\Support\Facades\View;
use Properos\Cms\Models\DocumentCategory;

public function boot()
{
    View::share('document_categories', DocumentCategory::all());
}
```



