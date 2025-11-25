<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
  protected string $apiKey;

  public function __construct()
  {
    $this->apiKey = config('services.openrouter.key') ?? env('OPENROUTER_API_KEY');
  }

  public function sendMessage(string $message): string
  {
    $message = trim($message);
    if (!$message) {
      return 'Pesan tidak boleh kosong.';
    }

    $cacheKey = 'chatbot_reply_' . md5($message);
    if (Cache::has($cacheKey)) {
      return Cache::get($cacheKey);
    }

    if (!$this->apiKey) {
      return 'API key OpenRouter belum diatur.';
    }

    try {
      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'HTTP-Referer' => url('/'),
        'X-Title' => config('app.name'),
        'Content-Type' => 'application/json',
      ])->timeout(20)
        ->post("https://openrouter.ai/api/v1/chat/completions", [
          "model" => "openai/gpt-4o-mini",
          "messages" => [
            [
              "role" => "system",
              "content" => "Kamu adalah chatbot perpustakaan. Jawab dengan sopan, informatif, dan hanya terkait dunia buku, penulis, penerbit, genre, sinopsis, aturan perpustakaan, atau hal literasi."
            ],
            [
              "role" => "user",
              "content" => $message
            ]
          ],
          "max_tokens" => 1000,
          "temperature" => 0.7,
        ]);

      if ($response->failed()) {
        Log::error('OpenRouter API failed', $response->json());
        return 'Gagal menghubungi OpenRouter.';
      }

      $data = $response->json();

      $reply = null;
      if (
        !empty($data['choices']) &&
        isset($data['choices'][0]['message']['content'])
      ) {
        $reply = $data['choices'][0]['message']['content'];
      }

      $reply = $reply ?? 'Maaf, saya tidak dapat memproses pertanyaan itu.';

      Cache::put($cacheKey, $reply, now()->addMinutes(30));

      return $reply;

    } catch (\Throwable $e) {
      Log::error('ChatbotService Error', ['message' => $e->getMessage()]);
      return 'Terjadi kesalahan internal: ' . $e->getMessage();
    }
  }
}
