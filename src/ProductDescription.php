<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Illuminate\Database\Eloquent\Model;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;

class ProductDescription extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_descriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'is_publish'
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['product'];





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the photo of the product.
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

    /**
     * Get the description attribute.
     * clean iframe for xss atack
     *
     * @param string $description
     * @return string
     */
    public function getDescriptionAttribute($description)
    {
        return clean($description, 'iframe');
    }
}
