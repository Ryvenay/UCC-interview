<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getHistory() : array 
    {
        return $this->messages()->orderBy('created_at')->get()->map(function ($message) {
            return [
                "content" => $message->message,
                "role" => $message->type,
            ];
        })
        ->toArray();
    }
}
