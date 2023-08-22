<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\roleHasPermission;

class PermissionController extends Controller
{

    public function getData(Request $request)
    {
        $permission = roleHasPermission::join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->join('roles', 'roles.id', '=', 'role_has_permissions.role_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission', 'permissions.set_permission', 'roles.name as name_role')
        ->where('role_id', $request->id);


        return Datatables::of($permission)
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('Usersmanager.permissions.actions', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('Y-MM-DD');
            })
            // ->rawColumns(['action'])
            ->toJson();
    }

    public function index($id)
    {
        $cekPermission = DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->select('role_has_permissions.*', 'permissions.name as name_permission')
        ->where('role_id', auth()->user()->id)->first();

        try{
            $data = [
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

                'existAccess' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', $id)
                ->get(),

                'detail' => RoleModel::find($id),
                'permission' => DB::table('permissions')->orderBy('id', 'ASC')->get(),
            ];

            return view('Usersmanager.permissions.index', $data, compact('cekPermission'));
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman permissions user !', 'warning');
            return redirect('app/usersroles');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|unique:role_has_permissions,permission_id,NULL,id,role_id,' . ($request['role_id'] ?? ''),
        ], [
            'permission_id.required' => 'Permission harus dipilih!',
            'permission_id.unique'   => 'Permission telah didaftarkan pada role ini!',
        ]);

        if ($validator->fails()) {
            toast('Oops, permission telah didaftarkan pada role ini', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $permission = roleHasPermission::create([
            'permission_id' => $request->input('permission_id'),
            'role_id' => $request->input('role_id'),
        ]);

        $cekPermission = RoleModel::where('id', $request->role_id)->first();

        if ($permission) {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'berhasil mengatur permission :'.' '.$cekPermission->name;
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
            return redirect('app/roles/permission/'. $request->role_id);
        } else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal mengatur permission :'.' '.$cekPermission->name;
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

    public function delete($id)
    {
        $permission = roleHasPermission::where('id', $id)->first();

        if ($permission) {
            $permission->delete();
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus permission role';
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
            $aktifitas = auth()->user()->username.' '.'Berhasil menghapus permission role';
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
}
