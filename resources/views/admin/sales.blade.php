<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-yellow-50 font-sans min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-yellow-200 to-yellow-100 shadow-lg min-h-screen p-6 flex flex-col">
        <h2 class="text-3xl font-bold text-yellow-700 mb-10 animate-pulse">Admin Panel</h2>
        <nav class="flex flex-col space-y-4">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg text-yellow-900 font-semibold hover:bg-yellow-300 transition">Dashboard</a>
            <a href="#" class="px-4 py-2 rounded-lg text-yellow-900 font-semibold hover:bg-yellow-300 transition">Day Transactions</a>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-lg text-yellow-900 font-semibold hover:bg-yellow-300 transition">Reports</a>
            
        </nav>

        <!-- Add New Drug Button -->
        <button onclick="openModal('createDrugModal')" 
            class="mt-6 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            + Add New Drug
        </button>

        <!-- logout button -->
         <form method="POST" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit" class="mt-6 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
        Logout
    </button>
</form>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <!-- Header -->
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-yellow-700">Welcome, Nurse!</h1>
            <p class="text-gray-600">Here’s a summary of today’s pharmacy activities.</p>
        </header>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Total Medicines</h3>
                <p class="text-2xl font-bold text-yellow-600">{{ $drugs->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Today’s Sales</h3>
                <p class="text-2xl font-bold text-green-600">UGX {{ $report->grand_total }}</p>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-xl font-semibold text-yellow-700 mb-4">Recent Transactions</h2>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="p-2 text-gray-500">Medicine</th>
                        <th class="p-2 text-gray-500">Quantity</th>
                        <th class="p-2 text-gray-500">Price</th>
                        <th class="p-2 text-gray-500">Amount</th>
                        <th class="p-2 text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($transactions as $transaction)
        @foreach($transaction->items as $item)
            <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                <td class="p-2">{{ $item->drug->name }}</td>
                <td class="p-2">{{ $item->quantity }}</td>
                <td class="p-2">shs {{ number_format($item->price) }}</td>
                <td class="p-2">shs {{ number_format($item->subtotal) }}</td>
                <td class="p-2">{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    @endforeach
</tbody>

            </table>
        </div>
</body>
</html>
