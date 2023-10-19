<?php

namespace App\Http\Resources\Scholar\Info;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Scholar\Info\ListResource;

class ViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'enrollments' => ListResource::collection($this->enrollments)
        ];
    }
}
