<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Jobs\GenerateAIResponse;
use App\Models\Message;
use App\Models\Chat;
use Illuminate\Http\Request;
use App\Services\HelpdeskAIService;

class MessageController extends Controller
{
    protected $ai;

    public function __construct(HelpdeskAIService $ai)
    {
        $this->ai = $ai;
    }

    public function show(Chat $chat,  Request $request)
    {
        $lastId = $request->query('last_id', 0);

        return new ChatResource($chat, $lastId);
    }

    /**
     * User sends a message
     */
    public function send(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'message' => $request->message,
            'type' => 'user',
        ]);

        if (!$chat->transfer_to_human) {
            GenerateAIResponse::dispatch($this->ai, $chat);
        }

        return response()->json([
            'content' => $message->message,
            'role' => $message->type === 'user' ? 'user' : 'agent',
            'created_at' => $message->created_at->toDateTimeString(),
        ]);
    }

    public function transferToHuman(Chat $chat)
    {
        $chat->transfer_to_human = true;
        $chat->save();

        return response()->json([
            'message' => 'The chat has been transferred to a human agent.',
            'chat_id' => $chat->id,
            'status' => $chat->status
        ]);
    }

    public function createOrGet()
    {
        $user = auth('sanctum')->user();

        if ($user) {
            $chat = $user->chats()->where('status', 'open')->first();

            if (is_null($chat)) {
                $chat = Chat::create([
                    'user_id' => $user->id,
                    'agent_id' => 1,
                    'status' => 'open',
                ]);
                $chat->messages()->create([
                    'type' => 'system',
                    'message' => "You are the official helpdesk assistant for a web application called **EventApp**. This website allows users to:

- Create, view, and manage events.
- View upcoming events and their details.
- Interact with a helpdesk for questions or issues.

Your role is to:

1. Answer user questions about how to use the website.
2. Provide guidance about features like creating events, logging in, or managing account settings.
3. If the user asks for help beyond your capabilities, politely suggest transferring them to a human support agent.
4. Always respond in a helpful, clear, and friendly tone.
5. Keep answers concise and actionable.

Examples of interactions:

- **User:** How do I create a new event?
  **Bot:** To create a new event, click the Add Event button on your dashboard, fill in the event details, and then click Save.

- **User:** I need to reset my password.
  **Bot:** You can reset your password by clicking Forgot Password on the login page and following the instructions sent to your email.

Remember: You only provide helpdesk guidance for EventApp features. For questions about unrelated topics, politely redirect the user to human support."
                ]);
            }
        } else {
            $chat = Chat::create([
                'agent_id' => 1,
                'status' => 'open',
            ]);
        }

        return response()->json([
            'chat_id' => $chat->id,
        ]);
    }
}
