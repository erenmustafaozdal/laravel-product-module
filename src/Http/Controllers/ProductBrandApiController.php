<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ProductBrand;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\StoreSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\StoreFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\UpdateSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\UpdateFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\DestroySuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductBrand\ApiStoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductBrand\ApiUpdateRequest;
// services
use LMBCollection;


class ProductBrandApiController extends BaseNodeController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'publish'       => ['route' => 'api.product_brand.publish', 'id' => true],
        'not_publish'   => ['route' => 'api.product_brand.notPublish', 'id' => true],
        'edit_page'     => ['route' => 'admin.product_brand.edit', 'id' => true]
    ];

    /**
     * default realtion urls of the model
     *
     * @var array
     */
    private $relationUrls = [
        'edit_page' => [
            'route'     => 'admin.product_category.product_brand.edit',
            'id'        => 0,
            'model'     => ''
        ],
        'show' => [
            'route'     => 'admin.product_category.product_brand.show',
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

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        if($request->has('id')) {
            $dealer_category = ProductBrand::find($request->input('id'));
            $models = $dealer_category->descendants()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = ProductBrand::where('name', 'like', "%{$request->input('query')}%");
        }

        $models = $models->get(['id','parent_id','lft','rgt','depth','name'])
            ->toHierarchy();
        return LMBCollection::relationRender($models, 'children', '/', ['name']);
    }
}
