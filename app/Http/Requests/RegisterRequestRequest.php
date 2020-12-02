<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
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
