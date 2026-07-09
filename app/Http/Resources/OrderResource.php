<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'menu_id' => $this->menu_id,
            'jumlah' => $this->jumlah,
            'status' => $this->status,
            'tanggal' => $this->tanggal,
            'user' => new UserResource($this->whenLoaded('user')),
            'menu' => new MenuResource($this->whenLoaded('menu')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
