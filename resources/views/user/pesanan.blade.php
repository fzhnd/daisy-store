<x-app-layout>
    <div class="max-w-5xl mx-auto py-8 px-4">
        <h2 class="text-2xl font-bold text-pink-500 mb-6">Pesanan Saya</h2>

        <!-- Tab Navigasi -->
        <div class="flex overflow-x-auto border-b border-gray-200 mb-6 bg-white rounded-t-xl shadow-sm">
            @php
                $tabs = [
                    'All' => 'All', 
                    'Belum_Bayar' => 'Belum Bayar', 
                    'Sedang_Dikemas' => 'Sedang Dikemas', 
                    'Dikirim' => 'Dikirim', 
                    'Selesai' => 'Selesai'
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <a href="?status={{ $key }}" 
                   class="flex-1 text-center py-4 text-sm font-medium {{ $status == $key ? 'text-pink-500 border-b-2 border-pink-500' : 'text-gray-500 hover:text-pink-500' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Daftar Pesanan -->
        <div class="space-y-6">
            @forelse ($pesanan as $order)
                <div class="bg-white rounded-2xl shadow-sm border border-pink-50 p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                        <span class="font-bold text-gray-800 text-lg">Daisy Store</span>
                        <span class="text-pink-500 font-semibold text-sm uppercase">{{ $order->status }}</span>
                    </div>

                    <!-- Detail Produk -->
                    <div class="space-y-4">
                        @foreach ($order->detail as $item)
                            <div class="flex gap-4">
                                <img src="{{ asset('images/produk/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama_produk }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200">
                                
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg">{{ $item->produk->nama_produk }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">Variasi: {{ $item->variasi ?? '-' }}</p>
                                    <p class="text-sm text-gray-500 mt-1">x{{ $item->jumlah }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-pink-500 font-bold">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Footer Total -->
                    <div class="border-t border-gray-100 pt-4 mt-4 flex justify-end items-center gap-4">
                        <span class="text-gray-600 text-sm">Total Pesanan:</span>
                        <span class="text-2xl font-bold text-pink-500">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-pink-50">
                    <p class="text-gray-500">Belum ada pesanan.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>