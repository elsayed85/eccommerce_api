<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\ResourceCollection;

class typeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            $this->collection->map(function ($type) {
                $arr = $type->only(['id', 'avaiable', 'name']);
                $arr['products']  = new ProductCollection($type->product->take(3));
                return $arr;
            });
    }
}
