<?php

namespace App\Http\Controllers;

use App\Models\GameLog;
use Illuminate\Http\Request;
use Pusher\Pusher;

class GameLogController extends Controller
{
    public function logEvent(Request $request)
    {
        $gameId = $request->input('game_id');
        $event = $request->input('event');
        $data = $request->input('data');

        $gameLog = GameLog::create([
            'game_id' => $gameId,
            'event' => $event,
            'data' => $data,
        ]);

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $eventData = [
            'gameLog' => $gameLog->toArray(),
        ];

        $pusher->trigger('game-channel', $event, $eventData);

        return response()->json([
            'message' => 'Event logged successfully',
            'gameLog' => $gameLog,
        ]);
    }
}