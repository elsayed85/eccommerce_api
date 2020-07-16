<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class productResoucre extends JsonResource
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
            'name' => $this->name,
            'type' => ['id' => $this->type->id, 'name' => $this->type->name],
            'quantity' => $this->quantity,
            'price' => $this->price,
            'discount' => $this->discount,
            'price_after_discount' => $this->price_after_discount,
            'images' => productImagesResource::collection($this->images),
            'brand' => $this->brand->name,
            'avaiable' => $this->avaiable,
            'spc' => ProductSpecifcationsResource::collection($this->specification),
            'reviews' => new ReviewCollection($this->review),
            'description' => $this->description
        ];
    }
}
