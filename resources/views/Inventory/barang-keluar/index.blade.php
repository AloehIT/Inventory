@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Barang Keluar')
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
                            <img src="{{ asset('assets/icon/bg-barang.png') }}" class="img-fluid" width="150">
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
                                    <i class="mdi mdi-menu"></i> Manajemen Barang Keluar
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="{{ route('barangkeluaradd.inventory') }}" class="dropdown-item" class="btn btn-primary"><i class="uil-plus"></i> Daftarkan Barang Keluar</a>
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
                                                <th>Kode Transaksi</th>
                                                <th>Nama Barang</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Stok Keluar</th>
                                                <th>Teknisi</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($barangKeluar as $data)
                                                <tr>
                                                    <div hidden>{{ $id = $data['kode_transaksi'] }}</div>

                                                    <td>
                                                        {{ $data['kode_transaksi'] ?? '' }}
                                                    </td>

                                                    <td>
                                                        <a data-bs-toggle="modal" data-bs-target="#detail{{ $id }}" class="text-body" style="cursor: pointer;"> <i class="bi bi-info-circle-fill text-info"></i> {{ $data['nama_barang'] ?? '' }}</a>
                                                    </td>

                                                    <td>
                                                        {{ $carbon::parse($data['tanggal_keluar'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}
                                                    </td>

                                                    <td>
                                                        {{ $data['jumlah_keluar'] ?? '0' }} {{ $data['satuan'] ?? '' }}
                                                    </td>

                                                    <td>
                                                        {{ $data['nama_teknisi'] ?? 'unknown' }}
                                                    </td>

                                                    <td>
                                                        <a href="{{ route('barangkeluar.inventory.del', $id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menhapus transaksi : {{ $data['nama_barang'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="mdi mdi-delete text-danger"></i></a>
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
                                                                            <h5 class="text-uppercase mb-0"><i class="uil-box text-warning"></i> {{ $data['nama_barang'] ?? '' }}</h5>
                                                                            <p class="mb-4">{{ $perusahaan['value'] ?? '' }}</p>
                                                                        </div>

                                                                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                                                                   </div>

                                                                    <span class="theme-color">Details</span>
                                                                    <div class="mb-3">
                                                                        <hr class="new1">
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Nama Teknisi :</span>
                                                                        <span class="text-muted">{{ $data['nama_teknisi'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Jumlah Barang Keluar :</span>
                                                                        <span class="text-muted">{{ $data['jumlah_keluar'] ?? '' }} {{ $data['satuan'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Dari Gudang :</span>
                                                                        <span class="text-muted">{{ $data['name'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Acc Barang Keluar :</span>
                                                                        <span class="text-muted">{{ $data['nama_users'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-4">
                                                                        <span class="font-weight-bold">Role Account Acc :</span>
                                                                        <span class="text-muted">{{ $data['role'] ?? '' }}</span>
                                                                    </div>

                                                                    <span class="theme-color">Detail Barang Keluar</span>
                                                                    <div class="">
                                                                        <hr class="new1">
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Lokasi Kerja :</span>
                                                                        <span class="text-muted">{{ $data['lokasi_kerja'] ?? '' }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Pada Tanggal :</span>
                                                                        <span class="text-muted">{{ $carbon::parse($data['tanggal_keluar'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}</i></span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-3">
                                                                        <span class="font-weight-bold">Maps Lokasi :</span>
                                                                        <span class="text-muted"><a href="{{ $data['maps'] }}" class="" target="_blank"><i class="bi bi-geo-fill"></i> Klik disini</a></span>
                                                                    </div>

                                                                    <div class="mb-4">
                                                                        <p class="font-weight-bold mb-0">Keperluan :</p>
                                                                        <p class="text-muted text-start">{{ $data['deskripsi_barang_keluar'] ?? 'deskripsi dikosongkan' }}</p>
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        // membatasi jumlah inputan
        var maxGroup = 10;

        //melakukan proses multiple input
        $(".addMore").click(function(){
            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
            }else{
                alert('Maximum '+maxGroup+' groups are allowed.');
            }
        });

        //remove fields group
        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });
    });
</script>
@endsection
