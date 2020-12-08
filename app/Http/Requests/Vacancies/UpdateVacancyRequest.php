<?php

namespace App\Http\Requests\Vacancies;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateVacancyRequest
 * @package App\Http\Requests\Vacancies
 */
class UpdateVacancyRequest extends FormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            "name"=>'sometimes|string|min:2|max:50',
            "workers_need"=>'sometimes|numeric|min:1',
            "salary"=>'sometimes|numeric|min:1'
        ];
    }
}
