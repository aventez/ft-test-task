<?php

namespace App\Jobs;

use App\Enums\DuelStatus;
use App\Enums\WinnerType;
use App\Events\PlayerWonDuel;
use App\Models\Duel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDuel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const MAX_ROUNDS = 5;

    public function __construct(
        public Duel $duel
    ) {}

    public function handle(): void
    {
        $firstUserCard = $this->duel->firstUserSelectedCard;
        $secondUserCard = $this->duel->secondUserSelectedCard;

        $this->calculatePoints($firstUserCard, $secondUserCard);

        $this->updateAlreadyUsedCards($firstUserCard, $secondUserCard);

        if ($this->shouldFinalize()) {
            $this->finalizeDuel();
        } else {
            $this->startNextRound();
        }

        $this->duel->save();
    }

    private function calculatePoints($firstUserCard, $secondUserCard): void
    {
        $this->duel->first_user_points += $firstUserCard?->power ?? 0;
        $this->duel->second_user_points += $secondUserCard?->power ?? 0;
    }

    private function updateAlreadyUsedCards($firstUserCard, $secondUserCard): void
    {
        $alreadyUsedCards = $this->duel->already_used_cards ?? [];

        if ($firstUserCard !== null) $alreadyUsedCards[] = $firstUserCard->id;
        if ($secondUserCard !== null) $alreadyUsedCards[] = $secondUserCard->id;

        $this->duel->already_used_cards = $alreadyUsedCards;
    }

    private function shouldFinalize(): bool
    {
        return $this->duel->round >= self::MAX_ROUNDS;
    }

    private function finalizeDuel(): void
    {
        $won = WinnerType::Draw;

        if ($this->duel->first_user_points > $this->duel->second_user_points) {
            $won = WinnerType::FirstUser;
            PlayerWonDuel::dispatch($this->duel->firstUser);
        } elseif ($this->duel->first_user_points < $this->duel->second_user_points) {
            $won = WinnerType::SecondUser;
            PlayerWonDuel::dispatch($this->duel->secondUser);
        }

        $this->duel->won = $won;
        $this->duel->status = DuelStatus::Finished;
    }

    private function startNextRound(): void
    {
        $this->duel->round++;
        ProcessDuel::dispatch($this->duel)->delay(now()->addSeconds(3));
    }
}
