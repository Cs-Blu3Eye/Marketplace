{{-- !-- resources/views/layouts/admin.blade.php (Layout khusus untuk Admin)  --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navbar Admin -->
        <nav class="bg-gray-800 p-4 text-white flex justify-between items-center">
            <div class="text-xl font-bold">Admin Panel</div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="px-3 py-2 rounded-md hover:bg-gray-700">Produk</a>
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md hover:bg-gray-700">Kembali ke Marketplace</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-md hover:bg-gray-700">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
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
                @if (session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Info!</strong>
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>