{{-- resources/views/nurse/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Pharmacy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [data-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-yellow-50 min-h-screen font-sans flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-yellow-100 shadow-md min-h-screen p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-2xl font-bold text-yellow-700 mb-8">Pharmacy Panel</h2>
            <nav class="flex flex-col space-y-4">
                <a href="{{ route('nurse.inventory') }}" class="text-yellow-800 hover:text-yellow-900 font-medium transition">Inventory</a>
                <a href="{{ route('nurse.day.transactions') }}" class="text-yellow-800 hover:text-yellow-900 font-medium transition">Today's Sales</a>

                <!-- Button to open modal -->
                <button id="openSaleModal" class="px-4 py-2 bg-blue-500 text-white rounded mb-6">+ Add Sale</button>
              <!-- report generation -->
                <form action="{{ route('admin.reports.generate') }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-2 bg-green-500 rounded hover:bg-green-600">Generate Today’s Report</button>
        </form>

                <!-- logout button -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="mt-6 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Logout
                    </button>
                </form>
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

        <!-- Modal for sales -->
        <div id="saleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg w-1/2 shadow-lg relative">
                <h2 class="text-2xl font-bold mb-4">Add New Sale</h2>
                <form action="{{ route('nurse.sale.store') }}" method="POST" id="saleForm">
                    @csrf
                    <div id="saleItemsContainer" class="space-y-4">
                        <!-- First item -->
                        <div class="flex space-x-2 items-center sale-item">
                            <select name="drug_id[]" class="border px-2 py-1 rounded flex-1 drug-select">
                                @foreach($drugs as $drug)
                                    <option value="{{ $drug->id }}" data-price="{{ $drug->unit_price }}">{{ $drug->name }} <!--(Stock: {{ $drug->stock }})--></option>
                                @endforeach
                            </select>
                            <input type="number" name="quantity[]" placeholder="Qty" class="border px-2 py-1 rounded w-24 quantity-input" min="1" value="1">
                            <span class="item-total w-24 text-right font-semibold">0</span>
                            <button type="button" class="text-red-500 remove-item">Remove</button>
                        </div>
                    </div>
                    <button type="button" id="addItemBtn" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mt-2">Add Another Drug</button>

                    <div class="mt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold">Total:</span>
                            <span id="runningTotal" class="font-bold">0</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <label for="payment" class="font-semibold">Amount Paid:</label>
                            <input type="number" id="payment" placeholder="Enter paid amount" class="border px-2 py-1 rounded w-32">
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="font-semibold">Change:</span>
                            <span id="changeAmount" class="font-bold">0</span>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" id="closeModalBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const saleModal = document.getElementById('saleModal');
    const openSaleModalBtn = document.getElementById('openSaleModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const saleItemsContainer = document.getElementById('saleItemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    const runningTotalEl = document.getElementById('runningTotal');
    const paymentInput = document.getElementById('payment');
    const changeAmountEl = document.getElementById('changeAmount');

    function calculateTotals() {
        let total = 0;
        saleItemsContainer.querySelectorAll('.sale-item').forEach(item => {
            const qty = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(item.querySelector('.drug-select').selectedOptions[0].dataset.price) || 0;
            const itemTotal = qty * price;
            item.querySelector('.item-total').textContent = itemTotal.toFixed(2);
            total += itemTotal;
        });
        runningTotalEl.textContent = total.toFixed(2);

        // Calculate change
        const paid = parseFloat(paymentInput.value) || 0;
        const change = paid - total;
        changeAmountEl.textContent = change >= 0 ? change.toFixed(2) : '0.00';
    }

    function attachItemEvents(item) {
        item.querySelector('.quantity-input').addEventListener('input', calculateTotals);
        item.querySelector('.drug-select').addEventListener('change', calculateTotals);
        item.querySelector('.remove-item').addEventListener('click', () => {
            if (saleItemsContainer.querySelectorAll('.sale-item').length > 1) {
                item.remove();
                calculateTotals();
            } else {
                alert("At least one drug is required in the sale.");
            }
        });
    }

    openSaleModalBtn.addEventListener('click', () => saleModal.classList.remove('hidden'));
    closeModalBtn.addEventListener('click', () => saleModal.classList.add('hidden'));

    addItemBtn.addEventListener('click', () => {
        const newItem = saleItemsContainer.querySelector('.sale-item').cloneNode(true);
        newItem.querySelector('.quantity-input').value = 1;
        saleItemsContainer.appendChild(newItem);
        attachItemEvents(newItem);
        calculateTotals();
    });

    // Attach events to initial item
    saleItemsContainer.querySelectorAll('.sale-item').forEach(attachItemEvents);
    paymentInput.addEventListener('input', calculateTotals);

    // Initial total calculation
    calculateTotals();
});

    </script>
</body>
</html>
