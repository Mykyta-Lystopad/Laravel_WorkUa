<?php

namespace App\Http\Requests\Vacancies;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateVacancyRequest
 * @package App\Http\Requests\Vacancies
 */
class UpdateRequest extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            "vacancy_name"=>'sometimes|string|min:2|max:50',
            "workers_amount"=>'sometimes|numeric|min:1',
            "salary"=>'sometimes|numeric|min:1'
        ];
    }
}
