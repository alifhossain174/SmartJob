<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountSubmissionResource extends JsonResource
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
            'amount' => $this->amount,
            'wallet_address_admin' => $this->wallet_address_admin,
            'transaction_hash' => $this->transaction_hash,
            'status' => $this->status,
            'created_at' => date("Y-m-d H:i:s",strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
        ];
    }
}
