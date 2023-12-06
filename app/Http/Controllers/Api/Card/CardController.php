<?php

namespace App\Http\Controllers\Api\Card;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Models\User;
use App\Services\CardService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CardController extends Controller
{
    public function __construct(
        private readonly CardService $cardService,
    ) {}

    public function draw(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        if (!$user->isNewCardAllowed()) {
            throw new BadRequestHttpException('No available draws');
        }

        return response(
            new CardResource($this->cardService->drawCard($user)),
            200
        );
    }
}
