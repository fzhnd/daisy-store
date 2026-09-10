@extends('layouts.store')

@section('content')

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="mb-8 mt-4">
        <h2 class="text-3xl font-bold text-pink-500 font-serif border-b-2 border-pink-100 pb-2">Jelajahi Produk</h2>
        <p class="text-gray-500 text-sm mt-2">Temukan koleksi lengkap Daisy Store berdasarkan kategori kesukaanmu.</p>
    </div>

    <x-product-carousel title="Produk Baru" :items="$produkBaru" id="carousel-baru" :hideViewAll="true" />
    <x-product-carousel title="Pakaian" :items="$pakaian" id="carousel-pakaian" link="/kategori/Pakaian" />
    <x-product-carousel title="Aksesoris" :items="$aksesoris" id="carousel-aksesoris" link="/kategori/Aksesoris" />
    <x-product-carousel title="Alat Tulis" :items="$alatTulis" id="carousel-tulis" link="/kategori/Alat Tulis" />

    <script>
        function slideLeft(id) {
            let container = document.getElementById(id);
            container.scrollBy({ left: -container.clientWidth, behavior: 'smooth' });
        }
        function slideRight(id) {
            let container = document.getElementById(id);
            container.scrollBy({ left: container.clientWidth, behavior: 'smooth' });
        }
    </script>
@endsection