<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Controllers;

use Illuminate\Http\Request;

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
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\MoveSuccess;
use ErenMustafaOzdal\LaravelProductModule\Events\ProductCategory\MoveFail;
// requests
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductCategory\ApiStoreRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductCategory\ApiUpdateRequest;
use ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductCategory\ApiMoveRequest;
// services
use LMBCollection;


class ProductCategoryApiController extends BaseNodeController
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param integer|null $id
     * @return array
     */
    public function index(Request $request, $id = null)
    {
        return $this->getNodes(ProductCategory::class, $id);
    }

    /**
     * get detail
     *
     * @param integer $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function detail($id, Request $request)
    {
        return ProductCategory::where('id', $id)
            ->select('crop_type')
            ->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ApiStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApiStoreRequest $request)
    {
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => StoreSuccess::class,
            'fail'      => StoreFail::class
        ]);
        return $this->storeNode(ProductCategory::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductCategory $product_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, ProductCategory $product_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $this->updateModel($product_category);

        return [
            'id'        => $product_category->id,
            'name'      => $product_category->name_uc_first
        ];
    }

    /**
     * Move the specified node.
     *
     * @param  ApiMoveRequest $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function move(ApiMoveRequest $request, $id)
    {
        $product_category = ProductCategory::findOrFail($id);
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => MoveSuccess::class,
            'fail'      => MoveFail::class
        ]);
        return $this->moveModel($product_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductCategory  $product_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $product_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($product_category);
    }

    /**
     * get roles with query
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function models(Request $request)
    {
        if($request->has('id')) {
            $product_category = ProductCategory::find($request->input('id'));
            $models = $product_category->descendants()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = ProductCategory::where('name', 'like', "%{$request->input('query')}%");
        }

        $parents = $models->get(['id','parent_id','lft','rgt','depth','name']);
        $ids = $parents->keyBy('id')->keys();
        $models = ProductCategory::whereIn('parent_id',$ids)
            ->get(['id','parent_id','lft','rgt','depth','name']);
        if ( ! $models ->isEmpty()) {
            $parents = $parents->merge($models);
        }
        return LMBCollection::renderAncestorsAndSelf($parents, '/', ['name_uc_first']);
    }
}
