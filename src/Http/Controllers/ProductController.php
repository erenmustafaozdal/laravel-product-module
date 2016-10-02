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
     * default relation datas
     *
     * @var array
     */
    private $relations = [
        'descriptions' => [
            'relation_type'     => 'hasMany',
            'relation'          => 'descriptions',
            'relation_model'    => '\App\ProductDescription',
            'datas'             => null
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(config('laravel-product-module.views.product.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $operation = 'create';
        return view(config('laravel-product-module.views.product.create'), compact('operation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->setToFileOptions($request, ['photos.photo' => 'multiple_photo']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        $relation = [];
        if ($request->has('group-description')) {
            $this->relations['descriptions']['datas'] = collect($request->get('group-description'))->reject(function($item)
            {
                return $item['description_title'] == '' || $item['description_description'] == '';
            })->map(function($item,$key)
            {
                $item['title'] = $item['description_title'];
                unsetReturn($item,'description_title');
                $item['description'] = $item['description_description'];
                unsetReturn($item,'description_description');
                $item['is_publish'] = !isset($item['description_is_publish']) || !$item['description_is_publish'] ? 0 : 1;
                unsetReturn($item,'description_is_publish');
                return $item;
            });
            $relation[] = $this->relations['descriptions'];
        }
        $this->setOperationRelation($relation);
        return $this->storeModel(Product::class,'index');
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
        return view(config('laravel-product-module.views.product.show'), compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $operation = 'edit';
        return view(config('laravel-product-module.views.product.edit'), compact('product','operation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $product)
    {
        $this->setToFileOptions($request, ['photos.photo' => 'multiple_photo']);
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $relation = [];
        if ($request->has('group-description')) {
            $this->relations['descriptions']['datas'] = collect($request->get('group-description'))->reject(function($item)
            {
                return $item['description_title'] == '' || $item['description_description'] == '';
            })->map(function($item,$key)
            {
                $item['title'] = $item['description_title'];
                unsetReturn($item,'description_title');
                $item['description'] = $item['description_description'];
                unsetReturn($item,'description_description');
                $item['is_publish'] = !isset($item['description_is_publish']) || !$item['description_is_publish'] ? 0 : 1;
                unsetReturn($item,'description_is_publish');
                return $item;
            });
            $relation[] = $this->relations['descriptions'];
        }
        $this->setOperationRelation($relation);
        return $this->updateModel($product,'show', true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product,'index');
    }

    /**
     * publish model
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function publish($product)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => true ] ]
        ]);
        return $this->updateAlias($product, [
            'success'   => PublishSuccess::class,
            'fail'      => PublishFail::class
        ],'show');
    }

    /**
     * not publish model
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function notPublish($product)
    {
        $this->setOperationRelation([
            [ 'relation_type'     => 'not', 'datas' => [ 'is_publish'    => false ] ]
        ]);
        return $this->updateAlias($product, [
            'success'   => NotPublishSuccess::class,
            'fail'      => NotPublishFail::class
        ],'show');
    }



    /*
    |--------------------------------------------------------------------------
    | Operation Trait Override
    |--------------------------------------------------------------------------
    */

    /**
     * fill model datas to database
     *
     * @param array $datas
     * @return boolean
     */
    protected function fillModel($datas)
    {
        if ( ! parent::fillModel($datas) ) {
            return false;
        }

        // eğer boşsa ana fotoğraf eklenir
        $model = $this->getModel();
        if ( is_null($model->photo_id) ) {
            if (!$model->fill(['photo_id' => $model->photos->first()->id])->save()) {
                return false;
            }
        }
        return true;
    }
}
