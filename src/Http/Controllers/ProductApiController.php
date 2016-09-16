<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Product;

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
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @return Datatables
     */
    public function index(Request $request)
    {
        $products = Product::with(['categories','brand','mainPhoto'])
            ->select(['id','brand_id','name','amount','code','photo_id','is_publish','created_at']);

        if ($request->has('action') && $request->input('action') === 'filter') {
            $products->filter($request);
        }
        $addColumns = [
            'addUrls'           => $this->urls,
            'status'            => function($model) { return $model->is_publish; }
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; },
            'amount'            => function($model) { return $model->amount_turkish; },
            'code'              => function($model) { return $model->code_uc; },
            'main_photo'        => function($model)
            {
                $photoKey = array_keys(config('laravel-product-module.product.uploads.photo.thumbnails'));
                return !is_null($model->mainPhoto) ? $model->mainPhoto->getPhoto([], $photoKey[1], true, 'product','product_id') : null;
            },
            'categories'        => function($model)
            {
                return $model->categories->map(function($item, $key)
                {
                    return $item->ancestorsAndSelf()->get();
                })->toArray();
            }
        ];
        $removeColumns = ['category_id','brand_id','photo_id','is_publish','mainPhoto'];
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
        $product = Product::with(['categories','brand','photos','showcases'])
            ->where('id',$id)
            ->select(['id','brand_id','name','amount','code','photo_id','short_description','description','meta_title','meta_description','meta_keywords','created_at','updated_at']);

        $editColumns = [
            'created_at'    => function($model) { return $model->created_at_table; },
            'updated_at'    => function($model) { return $model->updated_at_table; },
            'amount'        => function($model) { return $model->amount_turkish; },
            'code'          => function($model) { return $model->code_uc; },
            'photos'        => function($model)
            {
                // eğer çoklu fotoğraf ise
                return $model->photos->map(function($item,$key)
                {
                    return [
                        'photo'     => $item->getPhoto([], 'normal', true, 'product','product_id'),
                        'id'        => $item->id
                    ];
                })->toArray();
            },
            'categories'        => function($model) {
                return $model->categories->map(function($item, $key)
                {
                    return $item->ancestorsAndSelf()->get();
                })->toArray();
            }
        ];
        $removeColumns = ['category_id','brand_id'];
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
        return Product::with(['categories','brand'])
            ->where('id',$id)
            ->first(['id','brand_id','name','amount','code']);
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
     * remove photo of the product
     *
     * @param Product $product
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function removePhoto(Product $product, Request $request)
    {
        if ($product->photos()->where('id',$request->id)->first()->delete()) {
            return response()->json($this->returnData('success'));
        }
        return response()->json($this->returnData('error'));
    }

    /**
     * set main photo of the product
     *
     * @param Product $product
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function setMainPhoto(Product $product, Request $request)
    {
        $product->photo_id = $request->id;
        if ($product->save()) {
            return response()->json($this->returnData('success'));
        }
        return response()->json($this->returnData('error'));
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
