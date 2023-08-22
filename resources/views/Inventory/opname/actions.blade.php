@php
    $access = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('role_has_permissions.*', 'permissions.name as name_permission')
            ->where('role_id', auth()->user()->id)
            ->get();
    $ubah     = $access->where('name_permission', 'ubah opname')->first();
    $hapus    = $access->where('name_permission', 'hapus opname')->first();
@endphp

@if($row->status === "approve")
    @if($ubah)
        <a href="{{ route('update.opname', $row->id_opname) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @else
        <a href="{{ route('daftar.opname', $row->id_opname) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @endif
@else
    @if($ubah)
        <a href="{{ route('update.opname', $row->id_opname) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @else
        <a href="{{ route('daftar.opname', $row->id_opname) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @endif
    @if($hapus)
        <a href="{{ route('delete.opname', $row->id_opname) }}" type="button" onclick="return confirm('Apakah anda yakin ingin Transaksi Barang Masuk : {{ $row['keterangan'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
    @endif
@endif
