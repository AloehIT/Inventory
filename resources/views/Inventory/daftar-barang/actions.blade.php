<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#detail{{ $row->id }}"> <i class="bi bi-eye text-warning"></i></a>
<a class="action-icon" href="{{ route('update.barang', $row->id) }}"> <i class="bi bi-pencil-fill text-info"></i></a>
<a href="{{ route('delete.barang', $row->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus Barang : {{ $row['nama_barang'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
