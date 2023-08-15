@if($row->status === "approve")
<a href="{{ route('update.opname', $row->id_opname) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
@else
<a href="{{ route('update.opname', $row->id_opname) }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
<a href="{{ route('delete.opname', $row->id_opname) }}" type="button" onclick="return confirm('Apakah anda yakin ingin Transaksi Barang Masuk : {{ $row['keterangan'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
@endif
