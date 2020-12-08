<?php

namespace App\Http\Requests\Organizations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateOrganizationRequest
 * @package App\Http\Requests\Organizations
 */
class UpdateOrganizationRequest extends FormRequest
{
    /**
     * turn array
     */
    public function rules()
    {
        return [
            "orgName"=>'sometimes|string|min:3|max:100',
            "country"=>['sometimes','string','min:3','max:50'],
            "city"=>['sometimes','string','min:3','max:50'],
        ];
    }
}
