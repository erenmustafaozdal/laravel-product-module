<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Product;
use App\ProductCategory;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
// events
use ErenMustafaOzdal\LaravelProductModule\Events\Product\StoreSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\StoreFail;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\UpdateSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\UpdateFail;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\DestroySuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\DestroyFail;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\PublishSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\PublishFail;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\NotPublishSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\Product\NotPublishFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product\ApiStoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product\ApiUpdateRequest;


class ProductApiController extends BaseController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'publish'       => ['route' => 'api.product.publish', 'id' => true],
        'not_publish'   => ['route' => 'api.product.notPublish', 'id' => true],
        'edit_page'     => ['route' => 'admin.product.edit', 'id' => true]
    ];

    /**
     * default realtion urls of the model
     *
     * @var array
     */
    private $relationUrls = [
        'edit_page' => [
            'route'     => 'admin.product_category.product.edit',
            'id'        => 0,
            'model'     => ''
        ],
        'show' => [
            'route'     => 'admin.product_category.product.show',
            'id'        => 0,
            'model'     => ''
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @param integer|null $id
     * @return Datatables
     */
    public function index(Request $request, $id = null)
    {
        // query
        if (is_null($id)) {
            $products = Product::with('category');
        } else {
            $products = ProductCategory::findOrFail($id)->products();
        }
        $products->select(['id', 'category_id', 'name', 'is_publish', 'created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $products->filter($request);
        }

        // urls
        $addUrls = $this->urls;
        if( ! is_null($id)) {
            $this->relationUrls['edit_page']['id'] = $id;
            $this->relationUrls['edit_page']['model'] = config('laravel-product-module.url.product');
            $this->relationUrls['show']['id'] = $id;
            $this->relationUrls['show']['model'] = config('laravel-product-module.url.product');
            $addUrls = array_merge($addUrls, $this->relationUrls);
        }
        $addColumns = [
            'addUrls'           => $addUrls,
            'status'            => function($model) { return $model->is_publish; }
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; }
        ];
        $removeColumns = ['is_publish'];
        return $this->getDatatables($products, $addColumns, $editColumns, $removeColumns);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return Datatables
     */
    public function detail($id, Request $request)
    {
        $product = Product::with(['category', 'province', 'county', 'district', 'neighborhood', 'postalCode'])
            ->where('id',$id)
            ->select(['id','category_id','name','province_id','county_id','district_id','neighborhood_id','postal_code_id','address','land_phone','mobile_phone','url','created_at','updated_at']);

        $editColumns = [
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; },
            'address'       => function($model) { return $model->full_address; },
        ];
        $removeColumns = ['province_id','province','county_id','county','district_id','district','neighborhood_id','neighborhood','postal_code_id','postal_code'];
        return $this->getDatatables($product, [], $editColumns, $removeColumns);
    }

    /**
     * get model data for edit
     *
     * @param integer $id
     * @param Request $request
     * @return Product
     */
    public function fastEdit($id, Request $request)
    {
        return Product::with(['category','province','county','district','neighborhood','postalCode'])
            ->where('id',$id)
            ->first(['id','category_id','name','province_id','county_id','district_id','neighborhood_id','postal_code_id']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(Product::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Product $product
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, Product $product)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product);
    }

    /**
     * publish model
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function publish(Product $product)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($product, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ]);
    }

    /**
     * not publish model
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function notPublish(Product $product)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($product, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ]);
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        if ( $this->groupAlias(Product::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }
}
