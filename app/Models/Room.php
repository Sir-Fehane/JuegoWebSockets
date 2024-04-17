<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_id',
        'player2_id',
        'game_state',
        'player1_score',
        'player2_score',
        'winner_id',
        // Agrega más campos según sea necesario
    ];

    // Constantes para los estados de la sala
    const STATE_WAITING = 'waiting';
    const STATE_IN_PROGRESS = 'in_progress';
    const STATE_FINISHED = 'finished';
    const STATE_CANCELLED = 'cancelled';

    // Relación con el primer jugador (propietario de la sala)
    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    // Relación con el segundo jugador
    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    // Relación con el jugador ganador
    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
