<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 * @mixin User
 */
class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password' => $this->password,
            'country' => $this->country,
            'city' => $this->city,
            'telephone' => $this->telephone,
            'role' => $this->role,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
