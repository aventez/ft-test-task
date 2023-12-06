<?php

namespace App\Services;

use App\Models\Card;
use App\Models\User;

class CardService
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function drawCard(User $user): Card
    {
        // Draw a card
        $availableCards = config('game.cards');
        $randomCardIndex = mt_rand(0, count($availableCards) - 1);
        $randomCard = $availableCards[$randomCardIndex];

        /** @var Card $card */
        $card = $user->cards()->create([
            'name' => $randomCard['name'],
            'power' => $randomCard['power'],
            'image' => $randomCard['image'],
            'user_id' => $user->id,
        ]);

        $this->userService->reduceAvailableDraws($user);

        return $card;
    }

    public function getCardById(int $id): ?Card
    {
        return Card::find($id);
    }
}
