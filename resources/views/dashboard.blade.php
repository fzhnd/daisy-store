<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Produk') }}
            </h2>
            <a href="{{ route('produk.create') }}" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-md text-sm font-bold transition">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-pink-50 text-pink-500 border-b-2 border-pink-200">
                                <th class="p-3">ID</th>
                                <th class="p-3">Gambar</th>
                                <th class="p-3">Nama Produk</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3">Harga</th>
                                <th class="p-3">Stok</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produks as $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $item->id_produk }}</td>
                                <td class="p-3 text-gray-400 italic text-sm">{{ $item->gambar }}</td>
                                <td class="p-3 font-bold">{{ $item->nama_produk ?: 'Belum ada nama' }}</td>
                                <td class="p-3">{{ $item->kategori_produk }}</td>
                                <td class="p-3 text-green-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="p-3">{{ $item->stok }}</td>
                                <td class="p-3 text-center space-x-2">
                                    <a href="{{ route('produk.edit', $item->id_produk) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>