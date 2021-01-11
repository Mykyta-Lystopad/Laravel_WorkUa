<?php

namespace App\Http\Resources;

use App\Models\Vacancy;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VacancyResource
 * @package App\Http\Resources
 * @mixin Vacancy
 */
class VacancyResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'organization_id'=> $this->organization_id,
            'vacancy_name' => $this->vacancy_name,
            "workers_amount" => $this->workers_amount,
            "salary" => $this->salary,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'workers'=> UserResource::collection($this->whenLoaded('users'))
        ];
    }
}
