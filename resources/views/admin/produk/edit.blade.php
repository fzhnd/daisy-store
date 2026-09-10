<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk: ') }} <span class="text-pink-500">{{ $produk->nama_produk }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ $produk->nama_produk }}" class="w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                        <select name="kategori_produk" class="w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" required>
                            <option value="Pakaian" {{ $produk->kategori_produk == 'Pakaian' ? 'selected' : '' }}>Pakaian</option>
                            <option value="Aksesoris" {{ $produk->kategori_produk == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                            <option value="Alat Tulis" {{ $produk->kategori_produk == 'Alat Tulis' ? 'selected' : '' }}>Alat Tulis</option>
                        </select>
                    </div>

                    <div class="flex gap-4 mb-4">
                        <div class="w-1/2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                            <input type="number" name="harga" value="{{ $produk->harga }}" class="w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" required>
                        </div>
                        <div class="w-1/2">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                            <input type="number" name="stok" value="{{ $produk->stok }}" class="w-full border-pink-300 focus:border-pink-500 focus:ring-pink-500 rounded-md" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Produk</label>
                        
                        <p class="text-sm text-gray-500 mb-2">File saat ini: <span class="font-semibold text-pink-500">{{ $produk->gambar }}</span></p>
                        
                        <input type="file" name="gambar" class="w-full border border-gray-300 p-2 rounded-md" accept="image/*">
                        <p class="text-xs text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengganti gambar.</p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-bold mr-2">Batal</a>
                        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-md font-bold">Update Produk</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>