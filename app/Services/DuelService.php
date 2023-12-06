<?php

namespace App\Services;

use App\Enums\DuelStatus;
use App\Events\PlayerSelectedCard;
use App\Jobs\ProcessDuel;
use App\Models\Card;
use App\Models\Duel;
use App\Models\User;

class DuelService
{
    public function getUserActiveDuel(User $user): ?Duel
    {
        return Duel::where(function ($query) use ($user) {
            $query->where('first_user_id', $user->id)
                ->orWhere('second_user_id', $user->id);
        })->where('status', DuelStatus::Active)->first();
    }

    public function getUserDuels(User $user)
    {
        return Duel::where('first_user_id', $user->id)
            ->orWhere('second_user_id', $user->id)
            ->get();
    }

    public function startDuel(User $user): Duel
    {
        $level = $user->getLevelAttribute();

        // Create opponent account
        $opponent = User::factory()->create();
        $numberOfOpponentCards = $level * 5;

        Card::factory()
            ->count($numberOfOpponentCards)
            ->for($opponent)
            ->create();

        // Create duel
        $duel = new Duel();
        $duel->firstUser()->associate($user);
        $duel->secondUser()->associate($opponent);
        $duel->status = DuelStatus::Active;
        $duel->save();

        // Push job
        ProcessDuel::dispatch($duel)
            ->delay(now()->addSeconds(5));

        return $duel;
    }

    public function selectCardForFirstPlayer(Duel $duel, Card $card): void
    {
        $duel->firstUserSelectedCard()->associate($card);
        $duel->save();

        PlayerSelectedCard::dispatch($duel);
    }

    public function selectCardForSecondPlayer(Duel $duel, Card $card): void
    {
        $duel->secondUserSelectedCard()->associate($card);
        $duel->save();
    }
}
