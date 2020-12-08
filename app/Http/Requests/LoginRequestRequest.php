<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LoginRequestRequest
 * @package App\Http\Requests
 */
class LoginRequestRequest extends FormRequest
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
