<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InternalOrderResource extends JsonResource
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
            'orderId' => $this->trnxId,
            'user_id' => $this->user_id,
            'cart_id' => $this->cart_id,
            'amount' => $this->cart->total_amount,
            'items' => CartItemResource::collection($this->cart->items),
            'table_no' => $this->table_no,
            'recipient' => $this->recipient,
            'waiter' => $this->handler->name,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'amount_received' => $this->amount_received,
            'additional_information' => $this->additional_information,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'paid' => $this->paid ? 'yes' : 'no',
            'closed' => $this->closed ? 'closed' : 'opened',
        ];
    }
}
