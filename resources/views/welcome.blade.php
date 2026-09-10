@extends('layouts.store')

@section('content')
    <section class="mb-16 mt-4">
        <div class="w-full h-[400px] bg-gradient-to-r from-pink-100 to-purple-100 rounded-2xl flex items-center justify-center shadow-inner overflow-hidden relative">
            <div class="text-center">
                <h2 class="text-4xl font-bold text-pink-500 mb-4" style="font-family: serif;">New Blue Series</h2>
                <p class="text-gray-600">Koleksi terbaru hadir musim ini.</p>
            </div>
        </div>
    </section>

    <section class="mb-16">
        <h3 class="text-2xl text-center text-gray-800 font-bold mb-10"><span class="border-b-4 border-pink-300 pb-2">Rekomendasi Produk</span></h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($rekomendasi as $item)
                <a href="{{ route('produk.show', $item->id_produk) }}" class="bg-white rounded-xl shadow-sm p-4 group cursor-pointer block hover:shadow-md transition">
                    <div class="h-48 bg-gray-100 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/produk/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.outerHTML='<span class=\'text-xs italic\'>{{ $item->gambar }}</span>'">
                    </div>
                    <h4 class="text-gray-800 font-medium text-sm truncate">{{ $item->nama_produk ?: 'Produk' }}</h4>
                    <p class="text-pink-500 font-bold mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 mt-16">
        <div class="bg-pink-100 rounded-2xl p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-pink-400 pl-3">Jelajahi Produk Kami</h3>
            <p class="text-gray-600 text-sm">Temukan berbagai koleksi menarik mulai dari pakaian, aksesoris, hingga alat tulis super imut untuk menemanimu.</p>
            <a href="/produk" class="inline-block mt-4 bg-white border border-pink-300 text-pink-500 px-6 py-2 rounded-full hover:bg-pink-400 hover:text-white transition text-sm shadow-sm">Belanja Sekarang</a>
        </div>
        <div class="bg-white rounded-2xl p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4 border-l-4 border-gray-400 pl-3">Informasi Toko</h3>
            <ul class="text-gray-600 text-sm space-y-2">
                <li><i class="fas fa-truck text-pink-300 mr-2 w-4"></i> Pengiriman ke seluruh Indonesia</li>
                <li><i class="fas fa-box text-pink-300 mr-2 w-4"></i> Garansi produk asli</li>
                <li><i class="fas fa-headset text-pink-300 mr-2 w-4"></i> Layanan CS 09:00 - 17:00</li>
            </ul>
        </div>
    </div>
@endsection