<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daisy Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-pink-50 font-sans text-gray-800"
      x-data="{
          cartOpen: false,
          showSuccessPopup: false,
          cartItems: JSON.parse(localStorage.getItem('daisy_cart') || '[]'),

          favorites: JSON.parse(localStorage.getItem('daisy_fav') || '[]').map(Number),

          toggleFav(id) {
              let numId = parseInt(id); // Paksa menjadi angka
              if (this.favorites.includes(numId)) {
                  this.favorites = this.favorites.filter(f => f !== numId);
              } else {
                  this.favorites.push(numId);
              }
          },

          get subtotal() {
              return this.cartItems.reduce((total, item) => total + (item.harga * item.qty), 0);
          },
          get totalBarang() {
              return this.cartItems.reduce((total, item) => total + parseInt(item.qty), 0);
          }
      }"

      @sync-keranjang.window="if (JSON.stringify(cartItems) !== JSON.stringify($event.detail)) { cartItems = $event.detail; }"

      x-init="
          // UBAH BARIS INI JUGa: Pantau cartItems, bukan cart
          $watch('cartItems', value => {
              localStorage.setItem('daisy_cart', JSON.stringify(value));
              window.dispatchEvent(new CustomEvent('sync-keranjang', { detail: value }));
          }, { deep: true });
      ">

    <div class="sticky top-0 z-50 shadow-sm">
        <header class="bg-white py-4 border-b border-pink-100">
            <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">

                <div class="w-full md:w-1/4 text-center md:text-left">
                    <a href="/" class="text-4xl font-bold text-pink-400 tracking-wide" style="font-family: serif;">Daisy Store</a>
                </div>

                <form action="/search" method="GET" class="w-full md:w-2/4 relative">
                    <input type="text" name="q" value="{{ request('q') }}" required placeholder="Cari nama produk..."
                        class="w-full border-2 border-pink-200 rounded-full py-2 px-6 focus:outline-none focus:border-pink-400 focus:ring-1 focus:ring-pink-400 placeholder-pink-300">
                    <button type="submit" class="absolute right-4 top-2.5 text-pink-400 hover:text-pink-600 focus:outline-none">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="w-full md:w-1/4 flex justify-center md:justify-end space-x-6 text-gray-500">
                    <a href="/favorit" class="flex flex-col items-center hover:text-pink-500 transition">
                        <i class="far fa-heart text-xl mb-1"></i>
                        <span class="text-[10px] font-semibold tracking-wider">FAVORIT</span>
                    </a>

                    @auth
                        <div x-data="{ open: false }" class="relative flex flex-col items-center">
                            <button @click="open = !open" @click.outside="open = false" class="flex flex-col items-center hover:text-pink-500 transition focus:outline-none">
                                <i class="far fa-user text-xl mb-1"></i>
                                <span class="text-[10px] font-semibold tracking-wider uppercase">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            </button>

                            <div x-show="open" style="display: none;" class="absolute top-full mt-3 w-72 bg-white border border-pink-100 rounded-3xl shadow-xl p-5 right-[-3rem] z-50"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">

                                <div class="mb-5">
                                    <p class="text-gray-500 text-sm mb-1">akun</p>
                                    <p class="text-gray-600 font-medium truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="flex gap-3">
                                    <a href="{{ route('pesanan.index') }}" class="flex-1 flex items-center justify-center gap-2 py-2.5 border border-pink-400 text-pink-500 rounded-2xl hover:bg-pink-50 transition text-sm font-bold">
                                        <i class="fas fa-box"></i> Pesanan
                                    </a>
                                    <a href="/profile" class="flex-1 flex items-center justify-center gap-2 py-2.5 border border-pink-400 text-pink-500 rounded-2xl hover:bg-pink-50 transition text-sm font-bold">
                                        <i class="far fa-user"></i> Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="flex flex-col items-center hover:text-pink-500 transition">
                            <i class="far fa-user text-xl mb-1"></i>
                            <span class="text-[10px] font-semibold tracking-wider">PROFIL</span>
                        </a>
                    @endauth

                    <button @click="cartOpen = true" class="flex flex-col items-center hover:text-pink-500 transition focus:outline-none">
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-xl mb-1"></i>
                            <span x-show="totalBarang > 0" x-text="totalBarang" style="display: none;" class="absolute -top-2 -right-3 bg-pink-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white"></span>
                        </div>
                        <span class="text-[10px] font-semibold tracking-wider">KERANJANG</span>
                    </button>
                </div>
            </div>
        </header>

        <nav class="bg-pink-200 py-3">
            <div class="container mx-auto px-4 flex justify-center space-x-8 text-sm font-medium text-gray-700">
                <a href="/" class="hover:text-pink-600 transition">Halaman Utama</a>
                <a href="/produk" class="hover:text-pink-600 transition">Produk</a>
                <a href="/kategori/pakaian" class="hover:text-pink-600 transition">Pakaian</a>
                <a href="/kategori/aksesoris" class="hover:text-pink-600 transition">Aksesoris</a>
                <a href="/kategori/alat tulis" class="hover:text-pink-600 transition">Alat Tulis</a>
            </div>
        </nav>
    </div>

    <main class="container mx-auto px-4 py-8 min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-pink-100 py-8 text-center text-pink-400 text-sm mt-auto">
        <p>&copy; 2026 Daisy Store. All rights reserved.</p>
    </footer>
</body>

    <div x-show="showSuccessPopup" style="display: none;" class="fixed inset-0 z-[80] flex items-center justify-center bg-black/20" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-[400px] max-w-[90%] relative" @click.outside="showSuccessPopup = false">
            <button @click="showSuccessPopup = false" class="absolute top-4 right-4 text-gray-400 hover:text-pink-500 transition"><i class="fas fa-times text-xl"></i></button>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-full border-2 border-pink-400 flex items-center justify-center text-pink-400 text-2xl shrink-0">
                    <i class="fas fa-check"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 leading-tight">Produk ditambahkan ke keranjang</h3>
            </div>
            <div class="flex justify-between gap-4">
                <button @click="showSuccessPopup = false; cartOpen = true" class="w-1/2 py-2 text-gray-600 font-medium hover:text-pink-500 transition text-left">Lihat keranjang</button>
                <button @click="showSuccessPopup = false" class="w-1/2 py-2 text-gray-600 font-medium hover:text-pink-500 transition text-right">Lanjut belanja</button>
            </div>
        </div>
    </div>

    <div x-show="cartOpen" style="display: none;" class="fixed inset-0 bg-black/20 z-[60]" @click="cartOpen = false" x-transition.opacity></div>

    <div x-show="cartOpen" style="display: none;"
         class="fixed top-0 right-0 h-full w-full sm:w-1/4 bg-pink-50 shadow-2xl z-[70] flex flex-col"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

        <div class="p-4 border-b border-pink-100 flex justify-between items-center bg-white shrink-0">
            <h2 class="text-xl font-bold text-gray-800">Keranjang</h2>
            <button @click="cartOpen = false" class="text-gray-400 hover:text-pink-500 text-2xl focus:outline-none transition">&times;</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 relative">
            <template x-if="cartItems.length === 0">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <p class="text-gray-500 mb-4 font-medium text-lg">Keranjang belanja kosong.</p>
                    <button @click="cartOpen = false" class="bg-pink-400 hover:bg-pink-500 text-white font-bold py-3 px-6 rounded-full transition">Lanjutkan berbelanja</button>
                </div>
            </template>

            <template x-for="(item, index) in cartItems" :key="index">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-pink-100 mb-4 flex gap-4">

                    <div class="w-20 h-20 bg-pink-50 rounded-lg overflow-hidden shrink-0 border border-pink-100">
                        <img :src="'/images/produk/' + item.gambar" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-gray-800 font-bold text-sm leading-tight mb-1" x-text="item.nama"></h4>
                            <p class="text-gray-400 text-xs mb-1" x-text="item.varian"></p>
                            <p class="text-gray-400 text-[10px]">Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga)"></span> / pcs</p>
                        </div>

                        <div class="flex justify-between items-center mt-2">
                            <p class="text-pink-500 font-bold text-sm">Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga * item.qty)"></span></p>

                            <div class="flex items-center gap-3">
                                <div class="flex items-center border border-pink-200 rounded-md bg-white w-20 h-7">
                                    <button @click="if(item.qty > 1) item.qty--" class="w-1/3 h-full flex items-center justify-center text-gray-400 hover:text-pink-500 focus:outline-none"><i class="fas fa-minus text-[10px]"></i></button>
                                    <input type="text" x-model="item.qty" class="w-1/3 h-full text-center border-none p-0 text-xs font-bold focus:ring-0 text-gray-800" readonly>
                                    <button @click="item.qty++" class="w-1/3 h-full flex items-center justify-center text-gray-400 hover:text-pink-500 focus:outline-none"><i class="fas fa-plus text-[10px]"></i></button>
                                </div>

                                <button @click="cartItems.splice(index, 1)" class="text-gray-300 hover:text-pink-500 transition focus:outline-none">
                                    <i class="far fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </template>
        </div>

        <div x-show="cartItems.length > 0" class="bg-white border-t border-pink-100 p-6 shrink-0 shadow-[0_-5px_10px_rgba(0,0,0,0.02)]">
            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-bold text-gray-800">Subtotal</span>
                <span class="text-xl font-bold text-pink-500">Rp <span x-text="new Intl.NumberFormat('id-ID').format(subtotal)"></span></span>
            </div>

            <!-- Tombol Checkout -->
            <a href="/checkout" @click="localStorage.removeItem('daisy_direct_cart')" class="w-full bg-pink-400 hover:bg-pink-500 text-white font-bold py-3.5 px-4 rounded-full flex justify-center items-center gap-2 transition shadow-md">
                Checkout <i class="fas fa-chevron-right text-xs mt-0.5"></i>
            </a>
        </div>
    </div>
</html>
