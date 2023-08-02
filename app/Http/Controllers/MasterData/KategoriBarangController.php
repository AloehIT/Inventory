<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Perusahaan;

class KategoriBarangController extends Controller
{

    public function index()
    {
        try {
            $data = [
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),

                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),
                'kategori' => Kategori::Where('guard_config', 'Barang')->get(),
            ];

            return view('inventory.kategori-barang.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_kategori.*'      => 'required|unique:kategori_item,name_kategori,' . ($request['id'] ?? '') . ',id',
            'guard_config.*'       => 'required',
        ], [
            'name_kategori.*.required' => 'Isi format input dengan benar !',
            'name_kategori.*.unique' => 'Jenis Pelanggan sudah ada !',
            'guard_config.*.required' => 'Isi format input dengan benar !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $set = false;

        if (is_array($request->guard_config)) {
            foreach ($request->guard_config as $key => $guard_config) {
                $newSet = Kategori::create([
                    $roles = Kategori::max('id'),
                    $lastid = $roles + 1,
                    'guard_config' => $guard_config,
                    'id' => $lastid,
                    'name_kategori' => $request->name_kategori[$key],
                ]);

                if ($newSet) {
                    $set = true; // Update the variable if a record is successfully created
                }
            }
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }

        if ($set) {
            toast('Proses berhasil dilakukan','success');
            return redirect()->back();
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }

    public function upposts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_kategori'      => 'required|unique:kategori_item,name_kategori,' . ($request['id'] ?? '') . ',id',
            'guard_config'       => 'required',
        ], [
            'name_kategori.required' => 'Isi format input dengan benar !',
            'name_kategori.unique' => 'Jenis Pelanggan sudah ada !',
            'guard_config.required' => 'Isi format input dengan benar !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lastid = $request['id'] ? $request['id'] : Kategori::max('id') + 1;

        $set = Kategori::updateOrCreate(['id' => $request['id']], [
            'id' => $lastid,
            'name_kategori' => $request['name_kategori'] == '' ? '' : $request['name_kategori'],
            'guard_config' => $request['guard_config'] == '' ? '' : $request['guard_config'],
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
        Kategori::Where('id', $id)->delete();
        toast('Kategori berhasil di hapus','success');
        return redirect()->back();
    }
}
