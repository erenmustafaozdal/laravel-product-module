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
     * @param  ProductCategory $dealer_category
     * @param  ApiUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ApiUpdateRequest $request, ProductCategory $dealer_category)
    {
        $this->setEvents([
            'success'   => UpdateSuccess::class,
            'fail'      => UpdateFail::class
        ]);
        $this->updateModel($dealer_category);

        return [
            'id'        => $dealer_category->id,
            'name'      => $dealer_category->name_uc_first
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
        $dealer_category = ProductCategory::findOrFail($id);
        $this->setDefineValues(['type']);
        $this->setEvents([
            'success'   => MoveSuccess::class,
            'fail'      => MoveFail::class
        ]);
        return $this->moveModel($dealer_category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ProductCategory  $dealer_category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $dealer_category)
    {
        $this->setEvents([
            'success'   => DestroySuccess::class,
            'fail'      => DestroyFail::class
        ]);
        return $this->destroyModel($dealer_category);
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
            $dealer_category = ProductCategory::find($request->input('id'));
            $models = $dealer_category->descendants()->where('name', 'like', "%{$request->input('query')}%");

        } else {
            $models = ProductCategory::where('name', 'like', "%{$request->input('query')}%");
        }

        $models = $models->get(['id','parent_id','lft','rgt','depth','name'])
            ->toHierarchy();
        return LMBCollection::relationRender($models, 'children', '/', ['name']);
    }
}
