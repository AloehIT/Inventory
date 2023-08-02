@extends('layouts.app')
@section('title', 'Laporan Barang Keluar')
@section('content-page')
<div class="container-fluid">
    @include('main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') ERP <i class="bi bi-box-arrow-in-right text-danger"></i></h3>
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

                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="ml-auto text-end">
                                    <a href="javascript:void(0)" class="btn btn-danger" id="print-barang-keluar"><i
                                            class="uil-print"></i> Print PDF</a>
                                </div>
                                <div class="form-group">
                                    <form id="filter_form" action="/laporan-barang-keluar/get-data" method="GET">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label>Pilih Tanggal Mulai :</label>
                                                <input type="date" class="form-control" name="tanggal_mulai"
                                                    id="tanggal_mulai">
                                            </div>
                                            <div class="col-md-5">
                                                <label>Pilih Tanggal Selesai :</label>
                                                <input type="date" class="form-control" name="tanggal_selesai"
                                                    id="tanggal_selesai">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                <button type="button" class="btn btn-danger"
                                                    id="refresh_btn">Refresh</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table id="table_id"
                                        class="display basic-datatable table dt-responsive nowrap w-100"
                                        style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Transaksi</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Nama Barang</th>
                                                <th>Jumlah Keluar</th>
                                                <th>Teknisi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabel-laporan-barang-keluar">
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
        var table = $('#table_id').DataTable({ paging: true});

        loadData(); // Panggil fungsi loadData saat halaman dimuat

        $('#filter_form').submit(function(event) {
            event.preventDefault();
            loadData(); // Panggil fungsi loadData saat tombol filter ditekan
        });

        $('#refresh_btn').on('click', function() {
            refreshTable();
        });

        //Fungsi load data berdasarkan range tanggal_mulai dan tanggal_selesai
        function loadData() {
            var tanggalMulai = $('#tanggal_mulai').val();
            var tanggalSelesai = $('#tanggal_selesai').val();

            $.ajax({
                url: '/laporan-barang-keluar/get-data',
                type: 'GET',
                dataType: 'json',
                data: {
                    tanggal_mulai: tanggalMulai,
                    tanggal_selesai: tanggalSelesai
                },
                success: function(response) {
                    table.clear().draw();

                    if (response.length > 0) {
                        $.each(response, function(index, item) {
                            var row = [
                                    (index + 1),
                                    item.kode_transaksi,
                                    item.tanggal_keluar,
                                    item.nama_barang,
                                    item.jumlah_keluar+item.satuan,
                                    item.nama_teknisi
                                ];
                               table.row.add(row).draw(false);
                        });
                    } else {
                        var emptyRow = ['','Tidak ada data yang tersedia.', '', '', '', ''];
                        table.row.add(emptyRow).draw(false); // Tambahkan baris kosong ke DataTable
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        //Fungsi Refresh Tabel
        function refreshTable(){
            $('#filter_form')[0].reset();
            loadData();
        }

        //Print barang keluar
        $('#print-barang-keluar').on('click', function(){
            var tanggalMulai    = $('#tanggal_mulai').val();
            var tanggalSelesai  = $('#tanggal_selesai').val();

            var url = '/laporan-barang-keluar/print-barang-keluar';

            if(tanggalMulai && tanggalSelesai){
                url += '?tanggal_mulai=' + tanggalMulai + '&tanggal_selesai=' + tanggalSelesai;
            }

            window.location.href = url;
        });

    });
</script>
@endsection
