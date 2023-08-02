<?php

namespace App\Http\Controllers\ManajemenUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\DetailUsers;
use App\Models\RoleModel;

class UsersController extends Controller
{
    public function index()
    {
        try {
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),


                'users' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->orderBy('users.created_at', 'DESC')
                ->get(),
            ];

            return view('usersmanager.users.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }

    public function create()
    {
        try {
            $data = [
                'title' => 'Tambah User Baru',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')
                ->where('name_config', 'conf_perusahaan')
                ->first(),

                'role' => RoleModel::all(),
            ];

            return view('usersmanager.users.cuuser', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('maintenece.maintenece');
        }
    }

    public function update($id)
    {
        try {
            $data = [
                'title' => 'Ubah User',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

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
            // Redirect to the error page
            return view('error.maintenece');
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
            toast('Proses berhasil dilakukan','success');
            return redirect('app/usermanager');
        } else {
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
            toast('Users berhasil di hapus','success');
            return redirect()->back();
        }
        toast('Maaf users gagal di hapus', 'error');
        return redirect()->back();

    }
}
