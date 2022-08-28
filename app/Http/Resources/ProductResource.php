<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'inStock' => $this->inStock == 1 ? 'Yes' : 'No',
            'tags' => $this->tags,
            'categories' => $this->categories,
            'brand' => $this->brand,
            'image' => $this->image->path,
            'classification' => $this->classification->name,
        ];
    }
}
