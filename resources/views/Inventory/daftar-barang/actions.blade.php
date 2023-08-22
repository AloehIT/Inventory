@php
    $access = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('role_has_permissions.*', 'permissions.name as name_permission')
            ->where('role_id', auth()->user()->id)
            ->get();
    $ubah     = $access->where('name_permission', 'ubah satuan')->first();
    $hapus    = $access->where('name_permission', 'hapus satuan')->first();
@endphp


<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#detail{{ $row->id }}"> <i class="bi bi-eye text-warning"></i></a>
@if($ubah)
<a class="action-icon" href="{{ route('update.barang', $row->id) }}"> <i class="bi bi-pencil-fill text-info"></i></a>
@endif
@if($hapus)
<a href="{{ route('delete.barang', $row->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus Barang : {{ $row['nama_barang'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
@endif
