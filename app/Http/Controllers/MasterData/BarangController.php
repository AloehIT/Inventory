<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Satuan;

class BarangController extends Controller
{

    public function barangData(Request $request)
    {
        $barang = Barang::join('users', 'users.id', '=', 'barangs.user_id')
        ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
        ->select('barangs.*', 'satuans.satuan', 'users.username');

        return Datatables::of($barang)
            ->addColumn('action', function ($row) {
                // Add your action buttons here, similar to your Blade file
                return view('inventory.daftar-barang.actions', compact('row'))->render();
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->isoFormat('dddd, D MMMM Y');
            })
            ->rawColumns(['action', 'barcode'])
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

                'barang' => Barang::join('users', 'users.id', '=', 'barangs.user_id')
                ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
                ->select('barangs.*', 'satuans.satuan', 'users.username')
                ->get(),
            ];

            return view('inventory.daftar-barang.index', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }

    public function create()
    {
        try {
            $data = [
                'title' => 'Tambah Daftar Barang',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),
                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),

                'satuan' => Satuan::get(),
                'kategori' => Kategori::Where('guard_config', 'Barang')->get(),
                'generate' => Barang::max('id'),
            ];

            return view('inventory.daftar-barang.cubarang', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }

    public function update($id)
    {
        try {
            $data = [
                'title' => 'Update Daftar Barang',
                'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->first(),
                'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->first(),

                'edit' => Barang::find($id),
                'satuan' => Satuan::get(),
                'kategori' => Kategori::Where('guard_config', 'Barang')->get(),
                'generate' => Barang::max('id'),
            ];

            return view('inventory.daftar-barang.cubarang', $data);
        } catch (\Throwable $e) {
            // Redirect to the error page
            return view('error.500');
        }
    }

    public function posts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang'     => 'required|unique:barangs,kode_barang,' . ($request['id'] ?? '') . ',id',
            'gambar'          => 'file|mimes:jpeg,bmp,png,gif|max:2000',
            'nama_barang'     => 'required',
            'kategori'        => 'required',
            'barcode'         => 'required',
            'satuan'          => 'required',
        ], [
            'kode_barang.required'    => 'Kode Barang tidak boleh sama!',
            'kode_barang.unique'      => 'Kode Barang tidak boleh sama!',
            'gambar.file'             => 'Masukan gambar sesuai format: jpeg, bmp, png, gif!',
            'gambar.mimes'            => 'Masukan gambar sesuai format: jpeg, bmp, png, gif!',
            'gambar.max'              => 'Ukuran gambar maksimal 2000 KB!',
            'nama_barang.required'    => 'Isi format nama barang !',
            'kategori.required'       => 'Isi format kategori !',
            'barcode.required'        => 'Isi format barcode !',
            'satuan.required'         => 'Isi format satuan !',
        ]);

        if ($validator->fails()) {
            toast('Oops terjadi kesalahan', 'warning');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $image = $request['gambar'];
        $imageName = $request['gambars'];

        if ($image && $image->isValid()) {
            $imageName = $image->hashName();
            if($request->gambars == 'upload.gif')
            {
                $image->move('storage/barang', $imageName);
            }else{
                unlink('storage/barang' .'/'. $request['gambars']);
                $image->move('storage/barang', $imageName);
            }

        }

        $lastid       = $request['id'] ? $request['id'] : Barang::max('id') + 1;
        $randomString = rand();
        $id_barang    = $request['id_barang'] ? $request['id_barang'] : $randomString;

        $set = Barang::updateOrCreate(['id' => $request['id']], [
            'id'          => $lastid,
            'id_barang'   => $id_barang,
            'kode_barang' => $request['kode_barang'],
            'nama_barang' => $request['nama_barang'],
            'deskripsi'   => $request['deskripsi'],
            'barcode'     => $request['barcode'],
            'kategori'    => $request['kategori'],
            'satuan_id'   => $request['satuan'],
            'gambar'      => $imageName,
            'user_id'     => auth()->user()->id,
        ]);

        if ($set) {
            toast('Proses berhasil dilakukan', 'success');
            return redirect('app/daftar-barang');
        } else {
            toast('Proses gagal dilakukan', 'error');
            return redirect()->back();
        }
    }



    public function delete(Request $request, $id)
    {
        $cekfile = Barang::Where('id', $request['id'])->first();
        if($cekfile->gambar == "upload.gif")
        {}
        else
        {
            unlink('storage/barang' .'/'. $cekfile->gambar);
        }

        $delete = Barang::find($id)->delete();

        if($delete)
        {
            toast('Barang berhasil di hapus','success');
            return redirect()->back();
        }
        toast('Maaf barang gagal di hapus', 'error');
        return redirect()->back();

    }


}

