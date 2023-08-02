@inject('carbon', 'Carbon\Carbon')
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
        <h2 style="margin-bottom: 0;">{{ $perusahaan->value }}</h2>
        <p style="margin-bottom: 0;">Laporan Barang Keluar</p>
        @if ($tanggalMulai && $tanggalSelesai)
            <p style="margin-bottom: 0;">Rentang Tanggal : {{ $tanggalMulai }} - {{ $tanggalSelesai }}<p>
        @else
            <p style="margin-bottom: 30px;">Rentang Tanggal : Semua</p>
        @endif
    </div>


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Masuk</th>
                <th>Nama Barang</th>
                <th>Stok Masuk</th>
                <th>Gudang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $carbon::parse($item['tanggal_masuk'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}</td>
                <td>{{ $item->nama_barang}} </td>
                <td>{{ $item->jumlah_masuk}} {{ $item->satuan}}</td>
                <td>{{ $item->name}} </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ $auth->nama_users }}<br>
        Tanggal: {{ $carbon::now()->isoFormat('dddd, D MMMM Y h:m A') }}
    </div>
</body>
</html>
