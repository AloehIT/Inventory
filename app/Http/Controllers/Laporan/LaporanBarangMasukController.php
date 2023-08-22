<?php

namespace App\Http\Controllers\Laporan;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;


use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Stok;
use App\Models\Barang;

class LaporanBarangMasukController extends Controller
{
    public function getData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $selected_barcode = $request->input('selected_barcode');

        $barang = Stok::Where('sts_inout', +1);

        if ($selected_barcode) {
            $barang->where('id_barang', $selected_barcode);
        } else {
            $barang->where('id_barang', ''); // Filter kosong agar tidak tampil data pada awalnya
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

            $sts_inout = $item->sts_inout == -1 ? '<span style="color: red;"><i class="bi bi-folder-minus"></i> Barang Keluar</span>' : '<span style="color: green;"><i class="bi bi-folder-plus"></i> Barang Masuk</span>';

            return [
                'tanggal' => $item->tanggal,
                'kode_transaksi' => $item->kode_transaksi,
                'nama_barang' => $item->nama_barang,
                'qty' => '+'.$item->qty,
                'keterangan' => $item->keterangan,
                'sts_inout' => $sts_inout,
            ];
        });

        return response()->json(['data' => $data]); // Mengembalikan data dalam format JSON
    }
    public function calculate(Request $request)
    {
        $id_barang = $request->input('id_barang');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $total = Stok::where('id_barang', $id_barang)->where('sts_inout', +1);

        if ($start_date && $end_date) {
            $total->whereBetween('tanggal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $total->where('tanggal', '>=', $start_date);
        } elseif ($end_date) {
            $total->where('tanggal', '<=', $end_date);
        }

        $result = $total->sum(DB::raw('sts_inout * qty'));

        return response()->json(['total' => $result]);
    }

    public function index()
    {
        try{
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'barang' => Barang::all(),
            ];

            return view('inventory.laporan-barang-masuk.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman laporan barang masuk !', 'warning');
            return redirect('app/dashboard');
        }
    }


    public function printPDF(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $selected_barcode = $request->input('selected_barcode');

        $auth = User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first();
        $perusahaan = Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first();

        $barang = Stok::query();

        if ($selected_barcode) {
            $barang->where('id_barang', $selected_barcode);
        } else {
            $barang->where('id_barang', ''); // Filter kosong agar tidak tampil data pada awalnya
        }

        if ($start_date && $end_date) {
            $barang->whereBetween('tanggal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tanggal', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tanggal', '<=', $end_date);
        }

        $data = $barang->get();

        $dompdf = new Dompdf();
        $html = view('inventory.laporan-barang-masuk.print-barang-masuk', ['data' => $data], compact('auth', 'perusahaan', 'start_date', 'end_date'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-stok.pdf', ['Attachment' => false]);
    }
}
