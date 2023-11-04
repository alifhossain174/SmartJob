<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawResource extends JsonResource
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
            'user_id' => $this->user_id,
            'trx' => $this->trx,
            'payment_title' => $this->payment_title,
            'user_wallet_address' => $this->user_wallet_address,
            'admin_wallet_address' => $this->admin_wallet_address,
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_at_formatted' => date("d/m/Y h:i:s a",strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
        ];
    }
}
