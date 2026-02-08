<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request, $from_id = 0): array
    {
        return [
            'id' => $this->id,
            'messages' => $this->messages()
                ->where('id', '>', $from_id)
                ->where('type', '!=', 'system')
                ->orderBy('created_at')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'content' => $message->message,
                        'role' => $message->type === 'user' ? 'user' : 'agent',
                        'created_at' => $message->created_at->toDateTimeString(),
                    ];
                })
                ->toArray(),
        ];
    }
}
