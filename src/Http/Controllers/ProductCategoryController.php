<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\ProductCategory;
use App\Product;
use Laracasts\Flash\Flash;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\StoreSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\StoreFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\UpdateSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\UpdateFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\DestroySuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductCategory\StoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductCategory\UpdateRequest;

class ProductCategoryController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        if (is_null($id)) {
            return view(config('laravel-product-module.views.product_category.index'));
        }

        $parent_product_category = ProductCategory::findOrFail($id);
        return view(config('laravel-product-module.views.product_category.index'), compact('parent_product_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id = null)
    {
        // eğer product ids var ve token hatalı ise hata döndür
        if ( $request->has('product_ids') && ( ! $request->has('_token') || session('_token') !== $request->input('_token') ) ) {
            abort(403);
        }

        // product ids var ise oluştur
        $products = $request->has('product_ids') ? Product::with('video','photo')->whereIn('id', explode(',', $request->product_ids))->get() : collect();

        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-product-module.views.product_category.create'), compact('operation','products'));
        }

        $parent_product_category = ProductCategory::findOrFail($id);
        $type = $parent_product_category->type;
        $types = $products->groupBy('type');

        // kategori olduğu için; gelen medyalar kategorinin tipine uygun olmalı
        if ( $type !== 'mixed' && ($types->count() === 1 && $types->keys()->first() !== $type) ) {
            Flash::error(lmcTrans('laravel-product-module/admin.flash.product_incompatible', [
                'type' => lmcTrans("laravel-product-module/admin.fields.product.{$type}")
            ]));
            return back();
        }
        return view(config('laravel-product-module.views.product_category.create'), compact('parent_product_category','operation','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, $id = null)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        if (is_null($id)) {
            $redirect = 'index';
            return $this->storeModel(ProductCategory::class,$redirect);
        }
        $redirect = 'product_category.product_category.index';
        $this->setRelationRouteParam($id, config('laravel-product-module.url.product_category'));
        $this->setDefineValues(['type']);
        return $this->storeNode(ProductCategory::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|ProductCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $product_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-product-module.views.product_category.show'), compact('product_category'));
        }

        $parent_product_category = ProductCategory::findOrFail($firstId);
        return view(config('laravel-product-module.views.product_category.show'), compact('parent_product_category','product_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|ProductCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $product_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-product-module.views.product_category.edit'), compact('product_category','operation'));
        }

        $parent_product_category = ProductCategory::findOrFail($firstId);
        return view(config('laravel-product-module.views.product_category.edit'), compact('parent_product_category','product_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|ProductCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $product_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'product_category.product_category.show';
            $this->setRelationRouteParam($firstId, config('laravel-product-module.url.product_category'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product_category, $redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|ProductCategory $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $product_category = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'product_category.product_category.index';
            $this->setRelationRouteParam($firstId, config('laravel-product-module.url.product_category'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_category, $redirect);
    }
}
