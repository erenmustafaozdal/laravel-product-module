<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use Carbon\Carbon;

class ProductBrand extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * query filter with id scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $request)
    {
        // filter id
        if ($request->has('id')) {
            $query->where('id',$request->get('id'));
        }
        // filter title
        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }
        // filter created_at
        if ($request->has('created_at_from')) {
            $query->where('created_at', '>=', Carbon::parse($request->get('created_at_from')));
        }
        if ($request->has('created_at_to')) {
            $query->where('created_at', '<=', Carbon::parse($request->get('created_at_to')));
        }
        return $query;
    }





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the products of the product category.
     */
    public function products()
    {
        return $this->hasMany('App\Product','brand_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    /**
     * model boot method
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * model saved method
         *
         * @param $model
         */
        parent::saved(function($model)
        {
            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_creative_slider');
            \Cache::forget('home_wide_showcase_small_product');
            \Cache::forget('home_narrow_showcase_small_product_1');
            \Cache::forget('home_narrow_showcase_small_product_2');
            \Cache::forget('home_wide_showcase_big_product');
            \Cache::forget('product_showcase');
            \Cache::forget('product_categories');
            \Cache::forget('product_brands');
            \Cache::forget('product_chances');
            \Cache::forget(implode('_',['product_brands',$model->id]));

            // marka sayfalama cache temizlemesi
            $totalPages = (int) ceil(\DB::table('products')->whereBrandId($model->id)->count()/6) + 1;
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['products','brand',$model->id,'page',$i]));
            }
        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            // cache forget
            \Cache::forget('home_mini_slider');
            \Cache::forget('home_creative_slider');
            \Cache::forget('home_wide_showcase_small_product');
            \Cache::forget('home_narrow_showcase_small_product_1');
            \Cache::forget('home_narrow_showcase_small_product_2');
            \Cache::forget('home_wide_showcase_big_product');
            \Cache::forget('product_showcase');
            \Cache::forget('product_categories');
            \Cache::forget('product_brands');
            \Cache::forget('product_chances');
            \Cache::forget(implode('_',['product_brands',$model->id]));

            // marka sayfalama cache temizlemesi
            $totalPages = (int) ceil(\DB::table('products')->whereBrandId($model->id)->count()/6) + 1;
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['products','brand',$model->id,'page',$i]));
            }
        });
    }
}
