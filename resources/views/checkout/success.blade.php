<!-- resources/views/checkout/success.blade.php (User: Pembayaran Sukses) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pembayaran Berhasil!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <svg class="mx-auto h-24 w-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-4 mb-2">Terima Kasih!</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Pembayaran Anda untuk Order ID: <span class="font-bold">{{ $orderId }}</span> telah berhasil.</p>
                <p class="text-gray-700 dark:text-gray-300">Pesanan Anda akan segera diproses.</p>

                <div class="mt-6 flex justify-center space-x-4">
                    <a href="{{ route('transactions.history') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Lihat Riwayat Transaksi
                    </a>
                    <a href="{{ route('marketplace.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>