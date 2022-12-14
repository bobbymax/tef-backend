<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'title' => $this->product->title,
            'brand' => $this->product->brand->name,
            'quantity' => $this->quantity,
            'amount' => $this->product->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
