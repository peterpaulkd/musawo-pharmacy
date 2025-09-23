<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Reports - Admin</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<!-- peterpaulkd - 0701902945 - for systems -->
<main class="flex-1 p-8 space-y-8">
    <header class="bg-yellow-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Daily Reports</h1>
        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 bg-gray-200 text-black rounded hover:bg-gray-300">Back</a>
    </header>

    <main class="p-6">
        <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-yellow-100">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Total Amount</th>
                    <th class="px-4 py-2 text-left">Total Profit</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $report->report_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">UGX {{ $report->grand_total}}</td>
                        <td class="px-4 py-2">UGX {{ $report->total_profit}}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">View</a>
                            <a href="{{ route('admin.reports.download', $report->id) }}" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Download PDF</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
    </main>

</body>
</html>
