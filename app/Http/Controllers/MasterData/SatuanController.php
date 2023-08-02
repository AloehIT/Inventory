<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function index()
    {
        try {
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),
                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),

                'satuan' => Satuan::get(),
            ];

            return view('inventory.satuan-barang.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
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
            'keterangan_satuan' => $request['keterangan'] == '' ? null : $request['keterangan'],
            'user_id' => auth()->user()->id,
        ]);

        if ($set) {
            toast('Proses berhasil dilakukan','success');
            return redirect('app/satuans');
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {
        $delete = Satuan::Where('id', $id)->delete();

        if($delete)
        {
            toast('Satuan Barang berhasil di hapus','success');
            return redirect()->back();
        }else{
            toast('Maaf Satuan Barang gagal di hapus', 'error');
            return redirect()->back();
        }
    }
}
