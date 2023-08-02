<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\BarangKeluar;
use App\Models\Teknisi;

class BarangKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $data = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),

            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->where('owner_id', auth()->user()->group)->first(),
            'barangKeluar' => BarangKeluar::join('lokasi', 'lokasi.id', '=', 'barang_keluars.lokasi')
            ->join('teknisi', 'teknisi.id', '=', 'barang_keluars.id_teknisi')
            ->leftjoin('barangs', 'barangs.nama_barang', '=', 'barang_keluars.nama_barang')
            ->leftjoin('satuans', 'satuans.id', '=', 'barangs.satuan_id')
            ->leftjoin('users', 'users.id', '=', 'barang_keluars.user_id')
            ->leftjoin('detail_users', 'detail_users.id', '=', 'users.id')
            ->select('barang_keluars.*', 'satuans.satuan', 'lokasi.name', 'teknisi.nama_teknisi', 'detail_users.nama_users', 'users.role')
            ->get(),
        ];

        return view('barang-keluar.index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Barang Keluar',
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->where('owner_id', auth()->user()->group)->first(),

            'lokasi' => Lokasi::get(),
            'barang' => Barang::get(),
            'teknisi' => Teknisi::get(),
        ];

        return view('barang-keluar.cubarang-keluar', $data);
    }

    public function postKeluar(Request $request)
    {
        $this->validate($request, [
            'tanggal_keluar'     => 'required',
            'nama_barang'        => 'required',
            'id_teknisi'        => 'required',
            'lokasi'        => 'required',
            'lokasi_kerja'        => 'required',
            'maps'        => 'required',
            'jumlah_keluar'      => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $nama_barang = $request->nama_barang;
                    $barang = Barang::where('nama_barang', $nama_barang)->first();

                    if ($value > $barang->stok) {
                        $fail("Stok Tidak Cukup !");
                    }
                },
            ],
        ],[
            'tanggal_keluar.required'    => 'Pilih Barang Terlebih Dahulu !',
            'lokasi.required'    => 'Pilih Lokasi Terlebih Dahulu !',
            'maps.required'    => 'Form Maps Wajib Di Isi !',
            'lokasi_kerja.required'    => 'Form Lokasi Kerja Wajib Di Isi !',
            'nama_barang.required'       => 'Form Nama Barang Wajib Di Isi !',
            'jumlah_keluar.required'     => 'Form Jumlah Stok Masuk Wajib Di Isi !',
            'id_teknisi.required'       => 'Pilih Customer !'
        ]);

        $barang = BarangKeluar::max('id');
        $lastid = $barang + 1;

        $barangKeluar = BarangKeluar::create([
            'id'                => $lastid,
            'tanggal_keluar'    => $request->tanggal_keluar,
            'nama_barang'       => $request->nama_barang,
            'lokasi'            => $request->lokasi,
            'lokasi_kerja'      => $request->lokasi_kerja,
            'maps'              => $request->maps,
            'jumlah_keluar'     => $request->jumlah_keluar,
            'id_teknisi'        => $request->id_teknisi,
            'kode_transaksi'    => $request->kode_transaksi,
            'deskripsi_barang_keluar'    => $request->deskripsi,
            'user_id'           => auth()->user()->id
        ]);

        if ($barangKeluar) {
            $barang = Barang::where('nama_barang', $request->nama_barang)->first();
            if ($barang) {
                $barang->stok -= $request->jumlah_keluar;
                $barang->save();
            }
        }

        if ($barangKeluar) {
            toast('Proses berhasil dilakukan','success');
            return redirect()->route('barangkeluar.inventory');
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }

    public function destroy($kode_transaksi)
    {
        $barangKeluar = BarangKeluar::Where('kode_transaksi', $kode_transaksi)->first();
        $jumlahKeluar = $barangKeluar->jumlah_keluar;

        $barang = Barang::where('nama_barang', $barangKeluar->nama_barang)->first();
        if($barang){
            $barang->stok += $jumlahKeluar;
            $barang->save();
        }

        if ($barangKeluar) {
            $barangKeluar->delete();
            toast('Hapus data berhasil dilakukan','success');
            return redirect()->route('barangkeluar.inventory');
        } else {
            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }

}
