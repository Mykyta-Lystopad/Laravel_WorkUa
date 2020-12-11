<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequestRequest
 * @package App\Http\Requests
 */
class LoginRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>'email|required',
            'password'=>['string', 'required']
        ];
    }
}
