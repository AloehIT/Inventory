@php
    $access = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('role_has_permissions.*', 'permissions.name as name_permission')
            ->where('role_id', auth()->user()->id)
            ->get();
    $ubah     = $access->where('name_permission', 'ubah users')->first();
    $hapus    = $access->where('name_permission', 'hapus users')->first();
@endphp

@if($ubah)
<a href="{{ route('update.usermanager', $row->id) }}" class="action-icon" type="button"> <i class="bi bi-pencil-fill text-info"></i></a>
@endif
@if($row->role == 'Super Admin')
@else
    @if($hapus)
        <a href="{{ route('delete.usermanager', $row->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus users : {{ $row['nama_users'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
    @endif
@endif
