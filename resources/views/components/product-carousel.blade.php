@props(['title', 'items', 'id', 'hideViewAll' => false, 'link' => '/produk'])

<div class="mb-12 group/carousel">
    
    <div class="flex justify-between items-end mb-4 px-2">
        <h3 class="text-xl font-bold text-gray-800">{{ $title }}</h3>
        
        @if(!$hideViewAll)
            <a href="{{ $link }}" class="text-sm border border-gray-400 rounded-full px-4 py-1 hover:border-pink-400 hover:text-pink-500 transition">View all</a>
        @endif
    </div>

    <div class="relative">
        <button onclick="slideLeft('{{ $id }}')" class="absolute left-0 top-28 -translate-y-1/2 -ml-4 z-10 bg-white shadow-md rounded-full w-10 h-10 flex items-center justify-center text-gray-600 hover:text-pink-500 opacity-0 group-hover/carousel:opacity-100 transition duration-300">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div id="{{ $id }}" class="flex gap-4 overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar pb-4 px-2">
            @foreach($items as $item)
                <a href="{{ route('produk.show', $item->id_produk) }}" class="block snap-start shrink-0 w-[calc(50%-0.5rem)] md:w-[calc(33.33%-0.66rem)] lg:w-[calc(20%-0.8rem)] group cursor-pointer relative">
                    
                    <button @click.prevent="toggleFav({{ $item->id_produk }})" 
                            class="absolute top-2 right-2 z-20 bg-white/90 w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition opacity-0 group-hover:opacity-100"
                            :class="favorites.includes({{ $item->id_produk }}) ? 'text-pink-500 opacity-100' : 'text-gray-400 hover:text-pink-500'">
                        <i :class="favorites.includes({{ $item->id_produk }}) ? 'fas fa-heart' : 'far fa-heart'"></i>
                    </button>

                    <div class="h-56 bg-gray-100 rounded-xl mb-3 overflow-hidden">
                        <img src="{{ asset('images/produk/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center bg-pink-50 text-xs text-pink-300 italic\'>{{ $item->gambar }}</div>'">
                    </div>
                    
                    <div class="flex gap-2 mb-1.5">
                        @if($item->stok == 0)
                            <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 border border-gray-300 rounded-sm">Terjual Habis</span>
                        @else
                            <span class="text-pink-500 text-[10px] font-bold px-2 py-0.5 border border-pink-300 rounded-sm uppercase">Baru</span>
                        @endif
                    </div>
                    
                    <h4 class="text-gray-800 font-medium text-sm truncate">{{ $item->nama_produk ?: 'Produk' }}</h4>
                    <p class="text-pink-500 font-bold mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                </a>
            @endforeach
            
            @if($items->isEmpty())
                <p class="text-gray-400 text-sm italic py-8">Belum ada produk di kategori ini.</p>
            @endif
        </div>

        <button onclick="slideRight('{{ $id }}')" class="absolute right-0 top-28 -translate-y-1/2 -mr-4 z-10 bg-white shadow-md rounded-full w-10 h-10 flex items-center justify-center text-gray-600 hover:text-pink-500 opacity-0 group-hover/carousel:opacity-100 transition duration-300">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>