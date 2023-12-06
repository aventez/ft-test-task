<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Database\Eloquent\Collection;

class DuelOpponentAiService
{
    public function chooseCard(Collection $cards): Card
    {
        return $cards->random();
    }
}
