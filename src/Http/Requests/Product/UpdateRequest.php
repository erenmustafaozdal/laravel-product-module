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
        $this->rules = [];

        if( $this->form === 'general' ) {
            $this->rules = [
                'category_id'       => 'required',
                'brand_id'          => 'required|integer',
                'name'              => 'required|max:255',
                'group-description' => 'array',
            ];
        }

        // photo elfinder mi
        $this->addFileRule('photo', $max_photo, $mimes_photo, $max_file);

        return $this->rules;
    }
}
