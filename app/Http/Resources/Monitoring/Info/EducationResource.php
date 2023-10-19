<?php

namespace App\Http\Resources\Monitoring\Info;

use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'school' => $this->school->school->name,
            'campus' => $this->school->campus,
            'gradings' => $this->school->gradings
        ];
    }
}
