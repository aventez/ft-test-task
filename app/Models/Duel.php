<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Duel
 *
 * @property int $id
 * @property int $round
 * @property string $status
 * @property array $already_used_cards
 * @property int $first_user_id
 * @property int $first_user_selected_card_id
 * @property int $first_user_points
 * @property int $second_user_id
 * @property int $second_user_selected_card_id
 * @property int $second_user_points
 * @property string $won
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read User $firstUser
 * @property-read User $secondUser
 * @property-read Card $firstUserSelectedCard
 * @property-read Card $secondUserSelectedCard
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Duel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Duel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Duel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereAlreadyUsedCards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereFirstUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereFirstUserPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereFirstUserSelectedCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereRound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereSecondUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereSecondUserPoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereSecondUserSelectedCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Duel whereWon($value)
 */
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
