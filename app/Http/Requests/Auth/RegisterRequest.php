<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequestRequest
 * @package App\Http\Requests
 */
class RegisterRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'=>'string|min:2|max:50|required',
            'last_name'=>'string|min:2|max:50|required',
            'email'=>'email|unique:users|required',
            'password'=>'string|min:6|max:20|required',
            'country'=>['string', 'min:2', 'max:20', 'required'],
            'city'=>'string|min:2|max:50|required',
            'telephone'=>'numeric|required',
            'role'=>'string|required',
        ];
    }
}
