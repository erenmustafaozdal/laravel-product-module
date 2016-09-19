<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General config
    |--------------------------------------------------------------------------
    */
    'date_format'           => 'd.m.Y H:i:s',
    'icons' => [
        'product'             => 'icon-diamond',
        'product_category'    => 'icon-basket-loaded',
        'product_brand'       => 'icon-badge',
        'product_showcase'    => 'icon-handbag',
    ],

    /*
    |--------------------------------------------------------------------------
    | URL config
    |--------------------------------------------------------------------------
    */
    'url' => [
        'product'                   => 'products',                  // products url
        'product_category'          => 'product-categories',        // product categories url
        'product_brand'             => 'product-brands',            // product categories url
        'product_showcase'          => 'product-showcases',         // product categories url
        'admin_url_prefix'          => 'admin',                     // admin dashboard url prefix
        'middleware'                => ['auth', 'permission']       // product module middleware
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller config
    | if you make some changes on controller, you create your controller
    | and then extend the Laravel Product Module Controller. If you don't need
    | change controller, don't touch this config
    |--------------------------------------------------------------------------
    */
    'controller' => [
        // Admin
        'product_admin_namespace'           => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        'product_category_admin_namespace'  => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        'product_brand_admin_namespace'     => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        'product_showcase_admin_namespace'  => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        // Api
        'product_api_namespace'             => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        'product_category_api_namespace'    => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        'product_brand_api_namespace'       => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        'product_showcase_api_namespace'    => 'ErenMustafaOzdal\LaravelProductModule\Http\Controllers',
        // Controllers
        'product'                           => 'ProductController',
        'product_category'                  => 'ProductCategoryController',
        'product_brand'                     => 'ProductBrandController',
        'product_showcase'                  => 'ProductShowcaseController',
        'product_api'                       => 'ProductApiController',
        'product_category_api'              => 'ProductCategoryApiController',
        'product_brand_api'                 => 'ProductBrandApiController',
        'product_showcase_api'              => 'ProductShowcaseApiController',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes on / off
    | if you don't use any route; set false
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'admin' => [
            'product_category'      => true,        // Is the route to be used categories admin
            'product_brand'         => true,        // Is the route to be used brands admin
            'product_showcase'      => true,        // Is the route to be used showcases admin
            'product'               => true,        // Is the route to be used products admin
        ],
        'api' => [
            'product_category'      => true,        // Is the route to be used categories api
            'product_brand'         => true,        // Is the route to be used brands api
            'product_showcase'      => true,        // Is the route to be used showcases api
            'product'               => true,        // Is the route to be used products api
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | View config
    |--------------------------------------------------------------------------
    | dot notation of blade view path, its position on the /resources/views directory
    */
    'views' => [
        // product view
        'product' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // product layout
            'index'     => 'laravel-modules-core::product.index',               // get product index view blade
            'create'    => 'laravel-modules-core::product.operation',           // get product create view blade
            'show'      => 'laravel-modules-core::product.show',                // get product show view blade
            'edit'      => 'laravel-modules-core::product.operation',           // get product edit view blade
        ],
        // product category view
        'product_category' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // product layout
            'index'     => 'laravel-modules-core::product_category.index',      // get product category index view blade
            'create'    => 'laravel-modules-core::product_category.operation',  // get product category create view blade
            'show'      => 'laravel-modules-core::product_category.show',       // get product category show view blade
            'edit'      => 'laravel-modules-core::product_category.operation',  // get product category edit view blade
        ],
        // product brand view
        'product_brand' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // product layout
            'index'     => 'laravel-modules-core::product_brand.index',         // get product brand index view blade
            'create'    => 'laravel-modules-core::product_brand.operation',     // get product brand create view blade
            'show'      => 'laravel-modules-core::product_brand.show',          // get product brand show view blade
            'edit'      => 'laravel-modules-core::product_brand.operation',     // get product brand edit view blade
        ],
        // product showcase view
        'product_showcase' => [
            'layout'    => 'laravel-modules-core::layouts.admin',               // product layout
            'index'     => 'laravel-modules-core::product_showcase.index',      // get product showcase index view blade
            'create'    => 'laravel-modules-core::product_showcase.operation',  // get product showcase create view blade
            'show'      => 'laravel-modules-core::product_showcase.show',       // get product showcase show view blade
            'edit'      => 'laravel-modules-core::product_showcase.operation',  // get product showcase edit view blade
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - path                            : file path
    | - max_size                        : file allowed maximum size
    | - max_file                        : maximum file count
    | - aspect_ratio                    : if file is image; crop aspect ratio
    | - mimes                           : file allowed mimes
    | - thumbnails                      : if file is image; its thumbnails options
    |
    | NOT: Thumbnails fotoğrafları yüklenirken bakılır:
    |       1. eğer post olarak x1, y1, x2, y2, width ve height değerleri gönderilmemiş ise bu değerlere göre
    |       thumbnails ayarlarında belirtilen resimleri sistem içine kaydeder.
    |       Yani bu değerler post edilmişse aşağıdaki değerleri yok sayar.
    |       2. Eğer yukarıdaki ilgili değerler post edilmemişse, thumbnails ayarlarında belirtilen değerleri
    |       dikkate alarak thumbnails oluşturur
    |
    |       Ölçü Belirtme:
    |       1. İstenen resmin width ve height değerleri verilerek istenen net bir ölçüde resimler oluşturulabilir
    |       2. Width değeri null verilerek, height değerine göre ölçeklenebilir
    |       3. Height değeri null verilerek, width değerine göre ölçeklenebilir
    |--------------------------------------------------------------------------
    */
    'product' => [
        'default_img_path'              => 'vendor/laravel-modules-core/assets/global/img/product',
        'uploads' => [
            'path'                  => 'uploads/product',
            'max_size'              => '5120',
            'upload_max_file'       => 5,
            'photo_aspect_ratio'    => 16/9,
            'photo_mimes'           => 'jpeg,jpg,jpe,png',
            'photo_thumbnails' => [
                'small'             => [ 'width' => 35, 'height' => null],
                'normal'            => [ 'width' => 300, 'height' => null],
                'big'               => [ 'width' => 800, 'height' => null],
            ]
        ]
    ]
];
