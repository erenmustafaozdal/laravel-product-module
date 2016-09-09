<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\ProductBrand;
use App\Product;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\StoreSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\StoreFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\UpdateSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\UpdateFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\DestroySuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductBrand\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductBrand\StoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductBrand\UpdateRequest;

class ProductBrandController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(config('laravel-product-module.views.product_brand.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $operation = 'create';
        return view(config('laravel-product-module.views.product_brand.create'), compact('operation'));
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
        return $this->storeModel(Product::class,'index');
    }

    /**
     * Display the specified resource.
     *
     * @param ProductBrand $product_brand
     * @return \Illuminate\Http\Response
     */
    public function show($product_brand)
    {
        return view(config('laravel-product-module.views.product_brand.show'), compact('product_brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductBrand $product_brand
     * @return \Illuminate\Http\Response
     */
    public function edit($product_brand)
    {
        $operation = 'edit';
        return view(config('laravel-product-module.views.product_brand.edit'), compact('product_brand','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param ProductBrand $product_brand
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $product_brand)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product_brand,'show', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductBrand $product_brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_brand)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_brand,'index');
    }
}
