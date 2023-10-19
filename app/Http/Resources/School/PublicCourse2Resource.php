<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\School\PublicSchoolResource;

class PublicCourse2Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'schools' => PublicSchoolResource::collection($this->lists)
        ];
    }
}
