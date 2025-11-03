<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Produk PDF</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 70px;
            position: absolute;
            left: 30px;
            top: 10px;
        }

        .periode {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background-color: #eaeaea;
            font-weight: bold;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
        }

        .ttd {
            text-align: right;
            margin-top: 40px;
        }

        .ttd p {
            margin: 2px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo">
        <h2>PT MILKO BEVERAGE INDUSTRY</h2>
        <h3>REKAP MUTASI STOCK BULANAN</h3>
    </div>

    <div class="periode">
        Periode: {{ $startPeriod }} s/d {{ $endPeriod }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Unit</th>
                <th>Kategori</th>
                <th>Keterangan</th>
                <th>Stok</th>
                <th>Supplier</th>
                <th>Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ $p->type }}</td>
                    <td>{{ $p->information }}</td>
                    <td>{{ $p->qty }}</td>
                    <td>{{ $p->producer }}</td>
                    <td>{{ $p->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        * Data ini bersifat rahasia. Tanggal cetak {{ now()->format('d F Y, H:i') }}.
    </div>

    <div class="ttd">
        <p>Diketahui,</p>
        <br><br><br>
        <p>(____________________)</p>
        <p>Kepala Logistik</p>
    </div>
</body>

</html>