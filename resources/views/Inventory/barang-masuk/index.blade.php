@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Daftar Barang Masuk')
@section('content-page')
<style>
    .dataTables_filter {
        display: none; /* Menyembunyikan kotak pencarian */
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
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') <i class="bi bi-box-arrow-in-left text-success"></i></h3>
                            <p class="mb-0">Data Seluruh @yield('title') yang terdaftar pada system</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                    class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end p-3">
                            <img src="{{ asset('assets/icon/bg-barang.png') }}" class="img-fluid" width="110">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="row mb-2 g-1 col-lg-12">
                                        <div class="col-sm-2">
                                            <label for="start_date">Dari :</label>
                                            <input type="date" id="start_date" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-2">
                                            <label for="end_date">Sampai :</label>
                                            <input type="date" id="end_date" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-3">
                                            <label></label><br>
                                            <a href="{{ route('create.barang-masuk') }}" class="btn btn-sm btn-info"><i class="uil-plus"></i> Tambah</a>
                                            <button id="refresh-btn" class="btn btn-sm text-white" style="background: rgb(27, 96, 255);"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                                        </div>
                                    </div>

                                    <table class="basic-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                        <thead class="">
                                            <tr>
                                                <th>
                                                    Jenis <br> <span><br></span>
                                                </th>
                                                <th>
                                                    Tanggal <br> <span><br></span>
                                                </th>
                                                <th>
                                                    No. Ref <br> <input type="text" class="form-control form-control-sm" placeholder="No. Ref"/>
                                                </th>
                                                <th>
                                                    Keterangan <br> <input type="text" class="form-control form-control-sm" placeholder="Keterangan"/>
                                                </th>
                                                <th>
                                                    QTY <br> <input type="number" min="0" class="form-control form-control-sm" style="width: 70px;" placeholder="QTY"/>
                                                </th>
                                                <th style="width: 75px;">
                                                    Action <br>  <span><br></span>
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('.basic-datatable').DataTable({
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: false,
            order: [[1, 'desc']],
            info: false,
            ajax: {
                url: '{!! route('data.barangmasuk') !!}',
                data: function(d) {
                    // Mengambil nilai tanggal dari input
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                { data: 'jenis', name: 'jenis' },
                { data: 'tgl_bm', name: 'tgl_bm' },
                { data: 'id_bm', name: 'id_bm' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'total_qty', name: 'total_qty'},
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

        // Event change pada input tanggal "start_date" dan "end_date"
        $('#start_date, #end_date').on('change', function() {
            table.ajax.reload();
        });

        // Fungsi untuk mengubah format tanggal menjadi YYYY-MM-DD
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        // Tampilkan tanggal awal dan akhir di input date ketika halaman dimuat
        function displayDateRange() {
            var dateRange = getDateRangeFromColumn();
            $('#start_date').val(dateRange.start_date);
            $('#end_date').val(dateRange.end_date);
        }

        // Fungsi untuk mengambil data tanggal awal dan akhir dari kolom "tgl_bm"
        function getDateRangeFromColumn() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            return {
                start_date: startDate,
                end_date: endDate,
            };
        }

        // Tampilkan tanggal awal dan akhir di input date ketika halaman dimuat
        displayDateRange();
    });

</script>
@endsection
