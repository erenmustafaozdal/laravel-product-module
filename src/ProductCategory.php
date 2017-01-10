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
        });
    }
}
