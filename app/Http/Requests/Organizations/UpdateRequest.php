<?php

namespace App\Http\Requests\Organizations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateOrganizationRequest
 * @package App\Http\Requests\Organizations
 */
class UpdateRequest extends FormRequest
{
    /**
     * turn array
     */
    public function rules()
    {
        return [
            "title"=>'sometimes|string|min:3|max:100',
            "country"=>['sometimes','string','min:3','max:50'],
            "city"=>['sometimes','string','min:3','max:50'],
        ];
    }
}
