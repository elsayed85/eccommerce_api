<?php

namespace App\Http\Resources\Api;

use App\products;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product = products::find($this->product_id);
        return [
            'id' => $product->id,
            'name' => $product->name,
            'type' => $product->type->name,
            'price' => $product->price,
            'images' => productImagesResource::collection($product->images),
            'brand' => $product->brand->name,
            'avaiable' => $product->avaiable,
            'spc' => ProductSpecifcationsResource::collection($product->specification)
        ];
    }
}
