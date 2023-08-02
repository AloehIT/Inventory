<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\RouterosAPI;
use App\Models\Report;
use App\Models\User;
use App\Models\Transaksi;

class DashboardController extends Controller
{
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
            ];

            return view('dashboard.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }
}
error_reporting(0);
