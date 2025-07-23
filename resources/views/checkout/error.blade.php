<!-- resources/views/checkout/error.blade.php (User: Pembayaran Gagal) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pembayaran Gagal!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <svg class="mx-auto h-24 w-24 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-4 mb-2">Maaf, Pembayaran Gagal!</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Order ID: <span class="font-bold">{{ $orderId }}</span></p>
                <p class="text-gray-700 dark:text-gray-300">Terjadi masalah saat memproses pembayaran Anda. Silakan coba lagi atau hubungi dukungan.</p>

                <div class="mt-6 flex justify-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Coba Lagi
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>