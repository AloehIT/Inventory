<a href="{{ route('set.permission', $row->id)  }}" class="action-icon"> <i class="bi bi-menu-button-wide text-info"></i></a>
<a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#edit{{ $row->id }}"> <i class="bi bi-pencil-fill text-info"></i></a>
@if($row->name == 'Super Admin')
@else
<a href="{{ route('delete.roles', $row->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus role : {{ $row['name'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="bi bi-trash text-danger"></i></a>
@endif
