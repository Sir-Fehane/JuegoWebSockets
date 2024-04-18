<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use App\Models\Room;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoomController extends Controller
{
    public function create(Request $request)
    {
        // Crea una nueva sala con el jugador 1 como propietario
        $user = JWTAuth::parseToken()->authenticate();
        $room = Room::create([
            'player1_id' => $user->id,
            'game_state' => Room::STATE_WAITING, // Establece el estado del juego como "waiting"
        ]);

        // Inicializa Pusher con las variables de entorno
        $pusher = new Pusher("7db2609c0d8a52d0047e", "60af793630219bb7312f", "1789056", [
            'cluster' => "us2",
            'useTLS' => true,
        ]);

        // Transmite un evento indicando que se ha creado una nueva sala
        $pusher->trigger('room-events', 'room-created', $room);

        return response()->json(['room' => $room], 201);
    }

    public function join(Request $request, Room $room)
    {
        // Asigna al jugador 2 a la sala
        $room->player2_id = Auth::id();
        
        // Actualiza el estado del juego a "in_progress"
        $room->game_state = Room::STATE_IN_PROGRESS;
        
        $room->save();

        // Transmite un evento indicando que un jugador se ha unido a la sala
        $pusher = new Pusher("7db2609c0d8a52d0047e", "60af793630219bb7312f", "1789056", [
            'cluster' => "us2",
            'useTLS' => true,
        ]);
        $pusher->trigger('room-events', 'player-joined', $room);

        return response()->json(['room' => $room]);
    }

    public function getWaitingRooms()
    {
        // Busca todas las salas que tengan el estado "waiting"
        $waitingRooms = Room::where('game_state', Room::STATE_WAITING)->get();

        return response()->json(['rooms' => $waitingRooms]);
    }

    public function playerOneLeft(Room $room)
    {
        // Verifica si hay un jugador 2 en la sala
        if ($room->player2_id !== null) {
            // Actualiza el jugador 1 al jugador 2
            $room->player1_id = $room->player2_id;
            $room->player2_id = null;
            $room->game_state = Room::STATE_WAITING; // Cambia el estado del juego a "waiting" porque queda un solo jugador
            $room->save();

            // Transmite un evento indicando que el jugador 1 se ha ido y el estado del juego ha cambiado
            $this->broadcastRoomEvent($room, 'player-one-left', $room);

            return response()->json(['message' => 'Player one has left and player two becomes player one']);
        }

        // Si no hay un jugador 2, simplemente elimina la sala
        $room->delete();

        return response()->json(['message' => 'Player one has left and room is deleted']);
    }

    // Otros métodos del controlador

    // Método para transmitir eventos de sala utilizando Pusher
    private function broadcastRoomEvent(Room $room, string $eventName, $data)
    {
        $pusher = new Pusher("7db2609c0d8a52d0047e", "60af793630219bb7312f", "1789056", [
            'cluster' => "us2",
            'useTLS' => true,
        ]);

        $pusher->trigger('room-events', $eventName, $data);
    }

    public function playerTwoLeft(Room $room)
    {
        // Verifica si hay un jugador 1 en la sala
        if ($room->player1_id !== null) {
            // Actualiza el jugador 2 al jugador 1
            $room->player2_id = null;
            $room->game_state = Room::STATE_WAITING; // Cambia el estado del juego a "waiting" porque queda un solo jugador
            $room->save();

            // Transmite un evento indicando que el jugador 2 se ha ido y el estado del juego ha cambiado
            $this->broadcastRoomEvent($room, 'player-two-left', $room);

            return response()->json(['message' => 'Player two has left and room is now waiting for another player']);
        }

        // Si no hay un jugador 1, simplemente elimina la sala
        $room->delete();

        return response()->json(['message' => 'Player two has left and room is deleted']);
    }

    public function bothPlayersLeft(Room $room)
    {
        // Actualiza el estado del juego a "cancelled"
        $room->game_state = Room::STATE_CANCELLED;
        $room->save();

        // Transmite un evento indicando que la partida se ha cancelado
        $this->broadcastRoomEvent($room, 'game-cancelled', $room);

        return response()->json(['message' => 'Both players have left']);
    }

    public function startGame(Room $room)
    {
        // Verifica si ambos jugadores están presentes en la sala
        if ($room->player1_id !== null && $room->player2_id !== null) {
            // Actualiza el estado del juego a "in_progress"
            $room->game_state = Room::STATE_IN_PROGRESS;
            $room->save();

            // Transmite un evento indicando que la partida ha comenzado
            $this->broadcastRoomEvent($room, 'game-started', $room);

            return response()->json(['message' => 'Game has started']);
        }

    }

    public function updateScores(Request $request, Room $room)
    {
        // Valida la petición para asegurarse de que incluya la puntuación de ambos jugadores
        $request->validate([
            'player1_score' => 'required|integer',
            'player2_score' => 'required|integer',
        ]);

        // Actualiza la puntuación de los jugadores en la sala
        $room->player1_score = $request->input('player1_score');
        $room->player2_score = $request->input('player2_score');
        $room->save();

        // Transmite un evento indicando que se ha actualizado la puntuación de los jugadores
        $this->broadcastRoomEvent($room, 'scores-updated', $room);

        return response()->json(['message' => 'Scores updated successfully']);
    }

    public function determineWinner(Room $room)
    {
        // Obtener las puntuaciones de los jugadores
        $player1Score = $room->player1_score;
        $player2Score = $room->player2_score;

        // Comparar las puntuaciones y determinar el ganador
        if ($player1Score > $player2Score) {
            $winnerId = $room->player1_id;
        } elseif ($player1Score < $player2Score) {
            $winnerId = $room->player2_id;
        } else {
            // En caso de empate, no hay un ganador definido
            $winnerId = null;
        }

        return response()->json([
            'winner_id' => $winnerId,
        ]);
    }
    
    public function exitRoom(Room $room)
    {
        // Verifica si el jugador que sale es el jugador 1 o el jugador 2
        $userId = Auth::id();
        if ($room->player1_id === $userId || $room->player2_id === $userId) {
            // Si el jugador es el jugador 1 o el jugador 2, actualiza la sala y elimina su ID
            if ($room->player1_id === $userId) {
                $room->player1_id = null;
            } else {
                $room->player2_id = null;
            }

            // Si ambos jugadores han salido, elimina la sala
            if ($room->player1_id === null && $room->player2_id === null) {
                $room->delete();
                return response()->json(['message' => 'Room deleted successfully']);
            }

            // Guarda los cambios en la sala
            $room->save();

            // Transmite un evento indicando que un jugador ha salido de la sala
            $this->broadcastRoomEvent($room, 'player-left', $room);

            return response()->json(['message' => 'Player left successfully']);
        }

        // Si el jugador no es ni el jugador 1 ni el jugador 2, devuelve un error
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function getPlayersByRoom(Request $request ,$id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $player1 = User::find($room->player1_id);
        $player2 = User::find($room->player2_id);

        $player1Name = $player1 ? $player1->name : 'Unknown';
        $player2Name = $player2 ? $player2->name : 'Unknown';

        return response()->json([
            'room_id' => $room->id,
            'player1_name' => $player1Name,
            'player2_name' => $player2Name,
        ]);
    }
}
