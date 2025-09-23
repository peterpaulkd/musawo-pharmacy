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
            <a href="{{ route('admin.sales') }}" class="px-4 py-2 rounded-lg text-yellow-900 font-semibold hover:bg-yellow-300 transition">Day Transactions</a>
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
    <main class="flex-1 p-8 space-y-8">
        <h1 class="text-4xl font-extrabold text-yellow-700 mb-4">Welcome, Dr. Shadra!</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center hover:scale-105 transform transition">
                <h3 class="text-gray-500 font-medium mb-2">Total Drugs</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $drugs->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5 flex flex-col items-center">
                <h3 class="text-gray-500 font-medium">Today’s Sales</h3>
                <p class="text-2xl font-bold text-green-600">UGX {{ $report->grand_total ?? 0 }}</p>
            </div>
        </div>

        <!-- Drug Inventory Table -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-2xl font-semibold text-yellow-700 mb-4">Drug Inventory</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-yellow-100 border-b border-yellow-300">
                            <th class="p-3 text-gray-600">Medicine</th>
                            <th class="p-3 text-gray-600">Stock</th>
                            <th class="p-3 text-gray-600">Price</th>
                            <th class="p-3 text-gray-600">Purchase</th>
                            <th class="p-3 text-gray-600">Profit</th>
                            <th class="p-3 text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drugs as $drug)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="p-2">{{ $drug->name }}</td>
                            <td class="p-2">{{ $drug->stock }}</td>
                            <td class="p-2">shs {{ $drug->unit_price }}</td>
                            <td class="p-2">shs {{ $drug->purchase_price }}</td>
                            <td class="p-2">shs {{ $drug->profit_per_unit }}</td>
                            <td class="p-2 flex space-x-2">
                                <!-- Edit -->
                                <button 
                                    onclick="openEditModal({{ $drug->id }}, '{{ $drug->name }}', {{ $drug->stock }}, {{ $drug->unit_price }}, {{ $drug->purchase_price }})" 
                                    class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                    Edit
                                </button>

                                <!-- Delete -->
                                <form action="{{ route('admin.drugs.destroy', $drug->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- CREATE Drug Modal -->
    <div id="createDrugModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-1/2 relative">
            <button onclick="closeModal('createDrugModal')" class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-4">Add New Drug</h2>
            <form action="{{ route('admin.drugs.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="Drug Name" class="w-full border px-3 py-2 rounded" required>
                    <input type="number" name="stock" placeholder="Stock" class="w-full border px-3 py-2 rounded" required>
                    <input type="number" step="0.01" name="unit_price" placeholder="Price" class="w-full border px-3 py-2 rounded" required>
                    <input type="number" step="0.01" name="purchase_price" placeholder="Purchase Price" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('createDrugModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add Drug</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT Drug Modal -->
    <div id="editDrugModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-1/2 relative">
            <button onclick="closeModal('editDrugModal')" class="absolute top-2 right-2 text-gray-600 hover:text-black text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-4">Edit Drug</h2>
            <form id="editDrugForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <input type="text" id="edit_name" name="name" class="w-full border px-3 py-2 rounded" required>
                    <input type="number" id="edit_stock" name="stock" class="w-full border px-3 py-2 rounded" required>
                    <input type="number" step="0.01" id="edit_unit_price" name="unit_price" class="w-full border px-3 py-2 rounded" required>
                    <input type="number" step="0.01" id="edit_purchase_price" name="purchase_price" class="w-full border px-3 py-2 rounded" required>
                </div>
                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editDrugModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Drug</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
        function openEditModal(id, name, stock, unit_price, purchase_price) {
            // Populate form
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_unit_price').value = unit_price;
            document.getElementById('edit_purchase_price').value = purchase_price;

            // Update form action
            let form = document.getElementById('editDrugForm');
            form.action = `/admin/drugs/${id}`;

            // Show modal
            openModal('editDrugModal');
        }
    </script>
</body>
</html>
