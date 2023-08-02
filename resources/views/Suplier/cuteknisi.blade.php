@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', $title)
@section('content-page')
@php
    $today          =  $carbon::now()->isoFormat('dddd, D MMMM Y');
    $ktp            =  $edit['ktp'] ?? 'ktp.png';
    $roles          =  $edit['role'] ?? '';
    $groups         =  $edit['group'] ?? '';
    $provinsis      =  $edit['provinsi'] ?? '';
    $kabupatens     =  $edit['kabupaten'] ?? '';
    $kecamatans     =  $edit['kecamatan'] ?? '';
    $desas          =  $edit['desa'] ?? '';
    $no             =  $generate + 1;
    $kode           =  sprintf("%02s", $no).date('Y');
@endphp


<div class="container-fluid">
    @include('layouts.main.breadcrumb')


    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">{{ $title }}</h3>
                            <p class="mb-0">Melakukan proses {{ $title }} hanya bisa dilakukan Administrator</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                    class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-setusers.png') }}" class="img-fluid" width="150">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <style>
                        .rounded1{
                            width: 150px;
                            height: 150px;
                            border-radius: 50%;
                        }
                    </style>
                    <form action="{{ route('posts.teknisi') }}" method="POST" class="row" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="no" value="{{ $edit['no_urut_teknisi'] ?? $no }}">
                        <input type="hidden" name="id" value="{{ $edit['id'] ?? '' }}">
                        <input type="hidden" name="kode" value="{{ $edit['kode_teknisi'] ?? '' }}">

                        <div class="col-xl-3 col-12 col-md-12">

                            <div class="mb-2">
                                <label for="nik" class="form-label">NIK|SIM|Paspor</label>
                                <input type="number" maxlength="18" id="nik" class="form-control text-capitalize @error('nik') is-invalid @enderror" name="nik" value="{{ $edit['nik_teknisi'] ?? '0000000000000000' }}" placeholder="NIK|SIM|Paspor">
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-2 mt-3 mt-xl-0">
                                <div class="col-lg-12">
                                    <center>
                                        <img id="ktp" src="{{ URL::to('storage/ktp/'. $ktp) }}" class="img-fluid" width="200">
                                    </center>
                                </div>
                                <div class="col-lg-12">
                                    <div class="py-2 d-flex flex-row justify-content-center">
                                        <input type="hidden" value="{{ $edit['ktp'] ?? 'ktp.png' }}" name="ktp">
                                        <center>
                                            <div class="col-lg-6 col-5 col-md-4">
                                                <label for="" class="mb-2">Foto KTP</label>
                                                <input type='file' name="imgKtp" id="imgKtp" accept="image/*" class="form-control mb-2 custom-file-input btn btn-sm btn-dark">

                                                <p class="text-secondary text-center" style="font-size: 12px; font-weight: 200;">Max image size 100 kb format PNG, JPG, GIF.</p>
                                            </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-9">
                            <div class="row">
                                <div class="col-xl-3">
                                    <div class="mb-2">
                                        <label for="id" class="form-label">No Teknisi</label>
                                        <div class="input-group">
                                            <input type="text" name="kode_teknisi" class="form-control @error('kode_teknisi') is-invalid @enderror" value="{{ $edit['kode_teknisi'] ?? old('kode_teknisi') ?? $kode }}" autocomplete="off">
                                            @error('kode_teknisi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="mb-2">
                                        <label for="perusahaan" class="form-label">Nama Teknisi</label>
                                        <input type="text" id="perusahaan" name="nama_teknisi" class="form-control @error('nama_teknisi') is-invalid @enderror" placeholder="Nama Teknisi" value="{{ $edit['nama_teknisi'] ?? old('nama_teknisi') }}" autocomplete="off">
                                        @error('nama_teknisi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="mb-2">
                                        <label for="id" class="form-label">Tanggal Lahir</label>
                                        <div class="input-group">
                                            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ $edit['tanggal_lahir'] ?? old('tanggal_lahir') }}" autocomplete="off">
                                            @error('tanggal_lahir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="mb-2">
                                        <label for="phone" class="form-label">Telpon/Wa</label>
                                        <input type="text" id="telpon" class="form-control @error('telpon') is-invalid @enderror" name="telpon" value="{{ $edit['telpon_teknisi'] ?? old('telpon') }}" placeholder="+62" autocomplete="off">
                                        @error('telpon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="mb-2">
                                        <label for="email" class="form-label">Email (Opsional)</label>
                                        <input type="text" id="email" class="form-control" name="email" value="{{ $edit['email'] ?? old('email') ?? 'example'.'gmail.com' }}" placeholder="Email" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="mb-2">
                                        <label for="group" class="form-label">Team</label>
                                        <select class="form-control select2 @error('group') is-invalid @enderror" data-toggle="select2" name="group">
                                            <option value="" selected disabled>Group Users</option>
                                            @foreach ($group as $item)
                                                @if($title == "Ubah Data Teknisi")
                                                    @if($groups == $item['name_group'] ?? '')
                                                        <option value="{{ $item->name_group }}" selected>{{ $item->name_group }}</option>
                                                    @else
                                                        <option value="{{ $item->name_group }}">{{ $item->name_group }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $item->name_group }}">{{ $item->name_group }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('group')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="mb-2">
                                        <label for="provinsi" class="form-label">Provinsi</label>
                                        <select class="form-control select2 inProv @error('provinsi') is-invalid @enderror" data-toggle="select2" name="provinsi">
                                            <option selected disabled>Provinsi</option>
                                            @foreach ($provinsi as $item)
                                                @if($title == "Ubah Data Teknisi")
                                                    @if($provinsis == $item['prov_id'] ?? '')
                                                        <option value="{{ $item->prov_id }}" selected>{{ $item->prov_name }}</option>
                                                    @else
                                                        <option value="{{ $item->prov_id }}">{{ $item->prov_name }}</option>
                                                    @endif
                                                @else
                                                    <option value="{{ $item->prov_id }}">{{ $item->prov_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="mb-2">
                                        <div class="form-group form-group-default">
                                            <label class="form-label">Kabupaten</label>
                                            <select class="form-control select2 inKab @error('kabupaten') is-invalid @enderror" data-toggle="select2" name="kabupaten" required>
                                                <option selected disabled>Kabupaten</option>
                                                @if($title == "Ubah Data Teknisi")
                                                    @foreach ($kabupaten as $item)
                                                        @if($provinsis == $item->prov_id ?? '')
                                                            @if($kabupatens == $item->city_id ?? '')
                                                                <option value="{{ $item->city_id }}" selected>{{ $item->city_name }}</option>
                                                            @else
                                                                <option value="{{ $item->city_id }}">{{ $item->city_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('kabupaten')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="mb-2">
                                        <div class="form-group form-group-default">
                                            <label class="form-label">Kecamatan</label>
                                            <select class="form-control select2 inKec @error('kecamatan') is-invalid @enderror" data-toggle="select2" name="kecamatan" required>
                                                <option selected disabled>Kecamatan</option>
                                                @if($title == "Ubah Data Teknisi")
                                                    @foreach ($kecamatan as $item)
                                                        @if($kabupatens == $item->city_id ?? '')
                                                            @if($kecamatans == $item->dis_id ?? '')
                                                                <option value="{{ $item->dis_id }}" selected>{{ $item->dis_name }}</option>
                                                            @else
                                                                <option value="{{ $item->dis_id }}">{{ $item->dis_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('kecamatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="mb-2">
                                        <div class="form-group form-group-default">
                                            <label class="form-label">Desa</label>
                                            <select class="form-control select2 inDesa @error('desa') is-invalid @enderror" data-toggle="select2" name="desa" required>
                                                <option selected disabled>Desa</option>
                                                @if($title == "Ubah Data Teknisi")
                                                    @foreach ($desa as $item)
                                                        @if($kecamatans == $item->dis_id ?? '')
                                                            @if($desas == $item->subdis_id ?? '')
                                                                <option value="{{ $item->subdis_id }}" selected>{{ $item->subdis_name }}</option>
                                                            @else
                                                                <option value="{{ $item->subdis_id }}">{{ $item->subdis_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('desa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="maps" class="form-label">Maps (Opsional)</label>
                                <input type="text" id="maps" class="form-control @error('maps') is-invalid @enderror" name="maps" value="{{ $edit['maps'] ?? old('maps') }}" placeholder="maps" autocomplete="off">
                                @error('maps')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" placeholder="Alamat teknisi" >{{ $edit['alamat'] ?? old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="deskripsi_teknisi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi_teknisi" placeholder="Tuliskan teks disini..." >{{ $edit['deskripsi_teknisi'] ?? old('deskripsi_teknisi') }}</textarea>
                            </div>
                        </div>


                        <div class="col-xl-12 mt-2 text-end">
                            <a href="{{ url('app/data-teknisi') }}" class="btn btn-sm text-white" style="background: red;">Batal <i class="bi bi-x-lg"></i></a>
                            <button type="submit" class="btn btn-sm btn-info">Simpan <i class="bi bi-check-lg"></i></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>



<script src="{{ URL::to('assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.inProv').change(function(){
            var kab = $(this).val();
            if(kab){
                $.ajax({
                    type:"GET",
                    url: '/kabupaten/'+kab,
                    dataType: 'JSON',
                    success:function(data){
                        if(data){
                            $(".inKab").empty();
                            $(".inKab").append('<option selected disabled>Kabupaten</option>');
                            $(".inKec").empty();
                            $(".inKec").append('<option selected disabled>Kecamatan</option>');
                            $(".inDesa").empty();
                            $(".inDesa").append('<option selected disabled>Desa</option>');
                            $.each(data,function(key, kabupaten){
                                console.log('hallo')

                                $(".inKab").append('<option value="'+kabupaten.city_id+'">'+kabupaten.city_name+'</option>');
                            });
                        }else{
                            $(".inKab").empty();
                            $(".inKab").append('<option selected disabled>Kabupaten</option>');
                            $(".inKec").empty();
                            $(".inKec").append('<option selected disabled>Kecamatan</option>');
                            $(".inDesa").empty();
                            $(".inDesa").append('<option selected disabled>Desa</option>');
                        }
                    }
                });
            }else{
                $(".inKab").empty();
            }
        });

        $('.inKab').change(function(){
            var kec = $(this).val();
            if(kec){
                $.ajax({
                    type:"GET",
                    url: '/kecamatan/'+kec,
                    dataType: 'JSON',
                    success:function(data){
                        if(data){
                            $(".inKec").empty();
                            $(".inKec").append('<option selected disabled>Kecamatan</option>');
                            $(".inDesa").empty();
                            $(".inDesa").append('<option selected disabled>Desa</option>');
                            $.each(data,function(key, kecamatan){
                                console.log('hallo')

                                $(".inKec").append('<option value="'+kecamatan.dis_id+'">'+kecamatan.dis_name+'</option>');
                            });
                        }else{
                            $(".inKec").empty();
                            $(".inKec").append('<option selected disabled>Kecamatan</option>');
                            $(".inDesa").empty();
                            $(".inDesa").append('<option selected disabled>Desa</option>');
                        }
                    }
                });
            }else{
                $(".inKec").empty();
            }
        });

        $('.inKec').change(function(){
            var desa = $(this).val();
            if(desa){
                $.ajax({
                    type:"GET",
                    url: '/desa/'+desa,
                    dataType: 'JSON',
                    success:function(data){
                        if(data){
                            $(".inDesa").empty();
                            $(".inDesa").append('<option selected disabled>Desa</option>');
                            $.each(data,function(key, desa){
                                console.log('hallo')

                                $(".inDesa").append('<option value="'+desa.subdis_id+'">'+desa.subdis_name+'</option>');
                            });
                        }else{
                            $(".inDesa").empty();
                            $(".inDesa").append('<option selected disabled>Desa</option>');
                        }
                    }
                });
            }else{
                $(".inDesa").empty();
            }
        });
    });
</script>


@endsection

