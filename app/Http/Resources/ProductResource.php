<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'label' => $this->label,
            'brand_id' => $this->brand_id,
            'classification_id' => $this->classification_id,
            'description' => $this->description,
            'price' => $this->price,
            'vip' => $this->vip,
            'discount' => $this->discount,
            'inStock' => $this->inStock == 1,
            'tags' => $this->tags,
            'categories' => $this->categories,
            'brand' => $this->brand,
            'images' => $this->images,
            'classification' => $this->classification,
        ];
    }
}
