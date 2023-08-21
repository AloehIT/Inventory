<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\User;
use DB;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest'])->except('logout');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $ip = request()->ip();
        $ip2 = request()->getClientIp();

        $cek = User::select('users.status')->where('status', 1)->where('username', $request->username)->first();

        $this->validate($request, [
            'username'=>'required',
            'password'=>'required',
        ]);


        if($cek && !auth()->attempt($request->only('username', 'password'), $request->remember))
        {
            toast('Sepertinya password dan username salah','error');
            return back()->with('gagal', 'invalid login details');
        }
        elseif($cek)
        {
            $usersid = User::select('id')->where('status', 1)->where('username', $request->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas =  $request->username.' '.'berhasil login ke sistem';
            $lastid    = DB::table('log_activity')->max('id') + 1;

            // console LOG::START
            $data = ([
                'id' => $lastid,
                'username' => $request->username,
                'id_user' => $setid,
                'keterangan' => $aktifitas,
                'ip_address' => $ip2,
            ]);
            DB::table('log_activity')->insert($data);
            // console LOG::END

            toast('Anda berhasil login','success');
            return redirect('app/dashboard');
        }
        else {
            toast('Sepertinya password dan username salah','error');
            return back()->with('gagal', 'invalid login details');
        }
    }
}
