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
            'copy'      => 'laravel-modules-core::product.operation',           // get product copy view blade
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
            'photo_vertical_ratio'  => 150/200,
            'photo_horizontal_ratio'=> 200/150,
            'photo_mimes'           => 'jpeg,jpg,jpe,png',
            'photo_thumbnails' => [
                'small'             => [ 'width' => 200, 'height' => 150],
                'normal'            => [ 'width' => 800, 'height' => 600],
                'big'               => [ 'width' => 1200, 'height' => 900],
            ]
        ]
    ],






    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'product_category' => [
            'title'                 => 'Ürün Kategorileri',
            'routes' => [
                'admin.product_category.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini veri tablosunda listeleyebilir.',
                ],
                'admin.product_category.create' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisi ekleyebilir',
                ],
                'admin.product_category.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisi bilgilerini görüntüleyebilir',
                ],
                'admin.product_category.edit' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisini düzenleyebilir',
                ],
                'admin.product_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisini silebilir',
                ],
                'api.product_category.models' => [
                    'title'         => 'Rolleri Listeleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini bazı seçim kutularında listeleyebilir',
                ],
                'api.product_category.move' => [
                    'title'         => 'Taşıma',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini taşıyarak yerini değiştirebilir.',
                ],
            ],
        ],
        'product_brand' => [
            'title'                 => 'Ürün Markaları',
            'routes' => [
                'admin.product_brand.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar markaları veri tablosunda listeleyebilir.',
                ],
                'admin.product_brand.create' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar marka ekleyebilir',
                ],
                'admin.product_brand.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar marka bilgilerini görüntüleyebilir',
                ],
                'admin.product_brand.edit' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar marka bilgilerini düzenleyebilir',
                ],
                'admin.product_brand.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar markayı silebilir',
                ],
                'api.product_brand.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar markalar veri tablosunda toplu işlem yapabilir',
                ],
                'api.product_brand.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar markalar tablosunda detayını görebilir.',
                ]
            ],
        ],
        'product' => [
            'title'                 => 'Ürünler',
            'routes' => [
                'admin.product.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar ürünleri veri tablosunda listeleyebilir.',
                ],
                'admin.product.create' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün ekleyebilir',
                ],
                'admin.product.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar ürün bilgilerini görüntüleyebilir',
                ],
                'admin.product.edit' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürün bilgilerini düzenleyebilir',
                ],
                'admin.product.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürünü silebilir',
                ],
                'api.product.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar ürünler veri tablosunda toplu işlem yapabilir',
                ],
                'api.product.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar ürünler tablosunda detayını görebilir.',
                ]
            ],
        ]
    ],
];
