<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\FeatureTestBase;

class UserControllerTest extends FeatureTestBase
{
    use RefreshDatabase;

    public function testUserDataEndpoint()
    {
        $numberOfCards = 5;
        $user = $this->generateUserWithCards($numberOfCards);

        $response = $this->actingAs($user)
            ->json('GET', '/api/user-data');

        $response->assertStatus(200);
        $response->assertJsonCount($numberOfCards, 'cards');
        $response->assertJsonStructure([
            'id',
            'username',
            'level',
            'level_points',
            'cards' => [],
            'new_card_allowed',
        ]);
    }

    public function testUserDataEndpointUnauthorized()
    {
        $response = $this->json('GET', '/api/user-data');

        $response->assertStatus(401);
    }
}
