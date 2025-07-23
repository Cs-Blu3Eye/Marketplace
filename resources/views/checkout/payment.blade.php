<!-- resources/views/checkout/payment.blade.php (User: Halaman Pembayaran Midtrans) -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Detail Pembayaran</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-2">Order ID: <span
                        class="font-bold">{{ $orderId }}</span></p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Total Pembayaran: <span
                        class="font-bold text-2xl text-indigo-600">Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                </p>

                <button id="pay-button"
                    class="bg-green-600 text-white py-3 px-6 rounded-md hover:bg-green-700 transition duration-300 font-semibold text-lg">
                    Bayar Sekarang
                </button>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Anda akan diarahkan ke halaman pembayaran
                    Midtrans.</p>
            </div>
        </div>
    </div>

    {{-- @push('scripts') --}}
    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                    window.location.href = "{{ route('checkout.finish', ['order_id' => $orderId]) }}";
                },
                onPending: function(result) {
                    alert("Pembayaran Anda pending!");
                    console.log(result);
                    window.location.href = "{{ route('checkout.pending', ['order_id' => $orderId]) }}";
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                    window.location.href = "{{ route('checkout.error', ['order_id' => $orderId]) }}";
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                    window.location.href = "{{ route('transactions.history') }}";
                }
            });
        };
    </script>

    {{-- @endpush --}}
</x-app-layout>
