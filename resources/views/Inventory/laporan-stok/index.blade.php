@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('content-page')
<div class="container-fluid">
    @include('main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') ERP <i class="bi bi-archive text-warning"></i></h3>
                            <p class="mb-0">Data Seluruh @yield('title') yang terdaftar pada system</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                    class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-barang.png') }}" class="img-fluid" width="150">
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
                            <div class="card-body p-4">
                                <div class="ml-auto text-end">
                                    <a href="javascript:void(0)" class="btn btn-danger" id="print-stok" target="_blank"><i class="uil-print"></i> Print PDF</a>
                                </div>
                                <div class="form-group">
                                    <label for="opsi-laporan-stok">Filter Stok Berdasarkan :</label>
                                    <select class="form-control" name="opsi-laporan-stok" id="opsi-laporan-stok">
                                        <option value="semua" selected>Semua</option>
                                        <option value="minimum">Batas Minimum</option>
                                        <option value="stok-habis">Stok Habis</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table id="table_id" class="display basic-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Stok</th>
                                                <th>Kategori</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabel-laporan-stok">
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

        var table = $('#table_id').DataTable({
            paging: true
        });

        loadData('semua');

        $('#opsi-laporan-stok').on('change', function(){
            var selectedOption = $(this).val();
            loadData(selectedOption);
        });

        function loadData(selectedOption) {
            $.ajax({
                url: '/laporan-stok/get-data',
                type: 'GET',
                data: { opsi: selectedOption },
                success: function(response){
                    table.clear().draw();

                    $.each(response, function(index, item) {
                        var row = [
                            item.kode_barang,
                            item.nama_barang,
                            item.stok,
                            item.kategori
                        ];
                        table.row.add(row);
                    });
                    table.draw();
                }
            });
        }

        $('#print-stok').on('click', function(){
            var selectedOption = $('#opsi-laporan-stok').val();
            window.location.href = '/laporan-stok/print-stok?opsi=' + selectedOption;
        });
    });
</script>
@endsection
