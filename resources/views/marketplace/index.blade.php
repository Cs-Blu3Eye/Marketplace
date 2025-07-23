<!-- resources/views/marketplace/index.blade.php (User: Daftar Produk) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Produk Terbaru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($products as $product)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                                <a href="{{ route('marketplace.show', $product) }}">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400/E0E0E0/333333?text=No+Image' }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                </a>
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $product->name }}</h3>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-3">{{ Str::limit($product->description, 70) }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-900 dark:text-gray-100">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Stok: {{ $product->stock }}</span>
                                    </div>
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition duration-300">
                                            Tambah ke Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500 dark:text-gray-300">
                                Tidak ada produk yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>