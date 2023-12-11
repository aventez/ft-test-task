<?php

namespace App\Services;

use App\Models\Card;
use App\Models\Duel;
use Illuminate\Database\Eloquent\Collection;

class DuelOpponentAiService
{
    public function chooseCard(Collection $cards, Duel $duel): Card
    {
        $alreadyUsedCards = $duel->already_used_cards;
        $availableCards = $cards->reject(function ($card) use ($alreadyUsedCards) {
            return in_array($card->id, $alreadyUsedCards);
        });

        $sortedCards = $availableCards->sortByDesc('power');
        return $sortedCards->first();
    }
}
