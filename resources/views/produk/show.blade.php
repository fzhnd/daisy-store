@extends('layouts.store')

@section('content')
<!-- Logika Alpine.js untuk interaksi halaman -->
<div x-data="{
        qty: 1,
        maxQty: {{ $produk->stok }},
        activeOption: 'Pink',
        activeImageIndex: 0,
        images: [
            '{{ asset('images/produk/' . $produk->gambar) }}',
            '{{ asset('images/produk/' . $produk->gambar) }}', // Mock gambar 2
            '{{ asset('images/produk/' . $produk->gambar) }}'  // Mock gambar 3
        ],
        nextImage() { this.activeImageIndex = (this.activeImageIndex + 1) % this.images.length; },
        prevImage() { this.activeImageIndex = (this.activeImageIndex - 1 + this.images.length) % this.images.length; }
    }" 
    class="max-w-6xl mx-auto pt-3 pb-8">

    <!-- Navigasi Breadcrumb -->
    <div class="flex justify-between items-center mb-8">
        
        <div class="text-sm text-gray-500">
            <a href="/" class="hover:text-pink-500 transition">Halaman Utama</a> <span class="mx-2">/</span>
            <a href="/kategori/{{ $produk->kategori_produk }}" class="hover:text-pink-500 transition">{{ $produk->kategori_produk }}</a> <span class="mx-2">/</span>
            <span class="text-gray-600 font-medium">{{ $produk->nama_produk }}</span>
        </div>

        <a href="{{ url()->previous() }}" class="text-sm text-gray-500 hover:text-pink-500 transition flex items-center gap-1.5 hover:bg-pink-50 py-2 px-3 rounded-lg">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        
    </div>

    <!-- KONTEN UTAMA: DIBAGI 2 KOLOM -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        
        <!-- ================= BAGIAN KIRI: GAMBAR ================= -->
        <div class="flex flex-col gap-4">
            <!-- Gambar Utama dengan Tombol Geser -->
            <div class="relative w-full aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center group">
                <img :src="images[activeImageIndex]" alt="Gambar Produk" class="w-full h-full object-cover transition-all duration-300">
                
                <!-- Tombol Kiri -->
                <button @click="prevImage" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-md text-gray-600 hover:text-pink-500 transition opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <!-- Tombol Kanan -->
                <button @click="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-md text-gray-600 hover:text-pink-500 transition opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Thumbnail (Urutan Tampilan) -->
            <div class="flex gap-4 overflow-x-auto no-scrollbar py-2">
                <template x-for="(img, index) in images" :key="index">
                    <button @click="activeImageIndex = index" 
                            :class="{'ring-2 ring-pink-400 opacity-100': activeImageIndex === index, 'opacity-60 hover:opacity-100 border border-gray-200': activeImageIndex !== index}"
                            class="w-20 h-20 shrink-0 rounded-xl overflow-hidden transition">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>

        <!-- ================= BAGIAN KANAN: DETAIL PRODUK ================= -->
        <div class="flex flex-col">
            
            <!-- Judul & Ikon Favorit -->
            <div class="flex justify-between items-start gap-4 mb-4">
                <h1 class="text-3xl font-bold text-gray-800 leading-tight">{{ $produk->nama_produk }}</h1>
                <button @click.prevent="toggleFav({{ $produk->id_produk }})" 
                        class="text-2xl transition mt-1 focus:outline-none"
                        :class="favorites.includes({{ $produk->id_produk }}) ? 'text-pink-500' : 'text-gray-400 hover:text-pink-500'">
                    <i :class="favorites.includes({{ $produk->id_produk }}) ? 'fas fa-heart' : 'far fa-heart'"></i>
                </button>
            </div>

            <!-- Harga -->
            <p class="text-3xl font-bold text-pink-500 mb-8 border-b border-pink-200 pb-6">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

            <!-- Bagian Option (Contoh UI Warna/Ukuran) -->
            <div class="mb-8">
                <p class="text-sm font-bold text-gray-700 mb-3">Varian: <span x-text="activeOption" class="text-pink-500 font-normal ml-1"></span></p>
                <div class="flex gap-4">
                    <!-- Opsi 1 -->
                    <button @click="activeOption = 'Pink'" :class="{'ring-2 ring-pink-400': activeOption === 'Pink', 'border border-gray-200': activeOption !== 'Pink'}" class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-pink-50 transition w-20">
                        <img src="{{ asset('images/produk/' . $produk->gambar) }}" class="w-12 h-12 rounded-md object-cover">
                        <span class="text-xs text-gray-600">Pink</span>
                    </button>
                    <!-- Opsi 2 -->
                    <button @click="activeOption = 'Biru'" :class="{'ring-2 ring-pink-400': activeOption === 'Biru', 'border border-gray-200': activeOption !== 'Biru'}" class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-pink-50 transition w-20">
                        <img src="{{ asset('images/produk/' . $produk->gambar) }}" class="w-12 h-12 rounded-md object-cover grayscale opacity-70">
                        <span class="text-xs text-gray-600">Biru</span>
                    </button>
                </div>
            </div>

            <!-- Bagian Jumlah & Stok -->
            <div class="mb-10">
                <p class="text-sm font-bold text-gray-700 mb-3">Jumlah</p>
                <div class="flex items-center gap-6">
                    <!-- Kotak Plus Minus -->
                    <div class="flex items-center border-2 border-gray-300 rounded-full w-32 h-12 overflow-hidden bg-transparent">
                        <button type="button" @click="if(qty > 1) qty--" class="w-1/3 h-full flex items-center justify-center text-gray-500 hover:text-pink-500 hover:bg-pink-50 focus:outline-none transition">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        
                        <input type="number" x-model="qty" 
                            class="w-1/3 h-full text-center border-none focus:ring-0 text-gray-800 font-bold p-0 bg-transparent no-spinners" 
                            readonly>
                        
                        <button type="button" @click="if(qty < maxQty) qty++" class="w-1/3 h-full flex items-center justify-center text-gray-500 hover:text-pink-500 hover:bg-pink-50 focus:outline-none transition">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                    <!-- Info Stok -->
                    <p class="text-sm text-gray-500">Tersisa <span class="font-bold text-pink-500">{{ $produk->stok }}</span> barang</p>
                </div>
            </div>

            <!-- Tombol Beli & Keranjang -->
            <div class="flex gap-4 mb-12">
                
                @if($produk->stok > 0)
                    <button type="button" 
                        @click.prevent="
                            // Simpan langsung ke memori jalur cepat
                            localStorage.setItem('daisy_direct_cart', JSON.stringify([{
                                id: {{ $produk->id_produk }},
                                nama: '{{ addslashes($produk->nama_produk) }}',
                                varian: activeOption,
                                harga: {{ $produk->harga }},
                                qty: parseInt(qty),
                                gambar: '{{ $produk->gambar }}'
                            }]));
                            
                            // Beri jeda sangat singkat (0.1 detik) sebelum pindah halaman agar memori sukses tersimpan
                            setTimeout(() => { 
                                window.location.href = '/checkout'; 
                            }, 100);
                        "
                        class="w-1/2 border-2 border-pink-400 text-pink-500 hover:bg-pink-50 font-bold py-4 rounded-full transition shadow-sm focus:outline-none">
                        Beli Sekarang
                    </button>
                    
                    <button type="button" 
                        @click.prevent="
                            let existingItem = cartItems.find(i => i.nama === '{{ addslashes($produk->nama_produk) }}' && i.varian === activeOption);
                            if(existingItem) {
                                existingItem.qty += parseInt(qty);
                            } else {
                                cartItems.push({
                                    id: {{ $produk->id_produk }},
                                    nama: '{{ addslashes($produk->nama_produk) }}',
                                    varian: activeOption,
                                    harga: {{ $produk->harga }},
                                    qty: parseInt(qty),
                                    gambar: '{{ $produk->gambar }}'
                                });
                            }
                            showSuccessPopup = true;
                        " 
                        class="w-1/2 bg-pink-400 hover:bg-pink-500 text-white font-bold py-4 rounded-full transition shadow-sm flex justify-center items-center gap-2 focus:outline-none">
                        <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                    </button>

                @else
                    <button type="button" disabled class="w-full bg-gray-200 text-gray-500 font-bold py-4 rounded-full cursor-not-allowed border border-gray-300">
                        Terjual Habis
                    </button>
                @endif

            </div>

            <!-- Deskripsi Produk -->
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Deskripsi Produk</h3>
                <div class="text-gray-600 text-sm leading-relaxed space-y-4">
                    <p>Produk koleksi {{ $produk->kategori_produk }} eksklusif dari Daisy Store. Didesain dengan penuh ketelitian menggunakan material premium yang nyaman digunakan.</p>
                    <p>Sangat cocok untuk melengkapi gaya harianmu atau dijadikan hadiah spesial untuk orang tersayang.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= BAGIAN BAWAH: CAROUSEL TERAKHIR DILIHAT ================= -->
    <div class="mt-24 border-t border-pink-200 pt-16">
        <x-product-carousel title="Produk Terakhir Dilihat" :items="$terakhirDilihat" id="carousel-terakhir" :hideViewAll="true" />
    </div>

</div>

<!-- CSS tambahan untuk menyembunyikan panah atas-bawah pada input number -->
<style>
    .no-spinners::-webkit-outer-spin-button,
    .no-spinners::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinners { -moz-appearance: textfield; }
</style>
@endsection