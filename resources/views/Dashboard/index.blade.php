@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content-page')
@php
    $today = $carbon::now()->isoFormat('dddd, D MMMM Y');
@endphp


<div class="container-fluid">
    @include('Layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark text-capitalize">Hallo {{ $auth['username'] ?? '' }}, </h3>
                            <p class="mb-0">Selamat datang di portal admin Inventory {{ $perusahaan['value'] ?? '' }}</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-barang.png') }}" class="img-fluid" width="150">
                        </div>
                    </div>
                </div>

            </div>
        </div>



    </div>

</div>

@endsection
