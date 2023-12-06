<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Resolvers\LevelResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'points',
        'cards',
        'available_draws',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function isNewCardAllowed(): bool
    {
        return ($this->available_draws > 0);
    }

    public function getLevelAttribute(): int
    {
        return LevelResolver::resolveLevelFromPoints($this->points);
    }

    public function getLevelProgressAttribute()
    {
        return sprintf('%d/%d',
            $this->points,
            LevelResolver::getNextLevelDesiredPoints($this->points),
        );
    }
}
