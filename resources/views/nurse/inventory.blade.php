{{-- resources/views/nurse/inventory.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - Pharmacy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-50 min-h-screen font-sans flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-yellow-100 shadow-md min-h-screen p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-2xl font-bold text-yellow-700 mb-8">Pharmacy Panel</h2>
            <nav class="flex flex-col space-y-4">
                <a href="{{ route('nurse.dashboard') }}" class="text-yellow-800 hover:text-yellow-900 font-medium transition">Dashboard</a>
                <a href="{{ route('nurse.inventory') }}" class="text-yellow-800 hover:text-yellow-900 font-medium transition">Inventory</a>
                
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="text-red-600 hover:text-red-800 font-medium transition">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button onclick="alert('Generating report...');" 
                        class="mt-6 w-full bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    Generate Daily Report
                </button>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <!-- Header -->
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-yellow-700">Inventory</h1>
            <p class="text-gray-600">Manage your pharmacy medicines and stock levels.</p>
        </header>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Total Medicines</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $drugs->count() }}</p>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold text-yellow-700 mb-4">Medicine Inventory</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="p-2 text-gray-500">Medicine</th>
                        <th class="p-2 text-gray-500">Quantity</th>
                        <th class="p-2 text-gray-500">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tbody>
                        @foreach($drugs as $drug)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="p-2">{{ $drug->name }}</td>
                            <td class="p-2">{{ $drug->stock }}</td>
                            <td class="p-2">shs {{ $drug->unit_price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </main>
</body>
</html>
