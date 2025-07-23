<!-- resources/views/cart/index.blade.php (User: Keranjang Belanja) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
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
                @if (empty($cartItems))
                    <p class="text-center text-gray-500 dark:text-gray-300 text-lg">Keranjang Anda kosong.</p>
                    <div class="text-center mt-4">
                        <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Lanjutkan Belanja
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            @foreach ($cartItems as $item)
                                <div class="flex items-center border-b border-gray-200 dark:border-gray-700 py-4">
                                    <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://placehold.co/100x100/E0E0E0/333333?text=No+Image' }}" alt="{{ $item->product->name }}" class="w-24 h-24 object-cover rounded-md mr-4">
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $item->product->name }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        <form action="{{ route('cart.update', $item->product) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 text-center mr-2">
                                            <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded-md hover:bg-blue-600 transition duration-300">Update</button>
                                        </form>
                                        <form action="{{ route('cart.remove', $item->product) }}" method="POST" class="ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600 transition duration-300">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="lg:col-span-1 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pesanan</h2>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-700 dark:text-gray-300">Total Harga:</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <form action="{{ route('checkout.process') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition duration-300 font-semibold text-lg">
                                    Lanjutkan ke Pembayaran
                                </button>
                            </form>
                            <div class="text-center mt-4">
                                <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Lanjutkan Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>