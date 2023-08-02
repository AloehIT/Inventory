@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Role Access')
@section('content-page')
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">

        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-8 col-md-8 col-12 py-3">
                            <h3 class="text-dark">Pengaturan @yield('title')</h3>
                            <p class="mb-0">Lakukan beberapa konfigurasi @yield('title') yang akan didaftarkan pada system.</p>
                            <p class="mb-0">Jumlah Role Access : <b>{{ count($role) }}</b></p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-roles.jpg') }}" class="img-fluid" width="150">
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
                                    <i class="mdi mdi-menu"></i> Manajemen @yield('title')
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add"><i class="mdi mdi-account-multiple-plus"></i> Tambah @yield('title') Baru</a>
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
                                                <th>Ditambahkan</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($role as $no => $roles)
                                                <tr>
                                                    <div hidden>{{ $id = $roles['id'] }}</div>
                                                    <td>
                                                        {{ $no+1 }}
                                                    </td>
                                                    <td>
                                                       <i class="bi bi-key-fill text-warning"></i> {{ $roles->name }}
                                                    </td>

                                                    <td>
                                                        {{ $carbon::parse($roles['created_at'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}
                                                    </td>

                                                    <td>
                                                        <a class="action-icon" type="button" data-bs-toggle="modal" data-bs-target="#edit{{ $id }}"> <i class="mdi mdi-square-edit-outline text-info"></i></a>
                                                        <a href="{{ route('delete.roles', $id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menhapus role : {{ $roles['name'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="mdi mdi-delete text-danger"></i></a>
                                                    </td>
                                                </tr>


                                                <div id="edit{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">

                                                            <div class="modal-body">
                                                                <div class="text-start mt-4 mb-2 mx-3">
                                                                    <div class="d-flex justify-content-between mt-3">
                                                                        <div>
                                                                            <h5 class="text-uppercase mb-0"><i class="uil-filter text-warning"></i> Ubah @yield('title')</h5>
                                                                            <p class="">{{ $perusahaan['value'] ?? '' }}</p>
                                                                        </div>

                                                                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                                                                   </div>
                                                                </div>

                                                                <form class="ps-3 pe-3" action="{{ route('posts.roles') }}" method="POST">
                                                                    @csrf
                                                                    <div class="mx-3">
                                                                        <div class="mb-3">
                                                                            <input type="hidden" name="id" value="{{ $id }}">
                                                                            <label for="role" class="form-label">Nama Role</label>
                                                                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="role" name="name" placeholder="Nama Role" value="{{ $roles['name'] ?? '' }}" autocomplete="off">
                                                                            @error('name')
                                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="guard" class="form-label">Guard Name</label>
                                                                            <input class="form-control" type="text" id="guard" name="guard" value="web" readonly>
                                                                            <input class="form-control" type="hidden" id="model" name="model" value="App\Models\User" readonly>
                                                                        </div>

                                                                        <div class="mb-3 text-end">
                                                                            <button class="btn btn-sm text-white" style="background: red;" type="button" data-bs-dismiss="modal">Batal <i class="bi bi-x-lg"></i></button>
                                                                            <button class="btn btn-sm btn-info" type="submit">Simpan <i class="bi bi-check-lg"></i></button>
                                                                        </div>

                                                                    </div>
                                                                </form>

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


<div id="add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-4 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="uil-filter text-warning"></i> Tambah @yield('title')</h5>
                            <p class="">{{ $perusahaan['value'] ?? '' }}</p>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <form class="ps-3 pe-3" action="{{ route('posts.roles') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">Nama Role</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="role" name="name" placeholder="Nama Role" autocomplete="off">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="guard" class="form-label">Guard Name</label>
                        <input class="form-control" type="text" id="guard" name="guard" value="web" readonly>
                        <input class="form-control" type="hidden" id="model" name="model" value="App\Models\User" readonly>
                    </div>

                    <div class="mb-3 text-end">
                        <button class="btn btn-sm text-white" style="background: red;" type="button" data-bs-dismiss="modal">Batal <i class="bi bi-x-lg"></i></button>
                        <button class="btn btn-sm btn-info" type="submit">Simpan <i class="bi bi-check-lg"></i></button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
