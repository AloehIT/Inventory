<?php

namespace App\Http\Controllers\ManajemenUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\RoleModel;
use App\Models\hasModelRole;

class RoleController extends Controller
{

    public function rolesData(Request $request)
    {
        $role = RoleModel::orderBy('id', 'DESC');

        return Datatables::of($role)
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('usersmanager.roles.actions', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('dddd, D MMMM Y');
            })
            ->rawColumns(['action'])
            ->toJson();
    }


    public function index()
    {
        try {
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'role' => RoleModel::orderBy('id', 'DESC')->get(),
            ];

            return view('usersmanager.roles.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|unique:roles,name,' . ($request['id'] ?? '') . ',id',
        ], [
            'name.required'  => 'Isi format input dengan benar !',
            'name.unique'    => 'Role access sudah ada !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lastid = $request['id'] ? $request['id'] : RoleModel::max('id') + 1;

        $set = RoleModel::updateOrCreate(['id' => $request['id']], [
            'id' => $lastid,
            'name' => $request['name'] == '' ? '' : $request['name'],
            'guard_name' => $request['guard'] == '' ? '' : $request['guard'],
        ]);

        $set = hasModelRole::updateOrCreate(['role_id' => $request['id']], [
            'role_id' => $lastid,
            'model_type' => $request['model'] == '' ? '' : $request['model'],
            'model_id' => $lastid,
        ]);

        if ($set) {
            toast('Proses berhasil dilakukan','success');
            return redirect()->back();
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {

        $role = RoleModel::Where('id', $id)->first();
        $delete = User::Where('role', $role->name)->first();

        if($delete)
        {
            toast('Maaf role gagal di hapus', 'error');
            return redirect()->back();
        }else{
            RoleModel::Where('id', $id)->delete();
            hasModelRole::Where('role_id', $id)->delete();
            toast('Role berhasil di hapus','success');
            return redirect()->back();
        }
    }
}
