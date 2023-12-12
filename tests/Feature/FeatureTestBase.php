<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeatureTestBase extends TestCase
{
    protected function generateUserWithCards(int $cardsCount = 5): User
    {
        $user = User::factory()->create();
        Card::factory()
            ->count($cardsCount)
            ->for($user)
            ->create();

        return $user;
    }

    protected function generateUserWithAvailableDraws(int $availableDraws): User
    {
        return User::factory()->create([
            'available_draws' => $availableDraws,
        ]);
    }
}
