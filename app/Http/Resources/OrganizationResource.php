<?php

namespace App\Http\Resources;

use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/**
 * Class OrganizationResource
 * @package App\Http\Resources\Resources
 * @mixin Organization
 */
class OrganizationResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'user_id'=> $this->user_id,
            'title'=> $this->title,
            'country'=> $this->country,
            'city'=> $this->city,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
//            'workers'=>UserResource::collection(User::paginate()),
//            'vacancyAddition'=>VacancyResource::where('status', '=','active')
//                        ->collection($this->whenLoaded('vacancies')),
        ];
    }
}
