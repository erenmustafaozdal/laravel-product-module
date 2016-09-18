<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;

class ProductPhoto extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['product'];
    public $timestamps = false;





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
     * Get the products of the product photo.
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
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
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            $file = new FileRepository(config('laravel-product-module.product.uploads'));
            $file->deletePhoto($model, 'product');
        });
    }
}
