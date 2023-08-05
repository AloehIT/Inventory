@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Pengaturan Umum')
@section('content-page')
@php
    $gambar =  $gambar['value'] ?? 'upload.gif';
@endphp

<div class="container-fluid">
    @include('layouts.main.breadcrumb')
    <div class="col-12">
        <div class="card recent-sales overflow-auto">
            <div class="card-body">
                <div class="row justify-content-between mx-3">
                    <div class="col-lg-6 col-md-6  col-12 py-3">
                        <p class="mb-0">{{ $perusahaan['value'] ?? '' }}</p>
                        <h3 class="text-dark">@yield('title')</h3>
                        <p class="mb-0 text-lowercase">Atur @yield('title') anda</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12 text-end p-3">
                        <img src="{{ asset('assets/icon/bg-pengaturanumum.png') }}" class="img-fluid" width="120">
                    </div>
                </div>
            </div>

        </div>
    </div>


    <form action="{{ url('app/pengaturan/posts') }}" method="POST" class="row" enctype="multipart/form-data">
        @csrf
        <div class="col-xl-3">
            <div class="row">
                <div class="col-lg-12">
                    <div class="info-box card px-4 pt-3 pb-5">
                        <h4>Logo Perusahaan</h4>
                        <div class="mt-3 mb-0">
                            <div class="col-lg-12 mb-4 mt-0 d-flex justify-content-center">
                                <img id="blah" src="{{ asset('storage/logo/'. $gambar) }}" alt="your image" class="img-fluid">
                            </div>
                            <input type="hidden" name="setting_gambar" value="Config">
                            <input type="hidden" name="name_config_gambar" value="conf_logo">
                            <input name="gambar" accept="image/*" type='file' id="imgInp" class="form-control" />
                            <input type="hidden" value="{{ $gambar['value'] ?? 'upload.gif' }}" name="gambars">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card p-4">

                <div class="row gy-4 g-2">
                    <input type="hidden" name="setting[]" value="Config">
                    <input type="hidden" name="name_config[]" value="conf_perusahaan">
                    <div class="col-sm-12 mb-0">
                        <div class="form-group form-group-default">
                            <label><span class="text-primary">!</span>Nama Perusahaan</label>
                            <input name="value[]" type="text" id="name"
                                class="form-control mb-0 @error ('value') is-invalid @enderror"
                                placeholder="Nama perusahaan" value="{{ $name['value'] ?? '' }}" autocomplete="off">

                            @error('value')
                            <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-sm-12 mb-0">
                        <div class="form-group form-group-default">
                            <label><span class="text-primary">!</span>No. HP</label>
                            <input type="hidden" name="setting[]" value="Config">
                            <input type="hidden" name="name_config[]" value="conf_phone">
                            <input name="value[]" type="number" id="phone"
                                class="form-control mb-0 @error ('value') is-invalid @enderror" placeholder="No. Hp"
                                value="{{ $phone['value'] ?? '' }}" autocomplete="off">

                            @error('value')
                            <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-12 mb-0">
                        <div calass="form-group form-group-default">
                            <input type="hidden" name="setting[]" value="Config">
                            <input type="hidden" name="name_config[]" value="conf_alamat">
                            <label>Alamat</label>
                            <textarea name="value[]" id="alamat" type="text"
                                class="form-control @error('value') is-invalid @enderror" placeholder="Alamat"
                                autocomplete="off">{{ $alamat['value'] ?? '' }}</textarea>
                            @error('value')
                            <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        <a href="{{ url('app/dashboard') }}" class="btn btn-sm text-white"
                            style="background: red;">Batal <i class="bi bi-x-lg"></i></a>
                        <button type="submit" class="btn btn-sm btn-info">Simpan <i class="bi bi-check-lg"></i></button>
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
@endsection
