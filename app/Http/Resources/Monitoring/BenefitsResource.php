<?php

namespace App\Http\Resources\Monitoring;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Scholar\Benefits\EnrollmentResource;

class BenefitsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'spas_id' => $this->spas_id,
            'awarded_year' => $this->awarded_year,
            'status' => $this->status,
            'firstname' => ucwords(strtolower($this->profile->firstname)),
            'middlename' => ucwords(strtolower($this->profile->middlename)),
            'lastname' => ucwords(strtolower($this->profile->lastname)),
            'avatar' => 'avatar.jpg',
            'enrollments' => EnrollmentResource::collection($this->enrollments),
        ];
    }
}
