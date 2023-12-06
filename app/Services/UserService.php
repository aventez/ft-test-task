<?php

namespace App\Services;

use App\Events\PlayerPromoted;
use App\Models\User;

class UserService
{
    public function reduceAvailableDraws(User $user): void
    {
        $user->available_draws = ($user->available_draws - 1);
        $user->save();
    }

    public function addAvailableDraws(User $user, int $amount): void
    {
        $user->available_draws = ($user->available_draws + $amount);
        $user->save();
    }

    public function giveUserPoints(User $user, int $amount): void
    {
        $newPointValue = $user->points + $amount;
        $oldLevel = $user->getLevelAttribute();

        $user->points = $newPointValue;
        $user->save();

        $newLevel = $user->getLevelAttribute();

        if ($oldLevel !== $newLevel) {
            PlayerPromoted::dispatch($user);
        }
    }
}
