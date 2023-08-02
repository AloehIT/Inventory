@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Data Teknisi')
@section('content-page')
@php
    $id = $kode->kode_uniq ?? 'TEK';
    $urutan = $teknisi->max('id') + 1;
    $kode = $id . '-' .sprintf("%04s", $urutan). date('Y');
@endphp
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title')</h3>
                            <p class="mb-0">Data Seluruh @yield('title') yang terdaftar pada system</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                    class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <img src="{{ asset('assets/icon/bg-teknisi.jpg') }}" class="img-fluid" width="150">
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
                                    <i class="mdi mdi-menu"></i> Manajemen @yield('title')
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" class="btn btn-primary" href="{{ route('create.teknisi') }}"><i class="mdi mdi-plus"></i> Tambah @yield('title')</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="basic-datatable table nowrap w-100 table-responsive text-nowrap" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID Teknisi</th>
                                                <th>Nama Teknisi</th>
                                                <th>Telpon</th>
                                                <th>Lokasi</th>
                                                <th>Group Team</th>
                                                <th>Ditambahkan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teknisi as $no => $data)
                                            <tr>
                                                <div hidden>{{ $id = $data['id'] }}</div>
                                                <td>
                                                    {{ $no + 1 }}
                                                </td>
                                                <td class="text-capitalize">
                                                    @if($data['status_teknisi'] == 1)<a data-bs-toggle="modal" data-bs-target="#detail{{ $id ?? '' }}" class="text-body" style="cursor: pointer;"><i class="bi bi-info-circle-fill text-info"></i> {{ $data['kode_teknisi'] ?? '' }}</a>@else<em><a data-bs-toggle="modal" data-bs-target="#detail{{ $id ?? '' }}" class="text-secondary" style="cursor: pointer;"><i class="bi bi-info-circle-fill text-secondary"></i> {{ $data['kode_teknisi'] ?? '' }}</a></em>@endif
                                                <td>
                                                    @if($data['status_teknisi'] == 1){{ $data['nama_teknisi'] }}@else<em>{{ $data['nama_teknisi'] }}</em>@endif
                                                </td>
                                                <td>
                                                    @if($data['status_teknisi'] == 1)<a href="https://api.whatsapp.com/send?phone={{ $data['telpon_teknisi'] ?? '' }}" class="text-body" target="_blank"><i class="bi bi-whatsapp text-success"></i> {{ $data['telpon_teknisi'] ?? '' }}</a>@else<em><a href="https://api.whatsapp.com/send?phone={{ $data['telpon_teknisi'] ?? '' }}" class="text-body" target="_blank"><i class="bi bi-whatsapp text-secondary"></i> {{ $data['telpon_teknisi'] ?? '' }}</a></em>@endif
                                                </td>
                                                <td>
                                                    @if($data['status_teknisi'] == 1)<a data-bs-toggle="modal" data-bs-target="#detailalamat{{ $id ?? '' }}" class="text-info" style="cursor: pointer;"><i class="bi bi-geo-fill"></i> {{ $data['alamat'] }}</a>@else<em><a data-bs-toggle="modal" data-bs-target="#detailalamat{{ $id ?? '' }}" class="text-secondary" style="cursor: pointer;"><i class="bi bi-geo-fill"></i> {{ $data['alamat'] }}</a></em>@endif
                                                </td>
                                                <td>
                                                    {{ $data['group'] ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $carbon::parse($data['created_at'] ?? 'd-m-Y')->isoFormat('dddd, D MMMM Y') }}
                                                </td>

                                                <td>
                                                    @if($data['status_teknisi'] == 1)
                                                    <form action="{{ route('status.teknisi') }}" method="POST">
                                                        @csrf
                                                        <button type="submit" type="button" onclick="return confirm('Apakah anda yakin ingin menonaktifkan teknisi {{ $data['nama_teknisi'] }} ?')" class="p-0" style="background: transparent; border: none;">
                                                            <input type="hidden" name="id" value="{{ $id }}">
                                                            <input type="hidden" name="status" value="0">
                                                            <span class="text-info"><i class="bi bi-lightbulb-fill"></i> Aktif</span>
                                                        </button>
                                                    </form>
                                                    @else
                                                    <form action="{{ route('status.teknisi') }}" method="POST">
                                                        @csrf
                                                        <button type="submit" type="button" onclick="return confirm('Apakah anda yakin ingin mengaktifkan teknisi {{ $data['nama_teknisi'] }} ?')" class="p-0" style="background: transparent; border: none;">
                                                            <input type="hidden" name="id" value="{{ $id }}">
                                                            <input type="hidden" name="status" value="1">
                                                            <span class="text-secondary"><i class="bi bi-lightbulb-off-fill"></i> Nonaktif</span>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </td>
                                                <td class="d-flex flex-row justify-content">
                                                    @if($data->status_teknisi == 1)
                                                        <a href="{{ route('update.teknisi', $id) }}" class="action-icon" style="outline: none; border: none; background: none;"><i class="mdi mdi-square-edit-outline text-info"></i></a>
                                                        <a href="{{ route('delete.teknisi', $id) }}" class="action-icon" onclick="return confirm('Apakah anda yakin ingin menghapus teknisi : {{ $data['nama_teknisi'] }} ?? ')" style="outline: none; border: none; background: none;" class="action-icon"><i class="mdi mdi-delete text-danger"></i></a>
                                                    @else
                                                        <a href="{{ route('delete.teknisi', $id) }}" class="action-icon" onclick="return confirm('Apakah anda yakin ingin menghapus teknisi : {{ $data['nama_teknisi'] }} ?? ')" style="outline: none; border: none; background: none;" class="action-icon"><i class="mdi mdi-delete text-danger"></i></a>
                                                    @endif
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="detailalamat{{ $id ?? '' }}" tabindex="-1" role="dialog"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body ">
                                                            <div class="px-4 py-2 mb-2">

                                                                <div class="d-flex justify-content-between mt-3">
                                                                    <div>
                                                                        <h5 class="text-uppercase mb-0"><i class="bi bi-people-fill text-info"></i> {{ $data['nama_teknisi'] ?? '' }}</h5>
                                                                        <p class="mb-4">{{ $perusahaan['value'] ?? '' }}</p>
                                                                    </div>

                                                                    <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                                                                </div>

                                                                <span class="theme-color">Detail Alamat</span>
                                                                <div class="mb-3">
                                                                    <hr class="new1">
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Provinsi :</span>
                                                                    <span class="text-muted">{{ $data['prov_name'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Kabupaten :</span>
                                                                    <span class="text-muted">{{ $data['city_name'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Kecamatan :</span>
                                                                    <span class="text-muted">{{ $data['dis_name'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Desa :</span>
                                                                    <span class="text-muted">{{ $data['subdis_name'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <p class="font-weight-bold mb-0">Alamat :</p>
                                                                    <p class="text-muted text-start">{{ $data['alamat'] ?? '' }}</p>
                                                                </div>

                                                                <hr class="new1 mb-2">

                                                                <div class="">
                                                                    <p class="text-muted text-center"><a href="{{ $data['maps'] }}" target="_blank"><i class="uil-location-point"></i>Info Maps</a></p>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="detail{{ $id ?? '' }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body ">
                                                            <div class="px-4 py-2 mb-3">

                                                               <div class="d-flex justify-content-between mt-3">
                                                                    <div>
                                                                        <h5 class="text-uppercase mb-0"><i class="bi bi-people-fill text-info"></i> {{ $data['nama_teknisi'] ?? '' }}</h5>
                                                                        <p class="mb-4">{{ $perusahaan['value'] ?? '' }}</p>
                                                                    </div>

                                                                    <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                                                               </div>

                                                                <span class="theme-color">Detail {{ $data['role'] ?? '' }}</span>
                                                                <div class="mb-3">
                                                                    <hr class="new1">
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Username :</span>
                                                                    <span class="text-muted">{{ $data['username'] ?? 'Belum Terdaftar' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Password :</span>
                                                                    <span class="text-muted">{{ $data['unique'] ?? '******' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Tanggal Lahir :</span>
                                                                    <span class="text-muted">{{ $data['nik_teknisi'] ?? 'Belum Terdaftar' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">NIK :</span>
                                                                    <span class="text-muted">{{ $data['nik_teknisi'] ?? 'Belum Terdaftar' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Telpon/WhatsApp :</span>
                                                                    <span class="text-muted">{{ $data['telpon_teknisi'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Email :</span>
                                                                    <span class="text-muted">{{ $data['email_teknisi'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Teknisi dari :</span>
                                                                    <span class="text-muted">{{ $data['group'] ?? '' }}</span>
                                                                </div>
                                                                <div class="mb-1 d-flex justify-content-between">
                                                                    <span class="font-weight-bold">Status Teknisi :</span>
                                                                    <span class="text-muted">
                                                                        @if($data['status_teknisi'] == 1)
                                                                        <span class="text-info"><i class="bi bi-lightbulb"></i> Aktif</span>
                                                                        @else
                                                                        <span class="text-secondary"><i class="bi bi-lightbulb-off-fill"></i> Nonaktif</span>
                                                                        @endif
                                                                    </span>
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
@endsection
