@extends('layouts.store')

@section('content')

<script>
    window.dataSearchLaravel = @json($produks);
</script>

<div x-data="{ 
        sortOpen: false,
        currentSort: 'Sortir',
        allProducts: window.dataSearchLaravel,
        
        get displayedProducts() {
            let result = [...this.allProducts];

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
    class="max-w-6xl mx-auto py-8 px-4">

    <div class="flex flex-col md:flex-row md:justify-between md:items-end border-b-2 border-pink-100 pb-3 mb-4 gap-4">
        <div>
            <p class="text-sm text-gray-500 mb-1">Hasil pencarian untuk:</p>
            <h2 class="text-3xl font-bold text-pink-500 font-serif">"{{ $kata_kunci }}"</h2>
        </div>
    </div>

    <div class="flex justify-between items-center mb-8 relative z-30">
        <span class="text-sm text-gray-500">Ditemukan <strong class="text-pink-500">{{ $produks->count() }}</strong> produk.</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-16" x-show="displayedProducts.length > 0">
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
    
    <div x-show="displayedProducts.length === 0" style="display: none;" class="text-center py-20 bg-white rounded-2xl border border-pink-100 shadow-sm">
        <div class="text-pink-200 text-6xl mb-4"><i class="fas fa-search"></i></div>
        <p class="text-gray-600 font-bold text-xl mb-2">Yah, barangnya nggak ketemu!</p>
        <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">Coba gunakan kata kunci lain.</p>
        <a href="/produk" class="inline-block bg-pink-400 text-white rounded-full px-8 py-3 hover:bg-pink-500 transition font-bold shadow-md">Lihat Semua Produk</a>
    </div>

</div>
@endsection