<?php

namespace App\Providers;

use App\Events\PlayerPromoted;
use App\Events\PlayerSelectedCard;
use App\Events\PlayerWonDuel;
use App\Listeners\PlayerPromotedListener;
use App\Listeners\PlayerSelectedCardListener;
use App\Listeners\PlayerWonDuelListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PlayerSelectedCard::class => [
            PlayerSelectedCardListener::class,
        ],
        PlayerPromoted::class => [
            PlayerPromotedListener::class,
        ],
        PlayerWonDuel::class => [
            PlayerWonDuelListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
