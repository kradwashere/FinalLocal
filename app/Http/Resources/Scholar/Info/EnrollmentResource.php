<?php

namespace App\Http\Resources\Scholar\Info;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_enrolled' => $this->is_enrolled,
            'is_delayed' => $this->is_delayed,
            'is_checked' => $this->is_checked,
            'is_locked' => $this->is_locked,
            'is_missed' => $this->is_missed,
            'is_grades_completed' => $this->is_grades_completed,
            'is_benefits_completed' => $this->is_benefits_completed,
            'is_completed' => $this->is_completed,
            'is_locked' => $this->is_locked,
            'semester' => $this->semester,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'has_failing' => $this->failed(),
            'has_empty' => $this->empty(),
            'attachment' => json_decode($this->attachment,true),
            'lists' => $this->lists,
            'created_at' => $this->created_at,
        ];
    }
}
