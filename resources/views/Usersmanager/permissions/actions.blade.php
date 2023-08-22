@if($row->name_permission == 'dashboard' || $row->name_permission == 'logActivity')
@else
<a href="{{ route('delete.permission', $row->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus role : {{ $row['name'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
@endif
