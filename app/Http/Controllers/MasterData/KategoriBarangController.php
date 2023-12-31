<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Perusahaan;

class KategoriBarangController extends Controller
{
    public function kategoriData(Request $request)
    {
        $kategori = Kategori::where('guard_config', 'Barang');

        return Datatables::of($kategori)
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.kategori-barang.actions', compact('row'))->render();
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
        ->where('permissions.name', 'kategori barang')
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

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'kategori' => Kategori::where('guard_config', 'Barang')->get(),
            ];

            return view('inventory.kategori-barang.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman kaategori barang !', 'warning');
            return redirect('app/dashboard');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_kategori.*'      => 'required|unique:kategori,name_kategori,' . ($request['id'] ?? '') . ',id',
            'guard_config.*'       => 'required',
        ], [
            'name_kategori.*.required' => 'Isi format input dengan benar !',
            'name_kategori.*.unique' => 'Jenis Pelanggan sudah ada !',
            'guard_config.*.required' => 'Isi format input dengan benar !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $set = false;

        if (is_array($request->guard_config)) {
            foreach ($request->guard_config as $key => $guard_config) {
                $newSet = Kategori::create([
                    $roles = Kategori::max('id'),
                    $lastid = $roles + 1,
                    'guard_config' => $guard_config,
                    'id' => $lastid,
                    'name_kategori' => $request->name_kategori[$key],
                ]);

                $ip2 = request()->getClientIp();
                $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
                foreach($usersid as $id);
                $setid = $id->id;
                $aktifitas = auth()->user()->username.' '.'berhasil menambahkan kategori '.': '.$request->name_kategori[$key];
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

                if ($newSet) {
                    $set = true; // Update the variable if a record is successfully created
                }
            }
        } else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal menambahkan kategori';
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

        if ($set) {
            toast('Proses berhasil dilakukan','success');
            return redirect()->back();
        } else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal menambahkan kategori';
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

    public function upposts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_kategori'      => 'required|unique:kategori,name_kategori,' . ($request['id'] ?? '') . ',id',
            'guard_config'       => 'required',
        ], [
            'name_kategori.required' => 'Isi format input dengan benar !',
            'name_kategori.unique' => 'Jenis Pelanggan sudah ada !',
            'guard_config.required' => 'Isi format input dengan benar !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lastid = $request['id'] ? $request['id'] : Kategori::max('id') + 1;

        $set = Kategori::updateOrCreate(['id' => $request['id']], [
            'id' => $lastid,
            'name_kategori' => $request['name_kategori'] == '' ? '' : $request['name_kategori'],
            'guard_config' => $request['guard_config'] == '' ? '' : $request['guard_config'],
        ]);

        if ($set) {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil mengubah kategori :'.' '.$request->name_kategori;
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
            $aktifitas = auth()->user()->username.' '.'gagal mengubah kategori :'.' '.$request->name_kategori;
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

    public function delete(Request $request, $id)
    {
        Kategori::Where('id', $id)->delete();
        $ip2 = request()->getClientIp();
        $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
        foreach($usersid as $id);
        $setid = $id->id;
        $aktifitas = auth()->user()->username.' '.'gagal menghapus kategori :'.' '.$request->name_kategori;
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
        toast('Kategori berhasil di hapus','success');
        return redirect()->back();
    }
}
