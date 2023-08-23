@extends('layouts.app')
@section('title', 'Laporan Stok')
@section('content-page')
@php
    $action     = $access->where('name_permission', 'print laporan')->first();
@endphp


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
                            <h3 class="text-dark">@yield('title') <i class="bi bi-archive text-warning"></i></h3>
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
                            <div class="card-body p-4">

                                <div class="row justify-content-between">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="opsi-laporan-stok">Nama Item :</label>
                                            <select class="form-control select2" data-toggle="select2" name="opsi-laporan-stok" id="opsi-laporan-stok">
                                                <option disabled selected>Pilih Barang</option>
                                                @foreach ($barang as $data)
                                                <option value="{{ $data['id_barang'] }}">{{ $data['id_barang'] }} | {{ $data['nama_barang'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="start_date">Dari :</label>
                                        <input type="date" id="start_date" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="end_date">Sampai :</label>
                                        <input type="date" id="end_date" class="form-control">
                                    </div>
                                    <div class="col-sm-2">
                                        <label><br></label><br>
                                        <button id="refresh-btn" class="btn text-white" style="background: rgb(27, 96, 255);"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="card mb-3">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="basic-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Kode Transaksi</th>
                                                <th>Nama Barang</th>
                                                <th>Qty</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="col-lg-5 ml-auto">
                                        <label for=""><br></label>
                                        @if($action)
                                        <div>
                                            <button class="btn text-white" style="background: blue;" id="btnPrint" target="_blank"><i class="uil-print"></i> Print PDF</button>
                                            <button class="btn text-white" style="background: green;" id="btnExport" target="_blank"><i class="uil-print"></i> Print Excel</button>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="">Jumlah Stok</label>
                                        <input type="text" id="hasil-perhitungan" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    </div>
    <!-- end row -->
</div>


<div id="emptyDataModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-2 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="bi bi-database-slash text-warning"></i> Stok Kosong</h5>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <div class="ps-3 pe-3">
                    <div class="">
                        <div class="mb-3">
                            Tidak bisa melakukan proses print
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="emptyDataBarcode" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-2 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="bi bi-database-slash text-warning"></i> Pilih Barang</h5>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <div class="ps-3 pe-3">
                    <div class="">
                        <div class="mb-3">
                            Pilih Barang terlebih dahulu
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
        paging: false,
        dom: '<"left"l>ftr<"right"ip>',
        serverSide: false,
        info: false,
        order: [[0, 'desc']],
        ajax: {
            url: '{!! route('data.daftarStok') !!}',
            data: function(d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.selected_barcode = $('#opsi-laporan-stok').val();
            },
            dataSrc: function(json) {
                return json.data; // Ambil data dari respons JSON
            }
        },
        columns: [
            { data: 'tanggal', name: 'tanggal' },
            { data: 'kode_transaksi', name: 'kode_transaksi' },
            { data: 'nama_barang', name: 'nama_barang' },
            { data: 'qty', name: 'qty' },
            { data: 'sts_inout', name: 'sts_inout'},
        ],
        language: {
            search: '',
            searchPlaceholder: 'Search...',
        }
    });

    function updateHasilPerhitungan() {
        var selectedBarcode = $('#opsi-laporan-stok').val();
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        if (selectedBarcode) {
            $.ajax({
                url: '{!! route('data.calculate') !!}',
                type: 'GET',
                data: {
                    id_barang: selectedBarcode,
                    start_date: startDate,
                    end_date: endDate,
                },
                success: function(response) {
                    var hasil = response.total;
                    $('#hasil-perhitungan').val(hasil);
                },
                error: function() {
                    console.error('Terjadi kesalahan dalam mengambil data.');
                }
            });
        } else {
            $('#hasil-perhitungan').val('');
        }
    }

    // Event change pada dropdown
    $('#opsi-laporan-stok').on('change', function () {
        table.ajax.reload();
        updateHasilPerhitungan();
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
        updateHasilPerhitungan();
    });

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

    $('#opsi-laporan-stok').on('change', function () {
        var selectedBarcode = $(this).val();

        getDataByBarcode(selectedBarcode, function (data) {
            table.clear().draw();
            table.rows.add(data).draw();
        });
        table.ajax.reload(null, false);
        updateHasilPerhitungan();
    });

    function printPDF() {
        var selectedBarcode = $('#opsi-laporan-stok').val();

        if (!selectedBarcode) {
            $('#emptyDataBarcode').modal('show'); // Tampilkan modal jika selectedBarcode kosong
            return; // Hentikan eksekusi fungsi
        }

        $.ajax({
            url: '/laporan-stok/print-stok',
            method: 'GET',
            data: {
                selected_barcode: selectedBarcode,
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                if (table.data().count() === 0) {
                    $('#emptyDataModal').modal('show'); // Tampilkan modal jika data kosong
                } else {
                    var blobUrl = URL.createObjectURL(response);
                    window.open(blobUrl);
                }
            },
            error: function() {
                console.error('Terjadi kesalahan saat mencetak PDF.');
            }
        });
    }

    function exportExcel() {
        var selectedBarcode = $('#opsi-laporan-stok').val();

        if (!selectedBarcode) {
            alert('Please select a barcode.');
            return;
        }

        $.ajax({
            url: '/laporan-stok/export-stok',
            method: 'GET',
            data: {
                selected_barcode: selectedBarcode,
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val()
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

                // Create a temporary URL for the blob
                var blobUrl = URL.createObjectURL(blob);

                // Create a temporary anchor element to trigger the download
                var link = document.createElement('a');
                link.href = blobUrl;
                link.download = 'laporan-stok.xlsx';

                // Trigger the download
                link.click();

                // Clean up
                URL.revokeObjectURL(blobUrl);
            },
            error: function() {
                console.error('Error exporting Excel.');
            }
        });
    }




    $(document).ready(function() {
        $('#btnPrint').click(function() {
            printPDF();
        });
    });

    $(document).ready(function() {
        $('#btnExport').click(function() {
            exportExcel();
        });
    });


    displayDateRange();
    updateHasilPerhitungan();
});

</script>
@endsection
