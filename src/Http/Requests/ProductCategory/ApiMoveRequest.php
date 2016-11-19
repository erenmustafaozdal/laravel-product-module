<?php

namespace ErenMustafaOzdal\LaravelProductModule\Http\Requests\ProductCategory;

use App\Http\Requests\Request;
use Sentinel;

class ApiMoveRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return hasPermission('api.product_category.move');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'position'      => 'required|in:firstChild,lastChild,before,after',
            'related'       => 'required|integer'
        ];
    }
}
