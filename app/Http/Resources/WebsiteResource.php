<?php

namespace App\Http\Resources;

use App\Models\Earnings;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteResource extends JsonResource
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
            'logo' => $this->logo,
            'description' => $this->description,
            'link' => $this->link,
            'visiting_seconds' => $this->visiting_seconds,
            'visiting_status' => Earnings::where('user_id', $this->user_id)->where('website_id', $this->id)->where('created_at', 'LIKE', date("Y-m-d").'%')->first() ? 1 : 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
