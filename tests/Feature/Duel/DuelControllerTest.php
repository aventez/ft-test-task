<?php

namespace Tests\Feature\Duel;

use App\Jobs\ProcessDuel;
use App\Models\Duel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\Feature\FeatureTestBase;

class DuelControllerTest extends FeatureTestBase
{
    use RefreshDatabase;

    public function testUserIsAbleToStartDuel()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // User should be able to start the duel
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // Find the duel and check has been opponent created
        $duel = Duel::first();
        $opponent = $duel->secondUser;
        $expectedNumberOfOpponentCards = $user->getLevelAttribute() * 5;

        $this->assertNotNull($duel);
        $this->assertNotNull($opponent);
        $this->assertCount($expectedNumberOfOpponentCards, $opponent->cards);

        // Assert is processing pushed
        Queue::assertPushed(ProcessDuel::class, function ($job) use ($user) {
            return $job->duel->firstUser->id === $user->id;
        });

        // Check has both users active duel
        $expectedActiveDuelResponse = [
            'id',
            'round',
            'your_points',
            'opponent_points',
            'status',
            'won',
            'your_card',
            'opponent_card',
        ];

        $userResponse = $this->actingAs($user)->json('GET', '/api/duels/active');
        $userResponse->assertStatus(200);
        $userResponse->assertJsonStructure($expectedActiveDuelResponse);

        $opponentResponse = $this->actingAs($opponent)->json('GET', '/api/duels/active');
        $opponentResponse->assertStatus(200);
        $opponentResponse->assertJsonStructure($expectedActiveDuelResponse);
    }

    public function testUserIsUnableToStartDuelTwice()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // User should be able to start the first duel
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // Shouldnt be able to start the second if one is still in progress
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels');
        $duelResponse->assertStatus(400);
    }

    public function testUserIsAbleToPlaceCard()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // User should be able to start the duel
        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // User should be able to place the card
        $cardId = $user->cards->first()->id;
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels/action', [
                'selectedCardId' => $cardId,
            ]);
        $duelResponse->assertStatus(200);

        // Opponent should also place his card
        $this->assertNotNull($duelResponse->json('opponent_card'));
    }

    public function testUserIsUnableToPlaceUnexistingCard()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels/action', [
                'selectedCardId' => -1,
            ]);
        $duelResponse->assertStatus(404);
    }

    public function testUserIsUnableToPlaceNotHisCard()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        $duel = Duel::first();
        $opponent = $duel->secondUser;
        $opponentCardId = $opponent->cards->first()->id;
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels/action', [
                'selectedCardId' => $opponentCardId,
            ]);
        $duelResponse->assertStatus(404);
    }

    public function testUserIsUnableToPlaceSameCardTwice()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // User should be able to start the duel
        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // User should be able to place the card
        $cardId = $user->cards->first()->id;
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels/action', [
                'selectedCardId' => $cardId,
            ]);
        $duelResponse->assertStatus(200);

        // Now, simulate the round is finished
        $duel = Duel::first();
        $job = new ProcessDuel($duel);
        $job->handle();

        // Try put the same card twice
        $duelResponse = $this->actingAs($user)
            ->json('POST', '/api/duels/action', [
                'selectedCardId' => $cardId,
            ]);
        $duelResponse->assertStatus(400);
    }

    public function testRoundsAreIncreasing()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // Starting the duel
        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // Placing the bets
        $cardId = $user->cards->first()->id;
        $this->actingAs($user)
            ->json('POST', '/api/duels/action', ['selectedCardId' => $cardId]);

        // Now, simulate the round is finished
        $duel = Duel::first();
        $job = new ProcessDuel($duel);
        $job->handle();

        // Get current duel
        $activeDuel = $this->actingAs($user)->json('GET', '/api/duels/active');
        $activeDuel->assertJson(['round' => 2]);
    }

    public function testIsGameFinishingAfterFiveRounds()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // Starting the duel
        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // Simulate the game
        for ($i = 0; $i < 5; $i++) {
            $cardId = $user->cards->values()->get($i)->id;
            $this->actingAs($user)
                ->json('POST', '/api/duels/action', ['selectedCardId' => $cardId]);

            $duel = Duel::first();
            $job = new ProcessDuel($duel);
            $job->handle();
        }

        // User should not have active duel
        $duel = Duel::first();
        $duelResponse = $this->actingAs($user)->json('GET', '/api/duels/active');
        $duelResponse->assertStatus(404);
    }

    public function testArePointsCalculatedProperly()
    {
        Queue::fake();

        $user = $this->generateUserWithCards();

        // Starting the duel
        $duelResponse = $this->actingAs($user)->json('POST', '/api/duels');
        $duelResponse->assertStatus(200);

        // Simulate the game
        for ($i = 0; $i < 5; $i++) {
            $cardId = $user->cards->values()->get($i)->id;
            $this->actingAs($user)
                ->json('POST', '/api/duels/action', ['selectedCardId' => $cardId]);

            $duel = Duel::first();
            $job = new ProcessDuel($duel);
            $job->handle();
        }

        $duel = Duel::first();
        $userPoints = $user->cards->sum('power');
        $opponentPoints = $duel->secondUser->cards->sum('power');

        $this->assertSame($userPoints, $duel->first_user_points);
        $this->assertSame($opponentPoints, $duel->second_user_points);
    }
}
