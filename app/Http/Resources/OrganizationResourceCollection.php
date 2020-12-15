<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class OrganizationResource
 * @package App\Http\Resources\Resources
 * @mixin Organization
 */
class OrganizationResourceCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'links'=> ['Organization(s)'=> $this->collection->count()],
            'data'=> $this->collection

        ];
    }
}
