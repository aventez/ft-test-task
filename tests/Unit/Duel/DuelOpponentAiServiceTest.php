<?php

namespace Tests\Unit\Duel;

use App\Models\Card;
use App\Models\Duel;
use App\Models\User;
use App\Services\DuelOpponentAiService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DuelOpponentAiServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testOpponentShouldChooseHighestCard()
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $duel = Duel::factory()->create([
            'first_user_id' => $firstUser->id,
            'second_user_id' => $secondUser->id,
        ]);

        $cards = new Collection([
            Card::factory()->create(['power' => 11]),
            Card::factory()->create(['power' => 3]),
            Card::factory()->create(['power' => 45]),
        ]);

        /** @var DuelOpponentAiService $duelOpponentAiService */
        $duelOpponentAiService = $this->app->make(DuelOpponentAiService::class);
        $chosenCard = $duelOpponentAiService->chooseCard($cards, $duel);

        $this->assertEquals($cards->last()->id, $chosenCard->id);
    }

    public function testOpponentShouldExcludeAlreadyUsedCards()
    {
        $cards = new Collection([
            Card::factory()->create(['power' => 11]),
            Card::factory()->create(['power' => 3]),
            Card::factory()->create(['power' => 45]),
        ]);
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $duel = Duel::factory()->create([
            'first_user_id' => $firstUser->id,
            'second_user_id' => $secondUser->id,
            'already_used_cards' => [$cards->last()->id],
        ]);

        /** @var DuelOpponentAiService $duelOpponentAiService */
        $duelOpponentAiService = $this->app->make(DuelOpponentAiService::class);
        $chosenCard = $duelOpponentAiService->chooseCard($cards, $duel);

        $this->assertEquals($cards->first()->id, $chosenCard->id);
    }
}
