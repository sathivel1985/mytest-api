<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (empty($this->id)) {
            return [];
        }
        return [
            'id'         => $this->id,
            'key'        => $this->name,
            'value'      => $this->value,
            'updated_at' => $this->updated_at
        ];
    }
}
