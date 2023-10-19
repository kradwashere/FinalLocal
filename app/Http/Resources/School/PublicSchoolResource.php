<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicSchoolResource extends JsonResource
{
    public function toArray($request)
    {
        $name = ucwords(strtolower($this->school->school->name));
        $campus = ($this->is_main) ? '' : ' - '.ucwords(strtolower($this->school->campus)) ;

        $region = ($this->school->region) ? $this->school->region->region : '';
        $province = ($this->school->province) ? $this->school->province->name.', ' : '';
        $municipality = ($this->school->municipality) ? $this->school->municipality->name.', ' : '';


        return [
            'name' => $name,
            'campus' => $campus,
            'address' => $municipality.$province.$region,
        ];
    }
}
