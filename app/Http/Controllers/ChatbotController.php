<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = strtolower($request->message);

        // ============================================
        // 1. LOAD DATASET DARI FILE SYSTEM
        // ============================================
        $path = storage_path('app/books.json');

        if (!file_exists($path)) {
            return $this->fallbackAI($message, "Dataset tidak ditemukan.");
        }

        $json = file_get_contents($path);
        $books = json_decode($json, true);

        if (!$books) {
            return $this->fallbackAI($message, "Dataset rusak / JSON invalid.");
        }

        // ============================================
        // 2. CARI BUKU DALAM DATASET
        // ============================================
        foreach ($books as $book) {
            if (str_contains(strtolower($book['title']), $message)) {

                return response()->json([
                    'reply' => "Berikut informasi lengkap tentang buku <span class='font-bold'>{$book['title']}</span>:",
                    'books' => [
                        [
                            'title'      => $book['title'],
                            'author'     => $book['author'],
                            'cover'      => $book['cover_image'],
                            'year'       => $book['year'],
                            'detail'     => $book['detail'],
                            'stock'      => $book['stock'],
                            'category'   => $book['category'],
                            'shelf_code' => $book['shelf_code']
                        ]
                    ]
                ]);
            }
        }

        // ============================================
        // 3. JIKA TIDAK ADA → AI JELASKAN
        // ============================================
        return $this->fallbackAI($message);
    }

    // ============================================
    // 4. FALLBACK KE OPENROUTER
    // ============================================
    private function fallbackAI($message, $note = null)
    {
        $payload = [
            'model' => config('services.openrouter.model'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' =>
                        "Kamu adalah chatbot perpustakaan. 
                         Jika buku tidak ada dalam dataset, 
                         jawab dengan penjelasan umum berdasarkan pengetahuan publik. 
                         Jika ada, jawab ringkas dan ramah."
                ],
                [
                    'role' => 'user',
                    'content' => $message
                ]
            ]
        ];

        $res = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
            'HTTP-Referer'  => url('/'),
            'X-Title'       => 'Library Chatbot'
        ])->post(config('services.openrouter.base_url'), $payload);

        if ($res->failed()) {
            return response()->json([
                'reply' => "Maaf, AI sedang tidak bisa menjawab."
            ]);
        }

        return response()->json([
            'reply' =>
                ($note ? "($note)\n\n" : "") .
                ($res->json()['choices'][0]['message']['content'] ?? "Tidak ada jawaban."),
            'books' => []
        ]);
    }
}
