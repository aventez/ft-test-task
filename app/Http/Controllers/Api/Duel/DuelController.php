<?php

namespace App\Http\Controllers\Api\Duel;

use App\Events\PlayerWonDuel;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\DuelResource;
use App\Models\User;
use App\Services\CardService;
use App\Services\DuelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DuelController extends Controller
{
    public function __construct(
        private readonly DuelService $duelService,
        private readonly CardService $cardService,
    ) {}

    public function start(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        if ($this->duelService->getUserActiveDuel($user)) {
            //throw new BadRequestHttpException('You have already active duel');
        }

        $this->duelService->startDuel($user);

        return new JsonResponse(null, 200);
    }

    public function active(Request $request): DuelResource
    {
        /** @var User $user */
        $user = $request->user();
        $duel = $this->duelService->getUserActiveDuel($user);

        if (!$duel) {
            throw new BadRequestHttpException('You do not have active duel');
        }

        return new DuelResource($duel);
    }

    public function selectCard(Request $request): DuelResource
    {
        $body = $request->validate(['selectedCardId' => ['required'],]);
        $selectedCardId = $body['selectedCardId'];

        /** @var User $user */
        $user = $request->user();
        $duel = $this->duelService->getUserActiveDuel($user);
        if (!$duel) {
            throw new BadRequestHttpException('You do not have active duel');
        }

        $card = $this->cardService->getCardById($selectedCardId);
        if (!$card || $card->user->id !== $user->id) {
            throw new NotFoundHttpException('Card not found');
        }
        if (in_array($card->id, $duel->already_used_cards)) {
            throw new BadRequestHttpException('You have already used this card');
        }

        $this->duelService->selectCardForFirstPlayer($duel, $card);

        return new DuelResource($duel);
    }

    public function list(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $duels = $this->duelService->getUserDuels($user);

        return DuelResource::collection($duels);
    }
}
