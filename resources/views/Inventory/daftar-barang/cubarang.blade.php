@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', $title)
@section('content-page')
@php
    $gambar  =  $edit['gambar'] ?? 'upload.gif';
    $upkat   =  $edit['kategori'] ?? '';
    $upsat   =  $edit['satuan_id'] ?? '';
    $tanggal =  date('Y-n-j');
    $bar     =  date('Ynj');
    $no      =  $generate + 1;
    $random  =  sprintf("%04s", $no);
    $kode    =  'BRG-'. $tanggal.'-'.$random;
    $barcode =  $random.$bar;
@endphp
<div class="container-fluid">
    @include('layouts.main.breadcrumb')


    <form action="{{ route('posts.barang') }}" method="POST" enctype="multipart/form-data">
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

            @csrf
            <div class="col-xl-3">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="info-box card px-4 pt-3 pb-5">
                            <h4>Gambar Barang</h4>
                            <div class="mt-3 mb-0">
                                <div class="col-lg-12 mb-4 mt-0 d-flex justify-content-center">
                                    <img id="blah" src="{{ asset('storage/barang/'. $gambar) }}" alt="your image"
                                    class="img-fluid">
                                </div>
                                <input name="gambar" accept="image/*" type='file' id="imgInp" class="form-control @error('gambar') is-invalid @enderror"/>
                                <input type="hidden" value="{{ $edit['gambar'] ?? 'upload.gif' }}" name="gambars">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-9">
                <div class="card p-4">

                    <div class="row gy-4 g-2">
                        <input type="hidden" name="id" value="{{ $edit['id'] ?? '' }}">
                        <input type="hidden" name="id_barang" value="{{ $edit['id_barang'] ?? '' }}">

                        <div class="col-sm-3 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Kode Barang</label>
                                <input name="kode_barang" type="text" class="form-control mb-0 @error('kode_barang') is-invalid @enderror" placeholder="Kode Barang" maxlength="15" value="{{ $edit['kode_barang'] ?? $kode }}" autocomplete="off" readonly>
                            </div>
                        </div>

                        <div class="col-sm-9 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Nama Barang</label>
                                <input name="nama_barang" type="text" class="form-control mb-0 @error('nama_barang') is-invalid @enderror" placeholder="Nama Barang" value="{{ $edit['nama_barang'] ?? '' }}" autocomplete="off">
                            </div>
                        </div>


                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Kategori</label>
                                <select class="form-control select2 @error('kategori') is-invalid @enderror" data-toggle="select2" name="kategori">
                                    <option selected disabled>Kategori</option>
                                    @foreach($kategori as $data)
                                        @if($data['name_kategori'] == $upkat ?? '')
                                            <option value="{{ $data['name_kategori'] ?? '' }}" selected>{{ $data['name_kategori'] ?? '' }}</option>
                                        @else
                                            <option value="{{ $data['name_kategori'] ?? '' }}">{{ $data['name_kategori'] ?? ''
                                                }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Barcode</label>
                                <input name="barcode" type="number" min="0" class="form-control mb-0 @error('barcode') is-invalid @enderror"
                                    placeholder="Barcode" value="{{ $edit['barcode'] ?? $barcode }}" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Satuan Barang</label>
                                <select class="form-control select2 @error('satuan') is-invalid @enderror" data-toggle="select2" name="satuan">
                                    <option selected disabled>Satuan</option>
                                    @foreach ($satuan as $data)
                                        @if($data['id'] == $upsat ?? '')
                                            <option value="{{ $data['id'] ?? '' }}" selected>{{ $data['satuan'] ?? '' }} </option>
                                        @else
                                            <option value="{{ $data['id'] ?? '' }}">{{ $data['satuan'] ?? '' }} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 mb-0">
                            <div calass="form-group form-group-default">
                                <label>Deskripsi Lainnya (Opsional)</label>
                                <textarea name="deskripsi" id="deskripsi" type="text" class="form-control"
                                    placeholder="Tuliskan teks disini...">{{ $edit['deskripsi'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-end">
                            <a href="{{ url('app/daftar-barang') }}" class="btn btn-sm text-white" style="background: red;">Cancel <i class="bi bi-x-lg"></i></a>
                            <button type="submit" class="btn btn-sm btn-info">Simpan <i class="bi bi-check-lg"></i></button>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </form>
</div>

<script src="{{ URL::to('assets/js/jquery-3.3.1.min.js') }}"></script>

<script>
    imgInp.onchange = evt => {
        const [file] = imgInp.files
        if (file) {
            blah.src = URL.createObjectURL(file)
        }
    }
</script>

<script>
    function generateKodeTransaksi(){
        var tanggal = new Date().toLocaleDateString('id-ID').split('/').reverse().join('-');
        var randomNumber = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        var kodeTransaksi = 'BRG-' + tanggal + '-' + randomNumber;

        $('#kode_barang').val(kodeTransaksi);
        return kodeTransaksi;
    }

    $(document).ready(function(){
        generateKodeTransaksi();
    });
</script>

@endsection
