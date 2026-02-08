<?php

namespace App\Http\Controllers;

use App\Models\Chat;
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
}
