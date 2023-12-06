<?php

namespace App\Listeners;

use App\Events\PlayerPromoted;
use App\Services\UserService;

class PlayerPromotedListener
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function handle(PlayerPromoted $event): void
    {
        $this->userService->addAvailableDraws($event->user, 5);
    }
}
