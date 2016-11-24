<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product;


use ErenMustafaOzdal\LaravelModulesBase\Requests\BaseRequest;
use Sentinel;

class UpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return hasPermission('admin.product.update');
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
        $max_file = config('laravel-product-module.product.uploads.multiple_photo.max_file');
        $this->rules = [];

        if( $this->form === 'general' ) {
            $this->rules = [
                'category_id'       => 'required',
                'brand_id'          => 'integer',
                'name'              => 'required|max:255',
                'amount'            => 'required',
                'group-description' => 'array',
            ];
        }

        // photo elfinder mi
        $this->addFileRule('photo', $max_photo, $mimes_photo, $max_file);

        return $this->rules;
    }
}
