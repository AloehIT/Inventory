<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function satuanData(Request $request)
    {
        $satuan = Satuan::all();

        return Datatables::of($satuan)
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.satuan-barang.actions', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('Y-MM-DD');
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

                'access' => DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->select('role_has_permissions.*', 'permissions.name as name_permission')
                ->where('role_id', auth()->user()->id)
                ->get(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),

                'satuan' => Satuan::get(),
            ];

            return view('inventory.satuan-barang.index', $data);
        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman satuan barang !', 'warning');
            return redirect('app/dahboard');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'satuan'  => 'required|unique:satuans,satuan,' . ($request['id'] ?? '') . ',id',
        ], [
            'satuan.required' => 'Isi format input dengan benar !',
            'satuan.unique'   => 'Satuans sudah ada !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $satuan = Satuan::max('id');
        if(!$request['id']){
            $lastid = $satuan + 1;
        }else{
            $lastid = $request['id'];
        }

        $set = Satuan::updateOrCreate(['id' => $request['id']], [
            'id' => $lastid,
            'satuan' => $request['satuan'] == '' ? '' : $request['satuan'],
            'keterangan_satuan' => $request['keterangan'] == '' ? 'Tidak terisi' : $request['keterangan'],
            'user_id' => auth()->user()->id,
        ]);

        if ($set) {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'berhasil'.' '.$request->aksi.' '.$request->satuan;
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
            return redirect('app/satuans');
        } else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal'.' '.$request->aksi.' '.$request->satuan;
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
        $cek = Satuan::Where('id', $id)->first();
        $status = auth()->user()->username.' '.'berhasil menghapus satuan :'.' '.$cek->satuan;
        $status1 = auth()->user()->username.' '.'berhasil menghapus satuan :'.' '.$cek->satuan;
        $delete = Satuan::Where('id', $id)->delete();

        if($delete)
        {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = $status;
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

            toast('Satuan Barang berhasil di hapus','success');
            return redirect()->back();
        }else{
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = $status1;
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

            toast('Maaf Satuan Barang gagal di hapus', 'error');
            return redirect()->back();
        }
    }
}
