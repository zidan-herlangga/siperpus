@extends('layouts.app')

@section('title', 'Katalog Buku - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/books.css') }}" media="print" onload="this.media='all'" fetchpriority="low">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/books.css') }}"></noscript>
<style>
    .bg-mesh {
        background-color: #f4f6f1;
        background-image: radial-gradient(#e2e9e1 1px, transparent 1px);
        background-size: 24px 24px;
    }
    .card-glass {
        background: #ffffff;
        border: 1px solid #e2e9e1;
        box-shadow: 0 20px 50px -30px rgba(0, 66, 37, 0.25);
    }
    .input-modern { border: 1.5px solid #e2e9e1; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .input-modern:hover { border-color: #c6d4c8; }
    .input-modern:focus { border-color: #006739; box-shadow: 0 0 0 3px rgba(0, 103, 57, 0.1); }
    .book-card { background: white; border: 1px solid #e2e9e1; border-radius: 16px; overflow: hidden; transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1); }
    .book-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -12px rgba(0, 66, 37, 0.18); border-color: #d2e6da; }
    .book-card:hover .book-cover-icon { transform: scale(1.1) rotate(-3deg); }
    .book-cover-icon { transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1); }
    .btn-detail { background: #006739; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .btn-detail:hover { background: #004225; box-shadow: 0 8px 20px -6px rgba(0, 66, 37, 0.5); }
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
                Jelajahi koleksi perpustakaan digital kami - temukan dan pinjam buku favoritmu dengan mudah.
            </p>
        </div>

        <livewire:books-catalog />

    </div>
</div>
@endsection

