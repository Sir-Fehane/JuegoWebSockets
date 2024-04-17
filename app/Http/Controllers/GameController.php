<?php

namespace App\Http\Controllers;

use App\Models\tableGame;
use App\Models\User;
use Illuminate\Http\Request;
use Pusher\Pusher;

class GameController extends Controller
{
    public function createGame(Request $request)
    {
        $user = $request->user();
        $game = tableGame::create([
            'player1_id' => $user->id,
            'status' => 'pending',
        ]);

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $data = [
            'game' => $game->toArray(),
        ];

        $pusher->trigger('game-channel', 'game.created', $data);

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $game,
        ]);
    }

    public function joinGame($gameId)
    {
        $user = auth()->user();
        $game = tableGame::findOrFail($gameId);

        if ($game->player2_id !== null) {
            return response()->json([
                'message' => 'Game is already full',
            ], 400);
        }

        $game->player2_id = $user->id;
        $game->status = 'in_progress';
        $game->save();

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $data = [
            'game' => $game->toArray(),
        ];

        $pusher->trigger('game-channel', 'game.joined', $data);
        $pusher->trigger('game-channel', 'game.started', $data);

        return response()->json([
            'message' => 'Joined game successfully',
            'game' => $game,
        ]);
    }

    public function updateScore(Request $request, $gameId)
    {
        $game = tableGame::findOrFail($gameId);

        $player = $request->input('player');
        $score = $request->input('score');

        if ($player === 'player1') {
            $game->player1_score = $score;
        } elseif ($player === 'player2') {
            $game->player2_score = $score;
        }

        $game->save();

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $data = [
            'game' => $game->toArray(),
        ];

        $pusher->trigger('game-channel', 'game.scoreUpdated', $data);

        return response()->json([
            'message' => 'Score updated successfully',
            'game' => $game,
        ]);
    }

    public function finishGame(Request $request, $gameId)
    {
        $game = tableGame::findOrFail($gameId);

        $winnerId = $request->input('winner_id');
        $game->status = 'finished';
        $game->winner_id = $winnerId;
        $game->save();

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $data = [
            'game' => $game->toArray(),
        ];

        $pusher->trigger('game-channel', 'game.finished', $data);

        return response()->json([
            'message' => 'Game finished successfully',
            'game' => $game,
        ]);
    }
}
