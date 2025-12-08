<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Menampilkan halaman chatbot.
     */
    public function index()
    {
        return view('chatbot', [
            'student' => auth()->guard('student')->user()
        ]);
    }

    /**
     * Menangani permintaan chat dari pengguna.
     */
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $message = strtolower(trim($request->message));
        $studentName = auth()->guard('student')->user()->name ?? 'Sobat';
        Log::info("Pesan diterima dari {$studentName}: {$message}");

        // 1. Cek apakah pesan adalah sapaan
        $greetingResponse = $this->handleGreetings($message, $studentName);
        if ($greetingResponse) {
            return $greetingResponse;
        }

        // 2. Cek apakah pesan adalah terima kasih
        $thanksResponse = $this->handleThanks($message, $studentName);
        if ($thanksResponse) {
            return $thanksResponse;
        }

        // 3. Cek apakah pesan adalah perkenalan/perkenalkan
        $introResponse = $this->handleIntroduction($message);
        if ($introResponse) {
            return $introResponse;
        }

        // 4. Cek apakah pesan adalah perintah bantuan
        $helpResponse = $this->handleHelp($message);
        if ($helpResponse) {
            return $helpResponse;
        }

        // 5. Muat dataset buku
        $books = $this->loadDataset();
        if ($books === null) {
            return $this->errorResponse("Maaf, database buku sedang tidak dapat diakses.");
        }

        // 6. Analisis intent (niat) pengguna
        $intent = $this->analyzeIntent($message);

        // 7. Eksekusi berdasarkan intent
        try {
            return match ($intent['type']) {
                'search_book'     => $this->searchByKeyword($books, $message, $studentName),
                'search_category' => $this->searchByCategory($books, $message),
                'search_author'   => $this->searchByAuthor($books, $message),
                'search_year'     => $this->searchByYear($books, $message),
                'search_shelf'    => $this->searchByShelf($books, $message),
                'recommendation'  => $this->getRecommendations($books, $message),
                'library_info'    => $this->getLibraryInfo(),
                default           => $this->searchByKeyword($books, $message, $studentName)
            };
        } catch (\Exception $e) {
            Log::error("Kesalahan saat memproses intent: " . $e->getMessage());
            return $this->errorResponse("Maaf, terjadi kesalahan. Silakan coba lagi.");
        }
    }

    // =========================================================================
    // HANDLER UNTUK INTERAKSI SOSIAL
    // =========================================================================

    /**
     * Menangani sapaan dari pengguna.
     */
    private function handleGreetings(string $message, string $studentName)
    {
        $greetings = [
            'pagi' => ['pagi', 'selamat pagi', 'good morning', 'morning'],
            'siang' => ['siang', 'selamat siang', 'good afternoon', 'afternoon'],
            'sore' => ['sore', 'selamat sore', 'good evening'],
            'malam' => ['malam', 'selamat malam', 'good night', 'night'],
            'halo' => ['halo', 'hai', 'hi', 'hello', 'hey', 'hoi', 'hei', 'hallo'],
            'apa kabar' => ['apa kabar', 'how are you', 'gimana kabarnya', 'kabar'],
            'selamat datang' => ['selamat datang', 'welcome'],
            'halo_nama' => ['halo perpus', 'hai perpus', 'hello perpus', 'halo bot', 'hai bot', 'halo perpusbot'],
            'assalamualaikum' => ['assalamualaikum', 'assalamu\'alaikum', 'salam'],
            'permisi' => ['permisi', 'excuse me', 'maaf ganggu']
        ];

        // Tentukan salam berdasarkan waktu
        $time = date('H');
        $timeGreeting = '';
        
        if ($time >= 5 && $time < 11) {
            $timeGreeting = 'pagi';
        } elseif ($time >= 11 && $time < 15) {
            $timeGreeting = 'siang';
        } elseif ($time >= 15 && $time < 19) {
            $timeGreeting = 'sore';
        } else {
            $timeGreeting = 'malam';
        }

        // Cek setiap jenis sapaan
        foreach ($greetings as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (Str::contains($message, $keyword)) {
                    $responses = $this->getGreetingResponse($type, $studentName, $timeGreeting);
                    
                    return response()->json([
                        'success' => true,
                        'reply' => $responses['reply'],
                        'ai_explanation' => $responses['suggestion'] ?? null,
                        'books' => []
                    ]);
                }
            }
        }

        return null;
    }

    /**
     * Mendapatkan respons sapaan yang tepat.
     */
    private function getGreetingResponse(string $type, string $studentName, string $timeGreeting): array
    {
        $responses = [
            'pagi' => [
                'reply' => "Selamat pagi, {$studentName}! 🌅<br>Semoga harimu menyenangkan. Ada yang bisa saya bantu cari di perpustakaan hari ini?",
                'suggestion' => "Pagi yang cerah untuk membaca buku baru! Mau cari buku apa hari ini?"
            ],
            'siang' => [
                'reply' => "Selamat siang, {$studentName}! ☀️<br>Istirahat siang yang pas untuk membaca buku. Butuh bantuan mencari bacaan?",
                'suggestion' => "Siang yang tepat untuk membaca buku ringan atau artikel pendek!"
            ],
            'sore' => [
                'reply' => "Selamat sore, {$studentName}! 🌇<br>Waktu yang tepat untuk membaca buku sambil menikmati senja. Ada yang bisa saya bantu?",
                'suggestion' => "Sore hari cocok untuk membaca novel atau buku non-fiksi yang ringan."
            ],
            'malam' => [
                'reply' => "Selamat malam, {$studentName}! 🌙<br>Waktu yang tenang untuk membaca sebelum tidur. Cari buku untuk dibaca malam ini?",
                'suggestion' => "Baca buku sebelum tidur bisa meningkatkan kualitas tidur lho!"
            ],
            'halo' => [
                'reply' => "Halo {$studentName}! 👋<br>Senang bertemu denganmu. Saya PerpusBot, asisten perpustakaan digital. Ada yang bisa saya bantu?",
                'suggestion' => "Coba tanyakan tentang buku, kategori, atau informasi perpustakaan!"
            ],
            'apa kabar' => [
                'reply' => "Saya baik-baik saja, terima kasih sudah bertanya! 😊<br>Bagaimana dengan kamu, {$studentName}? Siap membantu pencarian buku hari ini?",
                'suggestion' => "Saya selalu semangat membantu menemukan buku yang kamu cari!"
            ],
            'selamat datang' => [
                'reply' => "Terima kasih! Selamat datang di Perpustakaan Digital. 🤗<br>Saya PerpusBot, siap membantumu menjelajahi dunia buku.",
                'suggestion' => "Mari kita mulai petualangan membaca! Mau cari buku apa pertama kali?"
            ],
            'halo_nama' => [
                'reply' => "Halo {$studentName}! Iya, saya PerpusBot siap membantu! 🤖<br>Ada yang bisa saya bantu hari ini?",
                'suggestion' => "Saya bisa membantu mencari buku, memberikan rekomendasi, atau menjawab pertanyaan tentang perpustakaan."
            ],
            'assalamualaikum' => [
                'reply' => "Wa'alaikumussalam, {$studentName}! ✨<br>Semoga hari ini penuh berkah. Ada yang bisa saya bantu cari di perpustakaan?",
                'suggestion' => "Mari cari buku yang bermanfaat untuk menambah ilmu!"
            ],
            'permisi' => [
                'reply' => "Iya {$studentName}, ada yang bisa saya bantu? 😊<br>Saya di sini untuk membantu kamu mencari buku.",
                'suggestion' => "Jangan ragu bertanya apa saja tentang buku atau perpustakaan."
            ]
        ];

        // Jika ada respons spesifik, gunakan itu
        if (isset($responses[$type])) {
            return $responses[$type];
        }

        // Default response berdasarkan waktu
        $timeResponses = [
            'pagi' => "Selamat pagi, {$studentName}! 🌅",
            'siang' => "Selamat siang, {$studentName}! ☀️",
            'sore' => "Selamat sore, {$studentName}! 🌇",
            'malam' => "Selamat malam, {$studentName}! 🌙"
        ];

        $greeting = $timeResponses[$timeGreeting] ?? "Halo {$studentName}! 👋";

        return [
            'reply' => "{$greeting}<br>Senang berbicara denganmu. Ada yang bisa saya bantu cari di perpustakaan?",
            'suggestion' => "Coba tanyakan 'buku teknologi' atau 'rekomendasi novel' untuk mulai!"
        ];
    }

    /**
     * Menangani ucapan terima kasih.
     */
    private function handleThanks(string $message, string $studentName)
    {
        $thanksKeywords = [
            'terima kasih', 'terimakasih', 'thanks', 'thank you', 'makasih', 
            'terima kasih banyak', 'thank you so much', 'makasih ya', 'makasih banyak',
            'sama-sama', 'you\'re welcome', 'thanks banget', 'thank you very much'
        ];

        foreach ($thanksKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                $responses = [
                    "Sama-sama, {$studentName}! 😊<br>Senang bisa membantu. Kalau ada yang lain butuh bantuan, saya selalu siap!",
                    "Dengan senang hati! 🤗<br>Semoga kamu menemukan buku yang dicari. Ada lagi yang bisa saya bantu?",
                    "Terima kasih kembali, {$studentName}! 🌟<br>Jangan ragu bertanya lagi kalau butuh bantuan mencari buku.",
                    "Iya, sama-sama! 📚<br>Membantu kamu adalah kesenangan bagi saya. Mau cari buku lain?",
                    "Oh, terima kasih juga sudah bertanya! 😄<br>Saya selalu senang membantu pencarian buku."
                ];

                return response()->json([
                    'success' => true,
                    'reply' => $responses[array_rand($responses)],
                    'books' => []
                ]);
            }
        }

        return null;
    }

    /**
     * Menangani pertanyaan perkenalan/perkenalkan.
     */
    private function handleIntroduction(string $message)
    {
        $introKeywords = [
            'siapa kamu', 'siapa anda', 'perkenalkan diri', 'perkenalkan dirimu',
            'kamu siapa', 'what is your name', 'who are you', 'introduce yourself',
            'nama kamu', 'what\'s your name', 'kamu namanya siapa', 'siapa namamu'
        ];

        foreach ($introKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                $introText = "Hai! Saya <strong>PerpusBot</strong> 🤖<br><br>
                📚 <strong>Tentang Saya:</strong><br>
                • Asisten perpustakaan digital yang ramah<br>
                • Dibuat untuk membantu pencarian buku<br>
                • Selalu siap menjawab pertanyaan tentang perpustakaan<br><br>
                
                🔍 <strong>Apa yang bisa saya lakukan:</strong><br>
                • Mencari buku berdasarkan judul, penulis, kategori<br>
                • Memberikan rekomendasi bacaan yang menarik<br>
                • Menjelaskan tentang buku yang kamu tanyakan<br>
                • Memberi informasi jam operasional & peraturan<br>
                • Membantu menemukan buku di rak tertentu<br><br>
                
                💡 <strong>Tips:</strong> Coba tanyakan 'rekomendasi novel' atau 'buku teknologi' untuk mulai!";

                return response()->json([
                    'success' => true,
                    'reply' => $introText,
                    'books' => []
                ]);
            }
        }

        return null;
    }

    /**
     * Menangani permintaan bantuan.
     */
    private function handleHelp(string $message)
    {
        $helpKeywords = [
            'bantuan', 'help', 'tolong', 'cara pakai', 'cara menggunakan',
            'fitur', 'apa yang bisa', 'bisa apa', 'bisa apa aja',
            'bantuan apa', 'mau tanya', 'tanya dong'
        ];

        foreach ($helpKeywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                $helpText = "🆘 <strong>Panduan Penggunaan PerpusBot</strong><br><br>
                
                📖 <strong>Cara mencari buku:</strong><br>
                1. Sebutkan judul buku: 'cari buku Laskar Pelangi'<br>
                2. Cari berdasarkan penulis: 'buku karya Andrea Hirata'<br>
                3. Cari berdasarkan kategori: 'buku kategori teknologi'<br>
                4. Cari berdasarkan tahun: 'buku tahun 2020'<br><br>
                
                🔎 <strong>Contoh percakapan:</strong><br>
                • 'Cari novel Harry Potter'<br>
                • 'Rekomendasi buku sains'<br>
                • 'Jam buka perpustakaan'<br>
                • 'Buku di rak A1'<br><br>
                
                💬 <strong>Fitur lainnya:</strong><br>
                • Bertanya tentang buku tertentu<br>
                • Meminta rekomendasi bacaan<br>
                • Mengecek ketersediaan buku<br>
                • Informasi perpustakaan";

                return response()->json([
                    'success' => true,
                    'reply' => $helpText,
                    'books' => []
                ]);
            }
        }

        return null;
    }

    // =========================================================================
    // FUNGSI UTAMA BOT
    // =========================================================================

    /**
     * Memuat dataset buku dari file JSON.
     */
    private function loadDataset()
    {
        $path = storage_path('app/books.json');
        if (!file_exists($path)) {
            Log::error("File books.json tidak ditemukan di: {$path}");
            return null;
        }

        $data = json_decode(file_get_contents($path), true);
        return is_array($data) ? $data : null;
    }

    /**
     * Menganalisis pesan pengguna untuk menentukan niatnya.
     */
    private function analyzeIntent(string $message): array
    {
        // Skip analisis jika hanya sapaan pendek
        $wordCount = str_word_count($message);
        if ($wordCount <= 2 && !preg_match('/\b(20\d{2})\b/', $message)) {
            return ['type' => 'search_book'];
        }

        // Pencarian berdasarkan kata kunci
        $keywords = [
            'search_category' => ['kategori', 'genre', 'jenis', 'kategoti'],
            'search_author'   => ['penulis', 'pengarang', 'author', 'karya', 'karangan'],
            'recommendation'  => ['rekomendasi', 'saran', 'bacaan', 'rekomendasikan', 'sarankan'],
            'library_info'    => ['jam buka', 'peraturan', 'aturan', 'pinjam', 'kembali', 'operasional', 'jam operasional']
        ];

        foreach ($keywords as $intent => $words) {
            foreach ($words as $word) {
                if (Str::contains($message, $word)) {
                    return ['type' => $intent];
                }
            }
        }

        // Cek pola spesifik
        if (preg_match('/\b(20\d{2})\b/', $message, $matches)) {
            return ['type' => 'search_year', 'value' => $matches[1]];
        }
        
        if (preg_match('/\b([A-Z]\d{1,2})\b/', $message, $matches)) {
            return ['type' => 'search_shelf', 'value' => $matches[1]];
        }

        // Default ke pencarian buku
        return ['type' => 'search_book'];
    }

    // =========================================================================
    // FUNGSI PENCARIAN
    // =========================================================================

    /**
     * Pencarian umum berdasarkan kata kunci.
     */
    private function searchByKeyword(array $books, string $message, string $studentName = '')
    {
        $terms = $this->extractSearchTerms($message);
        
        // Jika tidak ada kata kunci yang berarti
        if (empty($terms)) {
            $responses = [
                "{$studentName}, ada yang bisa saya bantu cari? 🤔<br>Coba sebutkan judul, penulis, atau kategori buku.",
                "Mau cari buku apa nih? 📚<br>Ketik judul buku atau kata kunci yang kamu inginkan.",
                "Cari buku apa ya? 😊<br>Contoh: 'Harry Potter', 'buku teknologi', atau 'novel romantis'"
            ];
            
            return response()->json([
                'success' => true,
                'reply' => $responses[array_rand($responses)],
                'books' => []
            ]);
        }

        $results = [];
        $searchQuery = implode(' ', $terms);

        foreach ($books as $book) {
            $score = 0;
            foreach ($terms as $term) {
                // Skor tinggi untuk judul
                if (Str::contains(strtolower($book['title'] ?? ''), $term)) {
                    $score += 5;
                }
                // Skor sedang untuk penulis
                if (Str::contains(strtolower($book['author'] ?? ''), $term)) {
                    $score += 3;
                }
                // Skor rendah untuk kategori dan detail
                if (Str::contains(strtolower($book['category'] ?? ''), $term)) {
                    $score += 2;
                }
                if (Str::contains(strtolower($book['detail'] ?? ''), $term)) {
                    $score += 1;
                }
            }

            if ($score > 0) {
                $book['score'] = $score;
                $results[] = $book;
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        // Kasus 1: Buku DITEMUKAN
        if (!empty($results)) {
            $foundBook = $results[0];
            $aiExplanation = $this->getAIExplanation($foundBook['title']);
            
            $replyText = count($results) > 1 
                ? "Saya menemukan <strong>" . count($results) . " buku</strong> yang cocok dengan pencarianmu. Yang paling relevan:"
                : "Saya menemukan buku yang kamu cari! 📖";

            return response()->json([
                'success' => true,
                'reply' => $replyText,
                'ai_explanation' => $aiExplanation,
                'books' => $this->formatBooks(array_slice($results, 0, 5)),
                'total_results' => count($results)
            ]);
        }

        // Kasus 2: Buku TIDAK DITEMUKAN
        $aiExplanation = $this->getAIExplanation($searchQuery);
        
        $notFoundResponses = [
            "Maaf, saya tidak menemukan buku '<strong>{$searchQuery}</strong>' di katalog kami. 😕",
            "Sepertinya buku '<strong>{$searchQuery}</strong>' tidak ada di koleksi kami saat ini.",
            "Wah, buku '<strong>{$searchQuery}</strong>' tidak saya temukan. Mungkin coba kata kunci lain?"
        ];
        
        return response()->json([
            'success' => true,
            'reply' => $notFoundResponses[array_rand($notFoundResponses)] . "<br><br>Namun berikut informasi yang mungkin berhubungan:",
            'ai_explanation' => $aiExplanation,
            'books' => []
        ]);
    }

    /**
     * Pencarian berdasarkan kategori.
     */
    private function searchByCategory(array $books, string $message)
    {
        $category = $this->extractCategory($message);
        if (!$category) {
            return $this->searchByKeyword($books, $message);
        }

        $results = array_filter($books, fn($b) => Str::contains(strtolower($b['category'] ?? ''), $category));

        if (empty($results)) {
            $categories = implode(', ', $this->getAvailableCategories($books));
            
            return response()->json([
                'success' => true,
                'reply' => "Kategori '<strong>" . ucfirst($category) . "</strong>' tidak ditemukan. 😕<br><br>Kategori yang tersedia: <strong>{$categories}</strong>",
                'books' => []
            ]);
        }

        $categoryName = ucfirst($category);
        $count = count($results);
        
        return response()->json([
            'success' => true,
            'reply' => "Ditemukan <strong>{$count} buku</strong> dalam kategori <strong>{$categoryName}</strong>:",
            'books' => $this->formatBooks(array_slice($results, 0, 5)),
            'total_results' => $count
        ]);
    }

    /**
     * Pencarian berdasarkan penulis.
     */
    private function searchByAuthor(array $books, string $message)
    {
        $author = $this->extractAuthor($message);
        if (!$author) {
            return $this->searchByKeyword($books, $message);
        }

        $results = array_filter($books, fn($b) => Str::contains(strtolower($b['author'] ?? ''), $author));

        if (empty($results)) {
            return response()->json([
                'success' => true,
                'reply' => "Tidak ada buku dari penulis '<strong>" . ucfirst($author) . "</strong>' di katalog kami. 📚",
                'books' => []
            ]);
        }

        $authorName = ucfirst($author);
        $count = count($results);
        
        return response()->json([
            'success' => true,
            'reply' => "Ditemukan <strong>{$count} buku</strong> dari penulis <strong>{$authorName}</strong>:",
            'books' => $this->formatBooks(array_slice($results, 0, 5)),
            'total_results' => $count
        ]);
    }

    /**
     * Pencarian berdasarkan tahun.
     */
    private function searchByYear(array $books, string $message)
    {
        $year = $this->extractYear($message);
        if (!$year) {
            return $this->searchByKeyword($books, $message);
        }

        $results = array_filter($books, fn($b) => $b['year'] == $year);

        if (empty($results)) {
            return response()->json([
                'success' => true,
                'reply' => "Tidak ada buku yang terbit pada tahun <strong>{$year}</strong> di koleksi kami. 📅",
                'books' => []
            ]);
        }

        $count = count($results);
        
        return response()->json([
            'success' => true,
            'reply' => "Ditemukan <strong>{$count} buku</strong> yang terbit pada tahun <strong>{$year}</strong>:",
            'books' => $this->formatBooks(array_slice($results, 0, 5)),
            'total_results' => $count
        ]);
    }

    /**
     * Pencarian berdasarkan rak.
     */
    private function searchByShelf(array $books, string $message)
    {
        $shelf = $this->extractShelf($message);
        if (!$shelf) {
            return $this->searchByKeyword($books, $message);
        }

        $results = array_filter($books, fn($b) => Str::contains(strtolower($b['shelf_code'] ?? ''), $shelf));

        if (empty($results)) {
            return response()->json([
                'success' => true,
                'reply' => "Tidak ada buku di rak <strong>" . strtoupper($shelf) . "</strong>. 🗄️",
                'books' => []
            ]);
        }

        $shelfCode = strtoupper($shelf);
        $count = count($results);
        
        return response()->json([
            'success' => true,
            'reply' => "Ditemukan <strong>{$count} buku</strong> di rak <strong>{$shelfCode}</strong>:",
            'books' => $this->formatBooks(array_slice($results, 0, 5)),
            'total_results' => $count
        ]);
    }

    /**
     * Rekomendasi buku.
     */
    private function getRecommendations(array $books, string $message)
    {
        $category = $this->extractCategory($message);
        
        if ($category) {
            $pool = array_filter($books, fn($b) => Str::contains(strtolower($b['category'] ?? ''), $category));
            $replyText = "Berikut rekomendasi buku dari kategori <strong>" . ucfirst($category) . "</strong>:";
        } else {
            // Filter buku yang tersedia dan rating tinggi (jika ada)
            $pool = array_filter($books, fn($b) => ($b['stock'] ?? 0) > 0);
            $replyText = "Berikut rekomendasi buku terbaik untuk kamu:";
        }

        if (empty($pool)) {
            return response()->json([
                'success' => true,
                'reply' => "Maaf, tidak ada rekomendasi yang bisa saya berikan saat ini. 😔",
                'books' => []
            ]);
        }

        shuffle($pool);
        $recommendations = array_slice($pool, 0, 3);

        return response()->json([
            'success' => true,
            'reply' => $replyText,
            'books' => $this->formatBooks($recommendations),
            'total_results' => count($recommendations)
        ]);
    }

    /**
     * Informasi perpustakaan.
     */
    private function getLibraryInfo()
    {
        $info = "📚 <strong>Informasi Perpustakaan</strong><br><br>
                ⏰ <strong>Jam Operasional:</strong><br>
                • Senin - Jumat: 08.00 - 16.00<br>
                • Sabtu: 08.00 - 12.00<br>
                • Minggu & Hari Libur: Tutup<br><br>
                
                📖 <strong>Aturan Peminjaman:</strong><br>
                • Masa pinjam: 7 hari<br>
                • Perpanjangan: 1x (jika tidak ada antrian)<br>
                • Maksimal pinjam: 3 buku bersamaan<br>
                • Denda keterlambatan: Rp 1.000/hari<br><br>
                
                ℹ️ <strong>Informasi Lain:</strong><br>
                • Free WiFi tersedia<br>
                • Area membaca yang nyaman<br>
                • Ruang diskusi kelompok<br>
                • Fotokopi & print services";

        return response()->json([
            'success' => true,
            'reply' => $info,
            'books' => []
        ]);
    }

    // =========================================================================
    // INTEGRASI AI
    // =========================================================================

    /**
     * Meminta penjelasan tentang buku dari AI.
     */
    private function getAIExplanation(string $query): string
    {
        if (empty($query) || strlen($query) < 3) {
            return "Saya tidak memiliki informasi detail tentang ini.";
        }

        try {
            $systemPrompt = "Kamu adalah asisten perpustakaan yang ramah. Berikan penjelasan singkat, informatif, dan menarik tentang buku atau topik yang diminta. Gunakan bahasa Indonesia yang mudah dipahami. Maksimal 100 kata. Jika tidak tahu, akui dengan jujur.";

            $payload = [
                'model' => config('services.openrouter.model', 'openai/gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => "Jelaskan tentang: " . $query]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7
            ];

            $res = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
                'HTTP-Referer'  => config('app.url'),
                'X-Title'       => 'PerpusBot'
            ])->timeout(8)->post(config('services.openrouter.base_url'), $payload);

            if ($res->successful()) {
                $content = $res->json('choices.0.message.content', "Maaf, tidak ada informasi.");
                return strip_tags($content); // Hilangkan tag HTML untuk keamanan
            }

            Log::warning("AI API gagal, status: " . $res->status());
            return "Informasi tentang ini tidak tersedia saat ini.";

        } catch (\Exception $e) {
            Log::error("Kesalahan AI: " . $e->getMessage());
            return "Maaf, terjadi kesalahan saat mencari informasi.";
        }
    }

    // =========================================================================
    // HELPER FUNCTIONS
    // =========================================================================

    /**
     * Format data buku untuk response.
     */
    private function formatBooks(array $books): array
    {
        return array_map(function ($book) {
            return [
                'title'      => $book['title'] ?? 'Judul tidak tersedia',
                'author'     => $book['author'] ?? 'Penulis tidak diketahui',
                'cover'      => $book['cover_image'] ?? 'https://via.placeholder.com/70x95/f0f0f0/cccccc?text=No+Cover',
                'year'       => $book['year'] ?? 'Tidak diketahui',
                'stock'      => $book['stock'] ?? 0,
                'category'   => $book['category'] ?? 'Umum',
                'shelf_code' => $book['shelf_code'] ?? '-',
                'isbn'       => $book['isbn'] ?? 'Tidak tersedia',
                'pages'      => $book['pages'] ?? null,
                'language'   => $book['language'] ?? 'Indonesia',
                'detail'     => $book['detail'] ?? 'Tidak ada deskripsi'
            ];
        }, $books);
    }

    /**
     * Mendapatkan kategori yang tersedia.
     */
    private function getAvailableCategories(array $books): array
    {
        $categories = [];
        foreach ($books as $book) {
            if ($category = $book['category'] ?? null) {
                $categories[] = strtolower($category);
            }
        }
        return array_unique($categories);
    }

    /**
     * Respons error.
     */
    private function errorResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'reply' => "❌ " . $message,
            'books' => []
        ]);
    }

    /**
     * Ekstrak istilah pencarian.
     */
    private function extractSearchTerms(string $message): array
    {
        // Hapus kata sapaan umum dan stop words
        $greetingWords = ['halo', 'hai', 'hi', 'hello', 'permisi', 'tolong', 'mau', 'ingin', 'bisa', 'cari', 'carikan'];
        $stopWords = array_merge($greetingWords, ['judul', 'buku', 'yang', 'di', 'ada', 'untuk', 'dari', 'tentang', 'dengan', 'oleh', 'pada']);
        
        // Bersihkan tanda baca
        $cleanMessage = preg_replace('/[^\w\s]/', ' ', $message);
        $words = explode(' ', strtolower($cleanMessage));
        
        // Filter kata
        $terms = array_filter($words, function ($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 2 && !is_numeric($word);
        });
        
        return array_values(array_unique($terms));
    }

    /**
     * Ekstrak tahun.
     */
    private function extractYear(string $message): ?string
    {
        return preg_match('/\b(20\d{2})\b/', $message, $matches) ? $matches[1] : null;
    }

    /**
     * Ekstrak kode rak.
     */
    private function extractShelf(string $message): ?string
    {
        return preg_match('/\b([A-Z]\d{1,2})\b/i', $message, $matches) ? strtoupper($matches[1]) : null;
    }

    /**
     * Ekstrak kategori.
     */
    private function extractCategory(string $message): ?string
    {
        $categories = ['fiksi', 'non fiksi', 'sains', 'teknologi', 'sejarah', 'biografi', 'komik', 'novel', 'referensi', 'pendidikan', 'anak', 'remaja', 'dewasa', 'romantis', 'horor', 'misteri', 'fantasi', 'ilmiah'];
        
        foreach ($categories as $cat) {
            if (Str::contains($message, $cat)) {
                return str_replace(' ', '-', $cat);
            }
        }
        return null;
    }

    /**
     * Ekstrak penulis.
     */
    private function extractAuthor(string $message): ?string
    {
        if (preg_match('/(?:penulis|pengarang|karya|karangan)\s+(.+?)(?:\s+atau\s+|\s+dan\s+|$)/i', $message, $matches)) {
            return trim($matches[1]);
        }
        
        // Coba ambil nama setelah 'oleh'
        if (preg_match('/oleh\s+(.+?)(?:\s+atau\s+|\s+dan\s+|$)/i', $message, $matches)) {
            return trim($matches[1]);
        }
        
        return null;
    }
}