@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', $title)
@section('content-page')

@php
    $gambar =  $edit['gambar'] ?? 'upload.gif';
    $upkat =  $edit['kategori'] ?? '';
    $upsat =  $edit['satuan_id'] ?? '';
@endphp
<div class="container-fluid">
    @include('main.breadcrumb')


    <form action="{{ route('barangkeluar.inventory.post') }}" method="POST" enctype="multipart/form-data">
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

            @csrf
            <div class="col-xl-12">
                <div class="card p-4">

                    <div class="row gy-4 g-2">

                        <input type="hidden" name="id" value="{{ $edit['id'] ?? '' }}">

                        <div class="col-sm-3 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Kode Transaksi</label>
                                <input name="kode_transaksi" type="text" class="form-control mb-0"
                                    placeholder="Kode Barang" maxlength="15" id="kode_transaksi" readonly>
                            </div>
                        </div>

                        <div class="col-sm-6 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Lokasi Gudang</label>
                                <select class="form-control @error ('lokasi') is-invalid @enderror select2" data-toggle="select2" name="lokasi">
                                    <option selected disabled>Gudang :</option>
                                    @foreach ($lokasi as $data)
                                        <option value="{{ $data['id'] ?? '' }}">{{ $data['name'] ?? '' }} </option>
                                    @endforeach
                                </select>
                                @error('lokasi')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-sm-3 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Tanggal Keluar</label>
                                <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control @error ('tanggal_keluar') is-invalid @enderror" >
                                @error('tanggal_keluar')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- info lokasi kerja --}}
                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Nama Teknisi</label>
                                <select class="form-control select2 @error ('id_teknisi') is-invalid @enderror" data-toggle="select2" name="id_teknisi">
                                    <option selected disabled>Teknisi</option>
                                    @foreach($teknisi as $data)
                                    <option value="{{ $data['id'] ?? '' }}">{{ $data['nama_teknisi'] ?? '' }}</option>
                                    @endforeach
                                </select>
                                @error('id_teknisi')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Lokasi Kerja</label>
                                <input name="lokasi_kerja" type="text" class="form-control @error('lokasi_kerja') is-invalid @enderror mb-0"
                                    placeholder="Lokasi..">
                                @error('lokasi_kerja')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Maps</label>
                                <input name="maps" type="link" class="form-control @error('maps') is-invalid @enderror mb-0"
                                    placeholder="maps">
                                @error('maps')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- info lokasi kerja --}}

                        <div class="col-md-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Nama Barang</label>
                                <select class="form-control select2 barang @error ('nama_barang') is-invalid @enderror" data-toggle="select2" name="nama_barang">
                                    <option selected disabled>Nama Barang</option>
                                    @foreach($barang as $data)
                                    <option value="{{ $data['nama_barang'] ?? '' }}">{{ $data['nama_barang'] ?? '' }}</option>
                                    @endforeach
                                </select>
                                @error('nama_barang')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Stok Keluar</label>
                                <input name="jumlah_keluar" type="number" min="0" class="form-control @error ('jumlah_keluar') is-invalid @enderror mb-0"
                                    placeholder="Jumlah Barang Masuk" value="{{ $edit['jumlah_keluar'] ?? '' }}">
                                @error('jumlah_keluar')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Total Stok</label>
                                <input name="stok" type="number" min="0" class="form-control @error('stok') is-invalid @enderror mb-0 totalStok"
                                    placeholder="Jumlah Barang" readonly>
                                @error('stok')
                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12 mb-0">
                            <div calass="form-group form-group-default">
                                <label>Keterangan</label>
                                <textarea name="deskripsi" id="deskripsi" type="text" class="form-control"
                                    placeholder="Comment">{{ $edit['deskripsi'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-end">
                            <a href="{{ route('barangkeluar.inventory') }}" class="btn btn-sm text-white" style="background: red;">Batal <i
                                class="bi bi-x-lg"></i></a>
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
        var kodeTransaksi = 'BRG-OUT-' + tanggal + '-' + randomNumber;

        $('#kode_transaksi').val(kodeTransaksi);
        return kodeTransaksi;
    }

    $(document).ready(function(){
        generateKodeTransaksi();
    });
</script>


<script>
    $(document).ready(function() {
        $('.barang').on('change', function() {
            var barang = $(this).val();

            if(barang) {
                console.log('hallo')
                $.ajax({
                    url: '/barang/'+barang,
                    type: "GET",
                    data : {"_token":"{{ csrf_token() }}"},
                    dataType: "json",
                    success:function(data)
                    {
                        if(data){
                            $('.totalStok').val(0);

                            $.each(data, function(key, barang){
                                $('.totalStok').val(barang.stok);
                            });

                        }else{
                            $('.totalStok').val(0);
                        }
                    }
                });
            }else{
                $('.totalStok').val(0);
            }
        });
    });

</script>

<script>
    // Mendapatkan tanggal hari ini
    var today = new Date();

    // Mendapatkan nilai tahun, bulan, dan tanggal
    var year = today.getFullYear();
    var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Ditambahkan +1 karena indeks bulan dimulai dari 0
    var day = today.getDate().toString().padStart(2, '0');

    // Menggabungkan nilai tahun, bulan, dan tanggal menjadi format "YYYY-MM-DD"
    var formattedDate = year + '-' + month + '-' + day;

    // Mengisi nilai input field dengan tanggal hari ini
    document.getElementById('tanggal_keluar').value = formattedDate;
</script>
@endsection
