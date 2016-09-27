<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Illuminate\Support\ServiceProvider;

class LaravelProductModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../config/laravel-product-module.php' => config_path('laravel-product-module.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('ErenMustafaOzdal\LaravelModulesBase\LaravelModulesBaseServiceProvider');
        $this->app->register('Baum\Providers\BaumServiceProvider');
        $this->app->register('Mews\Purifier\PurifierServiceProvider');

        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-product-module.php', 'laravel-product-module'
        );
        // merge default configs with publish configs
        $this->mergeDefaultConfig();

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-product-module.url.product'),  'App\Product');
        $router->model(config('laravel-product-module.url.product_category'),  'App\ProductCategory');
        $router->model(config('laravel-product-module.url.product_brand'),  'App\ProductBrand');
        $router->model(config('laravel-product-module.url.product_showcase'),  'App\ProductShowcase');
    }

    /**
     * merge default configs with publish configs
     */
    protected function mergeDefaultConfig()
    {
        $config = $this->app['config']->get('laravel-product-module', []);
        $default = require __DIR__.'/../config/default.php';

        // admin product category routes
        $route = $config['routes']['admin']['product_category'];
        $default['routes']['admin']['product_category'] = $route;
        // admin product brand routes
        $route = $config['routes']['admin']['product_brand'];
        $default['routes']['admin']['product_brand'] = $route;
        // admin product showcase routes
        $route = $config['routes']['admin']['product_showcase'];
        $default['routes']['admin']['product_showcase'] = $route;
        // admin product routes
        $route = $config['routes']['admin']['product'];
        $default['routes']['admin']['product'] = $route;
        $default['routes']['admin']['product_publish'] = $route;
        $default['routes']['admin']['product_notPublish'] = $route;

        // api product category routes
        $apiCat = $config['routes']['api']['product_category'];
        $default['routes']['api']['product_category'] = $apiCat;
        $default['routes']['api']['product_category_models'] = $apiCat;
        $default['routes']['api']['product_category_move'] = $apiCat;
        // api product brand routes
        $apiCat = $config['routes']['api']['product_brand'];
        $default['routes']['api']['product_brand'] = $apiCat;
        $default['routes']['api']['product_brand_models'] = $apiCat;
        $default['routes']['api']['product_brand_group'] = $apiCat;
        $default['routes']['api']['product_brand_detail'] = $apiCat;
        $default['routes']['api']['product_brand_fastEdit'] = $apiCat;
        // api product showcase routes
        $apiCat = $config['routes']['api']['product_showcase'];
        $default['routes']['api']['product_showcase'] = $apiCat;
        $default['routes']['api']['product_showcase_group'] = $apiCat;
        $default['routes']['api']['product_showcase_detail'] = $apiCat;
        $default['routes']['api']['product_showcase_fastEdit'] = $apiCat;

        // api product routes
        $model = $config['routes']['api']['product'];
        $default['routes']['api']['product'] = $model;
        $default['routes']['api']['product_group'] = $model;
        $default['routes']['api']['product_detail'] = $model;
        $default['routes']['api']['product_fastEdit'] = $model;
        $default['routes']['api']['product_publish'] = $model;
        $default['routes']['api']['product_notPublish'] = $model;
        $default['routes']['api']['product_removePhoto'] = $model;
        $default['routes']['api']['product_setMainPhoto'] = $model;

        $config['routes'] = $default['routes'];


        $path = unsetReturn($config['product']['uploads'],'path');
        $default['product']['uploads']['photo']['path'] = $path;
        $default['product']['uploads']['multiple_photo']['path'] = $path;
        $max_size = unsetReturn($config['product']['uploads'],'max_size');
        $default['product']['uploads']['photo']['max_size'] = $max_size;
        $default['product']['uploads']['multiple_photo']['max_size'] = $max_size;
        $default['product']['uploads']['multiple_photo']['max_file'] = unsetReturn($config['product']['uploads'],'upload_max_file');
        $aspect_ratio = unsetReturn($config['product']['uploads'],'photo_aspect_ratio');
        $default['product']['uploads']['photo']['aspect_ratio'] = $aspect_ratio;
        $default['product']['uploads']['multiple_photo']['aspect_ratio'] = $aspect_ratio;
        $mimes = unsetReturn($config['product']['uploads'],'photo_mimes');
        $default['product']['uploads']['photo']['mimes'] = $mimes;
        $default['product']['uploads']['multiple_photo']['mimes'] = $mimes;
        $thumbnails = unsetReturn($config['product']['uploads'],'photo_thumbnails');
        $default['product']['uploads']['photo']['thumbnails'] = $thumbnails;
        $default['product']['uploads']['multiple_photo']['thumbnails'] = $thumbnails;
        $config['product']['uploads']['photo'] = $default['product']['uploads']['photo'];
        $config['product']['uploads']['multiple_photo'] = $default['product']['uploads']['multiple_photo'];


        $config['product_showcase'] = $default['product_showcase'];

        $this->app['config']->set('laravel-product-module', $config);
    }
}
