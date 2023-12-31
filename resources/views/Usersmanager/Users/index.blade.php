@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Manajemen users')
@section('content-page')
@php
    $tambah     = $access->where('name_permission', 'tambah users')->first();
@endphp
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
                    <div class="row justify-content-between mx-3">
                        <div class="col-lg-8 col-md-8 col-12 py-3">
                            <h3 class="text-dark">Master Users</h3>
                            <p class="mb-0">jumlah data users : <b>{{ count($users) }}</b> pada system.</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 text-end p-1">
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
                        <div class="col-sm-12">
                            <div class="d-flex flex-row">
                                @if($tambah)
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-secondary dropdown-toggle btn-info" href="#" role="button"
                                        id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="mdi mdi-menu"></i> Manajemen Users
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('create.usermanager') }}"><i
                                                class="mdi mdi-account-multiple-plus"></i> Tambah Users Baru</a>
                                    </div>
                                </div>
                                @endif
                                <button id="refresh-btn" class="btn btn-sm mx-1 text-white" style="background: rgb(27, 96, 255);"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="basic-datatable table dt-responsive nowrap w-100"
                                        style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Nama <br> <input type="text" class="form-control form-control-sm" placeholder="Nama" />
                                                </th>
                                                <th>
                                                    Posisi <br> <input type="text" class="form-control form-control-sm" placeholder="Posisi" />
                                                </th>
                                                <th>
                                                    Ditambahkan <br> <input type="date" class="form-control form-control-sm">
                                                </th>
                                                <th style="width: 75px;">
                                                    Action <br> <span><br></span>
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
                </div>
            </div>
        </div>
    </div>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('.basic-datatable').DataTable({
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: false,
            info: false,
            order: [[2, 'desc']],
            ajax: '{!! route('data.users') !!}',
            columns: [
                { data: 'nama_users', name: 'nama_users' },
                { data: 'role', name: 'users.role' },
                { data: 'created_at', name: 'created_at' },
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


        $('.basic-datatable thead th input').on('keyup change', function() {
            var index = $(this).closest('th').index();
            table.column(index).search(this.value).draw();
        });
    });
</script>
@endsection
