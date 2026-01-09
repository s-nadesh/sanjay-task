<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        .row {
            width: 100%;
            clear: both;
        }

        .col {
            float: left;
            box-sizing: border-box;
        }

        .col-4 { width: 33.33%; }
        .col-6 { width: 50%; }
        .col-12 { width: 100%; }

        h2, h4 {
            margin: 0 0 10px 0;
        }

        p {
            margin: 3px 0;
        }

        .invoice-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .invoice-info {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f4f6f9;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 40%;
            float: right;
        }

        .summary td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .summary .label {
            font-weight: bold;
        }

        .summary .final {
            font-size: 16px;
            font-weight: bold;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="invoice-header row">
        <div class="col col-6">
            <h2>Invoice</h2>
        </div>
        <div class="col col-6 text-right">
            <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- FROM / TO / INFO -->
    <div class="invoice-info row">
        <!-- FROM -->
        <div class="col col-4">
            <h4>From</h4>
            <p><strong>Your Company Name</strong></p>
            <p>795 Folsom Ave, Suite 600</p>
            <p>San Francisco, CA 94107</p>
            <p>Phone: +91 99999 99999</p>
            <p>Email: support@company.com</p>
        </div>

        <!-- TO -->
        <div class="col col-4">
            <h4>To</h4>
            <p><strong>{{ $order->user->name }}</strong></p>
            <p>{{ $order->user->address ?? 'Address not available' }}</p>
            <p>Email: {{ $order->user->email }}</p>
            <p>Phone: {{ $order->user->phone ?? '-' }}</p>
        </div>

        <!-- INVOICE DETAILS -->
        <div class="col col-4">
            <h4>Invoice Info</h4>
            <p><strong>Invoice #:</strong> {{ $order->id }}</p>
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Account:</strong> {{ $order->user_id }}</p>
            <p><strong>Status:</strong> Paid</p>
        </div>
    </div>

    <div class="clearfix"></div>

    <!-- ITEMS TABLE -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Product</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->category->name ?? '-' }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SUMMARY -->
    <table class="summary">
        <tr>
            <td class="label">Subtotal</td>
            <td class="text-right">{{ number_format($order->sub_total, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Discount</td>
            <td class="text-right">{{ number_format($order->discount, 2) }}</td>
        </tr>
        <tr>
            <td class="label final">Final Amount</td>
            <td class="text-right final">{{ number_format($order->final_amount, 2) }}</td>
        </tr>
    </table>

    <div class="clearfix"></div>

    <!-- FOOTER -->
    <p><strong>Note:</strong> Thank you for your business.</p>

</div>

</body>
</html>