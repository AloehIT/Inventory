<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\RouterosAPI;
use App\Models\Report;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Stok;
use App\Models\Barang;
use DB;

class DashboardController extends Controller
{
    public function getData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $selected_barcode = $request->input('selected_barcode');

        $barang = Stok::query();

        if ($selected_barcode) {
            $barang->where('id_barang', $selected_barcode);
        }

        if ($start_date && $end_date) {
            $barang->whereBetween('tanggal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tanggal', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tanggal', '<=', $end_date);
        }

        $data = $barang->get(); // Menggunakan ->get() untuk mengambil data

        // Ubah data menjadi array asosiatif
        $data = $data->map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'kode_transaksi' => $item->kode_transaksi,
                'nama_barang' => $item->nama_barang,
                'qty' => $item->qty,
                'sts_inout' => $item->sts_inout == -1 ? 'Barang Keluar' : 'Barang Masuk',
            ];
        });

        return response()->json(['data' => $data]); // Mengembalikan data dalam format JSON
    }


    public function calculate(Request $request)
    {
        $id_barang = $request->input('id_barang');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $totalQuery = Stok::query()
            ->selectRaw('SUM(sts_inout * qty) as total_qty')
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('tanggal', [$start_date, $end_date]);
            })
            ->when($start_date, function ($query) use ($start_date) {
                return $query->where('tanggal', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->where('tanggal', '<=', $end_date);
            });

        if ($id_barang !== '') {
            $nama_barang = 'Semua Barang';
        } else {
            $totalQuery->where('id_barang', $id_barang);
            $nama_barang = Stok::find($id_barang)->nama_barang;
        }

        $result = $totalQuery->value('total_qty') ?? '0';

        return response()->json(['namaBarang' => $nama_barang, 'total' => $result]);
    }



    public function index()
    {
        try {
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'barangMasuk'   => Stok::Where('sts_inout', +1)->get(),
                'barangKeluar'  => Stok::Where('sts_inout', -1)->get(),
                'user'          => User::all(),
                'barang'        => Barang::all(),
            ];

            return view('dashboard.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }
}
error_reporting(0);
