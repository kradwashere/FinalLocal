<?php

namespace App\Http\Resources\Monitoring;

use Hashids\Hashids;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Monitoring\Info\EducationResource;

class GradesResource extends JsonResource
{
    public function toArray($request)
    {
        $hashids = new Hashids('krad',10);
        $id = $hashids->encode($this->id);

        return [
            'id' => $id,
            'spas_id' => $this->spas_id,
            'awarded_year' => $this->awarded_year,
            'status' => $this->status,
            'firstname' => ucwords(strtolower($this->profile->firstname)),
            'middlename' => ucwords(strtolower($this->profile->middlename)),
            'lastname' => ucwords(strtolower($this->profile->lastname)),
            'avatar' => 'avatar.jpg',
            'enrollments' => $this->enrollments,
            'education' => new EducationResource($this->education)
        ];
    }
}
