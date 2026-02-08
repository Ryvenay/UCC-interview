<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Optional: only allow managers
        if ($user->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Filters: status, assigned agent
        $status = $request->query('status'); // e.g., 'open', 'human_requested'

        $chats = Chat::with(['user','messages']) // eager load relations
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'data' => $chats
        ]);
    }

        public function send(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'message' => $request->message,
            'type' => 'agent',
        ]);

        return response()->json([
            'content' => $message->message,
            'role' => $message->type === 'user' ? 'user' : 'agent',
            'created_at' => $message->created_at->toDateTimeString(),
        ]);
    }
}
