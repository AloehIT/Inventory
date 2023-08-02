@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Daftar Barang Masuk')
@section('content-page')
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') <i class="bi bi-box-arrow-in-left text-success"></i></h3>
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
                                    <i class="mdi mdi-menu"></i> Manajemen Barang Masuk
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="{{ route('create.barang-masuk') }}" class="dropdown-item" class="btn btn-primary"><i class="uil-plus"></i> Daftarkan Barang Baru</a>
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
                                                <th>ID Transaksi</th>
                                                <th>Keterangan</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Total Qty</th>
                                                <th>Status</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barangMasuk as $no => $data)
                                                <tr>
                                                    <div hidden>{{ $id = $data['id_bm'] }}</div>
                                                    <td>
                                                        {{ $no+1 }}
                                                    </td>
                                                    <td>
                                                        <a data-bs-toggle="modal" data-bs-target="#detail{{ $id }}" class="text-body" style="cursor: pointer;"><i class="bi bi-info-circle-fill text-info"></i> {{ $data['id_bm'] ?? '' }}</a>
                                                    </td>

                                                    <td>
                                                        {{ $data['deskripsi_barang_masuk'] ?? 'tidak ada' }}
                                                    </td>

                                                    <td>
                                                        {{ $carbon::parse($data['tanggal_masuk'] ?? '')->isoFormat('dddd, D MMMM Y') }}
                                                    </td>

                                                    <td>
                                                        {{ $data['total_qty'] ?? 'tidak ada' }} {{ $data['satuan'] ?? '' }}
                                                    </td>

                                                    <td>
                                                        @if($data['status'] == 'draft')
                                                            <span class="text-info"><i class="bi bi-bookmarks-fill"></i> Draft</span>
                                                        @else
                                                            <span class="text-success"><i class="bi bi-patch-check-fill"></i> Approve</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <a href="#" type="button" onclick="return confirm('Apakah anda yakin ingin menhapus Barang : {{ $data['nama_barang'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="mdi mdi-delete text-danger"></i></a>
                                                    </td>
                                                </tr>
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


@include('inventory.barang-masuk.tabletransaksi')
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
