<?php

namespace Database\Factories;

use App\Enums\DuelStatus;
use App\Models\Duel;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Duel>
 */
class DuelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'round' => 1,
            'status' => DuelStatus::Active,
            'already_used_cards' => [],
            'first_user_id' => null,
            'first_user_selected_card_id' => null,
            'first_user_points' => 0,
            'second_user_id' => null,
            'second_user_selected_card_id' => null,
            'second_user_points' => 0,
            'won' => null,
        ];
    }
}
