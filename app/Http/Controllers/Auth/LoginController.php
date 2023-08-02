<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


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
        $this->validate($request, [
            'username'=>'required',
            'password'=>'required',
        ]);

        if (!auth()->attempt($request->only('username', 'password'), $request->remember)){
            toast('Anda berhasil login','error');
            return back();
        }
        toast('Anda berhasil login','success');
        return redirect('app/dashboard');

    }
}
