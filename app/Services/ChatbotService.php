<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ChatbotService
{
    protected string $apiKey;
    protected string $model;

    public function __construct() {
        $this->apiKey = config('services.openrouter.api_key');
        $this->model = config('services.openrouter.model', 'openai/gpt-4o-mini');
    }

    public function sendMessage(string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'X-Title' => 'PerpusBot Engine',
            ])->post("https://openrouter.ai/api/v1/chat/completions", [
                "model" => $this->model,
                "messages" => [
                    [
                        "role" => "system", 
                        "content" => "Kamu adalah Chatbot Perpustakaan Digital dengan kemampuan Text Mining.
                        
                        PROTOKOL AKURASI:
                        1. Jika terdapat teks '[DATA ASLI DARI KATALOG]', kamu WAJIB menggunakan informasi tersebut sebagai prioritas utama.
                        2. Jika data di KATALOG tidak lengkap (seperti sinopsis kosong), tulis 'Informasi tidak tersedia' untuk bagian tersebut. JANGAN MENGARANG.
                        3. Jika buku TIDAK ADA di KATALOG, gunakan pengetahuan publikmu untuk menjelaskan buku tersebut secara detail dan akurat.
                        4. JANGAN PERNAH mengarang nama penulis.
                        5. Gunakan format: 📘 Judul, ✍️ Penulis, 📝 Sinopsis, 🏷️ Kategori, 📚 Rekomendasi serupa.
                        6. Nada bicara ramah, profesional, dan informatif."
                    ],
                    ["role" => "user", "content" => $message]
                ],
                "temperature" => 0.1, // Konsistensi maksimal, nol halusinasi.
            ]);

            $result = $response->json('choices.0.message.content');
            return ['success' => true, 'reply' => $result];
        } catch (\Throwable $e) {
            return ['success' => false, 'reply' => 'Gagal memproses informasi.'];
        }
    }
}