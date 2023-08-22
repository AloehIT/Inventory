<?php



use App\Http\Controllers\Auth\LogActivityController;
use App\Http\Controllers\Gudang\BarangKeluarController;
use App\Http\Controllers\Gudang\BarangMasukController;
use App\Http\Controllers\Gudang\OpnameController;
use App\Http\Controllers\Laporan\LaporanBarangKeluarController;
use App\Http\Controllers\Laporan\LaporanBarangMasukController;
use App\Http\Controllers\Laporan\LaporanOpnameController;
use App\Http\Controllers\Laporan\LaporanStokController;
use App\Http\Controllers\ManajemenUsers\RoleController;
use App\Http\Controllers\ManajemenUsers\UsersController;
use App\Http\Controllers\MasterData\BarangController;
use App\Http\Controllers\MasterData\KategoriBarangController;
use App\Http\Controllers\MasterData\SatuanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Setting\PengaturanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Access Login
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.access');
Route::get('/logout', [LogoutController::class, 'logout'])->name('logout.access');

// Dashboard Controller


Route::middleware('auth')->group(function(){
    Route::get('dashboard/data', [DashboardController::class, 'getData'])->name('data.dashboard');
    Route::get('dashboard/hitung', [DashboardController::class, 'calculate'])->name('data.calculateDashboard');
    Route::resource('app/dashboard', DashboardController::class);
});


// Pengaturan
Route::middleware('auth')->group(function(){
    Route::resource('app/pengaturan', PengaturanController::class);
    Route::post('app/pengaturan/posts', [PengaturanController::class, 'posts'])->name('umum.posts');
});

//log activity
Route::middleware('auth')->group(function(){
    Route::get('log/data', [LogActivityController::class, 'getData'])->name('data.log');
    Route::resource('app/log', LogActivityController::class);
});

//Data Role Users
Route::middleware('auth')->group(function(){
    Route::get('permission/detaildata/{id}', [PermissionController::class, 'getData'])->name('data.detail.permission');
    Route::get('app/roles/permission/{id}', [PermissionController::class, 'index'])->name('set.permission');
    Route::post('app/permission/posts', [PermissionController::class, 'posts'])->name('posts.permission');
    Route::get('app/permission/delete/{id}', [PermissionController::class, 'delete'])->name('delete.permission');


    Route::get('usersroles/data', [RoleController::class, 'rolesData'])->name('data.roles');
    Route::resource('app/usersroles', RoleController::class);
    Route::post('app/usersroles/posts', [RoleController::class, 'posts'])->name('posts.roles');
    Route::post('app/usersroles/upposts', [RoleController::class, 'upposts'])->name('upposts.roles');
    Route::get('app/usersroles/delete/{id}', [RoleController::class, 'delete'])->name('delete.roles');
});

//Data Users
Route::middleware('auth')->group(function(){
    Route::get('users/data', [UsersController::class, 'usersData'])->name('data.users');
    Route::get('app/usermanager/tambah', [UsersController::class, 'create'])->name('create.usermanager');
    Route::get('app/usermanager/ubah/{id}', [UsersController::class, 'update'])->name('update.usermanager');
    Route::resource('app/usermanager', UsersController::class);
    Route::post('app/usermanager/posts', [UsersController::class, 'posts'])->name('posts.usermanager');
    Route::get('app/usermanager/delete/{id}', [UsersController::class, 'delete'])->name('delete.usermanager');
});


// Master Kategori Barang
Route::middleware('auth')->group(function(){
    Route::get('kategori/data', [KategoriBarangController::class, 'kategoriData'])->name('data.kategori');
    Route::resource('app/kategori-barang', KategoriBarangController::class);
    Route::post('app/kategori-barang/posts', [KategoriBarangController::class, 'posts'])->name('posts.kategoribarang');
    Route::post('app/kategori-barang/upposts', [KategoriBarangController::class, 'upposts'])->name('upposts.kategoribarang');
    Route::get('app/kategori-barang/delete/{id}', [KategoriBarangController::class, 'delete'])->name('delete.kategoribarang');
});

//Master Satuan Barang
Route::middleware('auth')->group(function(){
    Route::get('satuan/data', [SatuanController::class, 'satuanData'])->name('data.satuan');
    Route::resource('app/satuans', SatuanController::class);
    Route::post('app/satuans/posts', [SatuanController::class, 'posts'])->name('posts.satuanbarang');
    Route::get('app/satuans/delete/{id}', [SatuanController::class, 'delete'])->name('delete.satuanbarang');
});


// Master Daftar Barang
Route::middleware('auth')->group(function(){
    Route::get('barang/data', [BarangController::class, 'barangData'])->name('data.barang');
    Route::get('app/daftar-barang/ubah/{id}', [BarangController::class, 'update'])->name('update.barang');
    Route::get('app/daftar-barang/tambah', [BarangController::class, 'create'])->name('create.barang');
    Route::resource('app/daftar-barang', BarangController::class);
    Route::post('app/daftar-barang/posts', [BarangController::class, 'posts'])->name('posts.barang');
    Route::get('app/daftar-barang/delete/{id}', [BarangController::class, 'delete'])->name('delete.barang');
});


// Master Daftar BarangMasuk
Route::middleware('auth')->group(function(){
    Route::get('barangmasuk/detaildata/{id_bm}', [BarangMasukController::class, 'detailData'])->name('data.detail.barangmasuk');
    Route::get('barangmasuk/data', [BarangMasukController::class, 'barangmasukData'])->name('data.barangmasuk');
    Route::get('app/barang-masuk/detail/{id_bm}', [BarangMasukController::class, 'update'])->name('update.barang-masuk');
    Route::get('app/barang-masuk/tambah', [BarangMasukController::class, 'create'])->name('create.barang-masuk');
    Route::resource('app/barang-masuk', BarangMasukController::class);
    Route::post('app/barang-masuk/posts', [BarangMasukController::class, 'posts'])->name('posts.barangmasuk');
    Route::post('app/barang-masuk/stok/posts', [BarangMasukController::class, 'poststok'])->name('stok.barangmasuk');
    Route::get('app/barang-masuk/delete/{id}', [BarangMasukController::class, 'deletebarangmasuk'])->name('delete.detail-barang-masuk');
    Route::get('app/barang-masuk/deleteall/{id_bm}', [BarangMasukController::class, 'delete'])->name('delete.barang-masuk');
});


// Master Daftar BarangKeluar
Route::middleware('auth')->group(function(){
    Route::get('barangkeluar/detaildata/{id_bk}', [BarangKeluarController::class, 'detailData'])->name('data.detail.barangkeluar');
    Route::get('barangkeluar/data', [BarangKeluarController::class, 'barangkeluarData'])->name('data.barangkeluar');
    Route::get('app/barang-keluar/detail/{id_bk}', [BarangKeluarController::class, 'update'])->name('update.barang-keluar');
    Route::get('app/barang-keluar/tambah', [BarangKeluarController::class, 'create'])->name('create.barang-keluar');
    Route::resource('app/barang-keluar', BarangKeluarController::class);
    Route::post('app/barang-keluar/posts', [BarangKeluarController::class, 'posts'])->name('posts.barangkeluar');
    Route::post('app/barang-keluar/stok/posts', [BarangKeluarController::class, 'poststok'])->name('stok.barangkeluar');
    Route::get('app/barang-keluar/delete/{id}', [BarangKeluarController::class, 'deletebarangkeluar'])->name('delete.detail-barang-keluar');
    Route::get('app/barang-keluar/deleteall/{id_bk}', [BarangKeluarController::class, 'delete'])->name('delete.barang-keluar');
});

// Master Daftar Opname
Route::middleware('auth')->group(function(){
    Route::get('opname/detaildata/{id_opname}', [OpnameController::class, 'detailData'])->name('data.detail.opname');
    Route::get('opname/data', [OpnameController::class, 'getData'])->name('data.opname');
    Route::get('app/opname/detail/{id_opname}', [OpnameController::class, 'update'])->name('update.opname');
    Route::get('app/opname/tambah', [OpnameController::class, 'create'])->name('create.opname');
    Route::resource('app/opname', OpnameController::class);
    Route::post('app/opname/posts', [OpnameController::class, 'posts'])->name('posts.opname');
    Route::post('app/opname/stok/posts', [OpnameController::class, 'poststok'])->name('stok.opname');
    Route::get('app/opname/delete/{id}', [OpnameController::class, 'deletebarangopname'])->name('delete.detail-opname');
    Route::get('app/opname/deleteall/{id_opname}', [OpnameController::class, 'delete'])->name('delete.opname');
});

// Kartu Stok
Route::middleware('auth')->group(function(){
    Route::get('kartu-stok/data', [LaporanStokController::class, 'getData'])->name('data.daftarStok');
    Route::get('kartu-stok/hitung', [LaporanStokController::class, 'calculate'])->name('data.calculate');
    Route::get('/laporan-stok/print-stok', [LaporanStokController::class, 'printPDF'])->name('print.pdf');
    Route::resource('app/kartu-stok', LaporanStokController::class);
});

// Laporaan Barang Masuk
Route::middleware('auth')->group(function(){
    Route::get('laporan/barang-masuk/data', [LaporanBarangMasukController::class, 'getData'])->name('data.daftarMasuk');
    Route::get('laporan/barang-masuk/hitung', [LaporanBarangMasukController::class, 'calculate'])->name('data.calculateMasuk');
    Route::get('/laporan/barang-maasuk/print-stok', [LaporanBarangMasukController::class, 'printPDF'])->name('print.pdf');
    Route::resource('app/laporan/barang-masuk', LaporanBarangMasukController::class);
});


// Laporaan Barang Keluar
Route::middleware('auth')->group(function(){
    Route::get('laporan/barang-keluar/data', [LaporanBarangKeluarController::class, 'getData'])->name('data.daftarKeluar');
    Route::get('laporan/barang-keluar/hitung', [LaporanBarangKeluarController::class, 'calculate'])->name('data.calculateKeluar');
    Route::get('/laporan/barang-keluar/print-stok', [LaporanBarangKeluarController::class, 'printPDF'])->name('print.pdf');
    Route::resource('app/laporan/barang-keluar', LaporanBarangKeluarController::class);
});

// Laporaan Opname
Route::middleware('auth')->group(function(){
    Route::get('laporan/opname/data', [LaporanOpnameController::class, 'getData'])->name('data.daftarOpname');
    Route::get('laporan/opname/hitung', [LaporanOpnameController::class, 'calculate'])->name('data.calculateOpname');
    Route::get('/laporan/opname/print-stok', [LaporanOpnameController::class, 'printPDF'])->name('print.pdf');
    Route::resource('app/laporan/opname', LaporanOpnameController::class);
});


// API GET Barang
Route::middleware('auth')->group(function(){
    Route::get('/caribarang/{barcode}', function ($barcode) {
        $barang = DB::table('barangs')
        ->join('satuans', 'satuans.id', '=', 'barangs.satuan_id')
        ->where('barcode', $barcode)->get();
        return response()->json($barang);
    })->name('barang');
});


Route::middleware('auth')->group(function(){
    Route::get('/opnameBarang/{barcode}', [OpnameController::class, 'getOpnameBarang'])->name('opnameBarang');
});
