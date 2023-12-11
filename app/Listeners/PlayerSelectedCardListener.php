<?php

namespace App\Listeners;

use App\Events\PlayerSelectedCard;
use App\Services\DuelOpponentAiService;
use App\Services\DuelService;

class PlayerSelectedCardListener
{
    public function __construct(
        private readonly DuelService $duelService,
        private readonly DuelOpponentAiService $duelOpponentAiService,
    ) {}

    public function handle(PlayerSelectedCard $event): void
    {
        $cards = $event->duel->secondUser->cards;
        $card = $this->duelOpponentAiService->chooseCard($cards, $event->duel);

        $this->duelService->selectCardForSecondPlayer(
            $event->duel,
            $card,
        );
    }
}
