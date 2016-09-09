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

        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-product-module.php', 'laravel-product-module'
        );

        $router = $this->app['router'];
        // model binding
        $router->model(config('laravel-product-module.url.product'),  'App\Product');
        $router->model(config('laravel-product-module.url.product_category'),  'App\ProductCategory');
        $router->model(config('laravel-product-module.url.product_brand'),  'App\ProductBrand');
        $router->model(config('laravel-product-module.url.product_showcase'),  'App\ProductShowcase');
    }
}
