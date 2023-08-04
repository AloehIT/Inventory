<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#edit{{ $row->id }}">
    <i class="mdi mdi-square-edit-outline text-info"></i>
</a>
<a href="{{ route('delete.kategoribarang', $row->id) }}"
    type="button"
    onclick="return confirm('Apakah anda yakin ingin menghapus kategori : {{ $row->name_kategori }} ?')"
    class="action-icon" style="outline: none; border: none; background: none;">
    <i class="mdi mdi-delete text-danger"></i>
</a>
