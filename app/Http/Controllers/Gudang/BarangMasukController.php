<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\DetailMasuk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Str;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangMasuk;
use App\Models\Barang;


class BarangMasukController extends Controller
{

    public function barangmasukData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $barang = BarangMasuk::all();

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
                return 'Barang Masuk';
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

        $barang = BarangMasuk::join('detail_barang_masuk', 'detail_barang_masuk.id_bm', '=', 'barang_masuks.id_bm')
        ->where('barang_masuks.id_bm', $request->id_bm);


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
                return 'Barang Masuk';
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

    public function update($id_bm)
    {
        $data = [
            'title' => 'Data Barang Masuk',
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
            'generate' => BarangMasuk::max('id'),

            'barang' => Barang::join('users', 'users.id', '=', 'barangs.user_id')
            ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
            ->select('barangs.*', 'satuans.satuan', 'users.username')
            ->get(),

            'detail'  => BarangMasuk::Where('id_bm', $id_bm)->first(),
        ];

        return view('inventory.barang-masuk.cubarang-masuk', $data);
    }


    public function posts(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'id_bm_transaksi' => 'required',
            'tgl_bm' => 'required',
            'id_barang' => 'required',
            'kode_barang' => 'required',
            'barcode' => 'required',
            'nama_barang' => 'required',
            'keterangan' => 'required',
            'qty' => 'required|integer|min:1',
            'satuan' => 'required',
        ], [
            'id_bm_transaksi.required' => 'Kode Transaksi harus diisi!',
            'tgl_bm.required' => 'Tanggal Masuk harus diisi!',
            'keterangan.required' => 'Keterangan Transaksi harus diisi',
            'barcode.required' => 'Barang harus dipilih!',
            'id_barang.required' => 'Barang harus dipilih!',
            'kode_barang.required' => 'Kode Barang harus diisi!',
            'nama_barang.required' => 'Nama Barang harus diisi!',
            'satuan.required' => 'Nama Barang harus diisi!',
            'qty.required' => 'Qty harus diisi!',
            'qty.integer' => 'Qty harus berupa angka!',
            'qty.min' => 'Qty minimal 1!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lastId = $request->has('id') ? $request->input('id') : BarangMasuk::max('id') + 1;

        $nullqty = 0;
        $nullsqn = 0;
        $qty = $request->input('qty');

        $no = DetailMasuk::max('id') + 1;
        $id_detail = sprintf("%04s", $no).rand();
        $tanggal = $request->input('tgl_bm');

        $existingItem = DetailMasuk::where('id_bm', $request->input('id_bm'))
        ->where('kode_barang', $request->input('kode_barang'))
        ->first();

        $cekbarang = DetailMasuk::where('id_bm', $request->id_bm)->first();
        if(!$cekbarang){
            $totalQty = $nullqty += $qty;
            $sequence = $nullsqn += 1;

        }else{
            $barangMasuk = DetailMasuk::Where('id_bm', $request->id_bm)->first();
            $barang = BarangMasuk::where('id_bm', $barangMasuk->id_bm)->first();
            $totalQty = $barang->total_qty += $qty;
            if($existingItem){
                $sequence = $barang->sequenc += 0;
            }else{
                $sequence = $barang->sequenc += 1;
            }
        }

        $existingItem = DetailMasuk::where('id_bm', $request->input('id_bm'))
        ->where('kode_barang', $request->input('kode_barang'))
        ->first();


        if ($existingItem) {
            $existingItem->update([
                'qty' => $existingItem->qty + $qty,
            ]);

        } else {
            $barangMasuk = DetailMasuk::create([
                'id_bm_detail' => $id_detail,
                'id' => $no,
                'tanggal' => $tanggal,
                'id_bm' => $request->input('id_bm'),
                'id_barang' => $request->input('id_barang'),
                'kode_barang' => $request->input('kode_barang'),
                'qty' => $request->input('qty'),
                'satuan' => $request->input('satuan'),
                'nama_barang' => $request->input('nama_barang'),
            ]);
        }

        $barangMasuk = BarangMasuk::updateOrCreate(['id_bm' => $request['id_bm_transaksi']], [
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
            return redirect('app/barang-masuk/detail/'. $request->id_bm_transaksi);
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }

    public function poststok(Request $request)
    {
        $id = $request['id_bm_detail'];
        $qty = $request->input('qty');

        $stok = DetailMasuk::updateOrCreate(['id_bm_detail' => $id], [
            'qty' => $qty,
        ]);

        $id_bm = $stok->id_bm;
        $barangMasuk = BarangMasuk::updateOrCreate(['id_bm' => $id_bm], [
            'total_qty' => $qty,
        ]);

        if ($barangMasuk) {
            toast('Proses berhasil dilakukan', 'success');
            return redirect()->back();
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
            return redirect()->back();
        } else {
            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }


    public function delete($id_bm)
    {
        $detailbarangMasuk = DetailMasuk::Where('id_bm', $id_bm)->first();
        $barangMasuk = BarangMasuk::Where('id_bm', $id_bm)->first();

        if ($barangMasuk) {
            $detailbarangMasuk->delete();
            $barangMasuk->delete();
            toast('Hapus data berhasil dilakukan','success');
            return redirect()->back();
        } else {
            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }
}



