<?php

namespace App\Http\Controllers\ManajemenUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\DetailUsers;
use App\Models\RoleModel;

class UsersController extends Controller
{
    public function usersData(Request $request)
    {
        $users = DetailUsers::join('users', 'users.id', '=', 'detail_users.id')
        ->select('detail_users.*', 'detail_users.nama_users', 'users.username', 'users.unique', 'users.role', 'users.status', 'users.email')
        ->where('users.status', 1);

        return Datatables::of($users)
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('usersmanager.users.actions', compact('row'))->render();
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
        ->where('permissions.name', 'user')
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

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'users' => User::Where('users.status', 1)
                ->get(),
            ];

            return view('usersmanager.users.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman users !', 'warning');
            return redirect('app/dashboard');
        }
    }

    public function create()
    {
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'tambah users')
        ->first();

        try {
            if (!$cekPermission) {
                toast('Halaman tidak ditemukan', 'warning');
                return redirect('app/usermanager');
            }

            $data = [
                'title' => 'Tambah Profil User Baru',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'role' => RoleModel::all(),
            ];

            return view('usersmanager.users.cuuser', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman tambah user !', 'warning');
            return redirect('app/usermanager');
        }
    }

    public function update($id)
    {
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)
        ->where('permissions.name', 'ubah users')
        ->first();

        try {
            if (!$cekPermission) {
                toast('Halaman tidak ditemukan', 'warning');
                return redirect('app/usermanager');
            }

            $data = [
                'title' => 'Ubah Profil User',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'role' => RoleModel::all(),

                'edit' => User::Where('users.id', $id)
                ->leftjoin('detail_users', 'detail_users.id', '=', 'users.id')
                ->first(),
            ];

            return view('usersmanager.users.cuuser', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman ubah user !', 'warning');
            return redirect('app/usermanager');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_users'      => 'required|unique:detail_users,nama_users,' . ($request['id'] ?? '') . ',id',
            'username'        => 'required|unique:users,username,' . ($request['id'] ?? '') . ',id',
            'password'        => 'required',
            'profile'         => 'file|mimes:jpeg,bmp,png,gif|max:2000',
            'roles'           => 'required',
            'telpon'          => 'required',
            'alamat'          => 'required',
        ], [
            'nama_users.required'      => 'Nama users tidak boleh kosong!',
            'nama_users.unique'        => 'Nama users tidak boleh sama!',
            'username.required'        => 'Username tidak boleh kosong!',
            'username.unique'          => 'Username tidak boleh sama!',
            'profile.file'             => 'Masukan profile sesuai format: jpeg, bmp, png, gif!',
            'profile.mimes'            => 'Masukan profile sesuai format: jpeg, bmp, png, gif!',
            'profile.max'              => 'Ukuran profile maksimal 2000 KB!',
            'roles.required'           => 'Form tidak boleh kosong!',
            'password.required'        => 'Form tidak boleh kosong!',
            'telpon.required'          => 'Form tidak boleh kosong!',
            'alamat.required'          => 'Form tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $image = $request['profile'];
        $profileName = $request['profil'];

        if ($image && $image->isValid()) {
            $profileName = $image->hashName();
            if($request->profil == 'profile.png')
            {
                $image->move('storage/profile', $profileName);
            }else{
                unlink('storage/profile' .'/'. $request['profil']);
                $image->move('storage/profile', $profileName);
            }
        }


        $role = RoleModel::where('id', $request->roles)->select('name')->first();
        if(!$request['roles'] == ""){
            $roles = $role->name;
        }else{
            $roles = $request['role'];
        }



        $lastid = $request['id'] ? $request['id'] : user::max('id') + 1;


        $set = User::updateOrCreate(['id' => $request['id']], [
            'id' => $lastid,
            'username' => $request['username'] == '' ? '' : $request['username'],
            'password' => Hash::make($request['password'] == '' ? '' : $request['password']),
            'unique' => $request['password'] == '' ? '' : $request['password'],
            'role' => $roles,
            'email' => $request['email'] == '' ? '' : $request['email'],
        ]);

        $set = DetailUsers::updateOrCreate(['id' => $request['id']], [
            'profile' => $profileName,
            'id' => $lastid,
            'nama_users' => $request['nama_users'] == '' ? '' : $request['nama_users'],
            'telpon' => $request['telpon'] == '' ? '' : $request['telpon'],
            'deskripsi_users' => $request['deskripsi_users'] == '' ? '' : $request['deskripsi_users'],
            'alamat_users' => $request['alamat'] == '' ? '' : $request['alamat'],
        ]);


        if ($set) {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'berhasil'.' '.$request->aksi.': '.$request->nama_users;
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
            return redirect('app/usermanager');
        } else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal'.' '.$request->aksi.': '.$request->nama_users;
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
        $cekfile = DetailUsers::Where('detail_users.id', $request['id'])->first();
        if($cekfile->ktp == "ktp.png" || $cekfile->profile == "profile.png")
        {}
        else
        {
            unlink('storage/profile' .'/'. $cekfile->profile);
            unlink('storage/ktp' .'/'. $cekfile->ktp);
        }

        $delete = User::find($id)->delete();
        $delete = DetailUsers::find($id)->delete();

        if($delete)
        {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'berhasil menghapus'.': '.$cekfile->nama_users;
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

            toast('Users berhasil di hapus','success');
            return redirect()->back();
        }else{
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal menghapus'.': '.$cekfile->nama_users;
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

            toast('Maaf users gagal di hapus', 'error');
            return redirect()->back();
        }
    }
}
