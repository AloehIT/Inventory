<?php

namespace App\Http\Controllers\Laporan;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangMasuk;

class LaporanBarangMasukController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $data = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->leftjoin('detail_alamat', 'detail_alamat.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->where('owner_id', auth()->user()->group)->first(),
        ];

        return view('laporan-barang-masuk.index', $data);
    }


    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $barangMasuk = BarangMasuk::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $barangMasuk->join('lokasi', 'lokasi.id', '=', 'barang_masuks.lokasi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_masuks.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
        }

        $data = $barangMasuk->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = BarangMasuk::join('lokasi', 'lokasi.id', '=', 'barang_masuks.lokasi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_masuks.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->get();
        }

        return response()->json($data);
    }


    public function printBarangMasuk(Request $request)
    {
        $info = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->leftjoin('detail_alamat', 'detail_alamat.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->where('owner_id', auth()->user()->group)->first(),
        ];

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $barangMasuk = BarangMasuk::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $barangMasuk->join('lokasi', 'lokasi.id', '=', 'barang_masuks.lokasi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_masuks.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->whereBetween('tanggal_masuk', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $data = $barangMasuk->get();
        } else {
            $data = BarangMasuk::join('lokasi', 'lokasi.id', '=', 'barang_masuks.lokasi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_masuks.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->get();
        }

        //Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-barang-masuk/print-barang-masuk', compact('data', 'tanggalMulai', 'tanggalSelesai'), $info)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-barang-masuk.pdf', ['Attachment' => false]);
    }
}
