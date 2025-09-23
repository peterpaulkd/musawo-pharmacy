<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report {{ $report->report_date }} - Admin</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <header class="bg-yellow-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Report for {{ $report->report_date->format('Y-m-d') }}</h1>
        <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 bg-gray-200 text-black rounded hover:bg-gray-300">Back</a>
    </header>

    <main class="p-6">
        <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-yellow-100">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Quantity</th>
                    <th class="px-4 py-2 text-left">Total:</th>
                    <th class="px-4 py-2 text-left">Profit:</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->data as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $item['name'] }}</td>
                        <td class="px-4 py-2">{{ $item['qty_sold'] }}</td>
                        <td class="px-4 py-2">UGX {{ $item['total_amount'] }}</td>
                        <td class="px-4 py-2">UGX {{ $item['total_profit'] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-yellow-100 font-bold">
                    <td colspan="2" class="px-4 py-2 text-left">Total</td>
                    <td class="px-4 py-2">UGX {{ $report->grand_total }}</td>
                    <td class="px-4 py-2">UGX {{ $report->total_profit }}</td>
                </tr>
            </tfoot>
        </table>
    </main>

</body>
</html>
