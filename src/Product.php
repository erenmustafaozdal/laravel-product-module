<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;
use Illuminate\Support\Facades\Request;
use App\ProductCategory;

class Product extends Model
{
    use ModelDataTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'amount',
        'code',
        'photo_id', // main photo
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_publish'
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
        // filter name
        if ($request->has('name')) {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }
        // filter code
        if ($request->has('code')) {
            $query->where('code', 'like', "%{$request->get('code')}%");
        }
        // filter category
        if ($request->has('category')) {
            $query->whereHas('category', function ($query) use($request) {
                $category = ProductCategory::where('name', 'like', "%{$request->get('category')}%")->get();
                $ltf = $category->keyBy('lft')->keys()->min();
                $rgt = $category->keyBy('rgt')->keys()->max();
                $query->where('lft', '>=', $ltf)
                    ->where('rgt', '<=', $rgt);
            });
        }
        // filter brand
        if ($request->has('brand')) {
            $query->whereHas('brand', function ($query) use($request) {
                $query->where('name', 'like', "%{$request->get('brand')}%");
            });
        }
        // filter amount
        if ($request->has('amount_from')) {
            $query->where('amount', '>=', $request->get('amount_from'));
        }
        if ($request->has('amount_to')) {
            $query->where('amount', '<=', $request->get('amount_to'));
        }
        // filter status
        if ($request->has('status')) {
            $query->where('is_publish',$request->get('status'));
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
     * Get the category of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\ProductCategory');
    }

    /**
     * Get the brand of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo('App\ProductBrand');
    }

    /**
     * Get the showcases of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function showcases()
    {
        return $this->belongsToMany('App\ProductShowcase');
    }

    /**
     * Get the product photos.
     */
    public function photos()
    {
        return $this->hasMany('App\ProductPhoto','product_id');
    }

    /**
     * Get the product main photo.
     */
    public function mainPhoto()
    {
        return $this->hasOne('App\ProductPhoto','product_id');
    }





    /*
    |--------------------------------------------------------------------------
    | Model Set and Get Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Set the amount attribute.
     *
     * @param number $amount
     * @return string
     */
    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = number_format($amount, 2, '.', ',');
    }

    /**
     * Get the amount attribute.
     *
     * @param number $amount
     * @return string
     */
    public function getAmountAttribute($amount)
    {
        return number_format($amount, 2, ',', '.');
    }

    /**
     * Get the amount with turkish lira attribute.
     *
     * @return string
     */
    public function getAmountTurkishAttribute()
    {
        return $this->amount . ' ₺';
    }

    /**
     * Get the code uppercase attribute.
     *
     * @return string
     */
    public function getCodeUcAttribute()
    {
        return strtoupper_tr($this->code);
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
            if (Request::has('showcase_id')) {
                $ids = array_map(function($id)
                {
                    return [ $id => ['order' => Request::get('order_' . $id)] ];
                }, array_filter(Request::get('showcase_id'), function($id)
                {
                    return $id != 0;
                }));
                $model->showcases()->sync( $ids );
            }

        });

        /**
         * model deleted method
         *
         * @param $model
         */
        parent::deleted(function($model)
        {
            $file = new FileRepository(config('laravel-product-module.product.uploads'));
            $file->deleteDirectories($model);
        });
    }
}
