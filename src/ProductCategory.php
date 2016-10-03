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
}
