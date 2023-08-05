@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Satuan Barang')
@section('content-page')
<style>
    .dataTables_filter {
        display: none;
    }
    .dataTables_paginate {
        float: left;
    }
</style>
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title')</h3>
                            <p class="mb-0">Data Seluruh @yield('title') yang terdaftar pada system</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                    class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-beratbarang.png') }}" class="img-fluid" width="150">
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
                                    <i class="mdi mdi-menu"></i> Manajemen Satuan
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add"><i class="mdi mdi-plus"></i> Tambah Satuan</a>
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
                                                <th>Satuan Barang</th>
                                                <th>Ditambahkan</th>
                                                <th>Keterangan</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Satuan" />
                                                </th>
                                                <th>
                                                    <input type="date" class="form-control form-control-sm" placeholder="Search Ditambahkan" />
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Keterangan">
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" readonly>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

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


{{-- <div id="add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-4 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="uil-balance-scale text-info"></i> Tambah @yield('title')</h5>
                            <p class="">{{ $perusahaan['value'] ?? '' }}</p>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <form class="ps-3 pe-3" action="{{ route('posts.satuanbarang') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan Barang</label>
                        <input class="form-control @error('satuan') is-invalid @enderror" type="text" id="satuan" name="satuan" placeholder="Satuan Barang" value="{{ old('satuan') }}">
                        @error('satuan')
                            <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="5" placeholder="Ketikan teks disini...">{{ old('keterangan') }}</textarea>
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

<div id="edit{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-4 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="uil-balance-scale text-info"></i> Ubah @yield('title')</h5>
                            <p class="">{{ $perusahaan['value'] ?? '' }}</p>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <form class="ps-3 pe-3" action="{{ route('posts.satuanbarang') }}" method="POST">
                    @csrf
                    <div class="mx-3">
                        <div class="mb-3">
                            <input type="hidden" name="id" value="{{ $id }}">
                            <label for="satuan" class="form-label">Satuan Barang</label>
                            <input class="form-control @error('satuan') is-invalid @enderror" type="text" id="satuan" name="satuan" placeholder="Satuan" value="{{ $item['satuan'] ?? old('satuan') }}">
                            @error('satuan')
                            <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" id="keterangan" cols="30" rows="5" placeholder="Ketikan teks disini...">{{ $item['keterangan_satuan'] ?? old('keterangan') }}</textarea>
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
</div> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('.basic-datatable').DataTable({
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: true,
            info: false,
            ajax: '{!! route('data.satuan') !!}',
            columns: [
                { data: 'satuan', name: 'satuan' },
                { data: 'created_at', name: 'created_at' },
                { data: 'keterangan_satuan', name: 'keterangan_satuan'},
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
            }
        });

        // Fungsi untuk melakukan refresh data tabel
        function refreshTable() {
            table.ajax.reload(null, false);
        }

        // Event click pada tombol refresh
        $('#refresh-btn').on('click', function() {
            refreshTable();
        });


        // Apply search for each column
        $('.basic-datatable thead th input').on('keyup change', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });
    });
</script>
@endsection
