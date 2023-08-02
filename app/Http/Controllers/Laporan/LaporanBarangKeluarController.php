<?php

namespace App\Http\Controllers\Laporan;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangKeluar;

class LaporanBarangKeluarController extends Controller
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

        return view('laporan-barang-keluar.index', $data);
    }

    public function getData(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $barangKeluar = BarangKeluar::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $barangKeluar->join('lokasi', 'lokasi.id', '=', 'barang_keluars.lokasi')->join('teknisi', 'teknisi.id', '=', 'barang_keluars.id_teknisi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_keluars.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        }

        $data = $barangKeluar->get();

        if (empty($tanggalMulai) && empty($tanggalSelesai)) {
            $data = BarangKeluar::join('lokasi', 'lokasi.id', '=', 'barang_keluars.lokasi')->join('teknisi', 'teknisi.id', '=', 'barang_keluars.id_teknisi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_keluars.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->get();
        }

        return response()->json($data);
    }

    /**
     * Print DomPDF
     */
    public function printBarangKeluar(Request $request)
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

        $barangKeluar = BarangKeluar::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $barangKeluar->join('lokasi', 'lokasi.id', '=', 'barang_keluars.lokasi')->join('teknisi', 'teknisi.id', '=', 'barang_keluars.id_teknisi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_keluars.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        }

        if ($tanggalMulai !== null && $tanggalSelesai !== null) {
            $data = $barangKeluar->get();
        } else {
            $data = BarangKeluar::join('lokasi', 'lokasi.id', '=', 'barang_keluars.lokasi')->join('teknisi', 'teknisi.id', '=', 'barang_keluars.id_teknisi')->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_keluars.nama_barang')->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')->get();
        }

        //Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-barang-keluar/print-barang-keluar', compact('data', 'tanggalMulai', 'tanggalSelesai'), $info)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-barang-keluar.pdf', ['Attachment' => false]);
    }

}
