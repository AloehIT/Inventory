<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluars';

    protected $fillable = ['id', 'kode_transaksi', 'tanggal_keluar', 'nama_barang', 'jumlah_keluar', 'id_teknisi', 'lokasi', 'maps', 'lokasi_kerja', 'user_id'];

    protected $hidden = [];
}
