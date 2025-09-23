<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report {{ $report->report_date }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #fbd38d; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Daily Report of Musawo</h1>
    <p>For the day - {{ $report->report_date->format('Y-m-d') }}</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['qty_sold'] }}</td>
                    <td>UGX {{ $item['total_amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align:right;">Total</td>
                <td>UGX {{ $report->grand_total }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
