@if($row->status === "approve")
<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#detail{{ $row->id_opname_detail }}"> <i class="bi bi-eye text-warning"></i></a>
@else
<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#detail{{ $row->id_opname_detail }}"> <i class="bi bi-eye text-warning"></i></a>
<a data-bs-toggle="modal" data-idopname="{{ $row->id_opname }}" data-idopnamedetail="{{ $row->id_opname_detail }}" data-satuans="{{ $row->satuan }}" data-stoks="{{ $row->qty }}" data-bs-target="#edit" class="action-icon passStok"> <i class="bi bi-pencil-fill text-info"></i></a>
{{-- <a href="{{ route('delete.detail-barang-masuk', $row->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus Barang : {{ $row['nama_barang'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a> --}}
@endif
