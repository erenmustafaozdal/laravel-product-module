<?php
//max level nested function 100 hatasını düzeltiyor
ini_set('xdebug.max_nesting_level', 300);

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
/*==========  Product Category Module  ==========*/
Route::group([
    'prefix' => config('laravel-product-module.url.admin_url_prefix'),
    'middleware' => config('laravel-product-module.url.middleware'),
    'namespace' => config('laravel-product-module.controller.product_category_admin_namespace')
], function()
{
    if (config('laravel-product-module.routes.admin.product_category')) {
        Route::resource(config('laravel-product-module.url.product_category'), config('laravel-product-module.controller.product_category'), [
            'names' => [
                'index'         => 'admin.product_category.index',
                'create'        => 'admin.product_category.create',
                'store'         => 'admin.product_category.store',
                'show'          => 'admin.product_category.show',
                'edit'          => 'admin.product_category.edit',
                'update'        => 'admin.product_category.update',
                'destroy'       => 'admin.product_category.destroy',
            ]
        ]);
    }
});

/*==========  Product Brand Module  ==========*/
Route::group([
    'prefix' => config('laravel-product-module.url.admin_url_prefix'),
    'middleware' => config('laravel-product-module.url.middleware'),
    'namespace' => config('laravel-product-module.controller.product_brand_admin_namespace')
], function()
{
    if (config('laravel-product-module.routes.admin.product_brand')) {
        Route::resource(config('laravel-product-module.url.product_brand'), config('laravel-product-module.controller.product_brand'), [
            'names' => [
                'index'         => 'admin.product_brand.index',
                'create'        => 'admin.product_brand.create',
                'store'         => 'admin.product_brand.store',
                'show'          => 'admin.product_brand.show',
                'edit'          => 'admin.product_brand.edit',
                'update'        => 'admin.product_brand.update',
                'destroy'       => 'admin.product_brand.destroy',
            ]
        ]);
    }
});

/*==========  Product Showcase Module  ==========*/
Route::group([
    'prefix' => config('laravel-product-module.url.admin_url_prefix'),
    'middleware' => config('laravel-product-module.url.middleware'),
    'namespace' => config('laravel-product-module.controller.product_showcase_admin_namespace')
], function()
{
    if (config('laravel-product-module.routes.admin.product_showcase')) {
        Route::resource(config('laravel-product-module.url.product_showcase'), config('laravel-product-module.controller.product_showcase'), [
            'names' => [
                'index'         => 'admin.product_showcase.index',
                'create'        => 'admin.product_showcase.create',
                'store'         => 'admin.product_showcase.store',
                'show'          => 'admin.product_showcase.show',
                'edit'          => 'admin.product_showcase.edit',
                'update'        => 'admin.product_showcase.update',
                'destroy'       => 'admin.product_showcase.destroy',
            ]
        ]);
    }
});

/*==========  Product Module  ==========*/
Route::group([
    'prefix'        => config('laravel-product-module.url.admin_url_prefix'),
    'middleware'    => config('laravel-product-module.url.middleware'),
    'namespace'     => config('laravel-product-module.controller.product_admin_namespace')
], function()
{
    // admin publish product
    if (config('laravel-product-module.routes.admin.product_publish')) {
        Route::get('product/{' . config('laravel-product-module.url.product') . '}/publish', [
            'as'                => 'admin.product.publish',
            'uses'              => config('laravel-product-module.controller.product').'@publish'
        ]);
    }
    // admin not publish product
    if (config('laravel-product-module.routes.admin.product_notPublish')) {
        Route::get('product/{' . config('laravel-product-module.url.product') . '}/not-publish', [
            'as'                => 'admin.product.notPublish',
            'uses'              => config('laravel-product-module.controller.product').'@notPublish'
        ]);
    }
    if (config('laravel-product-module.routes.admin.product')) {
        Route::resource(config('laravel-product-module.url.product'), config('laravel-product-module.controller.product'), [
            'names' => [
                'index'         => 'admin.product.index',
                'create'        => 'admin.product.create',
                'store'         => 'admin.product.store',
                'show'          => 'admin.product.show',
                'edit'          => 'admin.product.edit',
                'update'        => 'admin.product.update',
                'destroy'       => 'admin.product.destroy',
            ]
        ]);
    }
});



/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
/*==========  Product Category Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-product-module.url.middleware'),
    'namespace'     => config('laravel-product-module.controller.product_category_api_namespace')
], function()
{
    // api product category
    if (config('laravel-product-module.routes.api.product_category_models')) {
        Route::post('product-category/models', [
            'as'                => 'api.product_category.models',
            'uses'              => config('laravel-product-module.controller.product_category_api').'@models'
        ]);
    }
    // api product category move
    if (config('laravel-product-module.routes.api.product_category_move')) {
        Route::post('product-category/{id}/move', [
            'as'                => 'api.product_category.move',
            'uses'              => config('laravel-product-module.controller.product_category_api').'@move'
        ]);
    }
    // data table detail row
    if (config('laravel-product-module.routes.api.product_category_detail')) {
        Route::get('product-category/{id}/detail', [
            'as'                => 'api.product_category.detail',
            'uses'              => config('laravel-product-module.controller.product_category_api').'@detail'
        ]);
    }
    // product category resource
    if (config('laravel-product-module.routes.api.product_category')) {
        Route::resource(config('laravel-product-module.url.product_category'), config('laravel-product-module.controller.product_category_api'), [
            'names' => [
                'index'         => 'api.product_category.index',
                'store'         => 'api.product_category.store',
                'update'        => 'api.product_category.update',
                'destroy'       => 'api.product_category.destroy',
            ]
        ]);
    }
});

/*==========  Product Brand Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-product-module.url.middleware'),
    'namespace'     => config('laravel-product-module.controller.product_brand_api_namespace')
], function()
{
    // api group action
    if (config('laravel-product-module.routes.api.product_brand_group')) {
        Route::post('product-brand/group-action', [
            'as'                => 'api.product_brand.group',
            'uses'              => config('laravel-product-module.controller.product_brand_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-product-module.routes.api.product_brand_detail')) {
        Route::get('product-brand/{id}/detail', [
            'as'                => 'api.product_brand.detail',
            'uses'              => config('laravel-product-module.controller.product_brand_api').'@detail'
        ]);
    }
    // get product brand edit data for modal edit
    if (config('laravel-product-module.routes.api.product_brand_fastEdit')) {
        Route::post('product-brand/{id}/fast-edit', [
            'as'                => 'api.product_brand.fastEdit',
            'uses'              => config('laravel-product-module.controller.product_brand_api').'@fastEdit'
        ]);
    }
    if (config('laravel-product-module.routes.api.product_brand')) {
        Route::resource(config('laravel-product-module.url.product_brand'), config('laravel-product-module.controller.product_brand_api'), [
            'names' => [
                'index'         => 'api.product_brand.index',
                'store'         => 'api.product_brand.store',
                'update'        => 'api.product_brand.update',
                'destroy'       => 'api.product_brand.destroy',
            ]
        ]);
    }
});

/*==========  Product Showcase Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-product-module.url.middleware'),
    'namespace'     => config('laravel-product-module.controller.product_showcase_api_namespace')
], function()
{
    // api group action
    if (config('laravel-product-module.routes.api.product_showcase_group')) {
        Route::post('product-showcase/group-action', [
            'as'                => 'api.product_showcase.group',
            'uses'              => config('laravel-product-module.controller.product_showcase_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-product-module.routes.api.product_showcase_detail')) {
        Route::get('product-showcase/{id}/detail', [
            'as'                => 'api.product_showcase.detail',
            'uses'              => config('laravel-product-module.controller.product_showcase_api').'@detail'
        ]);
    }
    // get product showcase edit data for modal edit
    if (config('laravel-product-module.routes.api.product_showcase_fastEdit')) {
        Route::post('product-showcase/{id}/fast-edit', [
            'as'                => 'api.product_showcase.fastEdit',
            'uses'              => config('laravel-product-module.controller.product_showcase_api').'@fastEdit'
        ]);
    }
    if (config('laravel-product-module.routes.api.product_showcase')) {
        Route::resource(config('laravel-product-module.url.product_showcase'), config('laravel-product-module.controller.product_showcase_api'), [
            'names' => [
                'index'         => 'api.product_showcase.index',
                'store'         => 'api.product_showcase.store',
                'update'        => 'api.product_showcase.update',
                'destroy'       => 'api.product_showcase.destroy',
            ]
        ]);
    }
});

/*==========  Product Module  ==========*/
Route::group([
    'prefix'        => 'api',
    'middleware'    => config('laravel-product-module.url.middleware'),
    'namespace'     => config('laravel-product-module.controller.product_api_namespace')
], function()
{
    // api group action
    if (config('laravel-product-module.routes.api.product_group')) {
        Route::post('product/group-action', [
            'as'                => 'api.product.group',
            'uses'              => config('laravel-product-module.controller.product_api').'@group'
        ]);
    }
    // data table detail row
    if (config('laravel-product-module.routes.api.product_detail')) {
        Route::get('product/{id}/detail', [
            'as'                => 'api.product.detail',
            'uses'              => config('laravel-product-module.controller.product_api').'@detail'
        ]);
    }
    // get product category edit data for modal edit
    if (config('laravel-product-module.routes.api.product_fastEdit')) {
        Route::post('product/{id}/fast-edit', [
            'as'                => 'api.product.fastEdit',
            'uses'              => config('laravel-product-module.controller.product_api').'@fastEdit'
        ]);
    }
    // api publish product
    if (config('laravel-product-module.routes.api.product_publish')) {
        Route::post('product/{' . config('laravel-product-module.url.product') . '}/publish', [
            'as'                => 'api.product.publish',
            'uses'              => config('laravel-product-module.controller.product_api').'@publish'
        ]);
    }
    // api not publish product
    if (config('laravel-product-module.routes.api.product_notPublish')) {
        Route::post('product/{' . config('laravel-product-module.url.product') . '}/not-publish', [
            'as'                => 'api.product.notPublish',
            'uses'              => config('laravel-product-module.controller.product_api').'@notPublish'
        ]);
    }
    if (config('laravel-product-module.routes.api.product')) {
        Route::resource(config('laravel-product-module.url.product'), config('laravel-product-module.controller.product_api'), [
            'names' => [
                'index'         => 'api.product.index',
                'store'         => 'api.product.store',
                'update'        => 'api.product.update',
                'destroy'       => 'api.product.destroy',
            ]
        ]);
    }
});
