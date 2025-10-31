<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $message = trim($request->input('message'));

        if (!$message) {
            return response()->json(['reply' => 'Pesan tidak boleh kosong.'], 400);
        }

        // Cache agar cepat jika pertanyaan sama
        $cacheKey = 'chatbot_reply_' . md5($message);
        if (Cache::has($cacheKey)) {
            return response()->json(['reply' => Cache::get($cacheKey)]);
        }

        try {
            // Panggil API OpenAI (gunakan key kamu sendiri di .env)
            $apiKey = env('OPENAI_API_KEY');
            if (!$apiKey) {
                return response()->json(['reply' => 'API key OpenAI belum diatur.'], 500);
            }

            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Kamu adalah chatbot perpustakaan. Jawab dengan sopan, informatif, dan hanya terkait dunia buku, penulis, penerbit, genre, sinopsis, atau hal akademik terkait literasi.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $message
                        ]
                    ],
                    'max_tokens' => 1000,
                    'temperature' => 0.7,
                ]);

            if ($response->failed()) {
                return response()->json(['reply' => 'Gagal menghubungi server OpenAI.'], 500);
            }

            $data = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses pertanyaan itu.';

            // Simpan ke cache 30 menit
            Cache::put($cacheKey, $reply, now()->addMinutes(30));

            return response()->json(['reply' => $reply]);
        } catch (\Throwable $e) {
            return response()->json(['reply' => 'Terjadi kesalahan internal: ' . $e->getMessage()], 500);
        }   
    }
}
