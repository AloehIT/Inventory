<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OpnameDetail;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Opname;
use App\Models\Barang;
use App\Models\Stok;

class OpnameController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Tambah Barang Masuk',
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
            'generate' => Opname::max('id'),

            'barang' => Barang::join('users', 'users.id', '=', 'barangs.user_id')
            ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
            ->select('barangs.*', 'satuans.satuan', 'users.username')
            ->get(),

            'cardbarang' => Barang::join('users', 'users.id', '=', 'barangs.user_id')
            ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
            ->join('detail_barang_masuk', 'detail_barang_masuk.id_barang', '=', 'barangs.id_barang')
            ->select('barangs.*', 'satuans.satuan', 'users.username', 'detail_barang_masuk.tanggal', 'detail_barang_masuk.qty', 'detail_barang_masuk.satuan', 'detail_barang_masuk.id_bm_detail')
            ->get(),
        ];

        return view('inventory.barang-masuk.cubarang-masuk', $data);
    }
}
