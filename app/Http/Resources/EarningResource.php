<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EarningResource extends JsonResource
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
            'earning_from' => $this->earning_from,
            'title' => $this->title,
            'points' => $this->points,
            'created_at' => $this->created_at,
            'created_at_formatted' => date("d/m/Y h:i:s a",strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
        ];
    }
}
