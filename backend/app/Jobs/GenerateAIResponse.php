<?php

namespace App\Jobs;

use App\Models\Chat;
use App\Models\Message;
use App\Services\HelpdeskAIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateAIResponse implements ShouldQueue
{
    use Queueable;

    protected $ai;
    protected $chat;

    /**
     * Create a new job instance.
     */
    public function __construct(HelpdeskAIService $ai, Chat $chat)
    {
        $this->ai = $ai;
        $this->chat = $chat;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $history = $this->chat->getHistory();
        $aiResponse = $this->ai->ask($history);

        Message::create([
            'chat_id' => $this->chat->id,
            'message' => $aiResponse,
            'type' => 'ai',
        ]);
    }
}
