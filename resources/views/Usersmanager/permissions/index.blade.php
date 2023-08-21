@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Permission')
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
        <form id="form1" action="{{ route('posts.permission') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="card-body">
                        <div class="row justify-content-between">
                            <div class="col-lg-8 col-md-8 col-12 py-3">
                                <h3 class="text-dark">Atur @yield('title') {{ $detail->name }}</h3>
                                <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i class="bi bi-key-fill text-warning"></i></p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12 text-end">
                                <img src="{{ asset('assets/icon/bg-roles.jpg') }}" class="img-fluid" width="150">
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body mb-0">
                        <div class="table-responsive">
                            <div class="row mb-3 g-1 col-lg-12 px-2">
                                <div class="col-sm-3">
                                    <label></label><br>
                                    <button type="button" id="refresh-btn" class="btn btn-sm text-white" style="background: rgb(27, 96, 255);"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                                </div>
                            </div>

                            <table class="basic-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>
                                            Role <br> <input type="text" class="form-control form-control-sm" placeholder="Kode Barang" />
                                        </th>
                                        <th>
                                            Permission <br> <input type="text" class="form-control form-control-sm" placeholder="Nama Brang" />
                                        </th>
                                        <th>
                                            Ditambahkan <br> <input type="date" class="form-control form-control-sm" placeholder="Search Ditambahkan" />
                                        </th>
                                        <th style="width: 75px;">Action <br> <span><br></span></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>


                    <div class="card p-4">
                        <div class="row gy-4 g-2">
                            <div class="form-group fieldGroup">
                                <div class="row g-1">
                                    <table>
                                        <tr>
                                            <td class="col-sm-3 mb-0">
                                                <div class="form-group form-group-default">
                                                    <label>Hak Access</label>
                                                    <select class="form-control mb-0 barcode select2 select @error ('permission_id') is-invalid @enderror" data-toggle="select2" name="permission_id">
                                                        <option disabled selected>Cari..</option>
                                                        @foreach ($permission as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }} | {{ $item->set_permission }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('permission_id')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <input type="hidden" name="role_id" value="{{ $detail['id'] }}">
                                                </div>
                                            </td>

                                            <td class="col-sm-3 mb-0">
                                                <label for="">Aksi</label><br>
                                                <button type="submit" name="action" value="tambahBarang" class="btn btn-sm btn-info col-12"><i class="bi bi-database-fill-add"></i> Tambah</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="card px-2">
                        <div class="card-body d-flex flex-row justify-content-between mt-0">
                            <div class="col-lg-12">
                                <label for="">Aksi</label>
                                <div class="d-flex">
                                    <a href="{{ url('app/usersroles') }}" class="btn btn-sm text-white" style="background: red;"><i class="bi bi-x-lg"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>


            </div>

        </form>
    </div>
    <!-- end row -->
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('.basic-datatable').DataTable({
            lengthChange: false,
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: false,
            info: false,
            order: [[2, 'desc']],
            ajax: '{!! route('data.detail.permission', $detail['id'] ?? '') !!}',
            columns: [
                { data: 'name_role', name: 'name_role' },
                { data: 'name_permission', name: 'name_permission' },
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

        // Apply search for each column
        $('.basic-datatable thead th input').on('keyup change', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });

        // Fungsi untuk mengambil data tanggal awal dan akhir dari kolom "tgl_bm"
        function getDateRangeFromColumn() {
            var startDate = null;
            var endDate = null;

            table.column(1, { search: 'applied' }).data().each(function(date) {
                var currentDate = new Date(date);
                if (!startDate || currentDate < startDate) {
                    startDate = currentDate;
                }
                if (!endDate || currentDate > endDate) {
                    endDate = currentDate;
                }
            });

            return {
                start_date: startDate ? formatDate(startDate) : null,
                end_date: endDate ? formatDate(endDate) : null,
            };
        }

        // Fungsi untuk menampilkan data tanggal awal dan akhir di input date
        function displayDateRange() {
            var dateRange = getDateRangeFromColumn();
            $('#start_date').val(dateRange.start_date);
            $('#end_date').val(dateRange.end_date);
        }

        // Fungsi untuk mengubah format tanggal menjadi YYYY-MM-DD
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        // Event click pada tombol "Filter"
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // Event change pada input tanggal "start_date" dan "end_date"
        $('#start_date, #end_date').on('change', function() {
            table.ajax.reload();
        });

        // Tampilkan tanggal awal dan akhir di input date ketika halaman dimuat
        displayDateRange();
    });
</script>
@endsection
