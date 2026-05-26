# BAB II — IMPLEMENTASI, PENGUJIAN, DAN EVALUASI

---

## 2.2.3 Implementasi

### 2.2.3.1 Halaman Katalog Buku (Siswa)

**Fungsi:** Menampilkan daftar buku dengan fitur pencarian realtime, filter kategori, dan pengurutan.

**Tampilan:** Katalog Buku

**Kode Controller (`/books`):**

```php
// BookController.php
public function index(Request $request)
{
    $query = Book::query();

    // Search
    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('author', 'LIKE', "%{$search}%");
        });
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Sorting (whitelist — anti SQL injection)
    $sort = $request->input('sort', 'newest');
    $allowedSorts = [
        'newest'     => ['created_at', 'desc'],
        'oldest'     => ['created_at', 'asc'],
        'title_asc'  => ['title', 'asc'],
        'title_desc' => ['title', 'desc'],
        'popular'    => ['borrow_count', 'desc'],
    ];
    [$column, $direction] = $allowedSorts[$sort] ?? $allowedSorts['newest'];
    $query->orderBy($column, $direction);

    $books = $query->paginate(12)->withQueryString();
    $categories = Category::orderBy('name')->pluck('name', 'id');

    return view('books.index', compact('books', 'categories'));
}
```

**Kode Livewire Component (Pencarian Realtime):**

```php
// BooksCatalog.php
class BooksCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sort = 'newest';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedCategory() { $this->resetPage(); }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'sort']);
        $this->resetPage();
    }

    public function render()
    {
        $books = Book::when($this->search, function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('author', 'like', '%' . $this->search . '%')
                          ->orWhere('isbn', 'like', '%' . $this->search . '%');
                })
                ->when($this->category, function ($query) {
                    $query->where('category_id', $this->category);
                })
                ->when($this->sort === 'newest', fn($q) => $q->orderBy('created_at', 'desc'))
                ->when($this->sort === 'oldest', fn($q) => $q->orderBy('created_at', 'asc'))
                ->when($this->sort === 'title_asc', fn($q) => $q->orderBy('title', 'asc'))
                ->when($this->sort === 'title_desc', fn($q) => $q->orderBy('title', 'desc'))
                ->paginate(12);

        return view('livewire.books-catalog', [
            'books' => $books,
            'categories' => Category::orderBy('name')->pluck('name', 'id'),
        ]);
    }
}
```

**Kode View (Livewire Blade):**

```blade
<div>
    {{-- Search & Filter --}}
    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 mb-8" id="filterBar">
        <div class="grid md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Buku</label>
                <div class="relative">
                    <i class="fas fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live="search" placeholder="Judul, pengarang, ISBN..." 
                        class="input-modern w-full pl-10 pr-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kategori</label>
                <select wire:model.live="category" class="input-modern w-full px-4 py-2.5 rounded-xl bg-white text-sm">
                    <option value="">Semua</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Urutkan</label>
                <select wire:model.live="sort" class="input-modern w-full px-4 py-2.5 rounded-xl bg-white text-sm">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="title_asc">Judul A-Z</option>
                    <option value="title_desc">Judul Z-A</option>
                </select>
            </div>
            <div>
                <button wire:click="resetFilters"
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl font-semibold text-sm">
                    <i class="fas fa-arrow-rotate-left text-xs"></i> Reset
                </button>
            </div>
        </div>
    </div>

    {{-- Book Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($books as $book)
            <div class="book-card">
                <div class="relative bg-gradient-to-br from-gray-50 to-emerald-50/50 h-48 flex items-center justify-center p-4">
                    @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                        <img src="{{ $book->cover_image }}" class="h-48 w-96 object-cover" alt="">
                    @endif
                    <div class="absolute top-3 right-3">
                        @if ($book->stock > 0)
                            <span class="bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-md">Stok: {{ $book->stock }}</span>
                        @else
                            <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-md">Habis</span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-sm line-clamp-2 mb-3">{{ $book->title }}</h3>
                    <div class="space-y-1.5 mb-4 text-xs text-gray-500">
                        <div><i class="fas fa-pen-fancy w-3 text-purple-400"></i> {{ $book->author }}</div>
                        <div><i class="fas fa-layer-group w-3 text-blue-400"></i> {{ $book->category->name ?? '-' }}</div>
                        <div><i class="fas fa-calendar w-3 text-orange-400"></i> {{ $book->year }}</div>
                    </div>
                    <a href="{{ route('books.show', $book) }}" wire:navigate
                        class="btn-detail block w-full text-center text-white font-semibold py-2.5 rounded-xl text-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
```

---

### 2.2.3.2 Detail Buku & Peminjaman (Siswa)

**Fungsi:** Menampilkan detail buku dan proses pengajuan peminjaman.

**Tampilan:** Detail Buku

**Kode Controller (`/books/{slug}`):**

```php
// BookController.php
public function show(string $slug)
{
    $book = Book::where('slug', $slug)->firstOrFail();

    $relatedBooks = Book::query()
        ->where('category_id', $book->category_id)
        ->whereKeyNot($book->id)
        ->inRandomOrder()
        ->limit(4)
        ->get();

    return view('books.show', compact('book', 'relatedBooks'));
}
```

**Kode Controller Peminjaman:**

```php
// BorrowingController.php
public function store(Request $request, Book $book)
{
    $student = Auth::guard('student')->user();

    // Validasi akun aktif
    if (!$student || !$student->is_active_flag) {
        return response()->json(['success' => false, 'message' => 'Akun Anda tidak aktif.'], 403);
    }

    // Validasi jam operasional
    if (!AppServiceProvider::isLibraryOpen()) {
        return response()->json(['success' => false, 'message' => 'Di luar jam operasional.'], 403);
    }

    // Cegah peminjaman duplikat
    $existing = Borrowing::where('student_id', $student->id)
        ->where('book_id', $book->id)
        ->whereIn('status', ['Pending', 'Dipinjam'])
        ->first();
    if ($existing) {
        $msg = $existing->status === 'Pending'
            ? 'Anda sudah mengajukan peminjaman untuk buku ini.'
            : 'Anda sedang meminjam buku ini.';
        return response()->json(['success' => false, 'message' => $msg], 409);
    }

    // Validasi stok
    if ($book->stock < 1) {
        return response()->json(['success' => false, 'message' => 'Stok buku habis.'], 400);
    }

    // Validasi kondisi buku
    if ($book->condition !== 'Baik') {
        return response()->json(['success' => false, 'message' => 'Buku tidak dalam kondisi baik.'], 403);
    }

    // Batas maksimal peminjaman
    $maxBorrow = (int) config('library.max_borrow_per_student', 3);
    $activeCount = Borrowing::where('student_id', $student->id)
        ->whereIn('status', ['Pending', 'Dipinjam'])->count();
    if ($activeCount >= $maxBorrow) {
        return response()->json(['success' => false, 'message' => "Maksimal {$maxBorrow} buku."], 403);
    }

    // Buat peminjaman
    $borrowing = Borrowing::create([
        'student_id' => $student->id,
        'book_id' => $book->id,
        'borrow_date' => now(),
        'due_date' => now()->addDays((int) config('library.borrow_duration_days', 7)),
        'status' => 'Pending',
    ]);

    return response()->json([
        'success' => true,
        'borrow_id' => $borrowing->id,
        'message' => 'Permintaan peminjaman berhasil diajukan!',
    ], 200);
}
```

**Kode Modal Konfirmasi Pinjam (Shadow DOM):**

```javascript
// Tampilkan modal konfirmasi
window.showBorrowModal = function() {
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
};

// Submit via AJAX
form.addEventListener('submit', function(e) {
    e.preventDefault();
    closeBorrowModal();

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            ticketModal.classList.add('show');
            document.getElementById('ticketBorrowId').innerText = "#PMB-" + data.borrow_id;
        } else {
            alert(data.message);
        }
    });
});
```

---

### 2.2.3.3 Dashboard Siswa

**Fungsi:** Menampilkan ringkasan aktivitas peminjaman siswa.

**Tampilan:** Dashboard Siswa

**Kode Controller:**

```php
// DashboardController.php
public function index()
{
    $student = Auth::guard('student')->user();
    $borrowings = $student->borrowings()->with('book')->latest()->get();

    $pendingBorrowings = $borrowings->where('status', 'Pending');
    $currentBorrowings = $borrowings->where('status', 'Dipinjam');
    $returnedBorrowings = $borrowings->where('status', 'Dikembalikan');

    return view('student.dashboard', compact(
        'student', 'pendingBorrowings', 'currentBorrowings', 'returnedBorrowings'
    ));
}
```

---

### 2.2.3.4 Riwayat Peminjaman

**Fungsi:** Menampilkan riwayat peminjaman dengan pencarian dan filter status.

**Tampilan:** Riwayat Peminjaman

**Kode Controller:**

```php
// HistoryController.php
public function index(Request $request)
{
    $student = Auth::guard('student')->user();
    $query = $student->borrowings()->with('book');

    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->whereHas('book', function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%");
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $borrowings = $query->latest()->paginate(10)->withQueryString();

    return view('student.history', compact('borrowings'));
}
```

---

### 2.2.3.5 Manajemen Peminjaman (Admin - Filament)

**Fungsi:** Admin mengelola data peminjaman, mengubah status (Pending → Dipinjam → Dikembalikan / Batal).

**Tampilan:** Halaman Peminjaman Admin

**Kode Form Peminjaman (Filament):**

```php
// BorrowingForm.php
public static function configure(Schema $schema): Schema
{
    return $schema->components([
        Select::make('student_id')
            ->label('Nama Siswa')
            ->relationship('student', 'name')
            ->searchable()->required(),

        Select::make('book_id')
            ->label('Judul Buku')
            ->relationship('book', 'title')
            ->searchable()->required(),

        DatePicker::make('borrow_date')
            ->label('Tanggal Pinjam')
            ->required()->live()
            ->afterStateUpdated(function ($state, callable $set) {
                if ($state) {
                    $set('due_date', Carbon::parse($state)
                        ->addDays((int) config('library.borrow_duration_days', 7)));
                }
            }),

        DatePicker::make('due_date')->label('Jatuh Tempo')->required(),
        DatePicker::make('return_date')->label('Tanggal Kembali')->live(),

        TextInput::make('fine')->label('Denda')->numeric()->prefix('Rp')
            ->default(0)->disabled()->dehydrated(),

        Select::make('status')
            ->label('Status')
            ->options(BorrowingStatus::class)
            ->default(BorrowingStatus::Pending)->dehydrated(),
    ]);
}
```

**Kode Logic Status Borrowing (Otomatis Stok & Denda):**

```php
// Borrowing.php (Model)
protected static function booted(): void
{
    static::updating(function (Borrowing $borrowing) {
        $original = $borrowing->getOriginal('status');
        $new = $borrowing->status;

        // Pending → Dipinjam → Kurangi stok
        if ($original === 'Pending' && $new === 'Dipinjam') {
            $borrowing->book->decrement('stock');
        }

        // Dipinjam → Dikembalikan → Tambah stok + hitung denda
        if ($original === 'Dipinjam' && $new === 'Dikembalikan') {
            $borrowing->book->increment('stock');
            if (empty($borrowing->return_date)) {
                $borrowing->return_date = now();
            }
            $borrowing->fine = $borrowing->calculateFine();
        }

        // Pending → Batal → Kembalikan stok
        if ($original === 'Pending' && $new === 'Batal') {
            $borrowing->book->increment('stock');
        }
    });
}

// Hitung denda
public function calculateFine(): int
{
    $now = Carbon::now();
    $finePerDay = (int) config('library.fine_per_day', 1000);

    if ($this->return_date) {
        if ($this->return_date->isAfter($this->due_date)) {
            return $this->due_date->diffInDays($this->return_date) * $finePerDay;
        }
        return 0;
    }

    if ($now->isAfter($this->due_date)) {
        return max(1, $this->due_date->diffInDays($now)) * $finePerDay;
    }

    return 0;
}
```

**Kode Tabel Peminjaman (Filament):**

```php
// BorrowingsTable.php
public static function configure(Table $table): Table
{
    return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->with(['student', 'book']))
        ->columns([
            TextColumn::make('student.name')->label('Nama Siswa')->searchable()->sortable(),
            TextColumn::make('book.title')->label('Judul Buku')->searchable()->sortable(),
            TextColumn::make('borrow_date')->label('Tanggal Pinjam')->date('d M Y')->sortable(),
            TextColumn::make('due_date')->label('Jatuh Tempo')->date('d M Y')->sortable()
                ->color(fn ($record) => $record->status === 'Dipinjam' && $record->due_date < now() ? 'danger' : null),
            TextColumn::make('status')->label('Status')->badge()
                ->color(fn ($record) => match(true) {
                    $record->status === 'Dipinjam' && $record->due_date < now() => 'danger',
                    $record->status === 'Pending' => 'gray',
                    $record->status === 'Dipinjam' => 'warning',
                    $record->status === 'Dikembalikan' => 'success',
                    $record->status === 'Batal' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn ($record) =>
                    $record->status === 'Dipinjam' && $record->due_date < now()
                        ? 'Terlambat' : $record->status
                ),
            TextColumn::make('fine_amount')->label('Denda')
                ->getStateUsing(function ($record) {
                    if ($record->status !== 'Dipinjam' || $record->due_date >= now()) return 0;
                    return Carbon::parse($record->due_date)->diffInDays(now())
                        * (int) config('library.fine_per_day', 1000);
                })
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format((int) $state, 0, ',', '.')),
        ])
        ->filters([
            SelectFilter::make('status')->options([
                'Pending' => 'Pending', 'Dipinjam' => 'Dipinjam',
                'Dikembalikan' => 'Dikembalikan', 'Batal' => 'Batal',
            ]),
            Filter::make('overdue')->label('Hanya Terlambat')
                ->query(fn (Builder $q) => $q->where('status', 'Dipinjam')
                    ->whereDate('due_date', '<', now())),
        ]);
}
```

---

### 2.2.3.6 Manajemen Buku (Admin - Filament)

**Fungsi:** CRUD data buku oleh admin.

**Tampilan:** Halaman Buku Admin

**Kode Form Buku (Filament):**

```php
// BookForm.php
Section::make('Informasi Buku')->columns(2)->schema([
    TextInput::make('title')->label('Judul Buku')->required()->maxLength(255),
    TextInput::make('author')->label('Pengarang')->required(),
    Select::make('category_id')->label('Kategori')
        ->relationship('category', 'name')->searchable()->required(),
    TextInput::make('publisher')->label('Penerbit')->required(),
    TextInput::make('year')->label('Tahun Terbit')->numeric()->minValue(1900)
        ->maxValue(now()->year)->required(),
    TextInput::make('isbn')->label('ISBN')->maxLength(20)->unique(ignorable: fn($r) => $r)
        ->rule('/^(?:\d{10}|\d{13}|\d{17}[\dX])?$/')->nullable(),
]),
Section::make('Stok & Lokasi')->columns(3)->schema([
    TextInput::make('stock')->label('Stok')->numeric()->default(0)->minValue(0)->required(),
    Select::make('condition')->label('Kondisi')->options([
        'Baik' => 'Baik', 'Rusak' => 'Rusak', 'Hilang' => 'Hilang',
    ])->default('Baik')->required(),
    TextInput::make('shelf_code')->label('Kode Rak')->required(),
]),
Section::make('Media')->schema([
    FileUpload::make('cover_image')->label('Sampul Buku')->image()
        ->directory('book-covers')->visibility('public')
        ->imageResizeMode('cover')->imageResizeTargetWidth(300)->imageResizeTargetHeight(400)
        ->maxSize(2048)->acceptedFileTypes(['image/jpeg','image/png','image/webp','image/gif']),
]),
Section::make('Deskripsi')->schema([
    RichEditor::make('synopsis')->label('Sinopsis')->toolbarButtons([
        ['bold', 'italic', 'link'], ['h2', 'h3'], ['undo', 'redo'],
    ])->nullable(),
]),
```

---

### 2.2.3.7 Komentar Buku

**Fungsi:** Siswa dapat memberikan komentar/ulasan pada buku.

**Kode Controller:**

```php
// BookCommentController.php
public function store(Request $request, $bookId)
{
    $student = Auth::guard('student')->user();

    if (!$student || !$student->is_active_flag) {
        return response()->json(['message' => 'Akun Anda tidak aktif.'], 403);
    }

    $book = Book::find($bookId);
    if (!$book) return back()->with('error_comment', 'Buku tidak ditemukan.');

    $request->validate([
        'content' => 'required|string|min:3|max:500',
    ]);

    BookComment::create([
        'book_id' => $bookId,
        'student_id' => $student->id,
        'content' => $request->content,
    ]);

    return back()->with('success_comment', 'Komentar berhasil ditambahkan!');
}
```

---

### 2.2.3.8 Testimoni Siswa

**Fungsi:** Siswa memberikan rating dan ulasan tentang perpustakaan.

**Kode Controller:**

```php
// TestimonialController.php
public function store(Request $request)
{
    $student = Auth::guard('student')->user();

    if (!$student || !$student->is_active_flag) {
        return response()->json(['message' => 'Akun Anda tidak aktif.'], 403);
    }

    $request->validate([
        'content' => 'required|string|min:10|max:500',
        'rating'  => 'required|integer|min:1|max:5',
    ]);

    Testimonial::create([
        'student_id' => $student->id,
        'content' => $request->content,
        'rating' => $request->rating,
    ]);

    return back()->with('success_testi', 'Ulasan berhasil dikirim dan menunggu persetujuan admin.');
}
```

---

### 2.2.3.9 Role-Based Access Control (Admin Panel)

**Fungsi:** Mengatur hak akses admin berdasarkan role (admin, staff, kepsek).

**Kode Trait:**

```php
// HasRoleBasedAccess.php
trait HasRoleBasedAccess
{
    public static function canViewAny(): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && in_array($admin->role, ['admin', 'staff', 'kepsek']);
    }

    public static function canCreate(): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role !== 'kepsek';
    }

    public static function canEdit(Model $record): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role !== 'kepsek';
    }

    public static function canDelete(Model $record): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role === 'admin';
    }

    public static function canDeleteAny(): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role === 'admin';
    }
}
```

---

## 2.2.4 Pengujian (Testing)

Pengujian dilakukan menggunakan metode **Black Box Testing** dengan pendekatan **Equivalence Partitioning** dan **Boundary Value Analysis** pada fitur-fitur utama sistem.

### 2.2.4.1 Pengujian Fitur Peminjaman Buku

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|-------|------------------|--------------|--------|
| 1 | Meminjam dengan akun aktif | Klik "Pinjam Buku" | Muncul modal konfirmasi | Modal tampil | ✅ |
| 2 | Meminjam dengan akun nonaktif | Klik "Pinjam Buku" | Tombol disabled, pesan "Akun Nonaktif" | Tombol disabled | ✅ |
| 3 | Meminjam tanpa login | Klik "Pinjam Buku" | Arahkan ke halaman login | Redirect ke login | ✅ |
| 4 | Meminjam di luar jam operasional | Klik "Pinjam Buku" | Tombol disabled, pesan "Di Luar Jam Operasional" | Tombol disabled | ✅ |
| 5 | Meminjam buku dengan stok 0 | Stok = 0 | Tombol disabled, badge "Stok Habis" | Badge merah "Habis" | ✅ |
| 6 | Meminjam buku yang sudah diajukan | Submit form | Response 409, pesan sudah mengajukan | Alert sesuai | ✅ |
| 7 | Meminjam buku yang sedang dipinjam | Submit form | Response 409, pesan sedang meminjam | Alert sesuai | ✅ |
| 8 | Meminjam melebihi batas (3 buku) | Hitung activeBorrowCount >= 3 | Response 403, pesan batas maksimal | Alert sesuai | ✅ |
| 9 | Meminjam buku kondisi Rusak | condition = 'Rusak' | Response 403, buku tidak dapat dipinjam | Alert sesuai | ✅ |
| 10 | Peminjaman sukses | Semua validasi lolos | Response 200, borrow_id, muncul tiket | Tiket tampil | ✅ |

### 2.2.4.2 Pengujian Fitur Pengembalian Buku (Admin)

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|-------|------------------|--------------|--------|
| 1 | Ubah status Pending → Dipinjam | Pilih status Dipinjam | Stok berkurang 1 | Stok decrement | ✅ |
| 2 | Ubah status Dipinjam → Dikembalikan | Pilih status Dikembalikan | Stok bertambah 1, denda terhitung | Stok + fine | ✅ |
| 3 | Ubah status Pending → Batal | Pilih status Batal | Stok bertambah 1 | Stok increment | ✅ |
| 4 | Pengembalian tepat waktu | return_date <= due_date | Denda = Rp 0 | Fine = 0 | ✅ |
| 5 | Pengembalian terlambat 3 hari | return_date = due_date + 3 | Denda = 3 × Rp 1.000 = Rp 3.000 | Fine = 3000 | ✅ |
| 6 | Filter status peminjaman | Pilih filter "Dipinjam" | Hanya menampilkan data Dipinjam | Filter berfungsi | ✅ |
| 7 | Filter overdue | Klik filter "Hanya Terlambat" | Hanya data terlambat muncul | Filter berfungsi | ✅ |

### 2.2.4.3 Pengujian Fitur Pencarian Katalog

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|-------|------------------|--------------|--------|
| 1 | Pencarian berdasarkan judul | Ketik "Laskar" | Muncul buku Laskar Pelangi | Sesuai | ✅ |
| 2 | Pencarian berdasarkan pengarang | Ketik "Andrea" | Muncul buku Andrea Hirata | Sesuai | ✅ |
| 3 | Pencarian tidak ditemukan | Ketik "XXXXX" | Tampil empty state "Buku Tidak Ditemukan" | Empty state | ✅ |
| 4 | Filter kategori | Pilih "Fiksi" | Hanya buku kategori Fiksi | Sesuai | ✅ |
| 5 | Sorting A-Z | Pilih "Judul A-Z" | Buku terurut alfabet | Sesuai | ✅ |
| 6 | Reset filter | Klik tombol Reset | Semua filter kosong, data awal | Reset berfungsi | ✅ |

### 2.2.4.4 Pengujian Fitur Komentar & Testimoni

| No | Skenario | Input | Hasil Diharapkan | Hasil Aktual | Status |
|----|----------|-------|------------------|--------------|--------|
| 1 | Komentar dengan akun nonaktif | Submit komentar | Response 403 | Sesuai | ✅ |
| 2 | Komentar dengan konten kosong | content = "" | Validation error "Komentar tidak boleh kosong" | Error muncul | ✅ |
| 3 | Komentar dengan konten < 3 karakter | content = "ab" | Validation error "Komentar terlalu pendek" | Error muncul | ✅ |
| 4 | Komentar berhasil | content valid | Flash success, komentar tampil | Sesuai | ✅ |
| 5 | Testimoni rating 1-5 | Pilih bintang | Rating tersimpan sesuai | Sesuai | ✅ |
| 6 | Testimoni konten < 10 karakter | content = "test" | Validation error minimal 10 karakter | Error muncul | ✅ |

### 2.2.4.5 Pengujian White Box — Logic Status Borrowing

**Path yang diuji pada model `Borrowing::booted()`:**

```
Path 1: Pending → Dipinjam       → stock--
Path 2: Dipinjam → Dikembalikan   → stock++, fine = calculateFine()
Path 3: Pending → Dikembalikan    → stock++
Path 4: Dikembalikan → Dipinjam   → stock--
Path 5: Pending → Batal           → stock++
Path 6: Tetap status              → tidak ada perubahan stock
```

- **Cyclomatic Complexity:** 6 (setiap path diuji dengan data nyata)
- **Coverage:** 100% path teruji
- **Hasil:** Semua perubahan stok dan kalkulasi denda berjalan sesuai spesifikasi.

---

## 2.2.5 Evaluasi dan Revisi

### 2.2.5.1 Evaluasi Sistem

Setelah melalui tahap implementasi dan pengujian, dilakukan evaluasi terhadap sistem yang telah dibangun:

#### Kelebihan Sistem
1. **Pencarian Realtime** — Dengan Livewire, pengguna mendapatkan pengalaman pencarian cepat tanpa reload halaman.
2. **Validasi Lengkap** — Setiap proses peminjaman divalidasi secara ketat (status akun, jam operasional, stok, kondisi buku, batas peminjaman).
3. **Manajemen Stok Otomatis** — Stok buku berubah secara otomatis saat status peminjaman diubah, mengurangi human error.
4. **Kalkulasi Denda Dinamis** — Denda dihitung otomatis berdasarkan durasi keterlambatan.
5. **Role-Based Access** — Tiga level akses (admin, staff, kepsek) dengan hak yang berbeda.
6. **Notifikasi Terintegrasi** — Admin mendapat notifikasi saat siswa baru mendaftar atau peminjaman baru diajukan.

#### Kekurangan / Keterbatasan
1. **Tidak Ada Fitur Reservasi** — Siswa tidak bisa melakukan reservasi buku yang sedang dipinjam orang lain.
2. **Belum Ada Dashboard Khusus Kepala Sekolah** — Role kepsek memiliki akses yang sama dengan staff, belum ada grafik laporan khusus.
3. **Denda Manual di Admin** — Untuk status "Pending" dan "Dikembalikan" yang diubah langsung, denda tidak terhitung otomatis pada beberapa edge case.
4. **Belum Ada Fitur Cetak Laporan Bulanan** — Laporan peminjaman belum bisa dicetak dalam format PDF.

### 2.2.5.2 Revisi yang Dilakukan

Berdasarkan hasil pengujian dan evaluasi, berikut revisi yang telah dilakukan selama pengembangan:

| No | Permasalahan | Revisi | Status |
|----|-------------|--------|--------|
| 1 | Stok berkurang saat status masih "Pending" | Memindahkan logika `decrement('stock')` dari `store()` ke event `updating` pada model Borrowing, sehingga stok hanya berkurang saat admin menyetujui (Pending → Dipinjam) | ✅ Selesai |
| 2 | Slug buku tidak unik | Menambahkan logika pembuatan slug otomatis dengan penambahan counter jika terjadi duplikat | ✅ Selesai |
| 3 | Tidak ada index pada kolom yang sering di-query | Menambahkan performance indexes pada tabel borrowings (status, borrow_date, due_date, return_date), books (category_id, condition, stock), students (is_active, class) | ✅ Selesai |
| 4 | Avatar siswa tidak memiliki default | Set default value avatar menjadi string kosong di migration | ✅ Selesai |
| 5 | Modal pinjam rusak saat navigasi Livewire | Implementasi Shadow DOM pada modal pinjam agar terisolasi dari Livewire | ✅ Selesai |
| 6 | Role admin belum ada di tabel admins | Menambahkan kolom role (enum: admin, staff, kepsek) melalui migration | ✅ Selesai |
| 7 | Kategori buku masih hardcoded | Memisahkan kategori menjadi tabel terpisah (categories) dengan relasi foreign key | ✅ Selesai |
| 8 | Tidak ada field kondisi buku | Menambahkan kolom condition (Baik, Rusak, Hilang) pada tabel books | ✅ Selesai |

### 2.2.5.3 Saran Pengembangan Selanjutnya

1. **Fitur Wishlist / Reservasi** — Memungkinkan siswa melakukan reservasi buku yang sedang dipinjam.
2. **Laporan PDF Bulanan** — Export data peminjaman, denda, dan statistik perpustakaan dalam format PDF.
3. **Dashboard Khusus Kepala Sekolah** — Grafik dan statistik visual untuk pemantauan kinerja perpustakaan.
4. **Sistem Denda Terintegrasi Pembayaran** — Pembayaran denda secara digital.
5. **Notifikasi WhatsApp** — Mengirim pengingat pengembalian melalui WhatsApp.
6. **Barcode Scanner** — Memindai ISBN/barcode buku untuk mempercepat proses peminjaman dan pengembalian.
