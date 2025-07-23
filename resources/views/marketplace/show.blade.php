<!-- resources/views/marketplace/show.blade.php (User: Detail Produk) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $product->name }}
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
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x600/E0E0E0/333333?text=No+Image' }}" alt="{{ $product->name }}" class="w-full h-96 object-cover rounded-lg shadow-md">
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $product->name }}</h1>
                        <p class="text-gray-700 dark:text-gray-300 text-lg mb-6">{{ $product->description }}</p>
                        <div class="flex items-baseline mb-4">
                            <span class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400 mr-4">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-lg text-gray-600 dark:text-gray-400">Stok: {{ $product->stock }}</span>
                        </div>

                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center space-x-4">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <button type="submit" class="bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 transition duration-300 font-semibold text-lg">
                                Tambah ke Keranjang
                            </button>
                        </form>
                        @error('quantity')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        @if ($product->stock == 0)
                            <p class="text-red-500 text-sm mt-2">Stok produk ini habis.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>