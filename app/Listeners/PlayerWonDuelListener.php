<?php

namespace App\Listeners;

use App\Events\PlayerWonDuel;
use App\Services\UserService;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class PlayerWonDuelListener
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function handle(PlayerWonDuel $event): void
    {
        $this->userService->giveUserPoints($event->user, 100);
    }
}
