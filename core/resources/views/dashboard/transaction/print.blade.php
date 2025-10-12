<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>Orders Report | {{ __('backend.print') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/print.css') }}">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>

@if(count($orders) > 0)
    <div class="container" id="container">
        <h3>Orders Report</h3>
        <p><strong>Total Earnings:</strong> ${{ number_format($totalEarnings, 2) }}</p>

        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Package') }}</th>
                <th>{{ __('Order Type') }}</th>
                <th>{{ __('Customer Name') }}</th>
                <th>{{ __('Customer Email') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('OrderDate') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->slug }}</td>
                    <td>{{ optional($order->{$order->type})->ticketType ?? '-' }}</td>
                    <td>{{ $order->type }}</td>
                    <td>{{ $order->firstName }} {{ $order->lastName }}</td>
                    <td>{{ $order->email }}</td>
                    <td>${{ number_format($order->orderTotal, 2) }}</td>
                    <td>{{ $order->transactionId ? 'Paid' : 'Unpaid' }}</td>
                    <td>{{ $order->orderDate }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @if($stat == 'excel')
        <script src="{{ asset('assets/dashboard/js/xlsx.full.min.js') }}"></script>
        <script>
            var table = document.getElementById('container');
            var wb = XLSX.utils.table_to_book(table, {sheet: "Orders"});
            XLSX.writeFile(wb, 'Orders_Report.xlsx');
            window.onfocus = function () { setTimeout(() => window.close(), 100); }
        </script>
    @elseif($stat == 'pdf')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            var element = document.getElementById('container');
            html2pdf().set({
                margin: 0.5,
                filename: 'Orders_Report.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'A4', orientation: 'landscape' }
            }).from(element).save().then(() => {
                window.onfocus = function () { setTimeout(() => window.close(), 100); };
            });
        </script>
    @else
        <script>
            setTimeout(() => window.print(), 200);
            window.onfocus = function () { setTimeout(() => window.close(), 100); };
        </script>
    @endif
@else
    <div style="padding: 20px; text-align: center;">
        <h4>{{ __('backend.noData') }}</h4>
    </div>
@endif

</body>
</html>
