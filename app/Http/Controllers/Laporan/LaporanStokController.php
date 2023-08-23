<?php

namespace App\Http\Controllers\Laporan;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;
use App\Exports\StokExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Stok;
use App\Models\Barang;


class LaporanStokController extends Controller
{
    public function getData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $selected_barcode = $request->input('selected_barcode');

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

        $data = $barang->get(); // Menggunakan ->get() untuk mengambil data

        // Ubah data menjadi array asosiatif
        $data = $data->map(function ($item) {
            $status = $item->sts_inout;
            if($status == -1){
                $qty = '-'.abs($item->qty);
            }else if($status == 1){
                $qty = '+'.abs($item->qty);
            }

            $sts_inout = $item->sts_inout == -1 ? '<span style="color: red;"><i class="bi bi-folder-minus"></i> Barang Keluar</span>' : '<span style="color: green;"><i class="bi bi-folder-plus"></i> Barang Masuk</span>';

            return [
                'tanggal' => $item->tanggal,
                'kode_transaksi' => $item->kode_transaksi,
                'nama_barang' => $item->nama_barang,
                'qty' => $qty,
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

        $total = Stok::where('id_barang', $id_barang);

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

    public function index(){
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'kartu stok')
        ->first();

        try{
            if (!$cekPermission) {
                toast('Halaman tidak ditemukan', 'warning');
                return redirect('app/dashboard');
            }

            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'barang' => Barang::all(),
            ];

            return view('inventory.laporan-stok.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman laporan stok !', 'warning');
            return redirect('app/dashboard');
        }
    }

    public function printPDF(Request $request) {
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
        }

        if ($start_date && $end_date) {
            $barang->whereBetween('tanggal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tanggal', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tanggal', '<=', $end_date);
        }

        if (!$start_date && !$end_date) {
            $start_date = now()->toDateString();
            $end_date = now()->toDateString();
        }

        $data = $barang->get();

        // Check if data is empty
        if ($data->isEmpty()) {
            // return response()->json(['message' => 'Data kosong'], 400);
            toast('Data stok kosong !!', 'warning');
            return redirect()->back();
        }

        $dompdf = new Dompdf();
        $html = view('inventory.laporan-stok.print-stok', ['data' => $data, 'auth' => $auth, 'perusahaan' => $perusahaan, 'start_date' => $start_date, 'end_date' => $end_date, 'selected_barcode' => $selected_barcode])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Simpan PDF ke dalam variable
        $output = $dompdf->output();

        // Beri nama file PDF sesuai keinginan
        $filename = 'laporan-stok.pdf';

        // Mengirimkan file PDF sebagai respons
        return response($output, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function exportExcel(Request $request)
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
        }

        if ($start_date && $end_date) {
            $barang->whereBetween('tanggal', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tanggal', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tanggal', '<=', $end_date);
        }

        if (!$start_date && !$end_date) {
            $start_date = now()->toDateString();
            $end_date = now()->toDateString();
        }

        $data = $barang->get();

        if ($data->isEmpty()) {
            toast('Data stok kosong !!', 'warning');
            return redirect()->back();
        }

        $view = View::make('inventory.laporan-stok.export-stok', ['data' => $data, 'auth' => $auth, 'perusahaan' => $perusahaan, 'start_date' => $start_date, 'end_date' => $end_date, 'selected_barcode' => $selected_barcode]);

        return Excel::download(new StokExport($view), 'laporan-stok.xlsx');
    }

}
