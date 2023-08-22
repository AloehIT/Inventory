@php
    $access = Illuminate\Support\Facades\DB::table('role_has_permissions')->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('role_has_permissions.*', 'permissions.name as name_permission')
            ->where('role_id', auth()->user()->id)
            ->get();
    $ubah     = $access->where('name_permission', 'ubah barang masuk')->first();
    $hapus    = $access->where('name_permission', 'hapus barang masuk')->first();
@endphp
@if($row->status === "approve")
    @if($ubah)
        <a href="{{ route('update.barang-masuk', $row->id_bm) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @else
        <a href="{{ route('daftar.barang-masuk', $row->id_bm) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @endif
@else
    @if($ubah)
        <a href="{{ route('update.barang-masuk', $row->id_bm) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @else
        <a href="{{ route('daftar.barang-masuk', $row->id_bm) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
    @endif

    @if($hapus)
        <a href="{{ route('delete.barang-masuk', $row->id_bm) }}" type="button" onclick="return confirm('Apakah anda yakin ingin Transaksi Barang Masuk : {{ $row['keterangan'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
    @endif
@endif
