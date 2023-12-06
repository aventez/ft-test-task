<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->name,
            'level' => $this->getLevelAttribute(),
            'level_points' => $this->getLevelProgressAttribute(),
            'cards' => CardResource::collection($this->cards),
            'new_card_allowed' => $this->isNewCardAllowed(),
        ];
    }
}
