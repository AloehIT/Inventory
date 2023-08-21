<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use DB;
use App\Models\User;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {

        $ip2 = request()->getClientIp();
        $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
        foreach($usersid as $id);
        $setid = $id->id;
        $aktifitas = auth()->user()->username.' '.'berhasil logout dari sistem';
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

        auth()->logout();
        toast('Anda berhasil logout','success');
        return redirect()->route('login');
    }
}
