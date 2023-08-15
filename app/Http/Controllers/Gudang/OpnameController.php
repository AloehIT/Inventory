<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Models\OpnameDetail;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Opname;
use App\Models\Barang;
use App\Models\Stok;

class OpnameController extends Controller
{
    public function getOpnameBarang($barcode)
    {
        $opnameBarang = Barang::join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
            ->where('barcode', $barcode)
            ->get();

        return response()->json([
            'opname_barang' => $opnameBarang,
        ]);
    }

    public function getData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = Opname::query(); // Menggunakan query builder untuk membangun query

        // Filter berdasarkan tanggal awal dan akhir jika ada
        if ($start_date && $end_date) {
            $query->whereBetween('tgl_opname', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('tgl_opname', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('tgl_opname', '<=', $end_date);
        }

        $barang = $query->get();

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                return 'Opname';
            })
            ->addColumn('action', function ($row) {
                return view('inventory.opname.actions', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function detailData(Request $request)
    {
        $barang = Opname::join('tbl_opname_detail', 'tbl_opname_detail.id_opname', '=', 'tbl_opname.id_opname')
            ->where('tbl_opname.id_opname', $request->id_opname);

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                return 'Opname';
            })
            ->addColumn('action', function ($row) {
                return view('inventory.opname.actionsdetail', compact('row'))->render();
            })
            ->addColumn('qty', function ($row) {
                return $row->qty.' '.$row->satuan;
            })
            ->addColumn('current_qty', function ($row) {
                if ($row->status === "approve") {
                    if ($row->current_qty == 0) {
                        $opnameDetail = OpnameDetail::join('tbl_stok', 'tbl_stok.id_barang', '=', 'tbl_opname_detail.id_barang')
                            ->where('tbl_stok.id_barang', $row->id_barang)
                            ->select('tbl_stok.qty', 'tbl_stok.sts_inout')
                            ->get();

                        $totalQty = 0;
                        foreach ($opnameDetail as $data) {
                            $totalQty += $data->sts_inout*$data->qty;
                        }
                    } else {
                        $totalQty = $row->current_qty;
                    }

                    return $totalQty.' '.$row->satuan;
                }
                return ''; // Mengembalikan string kosong jika status bukan "approve"
            })
            ->addColumn('total_qty', function ($row) {
                if ($row->status === "approve") {
                    if ($row->current_qty == 0) {
                        $opnameDetail = OpnameDetail::join('tbl_stok', 'tbl_stok.id_barang', '=', 'tbl_opname_detail.id_barang')
                            ->where('tbl_stok.id_barang', $row->id_barang)
                            ->select('tbl_stok.qty', 'tbl_stok.sts_inout')
                            ->get();

                        $totalQty = 0;
                        foreach ($opnameDetail as $data) {
                            $totalQty += $data->sts_inout*$data->qty;
                        }
                    } else {
                        $totalQty = $row->current_qty;
                    }

                    $total = $row->qty - $totalQty;

                    return $total.' '.$row->satuan;
                }
                return ''; // Mengembalikan string kosong jika status bukan "approve"
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('dddd, D MMMM Y');
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
            'opname'   => Opname::all(),
            'daftarbarang'  => Opname::join('tbl_opname_detail', 'tbl_opname_detail.id_opname', '=', 'tbl_opname.id_opname')->get(),
            // 'barang' => Barang::join('tbl_stok', 'tbl_stok.id_barang', '=', 'barangs.id_barang')->select('qty')->get()
        ];


        return view('inventory.opname.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Opname',
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

        return view('inventory.opname.cuopname', $data);
    }

    public function update($id_opname)
    {
        $data = [
            'title' => 'Data Opname Masuk',
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
            ->join('tbl_opname_detail', 'tbl_opname_detail.id_barang', '=', 'barangs.id_barang')
            ->select('barangs.*', 'satuans.satuan', 'users.username', 'tbl_opname_detail.tanggal', 'tbl_opname_detail.qty', 'tbl_opname_detail.satuan', 'tbl_opname_detail.id_opname_detail')
            ->get(),

            'detail'  => Opname::Where('id_opname', $id_opname)->first(),
            'barangstok' => Opname::join('tbl_opname_detail', 'tbl_opname_detail.id_opname', '=', 'tbl_opname.id_opname')
            ->where('tbl_opname.id_opname', $id_opname)->get()
        ];

        $row = Opname::join('tbl_opname_detail', 'tbl_opname_detail.id_opname', '=', 'tbl_opname.id_opname')
        ->where('tbl_opname.id_opname', $id_opname)->get();


        return view('inventory.opname.cuopname', $data, compact('row'));
    }

    public function poststok(Request $request)
    {
        $id = $request['id_opname_detail'];
        $qty = $request->input('qty');

        $stok = OpnameDetail::updateOrCreate(['id_opname_detail' => $id], [
            'qty' => $qty,
        ]);

        $id_opname = $stok->id_opname;
        $cekstok = OpnameDetail::where('id_opname', $id_opname)->select('qty')->get();

        $total_qty = 0;

        foreach ($cekstok as $item) {
            $total_qty += $item->qty;
        }


        $opname = Opname::updateOrCreate(['id_opname' => $id_opname], [
            'total_qty' => $total_qty,
        ]);

        if ($opname) {
            toast('Proses berhasil dilakukan', 'success');
            return redirect()->back();
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }


    public function posts(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'tambahBarang') {
            $validator = Validator::make($request->all(), [
                'id_opname_set' => 'required',
                'tgl_opname' => 'required',
                'id_barang' => 'required',
                'kode_barang' => 'required',
                'barcode' => 'required',
                'nama_barang' => 'required',
                'qty' => 'required|integer|min:1',
                'satuan' => 'required',
            ], [
                'id_opname_set.required' => 'Kode Transaksi harus diisi!',
                'tgl_opname.required' => 'Tanggal Masuk harus diisi!',
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
                toast('Oops terjadi kesalahan', 'warning');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $lastId = $request->has('id') ? $request->input('id') : Opname::max('id') + 1;

            $nullqty = 0;
            $nullsqn = 0;
            $id_opname_set = $request->input('id_opname_set');
            $kode_barang = $request->input('kode_barang');
            $qty = $request->input('qty');

            $no = OpnameDetail::max('id') + 1;
            $id_detail = sprintf("%04s", $no).rand();
            $tanggal = $request->input('tgl_opname');

            $existingItem = OpnameDetail::where('id_opname', $id_opname_set)
            ->where('kode_barang', $kode_barang)
            ->first();

            $cekbarang = OpnameDetail::where('id_opname', $request->id_opname)->first();
            if(!$cekbarang){
                $totalQty = $nullqty += $qty;
                $sequence = $nullsqn += 1;

            }else{
                $opnameBarang = OpnameDetail::Where('id_opname', $request->id_opname)->first();
                $barang = Opname::where('id_opname', $opnameBarang->id_opname)->first();
                $totalQty = $barang->total_qty += $qty;
                if($existingItem){
                    $sequence = $barang->sequenc += 0;
                }else{
                    $sequence = $barang->sequenc += 1;
                }
            }

            if ($existingItem) {
                $existingItem->update([
                    'qty' => $existingItem->qty + $qty,
                ]);

            } else {
                $opname = OpnameDetail::create([
                    'id_opname_detail' => $id_detail,
                    'id' => $no,
                    'tanggal' => $tanggal,
                    'id_opname' => $request->input('id_opname'),
                    'id_barang' => $request->input('id_barang'),
                    'kode_barang' => $request->input('kode_barang'),
                    'qty' => $request->input('qty'),
                    'current_qty' => 0,
                    'satuan' => $request->input('satuan'),
                    'nama_barang' => $request->input('nama_barang'),
                ]);
            }

            $opname = Opname::updateOrCreate(['id_opname' => $request['id_opname_set']], [
                'id' => $lastId,
                'id_opname' => $request->input('id_opname_set'),
                'kode_barang' => $request->input('kode_barang'),
                'tgl_opname' => $request->input('tgl_opname'),
                'total_qty' => $totalQty,
                'sequenc' => $sequence,
                'keterangan' => $request->input('keterangan') ?? 'Tidak ada',
                'user_id' => auth()->user()->id,
                'status' => 'draft',
            ]);

            if ($opname) {
                toast('Proses berhasil dilakukan', 'success');
                return redirect('app/opname/detail/'. $request->id_opname_set);
            } else {
                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        } elseif ($action === 'simpan') {
            $validator = Validator::make($request->all(), [
                'id_opname_transaksi' => 'required',
                'tgl_opname' => 'required',
            ], [
                'id_opname_transaksi.required' => 'Kode Transaksi harus diisi!',
                'tgl_opname.required' => 'Tanggal Masuk harus diisi!',

            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $lastId = $request->has('id') ? $request->input('id') : Opname::max('id') + 1;

            $opname = Opname::updateOrCreate(['id_opname' => $request['id_opname_transaksi']], [
                'id' => $lastId,
                'id_opname' => $request->input('id_opname_transaksi'),
                'kode_barang' => $request->input('kode_barang'),
                'tgl_opname' => $request->input('tgl_opname'),
                'keterangan' => $request->input('keterangan') ?? 'Tidak ada',
                'user_id' => auth()->user()->id,
                'status' => 'draft',
            ]);

            if ($opname) {
                toast('Proses berhasil dilakukan', 'success');
                return redirect('app/opname/detail/'. $request->id_opname_transaksi);
            } else {
                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        } elseif ($action === 'approveStok') {

            $set = false;

            if (is_array($request->id_transaksi)) {
                foreach ($request->id_transaksi as $key => $id_transaksi) {
                    if($request->qty_stok[$key] == 0){

                    }else{
                        $last = Stok::max('id');
                        if($request->qty_stok[$key] > 0){
                            $kodeTransaksi = 'TRX'.'-'.'IN'.'-'.sprintf("%04s", $last).rand();
                            $sts_inout = '+1';
                        }elseif($request->qty_stok[$key] < 0){
                            $kodeTransaksi = 'TRX'.'-'.'OUT'.'-'.sprintf("%04s", $last).rand();
                            $sts_inout = '-1';
                        }

                        $newSet = Stok::create([
                            $lastid = $last + 1,
                            $id_stok = sprintf("%04s", $last).rand(),
                            $kode_transaksi = $kodeTransaksi,

                            'sts_inout' => $sts_inout,
                            'id_transaksi' => $id_transaksi,
                            'id' => $lastid,
                            'id_stok' => $id_stok,
                            'kode_transaksi' => $kode_transaksi,

                            'id_transaksi_detail' => $request->id_transaksi_detail[$key],
                            'id_barang' => $request->id_barang_stok[$key],
                            'kode_barang' => $request->kode_barang_stok[$key],
                            'nama_barang' => $request->nama_barang_stok[$key],
                            'tanggal' => $request->tanggal_stok[$key],
                            'qty' => abs($request->qty_stok[$key]),
                        ]);

                        if ($newSet) {
                            $set = true; // Update the variable if a record is successfully created
                        }
                    }
                }
            } else {
                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }

            if (is_array($request->id_transaksi_detail)) {
                foreach ($request->id_transaksi_detail as $key => $id_transaksi_detail) {
                    $newSet = OpnameDetail::updateOrCreate(['id_opname_detail' => $id_transaksi_detail ], [
                        'current_qty' => $request->current_qty[$key],
                    ]);

                    if ($newSet) {
                        $set = true; // Update the variable if a record is successfully created
                    }
                }
            } else {
                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }

            $set = Opname::updateOrCreate(['id_opname' => $request['id_transaksi'] ], [
                'status' => $request->status,
            ]);


            if ($set) {
                toast('Proses berhasil dilakukan','success');
                return redirect()->back();
            } else {
                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        }


    }

    public function delete($id_opname)
    {
        $opnameDetail = OpnameDetail::Where('id_opname', $id_opname)->first();
        $opname = Opname::Where('id_opname', $id_opname)->first();

        if (!$opnameDetail) {
            $opname->delete();
            toast('Hapus data berhasil dilakukan','success');
            return redirect()->back();
        } else if ($opname) {
            $opnameDetail->delete();
            $opname->delete();
            toast('Hapus data berhasil dilakukan','success');
            return redirect()->back();
        }else {
            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }
}