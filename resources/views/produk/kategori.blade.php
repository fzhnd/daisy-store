@extends('layouts.store')

@section('content')

<!-- 1. SIMPAN DATA DARI LARAVEL KE JAVASCRIPT -->
<script>
    window.dataKategoriLaravel = @json($produks);
</script>

<!-- 2. BUNGKUS DENGAN ALPINE.JS UNTUK FILTER OTOMATIS -->
<div x-data="{ 
        sortOpen: false,
        currentSort: 'Sortir',
        allProducts: window.dataKategoriLaravel,
        
        get displayedProducts() {
            let result = [...this.allProducts];

            // Logika Urutan (Sort)
            if (this.currentSort === 'A - Z') {
                result.sort((a, b) => (a.nama_produk || '').localeCompare(b.nama_produk || ''));
            } else if (this.currentSort === 'Z - A') {
                result.sort((a, b) => (b.nama_produk || '').localeCompare(a.nama_produk || ''));
            } else if (this.currentSort === 'Harga terendah') {
                result.sort((a, b) => parseInt(a.harga) - parseInt(b.harga));
            } else if (this.currentSort === 'Harga tertinggi') {
                result.sort((a, b) => parseInt(b.harga) - parseInt(a.harga));
            } else {
                result.sort((a, b) => (b.id_produk || b.id) - (a.id_produk || a.id)); // Terbaru
            }

            return result;
        }
    }" 
    class="mt-4">

    <!-- ======================================================== -->
    <!-- BARIS ATAS: JUDUL KATEGORI & TOMBOL KEMBALI              -->
    <!-- ======================================================== -->
    <div class="flex justify-between items-center border-b-2 border-pink-100 pb-3 mb-4">
        <h2 class="text-3xl font-bold text-pink-500 font-serif">Kategori: {{ ucwords($kategori) }}</h2>
        
        <a href="/produk" class="text-sm text-gray-500 hover:text-pink-500 transition flex items-center gap-1.5 hover:bg-pink-50 py-2 px-3 rounded-lg">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- ======================================================== -->
    <!-- BARIS BAWAH: KETERANGAN & TOMBOL SORTIR                  -->
    <!-- ======================================================== -->
    <div class="flex items-center gap-3 mb-8 relative z-30">
        <div class="relative">
            <button @click="sortOpen = !sortOpen" @click.outside="sortOpen = false" 
                    class="h-10 px-4 flex items-center justify-between gap-3 border rounded-lg transition focus:outline-none min-w-[180px]"
                    :class="sortOpen || currentSort !== 'Sortir' ? 'border-pink-400 text-pink-500 bg-pink-50' : 'border-gray-300 text-gray-600 hover:border-pink-400 hover:text-pink-500'">
                <div class="flex items-center gap-2">
                    <i class="fas fa-arrows-alt-v"></i>
                    <span class="text-sm font-medium" x-text="currentSort"></span>
                </div>
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="sortOpen ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="sortOpen" style="display: none;" 
                 class="absolute left-0 top-full mt-2 w-48 bg-white border border-pink-100 rounded-xl shadow-xl p-2 flex flex-col gap-1"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                
                <button @click="currentSort = 'Sortir'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Sortir' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Terbaru</button>
                <button @click="currentSort = 'A - Z'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'A - Z' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">A - Z</button>
                <button @click="currentSort = 'Z - A'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Z - A' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Z - A</button>
                <button @click="currentSort = 'Harga terendah'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Harga terendah' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Harga terendah</button>
                <button @click="currentSort = 'Harga tertinggi'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Harga tertinggi' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Harga tertinggi</button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- GRID PRODUK DINAMIS ALPINE.JS                            -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-16">
        <template x-for="item in displayedProducts" :key="item.id_produk">
            <a :href="'/produk/detail/' + item.id_produk" class="group cursor-pointer relative flex flex-col">
                
                <button @click.prevent="toggleFav(item.id_produk)" 
                        class="absolute top-2 right-2 z-20 bg-white/90 w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition opacity-0 group-hover:opacity-100"
                        :class="favorites.includes(item.id_produk) ? 'text-pink-500 opacity-100' : 'text-gray-400 hover:text-pink-500'">
                    <i :class="favorites.includes(item.id_produk) ? 'fas fa-heart' : 'far fa-heart'"></i>
                </button>

                <div class="h-56 w-full bg-gray-100 rounded-xl mb-3 overflow-hidden">
                    <img :src="'/images/produk/' + item.gambar" :alt="item.nama_produk" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                
                <div class="flex gap-2 mb-1.5">
                    <template x-if="item.stok == 0">
                        <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 border border-gray-300 rounded-sm">terjual habis</span>
                    </template>
                    <template x-if="item.stok > 0">
                        <span class="text-pink-500 text-[10px] font-bold px-2 py-0.5 border border-pink-300 rounded-sm uppercase">Baru</span>
                    </template>
                </div>

                <h3 class="text-gray-800 font-medium text-sm truncate" x-text="item.nama_produk || 'Produk Tanpa Nama'"></h3>
                <p class="text-pink-500 font-bold mt-1">Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga)"></span></p>
            </a>
        </template>
    </div>
    
    <div x-show="displayedProducts.length === 0" style="display: none;" class="text-center py-16">
        <p class="text-gray-400 italic">Belum ada produk untuk kategori <span x-text="window.location.pathname.split('/').pop().replace('%20', ' ')"></span>.</p>
    </div>

</div>
@endsection