<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $request->session()->forget('ip');
        $request->session()->forget('user');
        $request->session()->forget('password');
        auth()->logout();
        toast('Anda berhasil logout','success');
        return redirect()->route('login');
    }
}
