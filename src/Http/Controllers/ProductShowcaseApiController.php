<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\ProductShowcase;

use ErenMustafaOzdal\LaravelModulesBase\Controllers\BaseController;
// events
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\StoreSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\StoreFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\UpdateSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\UpdateFail;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\DestroySuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductShowcase\DestroyFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductShowcase\ApiStoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductShowcase\ApiUpdateRequest;
// services
use LMBCollection;


class ProductShowcaseApiController extends BaseController
{
    /**
     * default urls of the model
     *
     * @var array
     */
    private $urls = [
        'edit_page'     => ['route' => 'admin.product_showcase.edit', 'id' => true]
    ];

    /**
     * Display a listing of the resource.
     *
     * @param Request  $request
     * @return Datatables
     */
    public function index(Request $request)
    {
        $product_showcases = ProductShowcase::select(['id', 'name', 'created_at']);

        // if is filter action
        if ($request->has('action') && $request->input('action') === 'filter') {
            $product_showcases->filter($request);
        }

        $addColumns = [
            'addUrls'           => $this->urls
        ];
        $editColumns = [
            'created_at'        => function($model) { return $model->created_at_table; }
        ];
        return $this->getDatatables($product_showcases, $addColumns, $editColumns, []);
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
        $product = ProductShowcase::where('id',$id)->select(['id','name','created_at','updated_at']);

        $editColumns = [
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
        return ProductShowcase::where('id',$id)->first(['id','name']);
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
        return $this->storeModel(ProductShowcase::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductShowcase $product_showcase
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, ProductShowcase $product_showcase)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        return $this->updateModel($product_showcase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductShowcase  $product_showcase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductShowcase $product_showcase)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_showcase);
    }

    /**
     * group action method
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function group(Request $request)
    {
        if ( $this->groupAlias(ProductShowcase::class) ) {
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
        return ProductShowcase::where('name', 'like', "%{$request->input('query')}%")->get(['id','name']);
    }
}
