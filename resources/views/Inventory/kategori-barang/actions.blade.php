<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#edit{{ $row->id }}">
    <i class="bi bi-pencil-fill text-info"></i>
</a>
<a href="{{ route('delete.kategoribarang', $row->id) }}"
    type="button"
    onclick="return confirm('Apakah anda yakin ingin menghapus kategori : {{ $row->name_kategori }} ?')"
    class="action-icon" style="outline: none; border: none; background: none;">
    <i class="bi bi-trash text-danger"></i>
</a>
