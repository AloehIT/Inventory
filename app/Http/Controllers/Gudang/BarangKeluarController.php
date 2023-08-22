<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Str;
use DB;

use App\Models\DetailKeluar;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangKeluar;
use App\Models\Barang;
use App\Models\Stok;

class BarangKeluarController extends Controller
{
    public function barangkeluarData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = BarangKeluar::query(); // Menggunakan query builder untuk membangun query

        // Filter berdasarkan tanggal awal dan akhir jika ada
        if ($start_date && $end_date) {
            $query->whereBetween('tgl_bk', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('tgl_bk', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('tgl_bk', '<=', $end_date);
        }

        $barang = $query->get();

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                return 'Barang Keluar';
            })
            ->addColumn('action', function ($row) {
                return view('inventory.barang-keluar.actions', compact('row'))->render();
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

        $barang = BarangKeluar::join('detail_barang_keluar', 'detail_barang_keluar.id_bk', '=', 'barang_keluars.id_bk')
        ->where('barang_keluars.id_bk', $request->id_bk);


        // Filter berdasarkan tanggal awal dan akhir jika ada
        if ($start_date && $end_date) {
            $barang->whereBetween('tgl_bk', [$start_date, $end_date]);
        } elseif ($start_date) {
            $barang->where('tgl_bk', '>=', $start_date);
        } elseif ($end_date) {
            $barang->where('tgl_bk', '<=', $end_date);
        }

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return 'Barang Keluar';
            })
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.barang-keluar.actionsdetail', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('Y-MM-DD');
            })
            ->editColumn('qty', function ($row) {
                return $row->qty.' '.$row->satuan;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        try {
            $stokCount = Stok::count();

            if ($stokCount === 0) {
                toast('Oops stok masih kosong !', 'warning');
                return redirect('app/barang-masuk');
            }

            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->first(),

                'perusahaan'    => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'barangKeluar'  => BarangKeluar::all(),
                'daftarbarang'  => BarangKeluar::join('detail_barang_keluar', 'detail_barang_keluar.id_bk', '=', 'barang_keluars.id_bk')->get(),
            ];

            return view('inventory.barang-keluar.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman barang keluar !', 'warning');
            return redirect('app/dashboard');
        }
    }

    public function create()
    {
        try {
            $data = [
                'title' => 'Tambah Barang Keluar',

                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'generate' => BarangKeluar::max('id'),

                'barang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->select('barangs.*', 'satuans.satuan', 'users.username')
                ->get(),

                'cardbarang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->join('detail_barang_keluar', 'detail_barang_keluar.id_barang', '=', 'barangs.id_barang')
                ->select('barangs.*', 'satuans.satuan', 'users.username', 'detail_barang_keluar.tanggal', 'detail_barang_keluar.qty', 'detail_barang_keluar.satuan', 'detail_barang_keluar.id_bk_detail')
                ->get(),
            ];

            return view('inventory.barang-keluar.cubarang-keluar', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman tambah barang keluar !', 'warning');
            return redirect('app/barang-keluar');
        }
    }

    public function update($id_bk)
    {
        try {
            $data = [
                'title' => 'Data Barang Keluar',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'generate' => BarangKeluar::max('id'),

                'barang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->select('barangs.*', 'satuans.satuan', 'users.username')
                ->get(),

                'cardbarang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->join('detail_barang_keluar', 'detail_barang_keluar.id_barang', '=', 'barangs.id_barang')
                ->select('barangs.*', 'satuans.satuan', 'users.username', 'detail_barang_keluar.tanggal', 'detail_barang_keluar.qty', 'detail_barang_keluar.satuan', 'detail_barang_keluar.id_bk_detail')
                ->get(),

                'detail'  => BarangKeluar::Where('id_bk', $id_bk)->first(),
                'barangstok' => BarangKeluar::join('detail_barang_keluar', 'detail_barang_keluar.id_bk', '=', 'barang_keluars.id_bk')
                ->where('barang_keluars.id_bk', $id_bk)->get()
            ];

            return view('inventory.barang-keluar.cubarang-keluar', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman data barang keluar !', 'warning');
            return redirect('app/barang-keluar');
        }

    }

    public function posts(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'tambahBarang') {
            $validator = Validator::make($request->all(), [
                'id_bk_transaksi' => 'required',
                'tgl_bk' => 'required',
                'id_barang' => 'required',
                'kode_barang' => 'required',
                'barcode' => 'required',
                'nama_barang' => 'required',
                'qty' => 'required',
                'satuan' => 'required',
            ], [
                'id_bk_transaksi.required' => 'Kode Transaksi harus diisi!',
                'tgl_bk.required' => 'Tanggal Masuk harus diisi!',
                'barcode.required' => 'Barang harus dipilih!',
                'id_barang.required' => 'Barang harus dipilih!',
                'kode_barang.required' => 'Kode Barang harus diisi!',
                'nama_barang.required' => 'Nama Barang harus diisi!',
                'satuan.required' => 'Nama Barang harus diisi!',
                'qty.required' => 'Qty harus diisi!',
            ]);

            if ($validator->fails()) {
                toast('Oops terjadi kesalahan', 'warning');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $lastId = $request->has('id') ? $request->input('id') : BarangKeluar::max('id') + 1;

            $nullqty = 0;
            $nullsqn = 0;
            $qty = $request->input('qty');
            $kode_barang = $request->kode_barang;

            $no = DetailKeluar::max('id') + 1;
            $id_detail = sprintf("%04s", $no).rand();
            $tanggal = $request->input('tgl_bk');


            $lastId = $request->has('id') ? $request->input('id') : BarangKeluar::max('id') + 1;
            $nullqty = 0;
            $nullsqn = 0;

            $no = DetailKeluar::max('id') + 1;
            $id_detail = sprintf("%04s", $no) . rand();
            $tanggal = $request->input('tgl_bk');

            $existingItem = DetailKeluar::where('id_bk', $request->input('id_bk'))
                ->where('kode_barang', $request->input('kode_barang'))
                ->first();

            $cekbarang = DetailKeluar::where('id_bk', $request->id_bk)->first();

            if (!$cekbarang) {
                $totalQty = $nullqty += $qty;
                $sequence = $nullsqn += 1;
            } else {
                $barangKeluar = DetailKeluar::Where('id_bk', $request->id_bk)->first();
                $barang = BarangKeluar::where('id_bk', $barangKeluar->id_bk)->first();
                $totalQty = $barang->total_qty += $qty;

                if ($existingItem) {
                    $sequence = $barang->sequenc += 0;
                } else {
                    $sequence = $barang->sequenc += 1;
                }
            }

            $cekStok = Stok::where('kode_barang', $kode_barang)->sum(DB::raw('sts_inout * qty'));
            // $totalQty += $cekStok;

            if ($cekStok === null || $cekStok < $totalQty) {
                toast('Oops stok tidak cukup !', 'warning');
                return redirect()->back()->with('error', 'Stok Tidak Cukup !');
            }


            if ($existingItem) {
                $existingItem->update([
                    'qty' => $existingItem->qty + $qty,
                ]);
            } else {
                $barangKeluar = DetailKeluar::create([
                    'id_bk_detail' => $id_detail,
                    'id' => $no,
                    'tanggal' => $tanggal,
                    'id_bk' => $request->input('id_bk'),
                    'id_barang' => $request->input('id_barang'),
                    'kode_barang' => $request->input('kode_barang'),
                    'qty' => $request->input('qty'),
                    'satuan' => $request->input('satuan'),
                    'nama_barang' => $request->input('nama_barang'),
                ]);
            }

            $barangKeluar = BarangKeluar::updateOrCreate(['id_bk' => $request['id_bk_transaksi']], [
                'id' => $lastId,
                'id_bk' => $request->input('id_bk_transaksi'),
                'kode_barang' => $request->input('kode_barang'),
                'tgl_bk' => $request->input('tgl_bk'),
                'total_qty' => $totalQty,
                'sequenc' => $sequence,
                'deskripsi_barang_masuk' => $request->input('deskripsi'),
                'keterangan' => $request->input('keterangan') ?? 'Tidak ada',
                'user_id' => auth()->user()->id,
                'status' => 'draft',
            ]);


            if ($barangKeluar) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'berhasil menambahkan barang :'.' '.$request->nama_barang.', dengan jumlah stok : '.$request->qty.' kedalam transaksi barang keluar'.' '. $request->id_bk_transaksi;
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);
                DB::table('log_activity')->insert($data);

                toast('Proses berhasil dilakukan', 'success');
                return redirect('app/barang-keluar/detail/'. $request->id_bk_transaksi);
            } else {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'berhasil menambahkan barang :'.' '.$request->nama_barang.', dengan jumlah stok : '.$request->qty.' kedalam transaksi barang keluar'.' '. $request->id_bk_transaksi;
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);
                DB::table('log_activity')->insert($data);

                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        } elseif ($action === 'simpan') {
            $validator = Validator::make($request->all(), [
                'id_bk_transaksi' => 'required',
                'tgl_bk' => 'required',
            ], [
                'id_bk_transaksi.required' => 'Kode Transaksi harus diisi!',
                'tgl_bk.required' => 'Tanggal Masuk harus diisi!',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $lastId = $request->has('id') ? $request->input('id') : BarangKeluar::max('id') + 1;


            $barangKeluar = BarangKeluar::updateOrCreate(['id_bk' => $request['id_bk_transaksi']], [
                'id' => $lastId,
                'id_bk' => $request->input('id_bk_transaksi'),
                'kode_barang' => $request->input('kode_barang'),
                'tgl_bk' => $request->input('tgl_bk'),
                'deskripsi_barang_masuk' => $request->input('deskripsi'),
                'keterangan' => $request->input('keterangan') ?? 'Tidak ada',
                'user_id' => auth()->user()->id,
                'status' => 'draft',
            ]);


            if ($barangKeluar) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'berhasil menyimpan data transaksi barang keluar'.' '. $request->id_bk_transaksi;
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);
                DB::table('log_activity')->insert($data);

                toast('Proses berhasil dilakukan', 'success');
                return redirect('app/barang-keluar/detail/'. $request->id_bk_transaksi);
            } else {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'gagal menyimpan data transaksi barang masuk'.' '. $request->id_bk_transaksi;
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);
                DB::table('log_activity')->insert($data);

                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        } elseif ($action === 'approveStok') {
            $set = false;

            if (is_array($request->sts_inout)) {
                foreach ($request->sts_inout as $key => $sts_inout) {
                    $newSet = Stok::create([
                        $last = Stok::max('id'),
                        $lastid = $last + 1,
                        $id_stok = sprintf("%04s", $last).rand(),
                        $kode_transaksi = 'TRX'.'-'.'IN'.'-'.sprintf("%04s", $last).rand(),

                        'sts_inout' => $sts_inout,
                        'id' => $lastid,
                        'id_stok' => $id_stok,
                        'kode_transaksi' => $kode_transaksi,
                        'keterangan' => $request->stok_keterangan[$key],

                        'id_transaksi' => $request->id_transaksi[$key],
                        'id_transaksi_detail' => $request->id_transaksi_detail[$key],
                        'id_barang' => $request->id_barang_stok[$key],
                        'kode_barang' => $request->kode_barang_stok[$key],
                        'nama_barang' => $request->nama_barang_stok[$key],
                        'tanggal' => $request->tanggal_stok[$key],
                        'qty' => $request->qty_stok[$key],
                    ]);

                    if ($newSet) {
                        $set = true; // Update the variable if a record is successfully created
                    }
                }
            } else {
                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }

            $set = BarangKeluar::updateOrCreate(['id_bk' => $request['id_transaksi'] ], [
                'status' => $request->status,
            ]);

            if ($set) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'Berhasil memasukan data transksi :'.' '. $request->id_bk_transaksi.' '.'kedalam daftar stok';
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);

                DB::table('log_activity')->insert($data);

                toast('Proses berhasil dilakukan','success');
                return redirect()->back();
            } else {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'Berhasil memasukan data transksi :'.' '. $request->id_bk_transaksi.' '.'kedalam daftar stok';
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);

                DB::table('log_activity')->insert($data);

                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        }
    }

    public function poststok(Request $request)
    {
        $id = $request['id_bk_detail'];
        $id = $request['id_bk_detail'];
        $qty = $request->input('qty');
        $detai = DetailKeluar::where('id_bk_detail', $id)->first();
        $cekStok = Stok::where('kode_barang', $detai->kode_barang)->sum(DB::raw('sts_inout * qty'));

        $totalQty = 0;
        $totalQty += $qty;

        if ($cekStok === null || $cekStok < $totalQty) {
            toast('Oops stok tidak cukup !', 'warning');
            return redirect()->back()->with('error', 'Stok Tidak Cukup !');
        }
        else{
            $stok = DetailKeluar::updateOrCreate(['id_bk_detail' => $id], [
                'qty' => $qty,
            ]);

            $id_bk = $stok->id_bk;
            $cekstok = DetailKeluar::where('id_bk', $id_bk)->select('qty')->get();
            $barang = DetailKeluar::where('id_bk', $id_bk)->first();


            $total_qty = 0;
            foreach ($cekstok as $item) {
                $total_qty += $item->qty;
            }

            $barangKeluar = BarangKeluar::updateOrCreate(['id_bk' => $id_bk], [
                'total_qty' => $total_qty,
            ]);

            if ($barangKeluar) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'Berhasil mengubah data stok barang :'.' '.$barang->nama_barang.', dengan jumlah stok : '.$qty.' pada transaksi : '. $request->id_bk_transaksi;
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);

                DB::table('log_activity')->insert($data);

                toast('Proses berhasil dilakukan', 'success');
                return redirect()->back();
            } else {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'Gagal mengubah data stok barang :'.' '.$barang->nama_barang.', dengan jumlah stok : '.$qty.' pada transaksi : '. $request->id_bk_transaksi;
                $lastid    = DB::table('log_activity')->max('id') + 1;

                // console LOG::START
                $data = ([
                    'id' => $lastid,
                    'username' => auth()->user()->username,
                    'id_user' => $setid,
                    'keterangan' => $aktifitas,
                    'ip_address' => $ip2,
                ]);

                DB::table('log_activity')->insert($data);

                toast('Proses gagal dilakukan', 'error');
                return redirect()->back();
            }
        }
    }

    public function deletebarangkeluar($id)
    {
        $barangKeluar = DetailKeluar::Where('id', $id)->first();
        $jumlah = $barangKeluar->qty;

        $barang = BarangKeluar::where('id_bk', $barangKeluar->id_bk)->first();
        if($barang){
            $barang->total_qty -= $jumlah;
            $barang->sequenc -= 1;
            $barang->save();
        }

        if ($barangKeluar) {
            $barangKeluar->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus data barang :'.' '. $barangKeluar->nama_barang.' '.'pada transaksi : '. $barangKeluar->id_bk;
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => auth()->user()->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);

            DB::table('log_activity')->insert($data);

            toast('Hapus data berhasil dilakukan','success');
            return redirect()->back();
        } else {
            $barangKeluar->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Gagal menghapus data barang :'.' '. $barangKeluar->nama_barang.' '.'pada transaksi : '. $barangKeluar->id_bk;
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => auth()->user()->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);

            DB::table('log_activity')->insert($data);

            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }


    public function delete($id_bk)
    {
        $detailbarangKeluar = DetailKeluar::Where('id_bk', $id_bk)->first();
        $barangKeluar = BarangKeluar::Where('id_bk', $id_bk)->first();

        if (!$detailbarangKeluar) {
            $barangKeluar->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus data transaksi : '. $barangKeluar->id_bm;
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => auth()->user()->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);

            DB::table('log_activity')->insert($data);

            toast('Hapus data berhasil dilakukan','success');
            return redirect()->back();
        } else if ($barangKeluar) {
            $detailbarangKeluar->delete();
            $barangKeluar->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus data transaksi : '. $barangKeluar->id_bm;
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => auth()->user()->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);

            DB::table('log_activity')->insert($data);

            toast('Hapus data berhasil dilakukan','success');
            return redirect()->back();
        }else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Gagal menghapus data transaksi : '. $barangKeluar->id_bm;
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => auth()->user()->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);

            DB::table('log_activity')->insert($data);

            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }
}
