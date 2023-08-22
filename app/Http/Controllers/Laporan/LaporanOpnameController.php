<?php

namespace App\Http\Controllers\Laporan;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;


use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Opname;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\OpnameDetail;


class LaporanOpnameController extends Controller
{
    public function getData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $selected_barcode = $request->input('selected_barcode');

        $barang = Stok::join('tbl_opname', 'tbl_opname.id_opname', '=', 'tbl_stok.id_transaksi')
            ->leftJoin('tbl_opname_detail', 'tbl_opname_detail.kode_barang', '=', 'tbl_stok.kode_barang')
            ->where('tbl_opname.id_opname', $selected_barcode)
            ->select('tbl_opname.*', 'tbl_opname_detail.nama_barang', 'tbl_opname_detail.current_qty', 'tbl_opname_detail.qty as detail_qty', 'tbl_stok.qty as total_qty', 'tbl_stok.sts_inout', 'tbl_stok.kode_transaksi');

        if ($selected_barcode) {
            $barang->where('tbl_opname_detail.id_opname', $selected_barcode);
        } else {
            $barang->where('tbl_opname_detail.id_opname', '');
        }

        if ($start_date && $end_date) {
            $barang->whereBetween('tbl_opname_detail.tanggal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tbl_opname_detail.tanggal', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tbl_opname_detail.tanggal', '<=', $end_date);
        }

        $data = $barang->get();

        // Ubah data menjadi array asosiatif
        $data = $data->map(function ($item) {
            $hasil = $item->detail_qty - $item->current_qty;
            if($hasil < 0){
                $total_qty = '-'.abs($hasil);
            }else if($hasil > 0){
                $total_qty = '+'.abs($hasil);
            }else{
                $total_qty = abs($hasil);
            }

            $sts_inout = $total_qty == 0 ? '<span style="color: blue;"><i class="bi bi-file-diff-fill"></i> Balance</span>' : ($item->sts_inout == -1 ? '<span style="color: red;"><i class="bi bi-folder-minus"></i> Barang Keluar</span>' : '<span style="color: green;"><i class="bi bi-folder-plus"></i> Barang Masuk</span>');

            return [
                'tanggal' => $item->tgl_opname,
                'kode_transaksi' => $item->kode_transaksi,
                'nama_barang' => $item->nama_barang,
                'detail_qty' => $item->detail_qty,
                'current_qty' => $item->current_qty,
                'total_qty' => $total_qty,
                'stok' => $total_qty + $item->current_qty,
                'keterangan' => $item->keterangan,
                'sts_inout' => $sts_inout,
            ];
        });
        return response()->json(['data' => $data]); // Mengembalikan data dalam format JSON
    }

    public function calculate(Request $request)
    {
        $id_opname = $request->input('id_opname');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $total = Stok::where('id_transaksi', $id_opname);

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
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'opname' => Opname::all(),
            ];

            return view('inventory.laporan-opname.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman laporan opname !', 'warning');
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

        $barang = Opname::join('tbl_opname_detail', 'tbl_opname_detail.id_opname', '=', 'tbl_opname.id_opname.id_opname')
                  ->where('tbl_stok.id_barang', '=', 'tbl_opname_detail.id_barang');

        if ($selected_barcode) {
            $barang->where('tbl_opname.id_opname', $selected_barcode);
        } else {
            $barang->where('tbl_opname.id_opname', ''); // Filter kosong agar tidak tampil data pada awalnya
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
        $html = view('inventory.laporan-opname.print-opname', ['data' => $data], compact('auth', 'perusahaan', 'start_date', 'end_date'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-stok.pdf', ['Attachment' => false]);
    }
}
