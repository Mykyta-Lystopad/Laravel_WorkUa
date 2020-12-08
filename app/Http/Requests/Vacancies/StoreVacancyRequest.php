<?php

namespace App\Http\Requests\Vacancies;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreVacancyRequest
 * @package App\Http\Requests\Vacancies
 */
class StoreVacancyRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            "name"=>'required|string|min:2|max:50',
            "workers_need"=>'required|numeric|min:1',
            "salary"=>'required|numeric|min:1'
        ];
    }
}
