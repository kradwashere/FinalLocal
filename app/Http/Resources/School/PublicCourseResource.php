<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicCourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'years' => $this->years,
            'type' => $this->type,
            'course' => $this->course->name,
        ];
    }
}
