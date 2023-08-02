<?php

namespace App\Http\Controllers\ManajemenUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Permission;
use App\Models\hasModelRole;
use App\Models\RoleModel;
use App\Models\PageSite;

class PermissionController extends Controller
{
    public function index($id)
    {
        $data = [
            'title' => "Access Permission",
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),

            'perusahaan' => Perusahaan::where('setting', 'Config')
            ->where('name_config', 'conf_perusahaan')
            ->where('owner_id', auth()->user()->group)
            ->first(),


            'page' => PageSite::where('set_permission', 'Halaman')->get(),
            'form' => PageSite::where('set_permission', 'Form')->get(),
            'detailRole' => RoleModel::find($id),
            'users' => User::join('roles', 'roles.name', '=', 'users.role')
            ->join('detail_users', 'detail_users.id', '=', 'users.id')->get()
        ];

        return view('permission.index', $data);
    }
}
