@extends('layouts.app')

@section('title', 'Katalog Buku - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/books.css') }}">
<style>
    /* Pindahkan semua CSS custom anda (bg-mesh, card-glass, input-modern, dll) KESINI */
    .bg-mesh {
        background-color: #f9fafb;
        background-image: 
            radial-gradient(at 20% 20%, rgba(52, 211, 153, 0.08) 0px, transparent 50%),
            radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.06) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(167, 243, 208, 0.08) 0px, transparent 50%);
    }
    .card-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.05);
    }
    .input-modern { border: 1.5px solid #e5e7eb; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .input-modern:hover { border-color: #d1d5db; }
    .input-modern:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
    .book-card { background: white; border: 1px solid #f3f4f6; border-radius: 16px; overflow: hidden; transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1); }
    .book-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1); border-color: #e5e7eb; }
    .book-card:hover .book-cover-icon { transform: scale(1.1) rotate(-3deg); }
    .book-cover-icon { transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1); }
    .btn-detail { background: linear-gradient(135deg, #059669, #047857); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .btn-detail:hover { background: linear-gradient(135deg, #047857, #065f46); box-shadow: 0 8px 20px -4px rgba(5, 150, 105, 0.4); }
</style>
@stop

@section('content')
<div class="min-h-screen bg-mesh py-8">
    <div class="container mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 tracking-tight mb-2">
                Katalog Buku
            </h1>
            <p class="text-gray-500 max-w-2xl mx-auto text-sm">
                Jelajahi koleksi perpustakaan digital kami — temukan dan pinjam buku favoritmu dengan mudah.
            </p>
        </div>

        <livewire:books-catalog />

    </div>
</div>
@endsection

