<?php

return [
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
    | Models config
    |--------------------------------------------------------------------------
    |
    | ## Options
    |
    | - default_img_path                : model default avatar or photo
    |
    | --- uploads                       : model uploads options
    | - relation                        : file is in the relation table and what is relation type [false|hasOne|hasMany]
    | - relation_model                  : relation model [\App\Model etc...]
    | - type                            : file type [image,file]
    | - column                          : file database column
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
            ],
            // product multiple photo options
            'multiple_photo' => [
                'relation'              => 'hasMany',
                'relation_model'        => '\App\ProductPhoto',
                'type'                  => 'image',
                'column'                => 'photos.photo',
            ]
        ]
    ],
    'product_showcase' => [
        'type' => [ 'first', 'first_ten', 'first_hundred', 'random', 'last', 'clear' ]
    ]
];
