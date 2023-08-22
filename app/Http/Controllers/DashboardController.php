<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Stok;
use App\Models\Barang;
use App\Models\roleHasPermission;

use DB;
use Gate;

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
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'pengaturan')
        ->first();

        try {
            if (!$cekPermission) {
                toast('Role user tidak mendapatkan akses!', 'warning');
                return redirect('app/dashboard');
            }
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->first(),

                'barangMasuk'   => Stok::Where('sts_inout', +1)->get(),
                'barangKeluar'  => Stok::Where('sts_inout', -1)->get(),
                'user'          => User::all(),
                'barang'        => Barang::all(),
            ];

            return view('dashboard.index', $data, compact('cekPermission'));
        } catch (\Throwable $e) {
            auth()->logout();
            toast('Anda tidak bisa masuk ke sistem !','warning');
            return redirect()->route('login');
        }
    }
}
error_reporting(0);
