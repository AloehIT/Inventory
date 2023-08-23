<!DOCTYPE html>
<html>
<head>
    <title>Print Laporan Stok</title>
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
            width: 150px;
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
    @php
        $nama_barang  = $data->where('id_barang', $selected_barcode)->first();
        $total_stok = $nama_barang->where('id_barang', $selected_barcode)->sum(DB::raw('sts_inout * qty'));
    @endphp
    <div>
        <h2>{{ $perusahaan->value }}</h2>
        <p style="margin-bottom: 0;">Laporan Data Stok Barang : <b>{{ $nama_barang->nama_barang }}</b></p>
        <p style="margin-bottom: 30px;">Tanggal : {{ $start_date }} - {{ $end_date }}</p>
    </div>


    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>
                    @if ($item->sts_inout == -1)
                        Barang Keluar
                    @elseif ($item->sts_inout == 1)
                        Barang Masuk
                    @endif
                </td>
                <td>{{ $item->qty }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Jumlah Stok :</b></td>
                <td><b>{{ $total_stok }}</b></td>
            </tr>
        </tfoot>
    </table>


    <div class="footer">
        <div>
            Dicetak oleh: {{ $auth->nama_users }}<br>
            Tanggal: {{ date('d-m-Y') }}
        </div>
    </div>
</body>
</html>
