<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Baum\Node;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class ProductCategory extends Node
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'crop_type'
    ];





    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */





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
        return $this->hasMany('App\Product','category_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * get the category aspect ratio
     *
     * @return float
     */
    public function getAspectRatioAttribute()
    {
        if ($this->crop_type === 'square') {
            return 1;
        }

        if ($this->crop_type === 'vertical') {
            return config('laravel-product-module.product.uploads.photo.vertical_ratio');
        }

        return config('laravel-product-module.product.uploads.photo.horizontal_ratio');
    }





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
            \Cache::forget(implode('_',['product_categories',$model->id]));
            \Cache::forget(implode('_',['product_categories','getDescendantsAndSelf',$model->id]));

            // kategori sayfalama cache temizlemesi
            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('product_categories')
                ->where('product_categories.id', $category_id)
                ->join('product_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'product_categories.lft')
                        ->on('cat.lft', '<', 'product_categories.rgt');
                })->get();
            $ids = array_map(function ($item) {
                return $item->id;
            }, $categories);
            $totalPages = (int) ceil(\DB::table('products')->whereIn('category_id',$ids)->count()/6) + 1;
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['products','category',$model->id,'page',$i]));
                \Cache::forget(implode('_', ['products','category',$category_id,'page',$i]));
            }
        });

        /**
         * model moved method
         *
         * @param $model
         */
        parent::moved(function($model)
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
            \Cache::forget(implode('_',['product_categories',$model->id]));
            \Cache::forget(implode('_',['product_categories','getDescendantsAndSelf',$model->id]));

            // kategori sayfalama cache temizlemesi
            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('product_categories')
                ->where('product_categories.id', $category_id)
                ->join('product_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'product_categories.lft')
                        ->on('cat.lft', '<', 'product_categories.rgt');
                })->get();
            $ids = array_map(function ($item) {
                return $item->id;
            }, $categories);
            $totalPages = (int) ceil(\DB::table('products')->whereIn('category_id',$ids)->count()/6) + 1;
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['products','category',$model->id,'page',$i]));
                \Cache::forget(implode('_', ['products','category',$category_id,'page',$i]));
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
            \Cache::forget(implode('_',['product_categories',$model->id]));
            \Cache::forget(implode('_',['product_categories','getDescendantsAndSelf',$model->id]));

            // kategori sayfalama cache temizlemesi
            $category_id = $model->isRoot() ? $model->id : $model->getRoot()->id;
            $categories = \DB::table('product_categories')
                ->where('product_categories.id', $category_id)
                ->join('product_categories as cat', function ($join) {
                    $join->on('cat.lft', '>=', 'product_categories.lft')
                        ->on('cat.lft', '<', 'product_categories.rgt');
                })->get();
            $ids = array_map(function ($item) {
                return $item->id;
            }, $categories);
            $totalPages = (int) ceil(\DB::table('products')->whereIn('category_id',$ids)->count()/6) + 1;
            for($i = 1; $i <= $totalPages; $i++) {
                \Cache::forget(implode('_', ['products','category',$model->id,'page',$i]));
                \Cache::forget(implode('_', ['products','category',$category_id,'page',$i]));
            }
        });
    }
}
