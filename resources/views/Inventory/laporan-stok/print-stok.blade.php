<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div>
        <h2>{{ $perusahaan->value }}</h2>
        <p style="margin-bottom: 0;">Laporan Stok</p>
        <p style="margin-bottom: 30px;">Keterangan : {{ $selectedOption }}</p>
    </div>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok</th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $barang)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $barang->kode_barang }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->stok}}  {{ $barang->satuan }}</td>
                <td>{{ $barang->kategori}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ $auth->nama_users }}<br>
        Tanggal: {{ date('d-m-Y') }}
    </div>
</body>
</html>
