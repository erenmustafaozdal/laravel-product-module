<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product;

use App\Http\Requests\Request;
use Sentinel;

class ApiStoreRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return hasPermission('api.product.store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'       => 'required',
            'name'              => 'required|max:255',
        ];
    }
}
