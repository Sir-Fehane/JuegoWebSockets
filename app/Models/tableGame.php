<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tableGame extends Model
{
    protected $fillable = [
        'player1_id',
        'player2_id',
        'player1_score',
        'player2_score',
        'status',
        'winner_id',
    ];

    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function gameLogs()
    {
        return $this->hasMany(GameLog::class);
    }
}