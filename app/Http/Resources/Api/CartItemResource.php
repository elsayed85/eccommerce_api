<?php

namespace App\Http\Resources\Api;

use App\products;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'quantity' => $this->quantity,
            'price' => $product->price,
            'discount' => $product->discount,
            'total_price' => $product->PriceAfterDiscount,
            'id' => $product->id,
            'name' => $product->name,
            'type' => $product->type->name,
            'images' => productImagesResource::collection($product->images),
            'brand' => $product->brand->name,
            'avaiable' => $product->avaiable,
            'spc' => ProductSpecifcationsResource::collection($product->specification)
        ];
    }
}
