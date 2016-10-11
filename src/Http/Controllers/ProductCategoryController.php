<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;


use App\Http\Requests;
use App\ProductCategory;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(config('laravel-product-module.views.product_category.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $operation = 'create';
        return view(config('laravel-product-module.views.product_category.create'), compact('operation','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeModel(ProductCategory::class,'index');
    }

    /**
     * Display the specified resource.
     *
     * @param ProductCategory $product_category
     * @return \Illuminate\Http\Response
     */
    public function show($product_category)
    {
        return view(config('laravel-product-module.views.product_category.show'), compact('product_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductCategory $product_category
     * @return \Illuminate\Http\Response
     */
    public function edit($product_category)
    {
        $operation = 'edit';
        return view(config('laravel-product-module.views.product_category.edit'), compact('product_category','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param ProductCategory $product_category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $product_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product_category, 'show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductCategory $product_category
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_category, 'index');
    }
}
