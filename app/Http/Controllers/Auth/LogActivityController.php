<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\LogActivity;
use DB;

class LogActivityController extends Controller
{
    public function getData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $selected_barcode = $request->input('selected_barcode');

        if(auth()->user()->role == 'Super Admin'){
            $log = LogActivity::query();
        }else{
            $log = LogActivity::where('id_user', auth()->user()->id);
        }

        if ($selected_barcode) {
            $log->where('id_user', $selected_barcode);
        }

        if ($start_date && $end_date) {
            $log->whereBetween('time_log', [$start_date, $end_date]);
        } elseif ($start_date) {
            $log->where('time_log', '>=', $start_date);
        } elseif ($end_date) {
            $log->where('time_log', '<=', $end_date);
        }

        $data = $log->get(); // Menggunakan ->get() untuk mengambil data

        // Ubah data menjadi array asosiatif
        $data = $data->map(function ($item) {
            return [
                'time_log' => $item->time_log,
                'ip_address' => $item->ip_address,
                'username' => $item->username,
                'keterangan' => $item->keterangan,
            ];
        });

        return response()->json(['data' => $data]); // Mengembalikan data dalam format JSON
    }

    public function index()
    {
        try {
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'user' => User::all()

            ];
            return view('log', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman log activity !', 'warning');
            return redirect('app/dashboard');
        }
    }
}
