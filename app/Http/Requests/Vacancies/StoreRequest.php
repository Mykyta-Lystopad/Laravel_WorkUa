<?php

namespace App\Http\Requests\Vacancies;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreVacancyRequest
 * @package App\Http\Requests\Vacancies
 */
class StoreRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            "vacancy_name"=>'required|string|min:2|max:50',
            "workers_amount"=>'required|numeric|min:1',
            "salary"=>'required|numeric|min:1'
        ];
    }
}
