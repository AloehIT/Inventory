<?php

namespace App\Http\Controllers\Laporan;

use Dompdf\Dompdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Barang;

class LaporanStokController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $data = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->leftjoin('detail_alamat', 'detail_alamat.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->where('owner_id', auth()->user()->group)->first(),

            'barang' => Barang::all(),
        ];

        return view('laporan-stok.index', $data);
    }

    public function getData(Request $request)
    {
        $selectedOption = $request->input('opsi');

        if($selectedOption == 'semua'){
             $barangs = Barang::all();
        } elseif ($selectedOption == 'minimum'){
             $barangs = Barang::where('stok', '<=', 10)->get();
        } elseif ($selectedOption == 'stok-habis'){
             $barangs = Barang::where('stok', 0)->get();
        } else {
             $barangs = Barang::all();
        }

        return response()->json($barangs);
    }

    public function printStok(Request $request)
    {
        $data = [
            'auth' => User::join('detail_users', 'detail_users.id', '=', 'users.id')
            ->leftjoin('detail_alamat', 'detail_alamat.id', '=', 'users.id')
            ->where('users.id', auth()->user()->id)
            ->first(),
            'perusahaan' => Perusahaan::where('setting', 'Config')->where('name_config', 'conf_perusahaan')->where('owner_id', auth()->user()->group)->first(),
        ];

        $selectedOption = $request->input('opsi');

        if ($selectedOption == 'semua') {
            $barangs = Barang::all();
        } elseif ($selectedOption == 'minimum') {
            $barangs = Barang::where('stok', '<=', 10)->get();
        } elseif ($selectedOption == 'stok-habis') {
            $barangs = Barang::where('stok', 0)->get();
        } else {
            $barangs = Barang::all();
        }



        // Generate PDF
        $dompdf = new Dompdf();
        $html = view('/laporan-stok/print-stok', compact('barangs', 'selectedOption'), $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('print-stok.pdf', ['Attachment' => false]);
    }
}
