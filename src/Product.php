<?php

namespace ErenMustafaOzdal\LaravelProductModule;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use ErenMustafaOzdal\LaravelModulesBase\Traits\ModelDataTrait;
use ErenMustafaOzdal\LaravelModulesBase\Repositories\FileRepository;
use Illuminate\Support\Facades\Request;

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
                $query->where('name', 'like', "%{$request->get('category')}%");
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

    /**
     * showcase filter scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfShowcase($query, $id)
    {
        return $query->has('showcases', '>=', 1, 'and', function($query) use($id)
        {
            return $query->whereId($id);
        });
    }





    /*
    |--------------------------------------------------------------------------
    | Model Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get the category of the product.
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
        return $this->belongsToMany('App\ProductShowcase')
            ->withPivot('order');
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
        return $this->belongsTo('App\ProductPhoto','photo_id');
    }

    /**
     * Get the product descriptions.
     */
    public function descriptions()
    {
        return $this->hasMany('App\ProductDescription','product_id');
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
        if ($amount === '') {
            $this->attributes['amount'] = null;
            return;
        }
        $amount = str_replace( ',', '.', str_replace(['_','.'], [''], $amount) );
        $this->attributes['amount'] = floatval($amount);
    }

    /**
     * Get the amount attribute.
     *
     * @param number $amount
     * @return string
     */
    public function getAmountAttribute($amount)
    {
        if (is_null($amount)) return $amount;
        return number_format($amount, 2, ',', '.');
    }

    /**
     * Get the amount with turkish lira attribute.
     *
     * @return string
     */
    public function getAmountTurkishAttribute()
    {
        if (is_null($this->amount)) return $this->amount;
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

    /**
     * Get the short description attribute.
     * clean iframe for xss atack
     *
     * @param string $short_description
     * @return string
     */
    public function getShortDescriptionAttribute($short_description)
    {
        return clean($short_description, 'iframe');
    }

    /**
     * Get the striped short description attribute.
     *
     * @return string
     */
    public function getStripedShortDescriptionAttribute()
    {
        return strip_tags($this->short_description);
    }

    /**
     * Set the brand_id attribute.
     *
     * @param number $brand_id
     * @return string
     */
    public function setBrandIdAttribute($brand_id)
    {
        if ($brand_id === '') {
            return;
        }
        $this->attributes['brand_id'] = $brand_id;
    }





    /*
    |--------------------------------------------------------------------------
    | Model ShowCase Order Methods
    |--------------------------------------------------------------------------
    */

    /**
     * get the first order
     *
     * @param array $showcase
     * @param integer $showcase_id
     * @return integer
     */
    public function getFirstOrder($showcase, $showcase_id)
    {
        return 1;
    }

    /**
     * get the first ten order
     *
     * @param array $showcase
     * @param integer $showcase_id
     * @return integer
     */
    public function getFirstTenOrder($showcase, $showcase_id)
    {
        return rand(1,10);
    }

    /**
     * get the first hundred order
     *
     * @param array $showcase
     * @param integer $showcase_id
     * @return integer
     */
    public function getFirstHundredOrder($showcase, $showcase_id)
    {
        return rand(1,100);
    }

    /**
     * get the random order
     *
     * @param array $showcase
     * @param integer $showcase_id
     * @return integer
     */
    public function getRandomOrder($showcase, $showcase_id)
    {
        return rand(1,10000);
    }

    /**
     * get the random order
     *
     * @param array $showcase
     * @param integer $showcase_id
     * @return integer
     */
    public function getLastOrder($showcase, $showcase_id)
    {
        $lastShowcase = $this->showcases()
            ->wherePivot('product_showcase_id', '=', $showcase_id)
            ->orderBy('pivot_order','desc')
            ->take(1)
            ->first();
        if ( ! $lastShowcase ) {
            return 1;
        }
        return $lastShowcase->pivot->order + 1;
    }

    /**
     * get the user specific order
     *
     * @param array $showcase
     * @param integer $showcase_id
     * @return integer
     */
    public function getClearOrder($showcase, $showcase_id)
    {
        return (int) $showcase['order'];
    }

    /**
     * showcase order move up
     *
     * @param integer $showcase_id
     * @param integer $order
     * @return void
     */
    public function showcaseOrderMove($showcase_id, $order)
    {
        $this->showcases()
            ->wherePivot('product_showcase_id', '=', $showcase_id)
            ->wherePivot('order', '>=', $order)
            ->increment('order');
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
            // showcase add
            if (Request::has('showcase_id')) {
                $showcase = is_array(Request::get('showcase_id'))
                    ? Request::get('showcase_id')
                    : json_decode(Request::get('showcase_id'),true);
                $ids = [];
                if ($showcase != 0 || $showcase) {
                    $ids = collect($showcase)->filter(function($showcase)
                    {
                        return (isset($showcase['type']) && $showcase['type']) || (isset($showcase['order']) && $showcase['order']);
                    })->map(function($showcase, $id) use($model)
                    {
                        if ( ! $showcase['type'] ) {
                            return $id;
                        }
                        $method = camel_case( "get_{$showcase['type']}_order" );
                        $order = $model->$method($showcase, $id);
                        if ( $showcase['type'] !== 'last' ) {
                            $model->showcaseOrderMove($id,$order);
                        }
                        return ['order' => $order];
                    })->all();
                }
                $model->showcases()->sync( $ids );

                // cache forget
                \Cache::forget('home_mini_slider');
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

            // cache forget
            \Cache::forget('home_mini_slider');
        });
    }
}
