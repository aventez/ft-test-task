<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DuelResource extends JsonResource
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
            'round' => $this->round,
            'your_points' => $this->first_user_points,
            'opponent_points' => $this->second_user_points,
            'status' => $this->status,
            'won' => $this->won,
            'your_card' => new CardResource($this->firstUserSelectedCard),
            'opponent_card' => new CardResource($this->secondUserSelectedCard),
        ];
    }
}
