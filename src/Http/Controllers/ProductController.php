<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

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
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product\StoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product\UpdateRequest;

class ProductController extends BaseController
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
            return view(config('laravel-product-module.views.product.index'));
        }

        $product_category = ProductCategory::findOrFail($id);
        return view(config('laravel-product-module.views.product.index'), compact('product_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $operation = 'create';
        if (is_null($id)) {
            return view(config('laravel-product-module.views.product.create'), compact('operation'));
        }

        $product_category = ProductCategory::findOrFail($id);
        return view(config('laravel-product-module.views.product.create'), compact('product_category','operation'));
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
        if (is_null($id)) {
            $redirect = 'index';
        } else {
            $redirect = 'product_category.product.index';
            $this->setRelationRouteParam($id, config('laravel-product-module.url.product'));
        }

        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(Product::class,$redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param integer|Product $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        $product = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-product-module.views.product.show'), compact('product'));
        }

        $product_category = ProductCategory::findOrFail($firstId);
        return view(config('laravel-product-module.views.product.show'), compact('product', 'product_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|Product $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        $product = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            return view(config('laravel-product-module.views.product.edit'), compact('product','operation'));
        }

        $product_category = ProductCategory::findOrFail($firstId);
        return view(config('laravel-product-module.views.product.edit'), compact('product', 'product_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|Product $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $product = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'product_category.product.show';
            $this->setRelationRouteParam($firstId, config('laravel-product-module.url.product'));
        }

        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product,$redirect, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|Product $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $product = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'index';
        } else {
            $redirect = 'product_category.product.index';
            $this->setRelationRouteParam($firstId, config('laravel-product-module.url.product'));
        }

        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product,$redirect);
    }

    /**
     * publish model
     *
     * @param integer|Product $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function publish($firstId, $secondId = null)
    {
        $product = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'product_category.product.show';
            $this->setRelationRouteParam($firstId, config('laravel-product-module.url.product'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($product, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],$redirect);
    }

    /**
     * not publish model
     *
     * @param integer|Product $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function notPublish($firstId, $secondId = null)
    {
        $product = is_null($secondId) ? $firstId : $secondId;
        if (is_null($secondId)) {
            $redirect = 'show';
        } else {
            $redirect = 'product_category.product.show';
            $this->setRelationRouteParam($firstId, config('laravel-product-module.url.product'));
        }

        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($product, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ],$redirect);
    }
}
