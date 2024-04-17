<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameLog extends Model
{
    protected $fillable = [
        'game_id',
        'event',
        'data',
    ];

    public function game()
    {
        return $this->belongsTo(tableGame::class);
    }
}
