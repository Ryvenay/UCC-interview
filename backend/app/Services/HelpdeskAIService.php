<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HelpdeskAIService
{
    protected string $host;
    protected string $port;

    public function __construct()
    {
        $this->host = config('ollama.host');
        $this->port = config('ollama.port');
    }

    /**
     * Ask a question to the local LLM and get a response
     */
    public function ask($chatHistory, string $model = 'llama2'): string
    {
        $req_body = [
            'model' => $model,
            'messages' => $chatHistory
        ];

        $response = Http::timeout(120)->post("{$this->host}:{$this->port}/api/chat", $req_body);


        if ($response->ok()) {
            return $this->parseResponse($response->body());
        }

        return "I'm unable to answer at the moment.";
    }

    private function parseResponse($raw)
    {
        $lines = explode("\n", trim($raw));
        $finalResponse = '';

        $finalResponse = '';
        foreach ($lines as $line) {
            $json = json_decode($line, true);
            if ($json && isset($json['message'])) {
                $finalResponse .= $json['message']['content'];
            }
        }
        return $finalResponse;
    }
}
