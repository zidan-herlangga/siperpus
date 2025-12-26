<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected $botService;

    public function __construct(ChatbotService $botService) { $this->botService = $botService; }

    public function index()
    {
        return view('chatbot');
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);
        $userInput = $request->message;
        $query = strtolower($userInput);
        
        // 1. TEXT MINING: Cari kecocokan eksak atau partial
        $books = Book::where('title', 'like', "%$query%")
                     ->orWhere('author', 'like', "%$query%")
                     ->limit(5)->get();

        $suggestion = null;
        // 2. TYPO HANDLING: Jika tidak ketemu, cari judul terdekat (Fuzzy Search)
        if ($books->isEmpty()) {
            $allTitles = Book::pluck('title')->toArray();
            $closest = null;
            $shortest = -1;

            foreach ($allTitles as $title) {
                $lev = levenshtein($query, strtolower($title));
                if ($lev <= 3 && ($lev < $shortest || $shortest == -1)) {
                    $closest = $title;
                    $shortest = $lev;
                }
            }

            if ($closest) {
                $suggestion = "Mungkin yang Anda maksud adalah: **$closest**?";
                // Ambil data buku yang disarankan tersebut
                $books = Book::where('title', $closest)->get();
            }
        }

        // 3. CONTEXT INJECTION: Suntikkan data lokal ke otak AI
        $context = "";
        if($books->count() > 0) {
            $context = "\n\n[DATA ASLI DARI KATALOG PERPUSTAKAAN]:\n";
            foreach($books as $b) {
                $context .= "- Judul: {$b->title}, Penulis: " . ($b->author ?? 'Informasi tidak tersedia') . 
                            ", Sinopsis: " . ($b->description ?? 'Informasi tidak tersedia') . "\n";
            }
        }

        $botResponse = $this->botService->sendMessage($userInput . $context);

        // Tambahkan saran typo ke balasan AI jika ada
        $finalReply = $suggestion ? $suggestion . "\n\n" . $botResponse['reply'] : $botResponse['reply'];

        return response()->json([
            'success' => $botResponse['success'],
            'reply'   => $finalReply,
            'books'   => $books
        ]);
    }
}