@extends('layouts.store')

@section('content')

<script>
    window.dataProdukLaravel = @json($produks);
</script>

<div x-data="{ 
        filterOpen: false, 
        sortOpen: false,
        currentCategory: 'Semua kategori',
        currentSort: 'Terbaru',
        
        allProducts: window.dataProdukLaravel, // Panggil data dari script di atas
        
        get displayedProducts() {
            let result = this.allProducts.filter(p => this.favorites.includes(parseInt(p.id_produk || p.id)));

            if (this.currentCategory !== 'Semua kategori') {
                result = result.filter(p => p.kategori_produk === this.currentCategory);
            }

            if (this.currentSort === 'A - Z') {
                result.sort((a, b) => (a.nama_produk || '').localeCompare(b.nama_produk || ''));
            } else if (this.currentSort === 'Z - A') {
                result.sort((a, b) => (b.nama_produk || '').localeCompare(a.nama_produk || ''));
            } else if (this.currentSort === 'Harga terendah') {
                result.sort((a, b) => parseInt(a.harga) - parseInt(b.harga));
            } else if (this.currentSort === 'Harga tertinggi') {
                result.sort((a, b) => parseInt(b.harga) - parseInt(a.harga));
            } else {
                result.sort((a, b) => (b.id_produk || b.id) - (a.id_produk || a.id)); 
            }

            return result;
        }
    }" 
    class="max-w-6xl mx-auto py-8">

    <div class="flex justify-between items-center mb-8 border-b-2 border-pink-100 pb-4">
        <h2 class="text-3xl font-bold text-pink-500 font-serif">Favorit Produk</h2>

        <div class="flex gap-3 relative">
            
            <div class="relative">
                <button @click="filterOpen = !filterOpen; sortOpen = false" @click.outside="filterOpen = false" 
                        class="w-10 h-10 flex items-center justify-center border rounded-lg transition focus:outline-none"
                        :class="filterOpen || currentCategory !== 'Semua kategori' ? 'border-pink-400 text-pink-500 bg-pink-50' : 'border-gray-400 text-gray-500 hover:border-pink-400 hover:text-pink-500'">
                    <i class="fas fa-filter"></i>
                </button>

                <div x-show="filterOpen" style="display: none;" 
                     class="absolute right-0 top-full mt-2 w-48 bg-white border border-pink-100 rounded-xl shadow-xl z-50 p-2 flex flex-col gap-1"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="currentCategory = 'Semua kategori'; filterOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentCategory === 'Semua kategori' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Semua kategori</button>
                    <button @click="currentCategory = 'Pakaian'; filterOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentCategory === 'Pakaian' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Pakaian</button>
                    <button @click="currentCategory = 'Aksesoris'; filterOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentCategory === 'Aksesoris' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Aksesoris</button>
                    <button @click="currentCategory = 'Alat Tulis'; filterOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentCategory === 'Alat Tulis' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Alat Tulis</button>
                </div>
            </div>

            <div class="relative">
                <button @click="sortOpen = !sortOpen; filterOpen = false" @click.outside="sortOpen = false" 
                        class="w-10 h-10 flex items-center justify-center border rounded-lg transition focus:outline-none"
                        :class="sortOpen || currentSort !== 'Terbaru' ? 'border-pink-400 text-pink-500 bg-pink-50' : 'border-gray-400 text-gray-500 hover:border-pink-400 hover:text-pink-500'">
                    <i class="fas fa-arrows-alt-v"></i>
                </button>

                <div x-show="sortOpen" style="display: none;" 
                     class="absolute right-0 top-full mt-2 w-48 bg-white border border-pink-100 rounded-xl shadow-xl z-50 p-2 flex flex-col gap-1"
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="currentSort = 'Terbaru'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Terbaru' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Terbaru</button>
                    <button @click="currentSort = 'A - Z'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'A - Z' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">A - Z</button>
                    <button @click="currentSort = 'Z - A'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Z - A' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Z - A</button>
                    <button @click="currentSort = 'Harga terendah'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Harga terendah' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Harga terendah</button>
                    <button @click="currentSort = 'Harga tertinggi'; sortOpen = false" class="w-full text-left px-3 py-2 text-sm transition rounded-lg focus:outline-none" :class="currentSort === 'Harga tertinggi' ? 'text-pink-500 font-bold bg-pink-50' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-500'">Harga tertinggi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
        <template x-for="item in displayedProducts" :key="item.id_produk">
            <a :href="'/produk/detail/' + item.id_produk" class="group cursor-pointer relative flex flex-col">
                
                <button @click.prevent="toggleFav(item.id_produk)" 
                        class="absolute top-2 right-2 z-20 bg-white/90 w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition"
                        :class="favorites.includes(item.id_produk) ? 'text-pink-500' : 'text-gray-400 hover:text-pink-500'">
                    <i :class="favorites.includes(item.id_produk) ? 'fas fa-heart' : 'far fa-heart'"></i>
                </button>

                <div class="h-56 w-full bg-gray-100 rounded-xl mb-3 overflow-hidden">
                    <img :src="'/images/produk/' + item.gambar" :alt="item.nama_produk" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                
                <div class="flex gap-2 mb-1.5">
                    <template x-if="item.stok == 0">
                        <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 border border-gray-300 rounded-sm">Terjual Habis</span>
                    </template>
                    <template x-if="item.stok > 0">
                        <span class="text-pink-500 text-[10px] font-bold px-2 py-0.5 border border-pink-300 rounded-sm uppercase">Baru</span>
                    </template>
                </div>

                <h3 class="text-gray-800 font-medium text-sm truncate" x-text="item.nama_produk || 'Produk'"></h3>
                <p class="text-pink-500 font-bold mt-1">Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga)"></span></p>
            </a>
        </template>
    </div>
    
    <div x-show="displayedProducts.length === 0" style="display: none;" class="text-center py-20">
        <div class="text-pink-200 text-7xl mb-4"><i class="far fa-heart"></i></div>
        <p class="text-gray-500 font-medium text-lg">Daftar produk tidak ditemukan.</p>
        <p class="text-gray-400 text-sm mt-2 mb-6">Jelajahi produk dan simpan barang impianmu!</p>
        <a href="/produk" class="inline-block bg-pink-400 text-white rounded-full px-8 py-3 hover:bg-pink-500 transition font-bold shadow-sm">Jelajahi Produk</a>
    </div>

</div>
@endsection