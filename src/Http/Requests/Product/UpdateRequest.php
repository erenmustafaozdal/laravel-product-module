<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product;

use App\Http\Requests\Request;
use Sentinel;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('admin.product.update')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $max_photo = config('laravel-product-module.product.uploads.photo.max_size');
        $mimes_photo = config('laravel-product-module.product.uploads.photo.mimes');

        $rules = [
            'category_id'       => 'required|integer',
            'brand_id'          => 'required|integer',
            'name'              => 'required|max:255',
            'amount'            => 'required|numeric',
        ];

        // photo elfinder mi
        if ($this->has('photo') && is_string($this->photo)) {
            $rules['photo'] = "elfinder_max:{$max_photo}|elfinder:{$mimes_photo}";
        } else {
            $rules['photo'] = 'array|max:' . config('laravel-description-module.description.uploads.multiple_photo.max_file');
            for($i = 0; $i < count($this->file('photo')); $i++) {
                $rules['photo.' . $i] = "max:{$max_photo}|image|mimes:{$mimes_photo}";
            }
        }

        return $rules;
    }
}
