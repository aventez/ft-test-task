<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Duel extends Model
{
    use HasFactory;

    protected $fillable = [
        'round',
        'status',
        'already_used_cards',
        'first_user_id',
        'first_user_selected_card_id',
        'first_user_points',
        'second_user_id',
        'second_user_selected_card_id',
        'second_user_points',
        'won',
    ];

    protected $casts = [
        'already_used_cards' => 'json',
    ];

    public function firstUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_user_id');
    }

    public function secondUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user_id');
    }

    public function firstUserSelectedCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'first_user_selected_card_id');
    }

    public function secondUserSelectedCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'second_user_selected_card_id');
    }
}
