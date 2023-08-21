<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Perusahaan;
use App\Models\User;
use DB;

class PengaturanController extends Controller
{

    public function index()
    {
        try {
            if (auth()->user()->role === 'Super Admin') {
                $data = [
                    'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                    ->where('users.id', auth()->user()->id)
                    ->first(),

                    'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                    'name' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                    'alamat' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_alamat')->first(),
                    'phone' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_phone')->first(),
                    'gambar' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_logo')->first(),
                ];

                return view('pengaturan.index', $data);
            }else{
                toast('Role user tidak mendapatkan akses !', 'warning');
                return redirect('app/dashboard');
            }


        } catch (\Throwable $e) {
            toast('Terjadi kesalahan pada halaman pengaturan !', 'warning');
            return redirect('app/dashboard');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_config.*'        => 'required',
            'value.*'              => 'required',
            'setting.*'            => 'required',
        ],[
            'name_config.*.required'      => 'Isi format input dengan benar !',
            'value.*.required'            => 'Isi format input dengan benar !',
            'setting.*.required'          => 'Isi format input dengan benar !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $set = false; // Initialize the variable outside the loop

        $image = $request['gambar'];
        $imageName = $request['gambars'];

        if ($image && $image->isValid()) {
            $imageName = $image->hashName();
            if($request->gambars == 'upload.gif')
            {
                $image->move('storage/logo', $imageName);
            }else{
                unlink('storage/logo' .'/'. $request['gambars']);
                $image->move('storage/logo', $imageName);
            }

        }

        if (is_array($request->name_config)) {
            foreach ($request->name_config as $key => $name_config) {
                $newSet = Perusahaan::updateOrCreate(
                    ['name_config' => $name_config],
                    [
                        'value' => $request->value[$key],
                        'setting' => $request->setting[$key],
                    ]
                );

                if ($newSet) {
                    $set = true; // Update the variable if a record is successfully created
                }
            }
        } else {
            toast('Proses yang anda lakukan gagal', 'error');
            return redirect()->back();
        }

        $set = Perusahaan::updateOrCreate(['name_config' => $request['name_config_gambar']], [
            'value'         => $imageName,
            'name_config'   => $request['name_config_gambar'],
            'setting'       => $request['setting_gambar'],
        ]);

        if ($set) {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'berhasil mengubah info penganturan';
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
            return redirect()->back();
        } else {
            $ip2 = request()->getClientIp();
            $usersid = User::select('id')->where('status', 1)->where('username', auth()->user()->username)->get();
            foreach($usersid as $id);
            $setid = $id->id;
            $aktifitas = auth()->user()->username.' '.'gagal mengubah info penganturan';
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
            toast('Proses yang anda lakukan gagal', 'error');
            return redirect()->back();
        }
    }
}
