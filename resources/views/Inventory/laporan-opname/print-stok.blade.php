<!DOCTYPE html>
<html>
<head>
    <title>Print Laporan Opname</title>
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
            font-size: 11px;
        }
    </style>
</head>
<body>
    @php
        $total_stok = $data->sum(DB::raw('sts_inout * qty'))
    @endphp
    <div>
        <h2>{{ $perusahaan->value }}</h2>
        <p style="margin-bottom: 0;">Laporan Data Opname</p>
        <p style="margin-bottom: 0;">ID Opname : {{ $selected_barcode }}</p>
        <p style="margin-bottom: 30px;">Tanggal : {{ $start_date }} - {{ $end_date }}</p>
    </div>


    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Transaksi</th>
                <th>Nama Barang</th>
                <th>
                    Opname Qty
                </th>

                <th>
                    Qty Sebelumnya
                </th>

                <th>
                    Hasil Opname
                </th>
                <th>Kategori</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            @php
                $hasil = $item->detail_qty - $item->current_qty;
                if($hasil < 0){
                    $total_qty = '-'.abs($hasil);
                }else if($hasil > 0){
                    $total_qty = '+'.abs($hasil);
                }else{
                    $total_qty = abs($hasil);
                }
            @endphp
            <tr>
                <td>{{ $item->tgl_opname }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->detail_qty }}</td>
                <td>{{ $item->current_qty }}</td>
                <td>{{ $total_qty }}</td>
                <td>
                    @if ($item->sts_inout == -1)
                        Barang Keluar
                    @elseif ($item->sts_inout == 1)
                        Barang Masuk
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <div class="footer">
        <div>
            Dicetak oleh: {{ $auth->nama_users }}<br>
            Tanggal: {{ date('d-m-Y') }}
        </div>
    </div>
</body>
</html>
