<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = ['id', 'kode_barang', 'id_barang', 'barcode', 'nama_barang', 'deskripsi', 'gambar', 'user_id', 'kategori', 'satuan_id'];

    protected $hidden = [];
}
