@extends('layouts.store')

@section('content')
<form action="{{ route('pesanan.store') }}" method="POST" 
      @submit="localStorage.removeItem('daisy_cart'); localStorage.removeItem('daisy_direct_cart');"
      @sync-keranjang.window="if (JSON.stringify(cart) !== JSON.stringify($event.detail)) { cart = $event.detail; }"
      x-data="{
          isDirect: localStorage.getItem('daisy_direct_cart') !== null,
          cart: JSON.parse(localStorage.getItem('daisy_direct_cart') || localStorage.getItem('daisy_cart') || '[]'),
          
          shippingMethod: '', shippingCost: 0, paymentMethod: 'bank',
          isFetchingShipping: false, shippingOptions: [],
          
          wilayah: { prov: [], kota: [], kec: [], kel: [] },
          openProv: false, openKota: false, openKec: false, openKel: false,
          isEditingAddress: false,
          
          customerAddress: Object.assign({
              nama: '', telepon: '', alamat: '', 
              provName: 'Pilih Provinsi', kotaName: 'Pilih Kota/Kab', 
              kecName: 'Pilih Kecamatan', kelName: 'Pilih Kelurahan', 
              kodePos: '', village_code: ''
          }, JSON.parse(localStorage.getItem('daisy_address') || '{}')),
          
          fetchWilayah(tipe, id = '') {
              if (tipe === 'kota') { this.wilayah.kota = []; this.wilayah.kec = []; this.wilayah.kel = []; }
              else if (tipe === 'kec') { this.wilayah.kec = []; this.wilayah.kel = []; }
              else if (tipe === 'kel') { this.wilayah.kel = []; }

              let url = '';
              let baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';
              
              if (tipe === 'prov') url = `${baseUrl}/provinces.json`;
              else if (tipe === 'kota') url = `${baseUrl}/regencies/${id}.json`;
              else if (tipe === 'kec')  url = `${baseUrl}/districts/${id}.json`;
              else if (tipe === 'kel')  url = `${baseUrl}/villages/${id}.json`;

              if (url) {
                  fetch(url)
                      .then(res => res.json())
                      .then(data => {
                          this.wilayah[tipe] = data;
                      })
                      .catch(err => console.error('Gagal mengambil data wilayah:', err));
              }
          },
          
          saveAddress() {
              this.isEditingAddress = false;
              localStorage.setItem('daisy_address', JSON.stringify(this.customerAddress));
              this.fetchShipping(); 
          },
          
          fetchShipping() {
              if (!this.customerAddress.kodePos) return;
              
              this.isFetchingShipping = true;
              this.shippingOptions = [];
              
              fetch('/ro/calculate-by-district?kodepos=' + encodeURIComponent(this.customerAddress.kodePos))
                  .then(res => res.json())
                  .then(response => {
                      if (response.data) {
                          this.shippingOptions = [...(response.data.calculate_reguler || []), ...(response.data.calculate_cargo || [])];
                      }
                      this.isFetchingShipping = false;
                  })
                  .catch(err => { 
                      console.error('Error memuat ongkir:', err); 
                      this.isFetchingShipping = false; 
                  });
          },
          
          get subtotal() { return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0); },
          get total() { return this.subtotal + this.shippingCost; },
          setShipping(method, cost) { this.shippingMethod = method; this.shippingCost = cost; },
          
          tambahQty(index) { this.cart[index].qty++; },
          kurangiQty(index) {
              if (this.cart[index].qty > 1) { this.cart[index].qty--; } 
              else { this.hapusItem(index); }
          },
          hapusItem(index) { this.cart.splice(index, 1); }
      }" 
      x-init="
          fetchWilayah('prov'); 
          if(customerAddress.kecName !== 'Pilih Kecamatan') fetchShipping(); 
          $watch('cart', value => {
              let storageKey = isDirect ? 'daisy_direct_cart' : 'daisy_cart';
              localStorage.setItem(storageKey, JSON.stringify(value));
              window.dispatchEvent(new CustomEvent('sync-keranjang', { detail: value }));
          }, { deep: true });
      "
      class="max-w-6xl mx-auto py-8 px-4">
      
      @csrf
      <!-- Tambahkan dua input ini agar data terbaca oleh Controller Laravel -->
      <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
      <input type="hidden" name="total_harga" :value="total">

    <div class="border-b-2 border-pink-100 pb-4 mb-6">
        <h2 class="text-3xl font-bold text-pink-500 font-serif">Checkout Pesanan</h2>
    </div>

    <!-- ========================================== -->
    <!-- ALAMAT PENGIRIMAN -->
    <!-- ========================================== -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-pink-100 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800 text-lg">Alamat Pengiriman</h3>
            <button type="button" @click="isEditingAddress ? saveAddress() : isEditingAddress = true" 
                    class="text-sm text-gray-500 hover:text-pink-500 transition flex items-center gap-1.5 border border-transparent hover:bg-pink-50 py-1.5 px-3 rounded-md focus:outline-none"
                    :class="isEditingAddress ? 'text-pink-500 bg-pink-50 font-bold' : ''">
                <i :class="isEditingAddress ? 'fas fa-save' : 'fas fa-edit'"></i> 
                <span x-text="isEditingAddress ? 'Simpan' : 'Edit Alamat'"></span>
            </button>
        </div>

        <div x-show="!isEditingAddress" class="text-gray-600 text-sm space-y-1 bg-pink-50/50 p-4 rounded-lg border border-pink-50">
            <p class="font-bold text-gray-800">
                <span x-text="customerAddress.nama || 'Nama Penerima belum diatur'"></span> 
                <span class="text-gray-500 font-normal">| <span x-text="customerAddress.telepon || '08xxxxxxxxxx'"></span></span>
            </p>
            <p class="leading-relaxed">
                <span x-show="customerAddress.alamat" x-text="customerAddress.alamat + ', '"></span>
                <span x-show="customerAddress.kelName !== 'Pilih Kelurahan'" x-text="customerAddress.kelName + ', '"></span>
                <span x-show="customerAddress.kecName !== 'Pilih Kecamatan'" x-text="customerAddress.kecName + ', '"></span>
                <span x-show="customerAddress.kotaName !== 'Pilih Kota/Kab'" x-text="customerAddress.kotaName + ', '"></span>
                <span x-show="customerAddress.provName !== 'Pilih Provinsi'" x-text="customerAddress.provName + ', '"></span>
                <span x-show="customerAddress.kodePos" x-text="customerAddress.kodePos"></span>
                <span x-show="!customerAddress.alamat" class="text-gray-400 italic">Alamat belum lengkap. Silakan klik Edit Alamat.</span>
            </p>
        </div>

        <div x-show="isEditingAddress" style="display: none;" class="bg-pink-50 p-4 rounded-lg border border-pink-200 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" x-model="customerAddress.nama" placeholder="Nama Penerima" class="w-full border border-pink-200 rounded-lg py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400">
                <input type="text" x-model="customerAddress.telepon" placeholder="08xxxxxxxxxx" class="w-full border border-pink-200 rounded-lg py-2 px-3 text-sm text-gray-700 focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400">
            </div>

            <!-- DROPDOWN WILAYAH EMSIFA -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative z-50">
                 
                <div class="relative">
                    <button type="button" @click="openProv = !openProv; openKota = false; openKec = false; openKel = false" @click.away="openProv = false" class="w-full bg-white border border-pink-200 rounded-lg p-2 text-sm text-left text-gray-700 flex justify-between items-center h-[38px]">
                        <span x-text="customerAddress.provName" class="truncate"></span> <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                    </button>
                    <ul x-show="openProv" style="display: none;" class="absolute z-50 w-full bg-white border border-pink-200 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg">
                        <template x-for="p in wilayah.prov" :key="p.id">
                            <li @click="customerAddress.provName = p.name; openProv = false; 
                                        customerAddress.kotaName = 'Pilih Kota/Kab'; customerAddress.kecName = 'Pilih Kecamatan'; customerAddress.kelName = 'Pilih Kelurahan';
                                        fetchWilayah('kota', p.id)" 
                                class="p-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200" x-text="p.name"></li>
                        </template>
                    </ul>
                </div>

                <div class="relative">
                    <button type="button" @click="openKota = !openKota; openProv = false; openKec = false; openKel = false" @click.away="openKota = false" class="w-full bg-white border border-pink-200 rounded-lg p-2 text-sm text-left text-gray-700 flex justify-between items-center h-[38px]">
                        <span x-text="customerAddress.kotaName" class="truncate"></span> <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                    </button>
                    <ul x-show="openKota" style="display: none;" class="absolute z-50 w-full bg-white border border-pink-200 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg">
                        <template x-for="k in wilayah.kota" :key="k.id">
                            <li @click="customerAddress.kotaName = k.name; openKota = false; 
                                        customerAddress.kecName = 'Pilih Kecamatan'; customerAddress.kelName = 'Pilih Kelurahan';
                                        fetchWilayah('kec', k.id)" 
                                class="p-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200" x-text="k.name"></li>
                        </template>
                    </ul>
                </div>

                <div class="relative">
                    <button type="button" @click="openKec = !openKec; openProv = false; openKota = false; openKel = false" @click.away="openKec = false" class="w-full bg-white border border-pink-200 rounded-lg p-2 text-sm text-left text-gray-700 flex justify-between items-center h-[38px]">
                        <span x-text="customerAddress.kecName" class="truncate"></span> <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                    </button>
                    <ul x-show="openKec" style="display: none;" class="absolute z-50 w-full bg-white border border-pink-200 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg">
                        <template x-for="k in wilayah.kec" :key="k.id">
                            <li @click="customerAddress.kecName = k.name; openKec = false; customerAddress.kelName = 'Pilih Kelurahan'; fetchWilayah('kel', k.id)" 
                                class="p-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200" x-text="k.name"></li>
                        </template>
                    </ul>
                </div>

                <div class="relative">
                    <button type="button" @click="openKel = !openKel; openProv = false; openKota = false; openKec = false" @click.away="openKel = false" class="w-full bg-white border border-pink-200 rounded-lg p-2 text-sm text-left text-gray-700 flex justify-between items-center h-[38px]">
                        <span x-text="customerAddress.kelName" class="truncate"></span> <i class="fas fa-chevron-down text-gray-400 text-[10px]"></i>
                    </button>
                    <ul x-show="openKel" style="display: none;" class="absolute z-50 w-full bg-white border border-pink-200 rounded-lg mt-1 max-h-48 overflow-y-auto shadow-lg">
                        <template x-for="d in wilayah.kel" :key="d.id">
                            <li @click="customerAddress.kelName = d.name; openKel = false; customerAddress.village_code = d.id" 
                                class="p-2 text-sm text-gray-700 cursor-pointer hover:bg-gray-200" x-text="d.name"></li>
                        </template>
                    </ul>
                </div>

                <input type="text" x-model="customerAddress.kodePos" placeholder="Kode Pos" class="w-full bg-white border border-pink-200 rounded-lg p-2 text-sm text-gray-700 focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400 h-[38px]">
            </div>

            <textarea x-model="customerAddress.alamat" rows="2" 
                      class="w-full border border-pink-200 rounded-lg py-2 px-3 text-sm placeholder-gray-400 text-gray-700 focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400 transition" 
                      placeholder="Detail Alamat (Nama Jalan, No. Rumah, RT/RW)"></textarea>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- KOLOM PRODUK & PEMBAYARAN -->
    <!-- ========================================== -->
    <div class="flex flex-col md:flex-row gap-8 items-start">
        
        <div class="w-full md:w-3/5 space-y-4">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Produk Dipesan</h3>
            <div x-show="cart.length === 0" style="display: none;" class="bg-white p-8 rounded-xl border border-pink-100 text-center shadow-sm">
                <p class="text-gray-500 mb-4">Tidak ada produk untuk di-checkout.</p>
                <a href="/produk" class="inline-block bg-pink-400 hover:bg-pink-500 text-white font-bold py-2 px-6 rounded-full transition">Kembali Belanja</a>
            </div>

            <template x-for="(item, index) in cart" :key="index">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-pink-100 flex gap-4">
                    <img :src="'/images/produk/' + item.gambar" class="w-20 h-20 rounded-lg object-cover border border-pink-100">
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-gray-800 font-bold text-sm" x-text="item.nama"></h4>
                            <p class="text-gray-400 text-xs mb-1" x-text="item.varian"></p>
                            <p class="text-gray-400 text-[10px]">Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga)"></span> / pcs</p>
                        </div>
                        
                        <!-- Harga Total & Kontrol Qty (Baris Bawah) -->
                        <div class="flex justify-between items-end mt-2">
                            <p class="text-pink-500 font-bold text-sm">Rp <span x-text="new Intl.NumberFormat('id-ID').format(item.harga * item.qty)"></span></p>
                            
                            <!-- Grup Tombol - + dan Hapus -->
                            <div class="flex items-center gap-3">
                                <!-- Tombol Qty -->
                                <div class="flex items-center border border-pink-200 rounded-lg overflow-hidden bg-white">
                                    <button type="button" @click="kurangiQty(index)" class="px-2.5 py-1 text-gray-600 hover:bg-pink-50 transition">-</button>
                                    <span class="px-3 py-1 text-xs font-bold border-x border-pink-200" x-text="item.qty"></span>
                                    <button type="button" @click="tambahQty(index)" class="px-2.5 py-1 text-gray-600 hover:bg-pink-50 transition">+</button>
                                </div>
                                
                                <!-- Tombol Hapus -->
                                <button type="button" @click="hapusItem(index)" class="text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="w-full md:w-2/5 space-y-6">
            
            <div class="bg-white rounded-xl p-6 shadow-sm border border-pink-100">
                <h3 class="font-bold text-gray-800 mb-1">Pilih Metode Pengiriman</h3>
                <div class="space-y-3 mt-3">
                    <p x-show="isFetchingShipping" class="text-xs text-pink-500 font-medium">Sedang memuat estimasi ongkir...</p>
                    <p x-show="!isFetchingShipping && shippingOptions.length === 0" class="text-xs text-gray-400">Silahkan isi dan simpan alamat untuk melihat ongkir.</p>
                    
                    <!-- Loop Opsi Kurir RajaOngkir -->
                    <template x-for="kurir in shippingOptions" :key="kurir.service_name + kurir.shipping_name">
                        <div @click="setShipping(kurir.shipping_name + ' ' + kurir.service_name, kurir.shipping_cost_net)" 
                             class="border rounded-xl p-4 cursor-pointer transition flex justify-between items-center"
                             :class="shippingMethod === (kurir.shipping_name + ' ' + kurir.service_name) ? 'border-pink-500 bg-pink-50 shadow-sm' : 'border-gray-200 hover:border-pink-300'">
                            <div>
                                <p class="font-bold text-gray-800 text-sm"><span x-text="kurir.shipping_name"></span> - <span x-text="kurir.service_name"></span></p> <!-- Data dari Komship API[cite: 6] -->
                                <p class="text-xs text-gray-500">Estimasi: <span x-text="kurir.etd"></span></p>
                            </div>
                            <p class="font-bold text-pink-500 text-sm">Rp <span x-text="new Intl.NumberFormat('id-ID').format(kurir.shipping_cost_net)"></span></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-pink-100">
                <h3 class="font-bold text-gray-800 mb-4">Metode Pembayaran</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition" :class="paymentMethod === 'bank' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                        <input type="radio" x-model="paymentMethod" value="bank" class="text-pink-500 focus:ring-pink-300 h-4 w-4 mt-0.5">
                        <span class="text-sm font-medium text-gray-700">Transfer Bank</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition" :class="paymentMethod === 'ewallet' ? 'border-pink-500 bg-pink-50' : 'border-gray-200 hover:border-pink-300'">
                        <input type="radio" x-model="paymentMethod" value="ewallet" class="text-pink-500 focus:ring-pink-300 h-4 w-4 mt-0.5">
                        <span class="text-sm font-medium text-gray-700">E-Wallet</span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-pink-100">
                <div class="flex justify-between items-center mb-3 text-sm text-gray-600">
                    <span>Subtotal Pesanan</span>
                    <span>Rp <span x-text="new Intl.NumberFormat('id-ID').format(subtotal)"></span></span>
                </div>
                <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                    <span>Biaya Pengiriman</span>
                    <span>Rp <span x-text="new Intl.NumberFormat('id-ID').format(shippingCost)"></span></span>
                </div>
                <hr class="border-pink-100 mb-4">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-800 font-bold text-lg">Total</span>
                    <span class="text-pink-500 font-extrabold text-2xl tracking-tight">Rp <span x-text="new Intl.NumberFormat('id-ID').format(total)"></span></span>
                </div>
                <button type="submit" 
                        class="w-full bg-pink-400 hover:bg-pink-500 text-white font-bold py-4 rounded-full transition shadow-md" 
                        :disabled="cart.length === 0 || shippingMethod === ''" 
                        :class="(cart.length === 0 || shippingMethod === '') ? 'opacity-50 cursor-not-allowed' : ''">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection