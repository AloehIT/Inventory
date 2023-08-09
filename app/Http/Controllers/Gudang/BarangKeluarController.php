<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Str;

use App\Models\DetailKeluar;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangKeluar;
use App\Models\Barang;

class BarangKeluarController extends Controller
{
    public function barangkeluarData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $barang = BarangKeluar::all();

        // Filter berdasarkan tanggal awal dan akhir jika ada
        if ($start_date && $end_date) {
            $barang->whereBetween('tgl_bm', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tgl_bm', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tgl_bm', '<=', $end_date);
        }

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return 'Barang Keluar';
            })
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.barang-masuk.actions', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('dddd, D MMMM Y');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function detailData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $barang = BarangKeluar::join('detail_barang_keluar', 'detail_barang_keluar.id_bk', '=', 'barang_keluars.id_bk')
        ->where('barang_keluars.id_bk', $request->id_bk);


        // Filter berdasarkan tanggal awal dan akhir jika ada
        if ($start_date && $end_date) {
            $barang->whereBetween('tgl_bm', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tgl_bm', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tgl_bm', '<=', $end_date);
        }

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return 'Barang Keluar';
            })
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.barang-masuk.actionsdetail', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('dddd, D MMMM Y');
            })
            ->editColumn('qty', function ($row) {
                return $row->qty.' '.$row->satuan;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        $data = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan'    => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
            'barangKeluar'  => BarangKeluar::all(),
            'daftarbarang'  => BarangKeluar::join('detail_barang_keluar', 'detail_barang_keluar.id_bk', '=', 'barang_keluars.id_bk')->get(),
        ];

        return view('inventory.barang-keluar.index', $data);
    }
}
