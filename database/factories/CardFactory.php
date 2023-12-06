<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cards = config('game.cards');
        $card = $this->faker->randomElement($cards);

        return [
            'name' => $card['name'],
            'power' => $card['power'],
            'image' => $card['image'],
            'user_id' => User::factory(),
        ];
    }
}
