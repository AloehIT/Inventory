<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Str;
use DB;

use App\Models\DetailMasuk;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Stok;


class BarangMasukController extends Controller
{

    public function barangmasukData(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = BarangMasuk::query(); // Menggunakan query builder untuk membangun query

        // Filter berdasarkan tanggal awal dan akhir jika ada
        if ($start_date && $end_date) {
            $query->whereBetween('tgl_bm', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('tgl_bm', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('tgl_bm', '<=', $end_date);
        }

        $barang = $query->get();

        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                return 'Barang Masuk';
            })
            ->addColumn('action', function ($row) {
                return view('inventory.barang-masuk.actions', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->toJson();
    }


    public function detailData(Request $request)
    {
        $barang = BarangMasuk::join('detail_barang_masuk', 'detail_barang_masuk.id_bm', '=', 'barang_masuks.id_bm')
        ->where('barang_masuks.id_bm', $request->id_bm);


        return Datatables::of($barang)
            ->addColumn('jenis', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return 'Barang Masuk';
            })
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.barang-masuk.actionsdetail', compact('row'))->render();
            })
            ->editColumn('qty', function ($row) {
                return '+'.$row->qty.' '.$row->satuan;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('Y-MM-DD');
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'barang masuk')
        ->first();

        try {
            if (!$cekPermission) {
                toast('Halaman tidak ditemukan', 'warning');
                return redirect('app/dashboard');
            }

            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan'    => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'barangMasuk'   => BarangMasuk::all(),
                'daftarbarang'  => BarangMasuk::join('detail_barang_masuk', 'detail_barang_masuk.id_bm', '=', 'barang_masuks.id_bm')->get(),
            ];

            return view('inventory.barang-masuk.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman barang masuk !', 'warning');
            return redirect('app/dashboard');
        }
    }

    public function create()
    {
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'tambah barang masuk')
        ->first();

        try {
            if (!$cekPermission) {
                toast('Halaman tidak ditemukan', 'warning');
                return redirect('app/barang-masuk');
            }

            $data = [
                'title' => 'Tambah Barang Masuk',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'generate' => BarangMasuk::max('id'),

                'barang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->select('barangs.*', 'satuans.satuan', 'users.username')
                ->get(),

                'cardbarang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->join('detail_barang_masuk', 'detail_barang_masuk.id_barang', '=', 'barangs.id_barang')
                ->select('barangs.*', 'satuans.satuan', 'users.username', 'detail_barang_masuk.tanggal', 'detail_barang_masuk.qty', 'detail_barang_masuk.satuan', 'detail_barang_masuk.id_bm_detail')
                ->get(),
            ];

            return view('inventory.barang-masuk.cubarang-masuk', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman tambah barang masuk !', 'warning');
            return redirect('app/barang-masuk');
        }
    }

    public function update($id_bm)
    {
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'ubah barang masuk')
        ->first();

        try{
            if (!$cekPermission) {
                toast('Halaman tidak ditemukan', 'warning');
                return redirect('app/barang-masuk');
            }

            $data = [
                'title' => 'Data Barang Masuk',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'generate' => BarangMasuk::max('id'),

                'barang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->select('barangs.*', 'satuans.satuan', 'users.username')
                ->get(),

                'cardbarang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->join('detail_barang_masuk', 'detail_barang_masuk.id_barang', '=', 'barangs.id_barang')
                ->select('barangs.*', 'satuans.satuan', 'users.username', 'detail_barang_masuk.tanggal', 'detail_barang_masuk.qty', 'detail_barang_masuk.satuan', 'detail_barang_masuk.id_bm_detail')
                ->get(),

                'detail'  => BarangMasuk::Where('id_bm', $id_bm)->first(),
                'barangstok' => BarangMasuk::join('detail_barang_masuk', 'detail_barang_masuk.id_bm', '=', 'barang_masuks.id_bm')
                ->where('barang_masuks.id_bm', $id_bm)->get()
            ];

            return view('inventory.barang-masuk.cubarang-masuk', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman daftar barang masuk !', 'warning');
            return redirect('app/barang-masuk');
        }
    }

    public function daftar($id_bm)
    {
        try{
            $data = [
                'title' => 'Data Barang Masuk',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'generate' => BarangMasuk::max('id'),

                'barang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->select('barangs.*', 'satuans.satuan', 'users.username')
                ->get(),

                'cardbarang' => Barang::leftJoin('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->join('detail_barang_masuk', 'detail_barang_masuk.id_barang', '=', 'barangs.id_barang')
                ->select('barangs.*', 'satuans.satuan', 'users.username', 'detail_barang_masuk.tanggal', 'detail_barang_masuk.qty', 'detail_barang_masuk.satuan', 'detail_barang_masuk.id_bm_detail')
                ->get(),

                'detail'  => BarangMasuk::Where('id_bm', $id_bm)->first(),
                'barangstok' => BarangMasuk::join('detail_barang_masuk', 'detail_barang_masuk.id_bm', '=', 'barang_masuks.id_bm')
                ->where('barang_masuks.id_bm', $id_bm)->get()
            ];

            return view('inventory.barang-masuk.daftar-masuk', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman daftar barang masuk !', 'warning');
            return redirect('app/barang-masuk');
        }
    }


    public function posts(Request $request)
    {
        $action = $request->input('action');

        if ($action === 'tambahBarang') {
            $validator = Validator::make($request->all(), [
                'id_bm_transaksi' => 'required',
                'tgl_bm' => 'required',
                'id_barang' => 'required',
                'kode_barang' => 'required',
                'barcode' => 'required',
                'nama_barang' => 'required',
                'qty' => 'required|integer|min:1',
                'satuan' => 'required',
            ], [
                'id_bm_transaksi.required' => 'Kode Transaksi harus diisi!',
                'tgl_bm.required' => 'Tanggal Masuk harus diisi!',
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

            $lastId = $request->has('id') ? $request->input('id') : BarangMasuk::max('id') + 1;

            $nullqty = 0;
            $nullsqn = 0;
            $id_bm_transaksi = $request->input('id_bm_transaksi');
            $kode_barang = $request->input('kode_barang');
            $qty = $request->input('qty');


            $no = DetailMasuk::max('id') + 1;
            $id_detail = sprintf("%04s", $no).rand();
            $tanggal = $request->input('tgl_bm');

            $existingItem = DetailMasuk::where('id_bm', $id_bm_transaksi)
            ->where('kode_barang', $kode_barang)
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
                'keterangan' => $request->input('keterangan') ?? 'Tidak ada',
                'user_id' => auth()->user()->id,
                'status' => 'draft',
            ]);

            if ($barangMasuk) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'berhasil menambahkan barang :'.' '.$request->nama_barang.', dengan jumlah stok : '.$request->qty.' kedalam transaksi barang masuk'.' '. $request->id_bm_transaksi;
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
                return redirect('app/barang-masuk/detail/'. $request->id_bm_transaksi);
            } else {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'gagal menambahkan barang :'.' '.$request->nama_barang.', dengan jumlah stok : '.$request->qty.' kedalam transaksi barang masuk'.' '. $request->id_bm_transaksi;
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
                'id_bm_transaksi' => 'required',
                'tgl_bm' => 'required',
            ], [
                'id_bm_transaksi.required' => 'Kode Transaksi harus diisi!',
                'tgl_bm.required' => 'Tanggal Masuk harus diisi!',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $lastId = $request->has('id') ? $request->input('id') : BarangMasuk::max('id') + 1;

            $barangMasuk = BarangMasuk::updateOrCreate(['id_bm' => $request['id_bm_transaksi']], [
                'id' => $lastId,
                'id_bm' => $request->input('id_bm_transaksi'),
                'kode_barang' => $request->input('kode_barang'),
                'tgl_bm' => $request->input('tgl_bm'),
                'keterangan' => $request->input('keterangan') ?? 'Tidak ada',
                'user_id' => auth()->user()->id,
                'status' => 'draft',
            ]);

            if ($barangMasuk) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'berhasil menyimpan data transaksi barang masuk'.' '. $request->id_bm_transaksi;
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
                return redirect('app/barang-masuk/detail/'. $request->id_bm_transaksi);
            } else {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'gagal menyimpan data transaksi barang masuk'.' '. $request->id_bm_transaksi;
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

            $set = BarangMasuk::updateOrCreate(['id_bm' => $request['id_transaksi'] ], [
                'status' => $request->status,
            ]);

            if ($set) {
                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'Berhasil memasukan data transksi :'.' '. $request->id_bm_transaksi.' '.'kedalam daftar stok';
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
                $aktifitas = auth()->user()->username.' '.'Berhasil memasukan data transksi :'.' '. $request->id_bm_transaksi.' '.'kedalam daftar stok';
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
        $id = $request['id_bm_detail'];
        $qty = $request->input('qty');

        $stok = DetailMasuk::updateOrCreate(['id_bm_detail' => $id], [
            'qty' => $qty,
        ]);

        $id_bm = $stok->id_bm;
        $cekstok = DetailMasuk::where('id_bm', $id_bm)->select('qty')->get();
        $barang = DetailMasuk::where('id_bm', $id_bm)->first();

        $total_qty = 0;

        foreach ($cekstok as $item) {
            $total_qty += $item->qty;
        }


        $barangMasuk = BarangMasuk::updateOrCreate(['id_bm' => $id_bm], [
            'total_qty' => $total_qty,
        ]);

        if ($barangMasuk) {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil mengubah data stok barang :'.' '.$barang->nama_barang.', dengan jumlah stok : '.$qty.' pada transaksi : '. $request->id_bm_transaksi;
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
            $aktifitas = auth()->user()->username.' '.'Berhasil mengubah data stok barang :'.' '.$barang->nama_barang.', dengan jumlah stok : '.$qty.' pada transaksi : '. $request->id_bm_transaksi;
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
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus data barang :'.' '. $barangMasuk->nama_barang.' '.'pada transaksi : '. $barangMasuk->id_bm;
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
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal menghapus data barang :'.' '. $barangMasuk->nama_barang.' '.'pada transaksi : '. $barangMasuk->id_bm;
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => auth()->user()->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);

            toast('Hpaus data gagal dilakukan', 'error');
            return redirect()->back();
        }
    }


    public function delete($id_bm)
    {
        $detailbarangMasuk = DetailMasuk::Where('id_bm', $id_bm)->first();
        $barangMasuk = BarangMasuk::Where('id_bm', $id_bm)->first();

        if (!$detailbarangMasuk) {
            $barangMasuk->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus data transaksi : '. $barangMasuk->id_bm;
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
        } else if ($barangMasuk) {
            $detailbarangMasuk->delete();
            $barangMasuk->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus data transaksi : '. $barangMasuk->id_bm;
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
            $aktifitas = auth()->user()->username.' '.'Gagal menghapus data transaksi : '. $barangMasuk->id_bm;
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



