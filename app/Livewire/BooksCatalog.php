<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class BooksCatalog extends Component
{
    // 1. Aktifkan fitur pagination khusus Livewire (Tailwind)
    use WithPagination;

    // 2. Definisikan variabel yang akan terhubung ke input HTML
    public $search = '';
    public $category = '';
    public $sort = 'newest';

    // 3. Lifecycle method: Jalankan ini setiap ada perubahan di variabel di atas
    public function updatedSearch()
    {
        // Setiap user mengetik, reset halaman pagination ke 1
        $this->resetPage(); 
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    // 4. Fungsi untuk mengosongkan filter
    public function resetFilters()
    {
        $this->reset(['search', 'category', 'sort']);
        $this->resetPage();
    }

    // 5. Query Database Utama
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
                ->when($this->sort === 'newest', function ($query) {
                    $query->orderBy('created_at', 'desc');
                })
                ->when($this->sort === 'oldest', function ($query) {
                    $query->orderBy('created_at', 'asc');
                })
                ->when($this->sort === 'title_asc', function ($query) {
                    $query->orderBy('title', 'asc');
                })
                ->when($this->sort === 'title_desc', function ($query) {
                    $query->orderBy('title', 'desc');
                })
                ->paginate(12); // 12 buku per halaman

        $categories = \App\Models\Category::orderBy('name')->pluck('name', 'id');
        $selectedCategory = $this->category ? \App\Models\Category::find($this->category)?->name : null;

        return view('livewire.books-catalog', [
            'books' => $books,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
}