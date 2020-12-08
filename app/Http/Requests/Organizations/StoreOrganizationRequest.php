<?php

namespace App\Http\Requests\Organizations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreOrganizationRequest
 * @package App\Http\Requests\Organizations
 */
class StoreOrganizationRequest extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            "orgName"=>'required|string|min:3|max:100',
            "country"=>['required','string','min:3','max:50'],
            "city"=>['required','string','min:3','max:50'],
        ];
    }
}
