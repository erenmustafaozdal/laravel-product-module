<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\ProductShowcase;
use App\Product;
use Laracasts\Flash\Flash;

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
     * @param integer|null $id
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        return view(config('laravel-product-module.views.product.index'));
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
        $operation = 'create';
        return view(config('laravel-product-module.views.product.create'), compact('operation'));
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
        return $this->storeModel(Product::class,'index');
    }

    /**
     * Display the specified resource.
     *
     * @param integer|ProductShowcase $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function show($firstId, $secondId = null)
    {
        return view(config('laravel-product-module.views.product.show'), compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer|ProductShowcase $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function edit($firstId, $secondId = null)
    {
        $operation = 'edit';
        return view(config('laravel-product-module.views.product.edit'), compact('product','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param integer|ProductShowcase $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $firstId, $secondId = null)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product,'show', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer|ProductShowcase $firstId
     * @param integer|null $secondId
     * @return \Illuminate\Http\Response
     */
    public function destroy($firstId, $secondId = null)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product,'index');
    }
}
