@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Daftar Barang')
@section('content-page')
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') ERP</h3>
                            <p class="mb-0">Data Seluruh @yield('title') yang terdaftar pada mikrotik</p>
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
                                    <i class="mdi mdi-menu"></i> Manajemen Barang
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a href="{{ route('create.barang') }}" class="dropdown-item" class="btn btn-primary"><i class="uil-plus"></i> Daftarkan Barang Baru</a>
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
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Barcode</th>
                                                <th>Kategori</th>
                                                <th>Didaftarkan</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barang as $no => $data)
                                                <tr>
                                                    <div hidden>{{ $id = $data['id'] }}</div>

                                                    <td>
                                                        {{ $no+1 }}
                                                    </td>
                                                    <td class="col-2">
                                                        {{ $data['kode_barang'] ?? '' }}
                                                    </td>

                                                    <td>
                                                        <a data-bs-toggle="modal" data-bs-target="#detail{{ $data['id'] ?? '' }}" class="text-body" style="cursor: pointer;"><i class="bi bi-info-circle-fill text-info"></i> {{ $data['nama_barang'] ?? '' }}</a>
                                                    </td>

                                                    <td>
                                                        {!! DNS1D::getBarcodeHTML("$data->barcode", 'PHARMA') !!} {{ $data->barcode }}
                                                    </td>

                                                    <td>
                                                        {{ $data['kategori'] ?? 'tidak ada' }}
                                                    </td>

                                                    <td>
                                                        {{ $carbon::parse($data['created_at'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}
                                                    </td>

                                                    <td>
                                                        <a class="action-icon" href="{{ route('update.barang', $id) }}"> <i class="mdi mdi-square-edit-outline text-info"></i></a>
                                                        <a href="{{ route('delete.barang', $id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus Barang : {{ $data['nama_barang'] }} ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="mdi mdi-delete text-danger"></i></a>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="detail{{ $id }}" tabindex="-1" role="dialog"
                                                    aria-hidden="true">
                                                    @php
                                                        $gambar =  $data['gambar'] ?? 'upload.gif';
                                                    @endphp
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
                                                                    <div class="mb-3">
                                                                        <center>
                                                                            <p class="mb-0">Gambar Barang</p>
                                                                            <img src="{{ asset('storage/barang/'. $gambar) }}" class="img-fluid" width="200">
                                                                        </center>
                                                                    </div>

                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="font-weight-bold">Tipe :</span>
                                                                        <span class="text-muted">{{ $data['kategori'] ?? '' }}</span>
                                                                    </div>

                                                                    <div class="d-flex justify-content-between mb-2">
                                                                        <span class="font-weight-bold">Ditambahkan Oleh :</span>
                                                                        <span class="text-muted">{{ $data['username'] ?? '' }}</span>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <center>
                                                                            <span class="font-weight-bold">Barcode :</span>
                                                                            <span>{!! DNS1D::getBarcodeHTML("$data->barcode", 'PHARMA' ) !!} {{ $data->barcode }}</span>
                                                                        </center>
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
