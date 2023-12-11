<?php

namespace Tests\Feature\Card;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestBase;

class CardControllerTest extends FeatureTestBase
{
    use RefreshDatabase;

    public function testDrawCardEndpointWithAvailableDraws()
    {
        $availableDraws = 1;
        $user = $this->generateUserWithAvailableDraws($availableDraws);

        $response = $this->actingAs($user)
            ->json('POST', '/api/cards');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'power',
            'image',
        ]);
    }

    public function testDrawCardEndpointWithoutAvailableDraws()
    {
        $availableDraws = 0;
        $user = $this->generateUserWithAvailableDraws($availableDraws);

        $response = $this->actingAs($user)
            ->json('POST', '/api/cards');

        $response->assertStatus(400);
    }

    public function testIsDrawConsumingAvailableDraws()
    {
        $availableDraws = 1;
        $user = $this->generateUserWithAvailableDraws($availableDraws);

        // Initially, user should be allowed to draw a new card
        $userData = $this->actingAs($user)->json('GET', '/api/user-data');
        $userData->assertJson(['new_card_allowed' => true]);

        // Then, we draw a card
        $drawResult = $this->actingAs($user)
            ->json('POST', '/api/cards');
        $drawResult->assertStatus(200);

        // User should not be able to draw next card as its count is 0
        $userData = $this->actingAs($user)->json('GET', '/api/user-data');
        $userData->assertJson(['new_card_allowed' => false]);

        $drawResult = $this->actingAs($user)->json('POST', '/api/cards');
        $drawResult->assertStatus(400);
    }
}
