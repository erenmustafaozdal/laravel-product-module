<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ProductBrand;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
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


class ProductBrandApiController extends BaseController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'edit_page'     => ['route' => 'admin.product_brand.edit', 'id' => true]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @return Datatables
     */
    public function index(Request $request)
    {
        $product_brands = ProductBrand::select(['id', 'name', 'created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $product_brands->filter($request);
        }

        $addColumns = [
            'addUrls'           => $this->urls
        ];
        $editColumns = [
            'name'              => function($model) { return $model->name_uc_first; },
            'created_at'        => function($model) { return $model->created_at_table; }
        ];
        return $this->getDatatables($product_brands, $addColumns, $editColumns, []);
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
        $product = ProductBrand::where('id',$id)->select(['id','name','created_at','updated_at']);

        $editColumns = [
            'name'          => function($model) { return $model->name_uc_first; },
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; }
        ];
        return $this->getDatatables($product, [], $editColumns, []);
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
        return ProductBrand::where('id',$id)->first(['id','name']);
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
        return $this->storeModel(ProductBrand::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductBrand $product_brand
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, ProductBrand $product_brand)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product_brand);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductBrand  $product_brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBrand $product_brand)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_brand);
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        $this->clearCache();
        if ( $this->groupAlias(ProductBrand::class) ) {
            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }

    /**
     * clear cache
     *
     * @return void
     */
    private function clearCache()
    {
        // cache forget
        \Cache::forget('home_mini_slider');
        \Cache::forget('home_creative_slider');
        \Cache::forget('home_wide_showcase_small_product');
        \Cache::forget('home_narrow_showcase_small_product_1');
        \Cache::forget('home_narrow_showcase_small_product_2');
        \Cache::forget('home_wide_showcase_big_product');
        \Cache::forget('product_showcase');
        \Cache::forget('product_categories');
        \Cache::forget('product_brands');
        \Cache::forget('product_chances');
        $products = collect(\DB::table('products')->select('id')->get());
        $totalPages = (int) ceil($products->count()/6) + 1;
        $brands = \DB::table('product_brands')->select('id')->get();
        foreach($brands as $brand) {
            \Cache::forget(implode('_',['product_brands',$brand->id]));
            for ($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['products', 'brand', $brand->id, 'page', $i]));
            }
        }
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        return ProductBrand::where('name', 'like', "%{$request->input('query')}%")
            ->get(['id','name'])
            ->map(function($item,$key)
            {
                $item->name = $item->name_uc_first;
                return $item;
            });
    }
}
