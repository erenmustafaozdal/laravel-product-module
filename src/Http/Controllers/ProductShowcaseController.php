<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\ProductShowcase;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseNodeController;
// events
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\StoreSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\StoreFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\UpdateSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\UpdateFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\DestroySuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductShowcase\StoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductShowcase\UpdateRequest;

class ProductShowcaseController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(config('laravel-product-module.views.product_showcase.index'));
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
        return view(config('laravel-product-module.views.product_showcase.create'), compact('operation'));
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
        return $this->storeModel(ProductShowcase::class,'index');
    }

    /**
     * Display the specified resource.
     *
     * @param ProductShowcase $product_showcase
     * @return \Illuminate\Http\Response
     */
    public function show($product_showcase)
    {
        return view(config('laravel-product-module.views.product_showcase.show'), compact('product_showcase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductShowcase $product_showcase
     * @return \Illuminate\Http\Response
     */
    public function edit($product_showcase)
    {
        $operation = 'edit';
        return view(config('laravel-product-module.views.product_showcase.edit'), compact('product_showcase','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param ProductShowcase $product_showcase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $product_showcase)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product_showcase,'show', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductShowcase $product_showcase
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_showcase)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_showcase,'index');
    }
}
