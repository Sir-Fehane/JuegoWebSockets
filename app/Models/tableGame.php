<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tableGame extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player1_id',
        'player2_id',
        'player1_score',
        'player2_score',
        'is_finished',
    ];

    /**
     * Get the player1 for the game.
     */
    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    /**
     * Get the player2 for the game.
     */
    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }
}
