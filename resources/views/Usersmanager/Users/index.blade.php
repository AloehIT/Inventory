@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Manajemen users')
@section('content-page')
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-8 col-md-8 col-12 py-3">
                            <h3 class="text-dark">Pengaturan Account Users</h3>
                            <p class="mb-0">Lakukan beberapa konfigurasi akun bank yang akan didaftarkan pada system.</p>
                            <p class="mb-0">Jumlah Users : <b>{{ count($users) }}</b></p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-users.jpg') }}" class="img-fluid" width="200">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <div class="dropdown">
                                <a class="btn btn-sm btn-secondary dropdown-toggle btn-info" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-menu"></i> Manajemen Users
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('create.usermanager') }}"><i class="mdi mdi-account-multiple-plus"></i> Tambah Users Baru</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="basic-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Posisi</th>
                                                <th>Status</th>
                                                <th>Ditambahkan</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $no => $user)
                                                <tr>
                                                    <div hidden>{{ $id = $user['id'] }}</div>
                                                    <td>{{ $no+1 }}</td>
                                                    <td class="table-user">
                                                        <a data-bs-toggle="modal" data-bs-target="#detail{{ $id ?? '' }}" class="text-body" style="cursor: pointer;"><img src="{{ asset('storage/profile/'. $user->profile) }}" alt="table-user" class="me-1 rounded-circle"></a>
                                                        <a data-bs-toggle="modal" data-bs-target="#detail{{ $id ?? '' }}" class="text-body" style="cursor: pointer;">{{ $user->nama_users }} <i class="bi bi-info-circle-fill text-info"></i></a>
                                                    </td>
                                                    <td>
                                                        {{ $user->role }}
                                                    </td>

                                                    <td>
                                                        @if($user->status == 1)
                                                        <span class="badge badge-success-lighten">Active</span>
                                                        @else
                                                        <span class="badge badge-danger-lighten">Nonaktif</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ $carbon::parse($user['created_at'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}
                                                    </td>

                                                    <td>
                                                        <a href="{{ route('update.usermanager', $id) }}" class="action-icon" type="button"> <i class="mdi mdi-square-edit-outline text-info"></i></a>
                                                        <a href="{{ route('delete.usermanager', $id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menhapus user {{ $user['nama_users'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="mdi mdi-delete text-danger"></i></a>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="detail{{ $id ?? '' }}" tabindex="-1" role="dialog"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-body ">
                                                                <div class="px-4 py-2">

                                                                   <div class="d-flex justify-content-between mt-3">
                                                                        <div>
                                                                            <h5 class="text-uppercase mb-0"><i class="bi bi-people-fill text-info"></i> {{ $user['nama_users'] ?? '' }}</h5>
                                                                            <p class="mb-4">{{ $perusahaan['value'] ?? '' }}</p>
                                                                        </div>

                                                                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                                                                   </div>


                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="theme-color">Details</span>
                                                                        <span>Terakhir diubah : {{ $carbon::parse($user['updated_at'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}</span>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <hr class="new1">
                                                                    </div>
                                                                    <div class="mt-2 mb-2">
                                                                        <img src="{{ asset('storage/profile/'. $user->profile) }}" alt="users" class="rounded" width="100">
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Username :</span>
                                                                        <span class="text-muted">{{ $user['username'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Password :</span>
                                                                        <span class="text-muted">{{ $user['unique'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Posisi :</span>
                                                                        <span class="text-muted">{{ $user['role'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Telpon/WhatsApp :</span>
                                                                        <span class="text-muted">{{ $user['telpon'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="mt-2 mb-3">
                                                                        <p class="font-weight-bold mb-0">Alamat :</p>
                                                                        <p class="text-muted text-start">{{ $user['alamat_users'] ?? '' }}</p>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div>


@endsection
