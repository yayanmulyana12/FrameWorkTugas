<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Products Export</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f4f4f4;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Products</h1>
        <p>Export generated: {{ now()->toDateTimeString() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->name ?? '-' }}</td>
                    <td>{{ isset($p->price) ? number_format($p->price, 0, ',', '.') : '-' }}</td>
                    <td>{{ $p->stock ?? '-' }}</td>
                    <td>{{ optional($p->created_at)->format('Y-m-d') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center">No products found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>