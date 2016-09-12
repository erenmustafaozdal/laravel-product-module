<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Requests\Product;

use App\Http\Requests\Request;
use Sentinel;

class ApiUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Sentinel::getUser()->is_super_admin || Sentinel::hasAccess('api.product.update')) {
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
        return [
            'category_id'       => 'required|array',
            'brand_id'          => 'required|integer',
            'name'              => 'required|max:255',
            'amount'            => 'required',
        ];
    }
}
