<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'review' => $this->review, // review is column
            'rate' => $this->rate,
            'created' => $this->created_at->diffforhumans(),
            'updated' => $this->updated_at->diffforhumans()
        ];
    }
}
