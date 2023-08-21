@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', $title)
@section('content-page')
@php
$tanggal = date('Y-n-j');
$no = $generate + 1;
$random = sprintf("%04s", $no);
$id_opname = 'OPM-'. $tanggal.'-'.$random;

$status = isset($detail['status']) ? ($detail['status'] == "approve" ? "approve" : "") : "";
@endphp

<style>
    .dataTables_filter {
        display: none;
    }
    .dataTables_paginate {
        float: left;
    }
</style>


<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <form action="{{ route('posts.opname') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <div class="row justify-content-between mx-3">
                            <div class="col-lg-6 col-md-6  col-12 py-3">
                                <h3 class="text-dark">@yield('title') <i class="bi bi-box-arrow-in-left text-success"></i>
                                </h3>
                                <p class="mb-0">@yield('title') barang pada system</p>
                                <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                        class="bi bi-key-fill text-warning"></i></p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 text-end p-3">
                                <img src="{{ asset('assets/icon/bg-barang.png') }}" class="img-fluid" width="110">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card p-4">
                    <div class="row gy-4 g-2">
                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label>ID Opname</label>
                                <input name="id_opname_set" type="hidden" class="form-control mb-0"
                                    placeholder="ID Barang Masuk" value="{{ $detail['id_opname'] ?? $id_opname }}" readonly>
                                <input name="id_opname" type="text" class="form-control mb-0"
                                    placeholder="ID Barang Masuk" value="{{ $detail['id_opname'] ?? $id_opname }}" readonly>
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label>Tanggal Opname</label>
                                <input type="date" name="tgl_opname" id="tgl_opname" value="{{ $detail['tgl_opname'] ?? date('Y-m-d') }}"  class="form-control @error ('tgl_opname') is-invalid @enderror">
                                @error('tgl_opname')
                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan" value="{{ $detail['keterangan'] ?? '' }}" class="form-control @error ('keterangan') is-invalid @enderror">
                                @error('keterangan')
                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body mb-0">
                        <div class="table-responsive">
                            <div class="row mb-3 g-1 col-lg-12 px-2">
                                <div class="col-sm-3">
                                    <label></label><br>
                                    <button type="button" id="refresh-btn" class="btn btn-sm text-white" style="background: rgb(27, 96, 255);"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
                                </div>
                            </div>

                            @if($title === "Data Opname Masuk")
                                @if($detail->status === "approve")
                                <table class="basic-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>
                                                Kode Barang <br> <input type="text" class="form-control form-control-sm" placeholder="Kode Barang" />
                                            </th>
                                            <th>
                                                Nama Barang <br> <input type="text" class="form-control form-control-sm" placeholder="Nama Brang" />
                                            </th>
                                            <th>
                                                Qty <br> <input type="number" min="0" class="form-control form-control-sm" placeholder="Qty" />
                                            </th>

                                            <th>
                                                Current Qty <br> <input type="number" min="0" class="form-control form-control-sm" placeholder="Qty" />
                                            </th>

                                            <th>
                                                Total Qty <br> <input type="number" min="0" class="form-control form-control-sm" placeholder="Qty" />
                                            </th>

                                            <th>
                                                Ditambahkan <br> <input type="date" class="form-control form-control-sm" placeholder="Search Ditambahkan" />
                                            </th>
                                            <th style="width: 75px;">Action <br> <span><br></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                @else
                                <table class="noapprove-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th>
                                                Kode Barang <br> <input type="text" class="form-control form-control-sm" placeholder="Kode Barang" />
                                            </th>
                                            <th>
                                                Nama Barang <br> <input type="text" class="form-control form-control-sm" placeholder="Nama Brang" />
                                            </th>
                                            <th>
                                                Qty <br> <input type="number" min="0" class="form-control form-control-sm" placeholder="Qty" />
                                            </th>

                                            <th>
                                                Ditambahkan <br> <input type="date" class="form-control form-control-sm" placeholder="Search Ditambahkan" />
                                            </th>
                                            <th style="width: 75px;">Action <br> <span><br></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                @endif
                            @else
                            <table class="add-datatable table dt-responsive nowrap w-100" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>
                                            Kode Barang <br> <input type="text" class="form-control form-control-sm" placeholder="Kode Barang" />
                                        </th>
                                        <th>
                                            Nama Barang <br> <input type="text" class="form-control form-control-sm" placeholder="Nama Brang" />
                                        </th>
                                        <th>
                                            Qty <br> <input type="number" min="0" class="form-control form-control-sm" placeholder="Qty" />
                                        </th>
                                        <th>
                                            Ditambahkan <br> <input type="date" class="form-control form-control-sm" placeholder="Search Ditambahkan" />
                                        </th>
                                        <th style="width: 75px;">Action <br> <span><br></span></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>

                @if($title === "Data Opname Masuk")
                    @if($status === "approve")
                    @else
                    <div class="card p-4">
                        <div class="row gy-4 g-2">
                            <div class="form-group fieldGroup">
                                <div class="row g-1">
                                    <table>
                                        <tr>
                                            <td class="col-sm-3 mb-0">
                                                <div class="form-group form-group-default">
                                                    <label>Barcode</label>
                                                    <select class="form-control mb-0 barcode select2 select @error ('barcode') is-invalid @enderror" data-toggle="select2" name="barcode">
                                                        <option disabled selected>Cari..</option>
                                                        @foreach ($barang as $item)
                                                        <option value="{{ $item->barcode }}">{{ $item->barcode }} | {{ $item->nama_barang }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('barcode')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </td>


                                            <td class="col-sm-1 mb-0">
                                                <div class="form-group form-group-default">
                                                    <label>Jumlah Fisik</label>
                                                    <input name="qty" type="number" min="0"
                                                        class="form-control @error ('qty') is-invalid @enderror mb-0"
                                                        placeholder="0">
                                                    @error('qty')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </td>

                                            <td class="col-sm-2 mb-0" hidden>
                                                <div class="form-group form-group-default">
                                                    <label>Satuan</label>
                                                    <input name="satuan" type="text" min="0" class="form-control mb-0 satuan @error ('satuan') is-invalid @enderror" placeholder="Satuan" readonly>
                                                    @error('satuan')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </td>

                                            <td class="col-sm-3 mb-0" hidden>
                                                <div class="form-group form-group-default">
                                                    <label>Kode Barang</label>
                                                    <input name="kode_barang" type="text"
                                                        class="form-control kodeBarang @error ('kode_barang') is-invalid @enderror mb-0"
                                                        placeholder="Kode Barang">
                                                    @error('kode_barang')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </td>

                                            <td class="col-sm-3 mb-0" hidden>
                                                <div class="form-group form-group-default">
                                                    <label>ID Barang</label>
                                                    <input name="id_barang" type="text" min="0"
                                                        class="form-control idBarang @error ('id_barang') is-invalid @enderror mb-0"
                                                        placeholder="ID Barang">
                                                    @error('id_barang')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </td>

                                            <td class="col-sm-4 mb-0" hidden>
                                                <div class="form-group form-group-default">
                                                    <label>Nama Barang</label>
                                                    <input name="nama_barang" type="text" min="0"
                                                        class="form-control namaBarang @error ('nama_barang') is-invalid @enderror mb-0"
                                                        placeholder="Nama Barang Masuk">
                                                    @error('nama_barang')
                                                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </td>


                                            <td class="col-sm-3 mb-0">
                                                <label for="">Aksi</label><br>
                                                <button type="submit" name="action" value="tambahBarang" class="btn btn-sm btn-info col-12"><i class="bi bi-database-fill-add"></i> Tambah</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                    @endif
                @else
                <div class="card p-4">
                    <div class="row gy-4 g-2">
                        <div class="form-group fieldGroup">
                            <div class="row g-1">
                                <table>
                                    <tr>
                                        <td class="col-sm-3 mb-0">
                                            <div class="form-group form-group-default">
                                                <label>Barcode</label>
                                                <select class="form-control mb-0 barcode select2 select @error ('barcode') is-invalid @enderror" data-toggle="select2" name="barcode">
                                                    <option disabled selected>Cari..</option>
                                                    @foreach ($barang as $item)
                                                    <option value="{{ $item->barcode }}">{{ $item->barcode }} | {{ $item->nama_barang }}</option>
                                                    @endforeach
                                                </select>
                                                @error('barcode')
                                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </td>

                                        <td class="col-sm-1 mb-0">
                                            <div class="form-group form-group-default">
                                                <label>Jumlah Fisik</label>
                                                <input name="qty" type="number" min="0"
                                                    class="form-control @error ('qty') is-invalid @enderror mb-0"
                                                    placeholder="0">
                                                @error('qty')
                                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </td>

                                        <td class="col-sm-2 mb-0" >
                                            <div class="form-group form-group-default">
                                                <label>Satuan</label>
                                                <input name="satuan" type="text" min="0" class="form-control mb-0 satuan @error ('satuan') is-invalid @enderror" placeholder="Satuan" readonly>
                                                @error('satuan')
                                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </td>

                                        <td class="col-sm-3 mb-0" hidden>
                                            <div class="form-group form-group-default">
                                                <label>Kode Barang</label>
                                                <input name="kode_barang" type="text"
                                                    class="form-control kodeBarang @error ('kode_barang') is-invalid @enderror mb-0"
                                                    placeholder="Kode Barang">
                                                @error('kode_barang')
                                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </td>

                                        <td class="col-sm-3 mb-0" hidden>
                                            <div class="form-group form-group-default">
                                                <label>ID Barang</label>
                                                <input name="id_barang" type="text" min="0"
                                                    class="form-control idBarang @error ('id_barang') is-invalid @enderror mb-0"
                                                    placeholder="ID Barang">
                                                @error('id_barang')
                                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </td>

                                        <td class="col-sm-4 mb-0" hidden>
                                            <div class="form-group form-group-default">
                                                <label>Nama Barang</label>
                                                <input name="nama_barang" type="text" min="0"
                                                    class="form-control namaBarang @error ('nama_barang') is-invalid @enderror mb-0"
                                                    placeholder="Nama Barang Masuk">
                                                @error('nama_barang')
                                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </td>


                                        <td class="col-sm-3 mb-0">
                                            <label for="">Aksi</label><br>
                                            <button type="submit" name="action" value="tambahBarang" class="btn btn-sm btn-info col-12"><i class="bi bi-database-fill-add"></i> Tambah</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                @endif
            </div>


            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body d-flex flex-row justify-content-between mt-0">
                        @if($title === "Data Opname Masuk")
                        <div class="col-lg-9">
                            <label for="">Aksi</label>
                            @foreach ($barangstok as $stok)
                                @php
                                    $opnameDetail = \App\Models\OpnameDetail::join('tbl_stok', 'tbl_stok.id_barang', '=', 'tbl_opname_detail.id_barang')
                                    ->where('tbl_stok.id_barang', $stok->id_barang)
                                    ->where('tbl_opname_detail.id_opname', $detail->id_opname)
                                    ->select('tbl_stok.sts_inout','tbl_stok.qty')
                                    ->get();

                                    $totalQty = 0;
                                    foreach ($opnameDetail as $data) {
                                        $totalQty += $data->sts_inout*$data->qty;
                                    }
                                    $qtyinput = $stok->qty - $totalQty;

                                @endphp

                                <div class="row g-1" hidden>
                                    <input type="hidden" name="stok_keterangan[]" value="{{ $detail['keterangan'] ?? '' }}">
                                    <div class="form-group col-lg-3">
                                        <label for="">ID Transaksi</label>
                                        <input type="text" name="id_transaksi[]" class="form-control" value="{{ $stok['id_opname'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label for="">ID Detail Transaksi</label>
                                        <input type="text" name="id_transaksi_detail[]" class="form-control" value="{{ $stok['id_opname_detail'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label for="">ID Barang</label>
                                        <input type="text" name="id_barang_stok[]" class="form-control" value="{{ $stok['id_barang'] ?? '' }}">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <label for="">Kode Barang</label>
                                        <input type="text" name="kode_barang_stok[]" class="form-control" value="{{ $stok['kode_barang'] ?? '' }}">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="">Nama Barang</label>
                                        <input type="text" name="nama_barang_stok[]" class="form-control" value="{{ $stok['nama_barang'] ?? '' }}">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="">Tanggal</label>
                                        <input type="date" name="tanggal_stok[]" class="form-control" value="{{ $stok['tanggal'] ?? '' }}">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="">Current QTY</label>
                                        <input type="text" name="current_qty[]" class="form-control" value="{{ $totalQty ?? '' }}">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="">QTY</label>
                                        <input type="text" name="qty_stok[]" class="form-control" value="{{ $qtyinput ?? '' }}">
                                    </div>
                                </div>
                            @endforeach
                            @php
                                $stoks = \App\Models\Opname::join('tbl_opname_detail', 'tbl_opname_detail.id_opname', '=', 'tbl_opname.id_opname')
                                ->where('tbl_opname.id_opname', $id_opname)->count();
                            @endphp
                            <input type="hidden" name="status" value="approve">
                            <div class="d-flex">
                                <a type="button" href="{{ url('app/opname') }}" class="btn btn-sm text-white" style="background: red;"><i class="bi bi-x-lg"></i> Tutup</a>
                                @if($status === "approve")
                                @else
                                    <button type="submit" name="action" value="simpan" class="btn btn-sm text-white mx-1" style="background: green;"><i class="bi bi-check-circle-fill"></i> Simpan</button>
                                    @if ($stoks > 0)
                                        <button type="submit" name="action" value="approveStok" class="btn btn-sm text-white" style="background: blue;"><i class="bi bi-download"></i> Aplikasikan Opname</button>
                                    @else

                                    @endif
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-lg-9">
                            <label for="">Aksi</label>
                            <div class="d-flex">
                                <a href="{{ url('app/opname') }}" class="btn btn-sm text-white" style="background: red;"><i class="bi bi-x-lg"></i> Tutup</a>
                                <button type="submit" name="action" value="simpan" class="btn btn-sm text-white mx-1" style="background: green;"><i class="bi bi-check-circle-fill"></i> Simpan</button>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-3">
                            <label for="">Total Qty : </label>
                            <input type="number"  class="form-control bg-transparent" value="{{ $detail['total_qty'] ?? 0 }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



<div id="edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-4 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="uil-filter text-warning"></i> Ubah Stok</h5>
                            <p class="">{{ $perusahaan['value'] ?? '' }}</p>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <form class="ps-3 pe-3" action="{{ route('stok.opname') }}" method="POST">
                    @csrf
                    <div>
                        <div class="mb-3">
                            <input type="hidden" id="idopname" name="id_opname">
                            <input type="hidden" id="idopnamedetail" name="id_opname_detail">
                            <label for="name_kategori" class="form-label">Jumlah Stok</label>
                            <div class="input-group">
                                <input class="form-control @error('qty') is-invalid @enderror" type="number" min="0" id="stoks" name="qty" placeholder="0">
                                <input type="text" id="satuans" class="form-control" readonly>
                            </div>
                            @error('jumlah')
                            <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="mb-3 text-end">
                            <button class="btn btn-sm btn-info col-12" type="submit">Simpan <i class="bi bi-check-lg"></i></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




@foreach ($cardbarang as $data)
<div class="modal fade" id="detail{{ $data->id_opname_detail }}" tabindex="-1" role="dialog"
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
                        <span class="font-weight-bold">Kode Barang :</span>
                        <span class="text-muted">{{ $data['kode_barang'] ?? '' }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="font-weight-bold">Tipe :</span>
                        <span class="text-muted">{{ $data['kategori'] ?? '' }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="font-weight-bold">Ditambahkan Tanggal :</span>
                        <span class="text-muted">{{ $data['tanggal'] ?? '' }}</span>
                    </div>


                    <div class="d-flex justify-content-between mb-2">
                        <span class="font-weight-bold">Qty :</span>
                        <span class="text-muted">{{ $data['qty'] ?? '' }} {{ $data['satuan'] ?? '' }}</span>
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ URL::to('assets/js/jquery-3.3.1.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $(document).on('input', '.barcode', function() {
            var barcode = $(this).val();
            var currentRow = $(this).closest('.fieldGroup'); // Menyimpan referensi ke baris saat ini

            if (barcode) {
                $.ajax({
                    url: '/opnameBarang/' + barcode,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.opname_barang.length > 0) {
                            var opnameBarang = data.opname_barang[0];
                            currentRow.find('.kodeBarang').val(opnameBarang.kode_barang);
                            currentRow.find('.idBarang').val(opnameBarang.id_barang);
                            currentRow.find('.namaBarang').val(opnameBarang.nama_barang);
                            currentRow.find('.satuan').val(opnameBarang.satuan);
                        } else {
                            currentRow.find('.kodeBarang').val('');
                            currentRow.find('.idBarang').val('');
                            currentRow.find('.namaBarang').val('');
                            currentRow.find('.satuan').val('');
                        }
                    }
                });
            } else {
                currentRow.find('.idBarang').val('');
                currentRow.find('.namaBarang').val('');
                currentRow.find('.kodeBarang').val('');
                currentRow.find('.satuan').val('');
            }
        });
    });

    $(document).ready(function() {
        var table = $('.basic-datatable').DataTable({
            lengthChange: false,
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: false,
            info: false,
            order: [[1, 'desc']],
            ajax: '{!! route('data.detail.opname', $detail['id_opname'] ?? '') !!}',
            columns: [
                { data: 'kode_barang', name: 'tbl_opname_detail.kode_barang' },
                { data: 'nama_barang', name: 'tbl_opname_detail.nama_barang' },
                { data: 'qty', name: 'qty' },
                { data: 'current_qty', name: 'current_qty' },
                { data: 'total_qty', name: 'total_qty' },
                { data: 'created_at', name: 'tbl_opname_detail.created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
            }
        });

        // Fungsi untuk melakukan refresh data tabel
        function refreshTable() {
            table.ajax.reload(null, false);
        }

        // Event click pada tombol refresh
        $('#refresh-btn').on('click', function() {
            refreshTable();
        });

        // Apply search for each column
        $('.basic-datatable thead th input').on('keyup change', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });

        // Fungsi untuk mengambil data tanggal awal dan akhir dari kolom "tgl_bm"
        function getDateRangeFromColumn() {
            var startDate = null;
            var endDate = null;

            table.column(1, { search: 'applied' }).data().each(function(date) {
                var currentDate = new Date(date);
                if (!startDate || currentDate < startDate) {
                    startDate = currentDate;
                }
                if (!endDate || currentDate > endDate) {
                    endDate = currentDate;
                }
            });

            return {
                start_date: startDate ? formatDate(startDate) : null,
                end_date: endDate ? formatDate(endDate) : null,
            };
        }

        // Fungsi untuk menampilkan data tanggal awal dan akhir di input date
        function displayDateRange() {
            var dateRange = getDateRangeFromColumn();
            $('#start_date').val(dateRange.start_date);
            $('#end_date').val(dateRange.end_date);
        }

        // Fungsi untuk mengubah format tanggal menjadi YYYY-MM-DD
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        // Event click pada tombol "Filter"
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // Event change pada input tanggal "start_date" dan "end_date"
        $('#start_date, #end_date').on('change', function() {
            table.ajax.reload();
        });

        // Tampilkan tanggal awal dan akhir di input date ketika halaman dimuat
        displayDateRange();
    });

    $(document).ready(function() {
        var table = $('.noapprove-datatable').DataTable({
            lengthChange: false,
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: true,
            info: false,
            ajax: '{!! route('data.detail.opname', $detail['id_opname'] ?? '') !!}',
            columns: [
                { data: 'kode_barang', name: 'tbl_opname_detail.kode_barang' },
                { data: 'nama_barang', name: 'tbl_opname_detail.nama_barang' },
                { data: 'qty', name: 'qty' },
                { data: 'created_at', name: 'tbl_opname_detail.created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
            }
        });

        // Fungsi untuk melakukan refresh data tabel
        function refreshTable() {
            table.ajax.reload(null, false);
        }

        // Event click pada tombol refresh
        $('#refresh-btn').on('click', function() {
            refreshTable();
        });

        // Apply search for each column
        $('.basic-datatable thead th input').on('keyup change', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });

        // Fungsi untuk mengambil data tanggal awal dan akhir dari kolom "tgl_bm"
        function getDateRangeFromColumn() {
            var startDate = null;
            var endDate = null;

            table.column(1, { search: 'applied' }).data().each(function(date) {
                var currentDate = new Date(date);
                if (!startDate || currentDate < startDate) {
                    startDate = currentDate;
                }
                if (!endDate || currentDate > endDate) {
                    endDate = currentDate;
                }
            });

            return {
                start_date: startDate ? formatDate(startDate) : null,
                end_date: endDate ? formatDate(endDate) : null,
            };
        }

        // Fungsi untuk menampilkan data tanggal awal dan akhir di input date
        function displayDateRange() {
            var dateRange = getDateRangeFromColumn();
            $('#start_date').val(dateRange.start_date);
            $('#end_date').val(dateRange.end_date);
        }

        // Fungsi untuk mengubah format tanggal menjadi YYYY-MM-DD
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        // Event click pada tombol "Filter"
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // Event change pada input tanggal "start_date" dan "end_date"
        $('#start_date, #end_date').on('change', function() {
            table.ajax.reload();
        });

        // Tampilkan tanggal awal dan akhir di input date ketika halaman dimuat
        displayDateRange();
    });

    $(document).ready(function() {
        var table = $('.add-datatable').DataTable({
            lengthChange: false,
            processing: true,
            dom: '<"left"l>ftr<"right"ip>',
            serverSide: true,
            info: false,
            ajax: '{!! route('data.detail.opname', $id_opname ?? '') !!}',
            columns: [
                { data: 'kode_barang', name: 'tbl_opname_detail.kode_barang' },
                { data: 'nama_barang', name: 'tbl_opname_detail.nama_barang' },
                { data: 'qty', name: 'qty' },
                { data: 'created_at', name: 'tbl_opname_detail.created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
            }
        });

        // Fungsi untuk melakukan refresh data tabel
        function refreshTable() {
            table.ajax.reload(null, false);
        }

        // Event click pada tombol refresh
        $('#refresh-btn').on('click', function() {
            refreshTable();
        });

        // Apply search for each column
        $('.basic-datatable thead th input').on('keyup change', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });

        // Fungsi untuk mengambil data tanggal awal dan akhir dari kolom "tgl_bm"
        function getDateRangeFromColumn() {
            var startDate = null;
            var endDate = null;

            table.column(1, { search: 'applied' }).data().each(function(date) {
                var currentDate = new Date(date);
                if (!startDate || currentDate < startDate) {
                    startDate = currentDate;
                }
                if (!endDate || currentDate > endDate) {
                    endDate = currentDate;
                }
            });

            return {
                start_date: startDate ? formatDate(startDate) : null,
                end_date: endDate ? formatDate(endDate) : null,
            };
        }

        // Fungsi untuk menampilkan data tanggal awal dan akhir di input date
        function displayDateRange() {
            var dateRange = getDateRangeFromColumn();
            $('#start_date').val(dateRange.start_date);
            $('#end_date').val(dateRange.end_date);
        }

        // Fungsi untuk mengubah format tanggal menjadi YYYY-MM-DD
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            return year + '-' + month + '-' + day;
        }

        // Event click pada tombol "Filter"
        $('#filter-btn').on('click', function() {
            table.ajax.reload();
        });

        // Event change pada input tanggal "start_date" dan "end_date"
        $('#start_date, #end_date').on('change', function() {
            table.ajax.reload();
        });

        // Tampilkan tanggal awal dan akhir di input date ketika halaman dimuat
        displayDateRange();
    });
</script>


<script>
    $(document).on("click", ".passStok", function () {
        var satuans = $(this).data('satuans');
        var stoks = $(this).data('stoks');
        var idopname = $(this).data('idopname');
        var idopnamedetail = $(this).data('idopnamedetail');

        $(".modal-body #satuans").val( satuans );
        $(".modal-body #stoks").val( stoks );
        $(".modal-body #idopname").val( idopname );
        $(".modal-body #idopnamedetail").val( idopnamedetail );
    });
</script>
@endsection
