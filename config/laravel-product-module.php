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
            // resource
            'product'                   => true,    // admin product resource route
            'product_category'          => true,    // admin product category resource route
            'product_brand'             => true,    // admin product brand resource route
            'product_showcase'          => true,    // admin product showcase resource route
            // others
            'product_copy'              => true,   // admin product copy get route
            'product_storeCopy'         => true,   // admin product store copy post route
            'product_publish'           => true,   // admin product publish get route
            'product_notPublish'        => true,   // admin product not publish get route
        ],
        'api' => [
            // resource
            'product'                   => true,   // api product resource route
            'product_category'          => true,   // api product category resource route
            'product_brand'             => true,   // api product brand resource route
            'product_showcase'          => true,   // api product showcase resource route
            // others
            'product_group'             => true,   // api product group post route
            'product_detail'            => true,   // api product detail get route
            'product_fastEdit'          => true,   // api product fast edit post route
            'product_publish'           => true,   // api product publish post route
            'product_notPublish'        => true,   // api product not publish post route
            'product_removePhoto'       => true,   // api product destroy photo post route
            'product_setMainPhoto'      => true,   // api product set main photo post route
            'product_category_models'   => true,   // api product category model post route
            'product_category_move'     => true,   // api product category move post route
            'product_category_detail'   => true,   // api product category detail post route
            'product_brand_models'      => true,   // api product brand model post route
            'product_brand_group'       => true,   // api product brand group post route
            'product_brand_detail'      => true,   // api product brand detail get route
            'product_brand_fastEdit'    => true,   // api product brand fast edit post route
            'product_showcase_group'    => true,   // api product showcase group post route
            'product_showcase_detail'   => true,   // api product showcase detail get route
            'product_showcase_fastEdit' => true,   // api product showcase fast edit post route
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
            // product photo options
            'photo' => [
                'relation'              => 'hasOne',
                'relation_model'        => '\App\ProductPhoto',
                'type'                  => 'image',
                'column'                => 'mainPhoto.photo',
                'path'                  => 'uploads/product',
                'max_size'              => '5120',
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'vertical_ratio'        => 150/200,
                'horizontal_ratio'      => 200/150,
                'thumbnails' => [
                    'small'             => [ 'width' => 200, 'height' => 150],
                    'normal'            => [ 'width' => 800, 'height' => 600],
                    'big'               => [ 'width' => 1200, 'height' => 900],
                ]
            ],
            // product multiple photo options
            'multiple_photo' => [
                'relation'              => 'hasMany',
                'relation_model'        => '\App\ProductPhoto',
                'type'                  => 'image',
                'column'                => 'photos.photo',
                'path'                  => 'uploads/product',
                'max_size'              => '5120',
                'max_file'              => 5,
                'mimes'                 => 'jpeg,jpg,jpe,png',
                'vertical_ratio'        => 150/200,
                'horizontal_ratio'      => 200/150,
                'thumbnails' => [
                    'small'             => [ 'width' => 200, 'height' => 150],
                    'normal'            => [ 'width' => 800, 'height' => 600],
                    'big'               => [ 'width' => 1200, 'height' => 900],
                ]
            ]
        ]
    ],
    'product_showcase' => [
        'type' => [ 'first', 'first_ten', 'first_hundred', 'random', 'last', 'clear' ]
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
                    'description'   => 'Bu izne sahip olanlar ürün kategorileri veri tablosu sayfasına gidebilir.',
                ],
                'admin.product_category.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisi ekleme sayfasına gidebilir',
                ],
                'admin.product_category.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisi ekleyebilir',
                ],
                'admin.product_category.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisi bilgilerini görüntüleyebilir',
                ],
                'admin.product_category.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisini düzenleme sayfasına gidebilir',
                ],
                'admin.product_category.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisini düzenleyebilir',
                ],
                'admin.product_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorisini silebilir',
                ],
                'api.product_category.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini veri tablosunda listeleyebilir',
                ],
                'api.product_category.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini veri tablosunda hızlı ekleyebilir.',
                ],
                'api.product_category.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.product_category.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini veri tablosunda silebilir',
                ],
                'api.product_category.models' => [
                    'title'         => 'Seçim İçin Listeleme',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini bazı seçim kutularında listeleyebilir',
                ],
                'api.product_category.move' => [
                    'title'         => 'Taşıma',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerini taşıyarak yerini değiştirebilir.',
                ],
                'api.product_category.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar ürün kategorilerinin detay bilgilerini getirebilir.',
                ],
            ],
        ],
        'product_brand' => [
            'title'                 => 'Ürün Markaları',
            'routes' => [
                'admin.product_brand.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar ürün markaları veri tablosu sayfasına gidebilir.',
                ],
                'admin.product_brand.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürün markası ekleme sayfasına gidebilir',
                ],
                'admin.product_brand.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün markası ekleyebilir',
                ],
                'admin.product_brand.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar ürün markası bilgilerini görüntüleyebilir',
                ],
                'admin.product_brand.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürün markasını düzenleme sayfasına gidebilir',
                ],
                'admin.product_brand.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürün markasını düzenleyebilir',
                ],
                'admin.product_brand.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürün markasını silebilir',
                ],
                'api.product_brand.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar ürün markalarını veri tablosunda listeleyebilir',
                ],
                'api.product_brand.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün markalarını veri tablosunda hızlı ekleyebilir.',
                ],
                'api.product_brand.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürün markalarını veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.product_brand.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürün markalarını veri tablosunda silebilir',
                ],
                'api.product_brand.models' => [
                    'title'         => 'Seçim İçin Listeleme',
                    'description'   => 'Bu izne sahip olanlar ürün markalarını bazı seçim kutularında listeleyebilir',
                ],
                'api.product_brand.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar ürün markaları veri tablosunda toplu işlem yapabilir',
                ],
                'api.product_brand.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar ürün markaları tablosunda detayını görebilir.',
                ],
                'api.product_brand.fastEdit' => [
                    'title'         => 'Hızlı Düzenleme Bilgileri',
                    'description'   => 'Bu izne sahip olanlar ürün markaları tablosunda hızlı düzenleme amacıyla bilgileri getirebilir.',
                ],
            ],
        ],
        'product' => [
            'title'                 => 'Ürünler',
            'routes' => [
                'admin.product.index' => [
                    'title'         => 'Veri Tablosu',
                    'description'   => 'Bu izne sahip olanlar ürünler veri tablosu sayfasına gidebilir.',
                ],
                'admin.product.create' => [
                    'title'         => 'Ekleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürün ekleme sayfasına gidebilir',
                ],
                'admin.product.store' => [
                    'title'         => 'Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürün ekleyebilir',
                ],
                'admin.product.show' => [
                    'title'         => 'Gösterme',
                    'description'   => 'Bu izne sahip olanlar ürün bilgilerini görüntüleyebilir',
                ],
                'admin.product.edit' => [
                    'title'         => 'Düzenleme Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürünü düzenleme sayfasına gidebilir',
                ],
                'admin.product.update' => [
                    'title'         => 'Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürünü düzenleyebilir',
                ],
                'admin.product.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürünü silebilir',
                ],
                'admin.product.copy' => [
                    'title'         => 'Kopyalama Sayfası',
                    'description'   => 'Bu izne sahip olanlar ürünü kopyalama sayfasına gidebilir.',
                ],
                'admin.product.storeCopy' => [
                    'title'         => 'Kopyalama',
                    'description'   => 'Bu izne sahip olanlar ürünü kopyalayabilir.',
                ],
                'admin.product.publish' => [
                    'title'         => 'Yayınlama',
                    'description'   => 'Bu izne sahip olanlar ürünü yayınlayabilir',
                ],
                'admin.product.notPublish' => [
                    'title'         => 'Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar ürünü yayından kaldırabilir',
                ],
                'api.product.index' => [
                    'title'         => 'Listeleme',
                    'description'   => 'Bu izne sahip olanlar ürünleri veri tablosunda listeleyebilir',
                ],
                'api.product.store' => [
                    'title'         => 'Hızlı Ekleme',
                    'description'   => 'Bu izne sahip olanlar ürünleri veri tablosunda hızlı ekleyebilir.',
                ],
                'api.product.update' => [
                    'title'         => 'Hızlı Düzenleme',
                    'description'   => 'Bu izne sahip olanlar ürünleri veri tablosunda hızlı düzenleyebilir.',
                ],
                'api.product.destroy' => [
                    'title'         => 'Silme',
                    'description'   => 'Bu izne sahip olanlar ürünleri veri tablosunda silebilir',
                ],
                'api.product.group' => [
                    'title'         => 'Toplu İşlem',
                    'description'   => 'Bu izne sahip olanlar ürünler veri tablosunda toplu işlem yapabilir',
                ],
                'api.product.detail' => [
                    'title'         => 'Detaylar',
                    'description'   => 'Bu izne sahip olanlar ürünler tablosunda detayını görebilir.',
                ],
                'api.product.fastEdit' => [
                    'title'         => 'Hızlı Düzenleme Bilgileri',
                    'description'   => 'Bu izne sahip olanlar ürünler tablosunda hızlı düzenleme amacıyla bilgileri getirebilir.',
                ],
                'api.product.publish' => [
                    'title'         => 'Hızlı Yayınlama',
                    'description'   => 'Bu izne sahip olanlar ürünler tablosunda ürünü yayınlanyabilir.',
                ],
                'api.product.notPublish' => [
                    'title'         => 'Hızlı Yayından Kaldırma',
                    'description'   => 'Bu izne sahip olanlar ürünler tablosunda ürünü yayından kaldırabilir.',
                ],
                'api.product.removePhoto' => [
                    'title'         => 'Fotoğraf Silme',
                    'description'   => 'Bu izne sahip olanlar fotoğraf silebilir.',
                ],
                'api.product.setMainPhoto' => [
                    'title'         => 'Ana Fotoğraf',
                    'description'   => 'Bu izne sahip olanlar fotoğrafı ürünün ana fotoğrafı yapabilir.',
                ],
            ],
        ]
    ],
];
