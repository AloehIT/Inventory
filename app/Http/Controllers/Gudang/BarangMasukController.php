<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\DetailMasuk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangMasuk;
use App\Models\Barang;


class BarangMasukController extends Controller
{

    public function index()
    {
        $data = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan'    => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
            'barangMasuk'   => BarangMasuk::all(),
            'daftarbarang'  => BarangMasuk::join('detail_barang_masuk', 'detail_barang_masuk.id_bm', '=', 'barang_masuks.id_bm')->get(),
        ];

        return view('inventory.barang-masuk.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Barang Masuk',
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
            'generate' => BarangMasuk::max('id'),

            'barang' => Barang::join('users', 'users.id', '=', 'barangs.user_id')
            ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
            ->select('barangs.*', 'satuans.satuan', 'users.username')
            ->get(),
        ];

        return view('inventory.barang-masuk.cubarang-masuk', $data);
    }


    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_bm_transaksi' => 'required',
            'tgl_bm' => 'required',
            'id_barang.*' => 'required',
            'kode_barang.*' => 'required',
            'nama_barang.*' => 'required',
            'qty.*' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ], [
            'id_bm_transaksi.required' => 'Kode Transaksi harus diisi!',
            'tgl_bm.required' => 'Tanggal Masuk harus diisi!',
            'id_barang.*.required' => 'Barang harus dipilih!',
            'kode_barang.*.required' => 'Kode Barang harus diisi!',
            'nama_barang.*.required' => 'Nama Barang harus diisi!',
            'qty.*.required' => 'Qty harus diisi!',
            'qty.*.integer' => 'Qty harus berupa angka!',
            'qty.*.min' => 'Qty minimal 1!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lastId = $request->has('id') ? $request->input('id') : BarangMasuk::max('id') + 1;
        $totalQty = 0;
        $sequence = 0;

        if ($request->has('qty') && $request->has('id_barang')) {
            $totalQty = array_sum($request->input('qty'));
            $sequence = count($request->input('id_barang'));
        }

        if ($request->has('id_bm') && is_array($request->input('id_bm'))) {
            foreach ($request->input('id_bm') as $key => $id_bm) {
                $detail = DetailMasuk::max('id') + 1;
                $id_detail = sprintf("%04s", $detail) . rand();
                $tanggal = $request->input('tgl_bm');

                $newSet = DetailMasuk::create([
                    'id_bm' => $id_bm,
                    'id' => $detail,
                    'tanggal' => $tanggal,
                    'id_bm_detail' => $id_detail,
                    'id_barang' => $request->input('id_barang')[$key],
                    'kode_barang' => $request->input('kode_barang')[$key],
                    'qty' => $request->input('qty')[$key],
                    'nama_barang' => $request->input('nama_barang')[$key],
                ]);

                if ($newSet) {
                    $barangMasuk = true; // Update the variable if a record is successfully created
                }
            }
        }


        $barangMasuk = BarangMasuk::create([
            'id' => $lastId,
            'id_bm' => $request->input('id_bm_transaksi'),
            'kode_barang' => $request->input('kode_barang'),
            'tgl_bm' => $request->input('tgl_bm'),
            'total_qty' => $totalQty,
            'sequenc' => $sequence,
            'deskripsi_barang_masuk' => $request->input('deskripsi'),
            'keterangan' => $request->input('keterangan'),
            'user_id' => auth()->user()->id,
        ]);

        if ($barangMasuk) {
            toast('Proses berhasil dilakukan', 'success');
            return redirect('app/barang-masuk');
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }



    public function deletebarangmasuk($id)
    {
        $barangMasuk = DetailMasuk::Where('id', $id)->first();
        $jumlah = $barangMasuk->qty;

        $barang = BarangMasuk::where('id_bm', $barangMasuk->id_bm)->first();
        if($barang){
            $barang->total_qty -= $jumlah;
            $barang->sequenc -= 1;
            $barang->save();
        }

        if ($barangMasuk) {
            $barangMasuk->delete();
            toast('Hapus data berhasil dilakukan','success');
            return redirect('app/barang-masuk');
        } else {
            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }
}
