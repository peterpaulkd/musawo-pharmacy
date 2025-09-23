{{-- resources/views/nurse/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Pharmacy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-yellow-50 min-h-screen font-sans flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-yellow-100 shadow-md min-h-screen p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-2xl font-bold text-yellow-700 mb-8">Pharmacy Panel</h2>
            <nav class="flex flex-col space-y-4">
                <a href="{{ route('nurse.inventory') }}" class="text-yellow-800 hover:text-yellow-900 font-medium transition">Inventory</a>
                <a href="#" class="text-yellow-800 hover:text-yellow-900 font-medium transition">Today's Sales</a>
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
            <h1 class="text-3xl font-bold text-yellow-700">Welcome, Nurse!</h1>
            <p class="text-gray-600">Here’s a summary of today’s pharmacy activities.</p>
        </header>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Total Medicines</h3>
                <p class="text-2xl font-bold text-yellow-600">350</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Low Stock</h3>
                <p class="text-2xl font-bold text-red-600">12</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Near Expiry</h3>
                <p class="text-2xl font-bold text-orange-600">5</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Today’s Sales</h3>
                <p class="text-2xl font-bold text-green-600">$1,250</p>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-xl font-semibold text-yellow-700 mb-4">Recent Transactions</h2>
            <!-- Sale Modal with Alpine -->
        <div x-data="saleModal()" class="relative" x-cloak>
            <!-- Button to open modal -->
            <button @click="open = true" class="px-4 py-2 bg-blue-500 text-white rounded mb-6">Add Sale</button>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="p-2 text-gray-500">Medicine</th>
                        <th class="p-2 text-gray-500">Quantity</th>
                        <th class="p-2 text-gray-500">Customer</th>
                        <th class="p-2 text-gray-500">Date</th>
                        <th class="p-2 text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                        <td class="p-2">Paracetamol</td>
                        <td class="p-2">22</td>
                        <td class="p-2">John Doe</td>
                        <td class="p-2">2025-08-19</td>
                        <td class="p-2 text-green-600 font-semibold">Sold</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                        <td class="p-2">Amoxicillin</td>
                        <td class="p-2">10</td>
                        <td class="p-2">Jane Smith</td>
                        <td class="p-2">2025-08-19</td>
                        <td class="p-2 text-green-600 font-semibold">Sold</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-yellow-50 transition">
                        <td class="p-2">Cough Syrup</td>
                        <td class="p-2">5</td>
                        <td class="p-2">Mark Lee</td>
                        <td class="p-2">2025-08-18</td>
                        <td class="p-2 text-yellow-600 font-semibold">Pending</td>
                    </tr>
                </tbody>
            </table>
        </div>

        

            <!-- Modal -->
            <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition>
                <div class="bg-white p-6 rounded-lg w-1/2" @click.away="open = false">
                    <h2 class="text-2xl font-bold mb-4">Add New Sale</h2>
                    <form action="{{ route('nurse.sale.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex space-x-2 items-center">
                                    <select name="drug_id[]" class="border px-2 py-1 rounded flex-1">
                                        @foreach($drugs as $drug)
                                            <option value="{{ $drug->id }}">{{ $drug->name }} (Stock: {{ $drug->stock }})</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="quantity[]" placeholder="Qty" class="border px-2 py-1 rounded w-24">
                                    <button type="button" @click="removeItem(index)" class="text-red-500">Remove</button>
                                </div>
                            </template>
                            <button type="button" @click="addItem()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Add Another Drug</button>
                        </div>

                        <div class="mt-4 flex justify-end space-x-2">
                            <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit Sale</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function saleModal() {
            return {
                open: false,
                items: [{}],
                addItem() { this.items.push({}); },
                removeItem(index) { this.items.splice(index, 1); }
            }
        }
    </script>

</body>
</html>
