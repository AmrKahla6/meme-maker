<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StikerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'stiker_path' => $this->stiker_path
        ];
    }
}
