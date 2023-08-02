@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', $title)
@section('content-page')
@php
$gambar = $edit['gambar'] ?? 'upload.gif';
$upkat = $edit['kategori'] ?? '';
$upsat = $edit['satuan_id'] ?? '';

$tanggal = date('Y-n-j');
$no = $generate + 1;
$random = sprintf("%04s", $no);
$id_bm = 'BRG-'. $tanggal.'-'.$random;
@endphp
<div class="container-fluid">
    @include('layouts.main.breadcrumb')



    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') <i class="bi bi-box-arrow-in-left text-success"></i>
                            </h3>
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


        <form action="{{ route('posts.barang-masuk') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-xl-12">
                <div class="card p-4">

                    <div class="row gy-4 g-2">
                        <input type="hidden" name="id" value="{{ $edit['id'] ?? '' }}">

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Kode Transaksi</label>
                                <input name="id_bm_transaksi" type="hidden" class="form-control mb-0"
                                    placeholder="ID Barang Masuk" value="{{ $edit['id_bm'] ?? $id_bm }}" readonly>
                                <input name="id_bm[]" type="text" class="form-control mb-0"
                                    placeholder="ID Barang Masuk" value="{{ $edit['id_bm'] ?? $id_bm }}" readonly>
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Tanggal Masuk</label>
                                <input type="date" name="tgl_bm" id="tgl_bm"
                                    class="form-control @error ('tgl_bm') is-invalid @enderror">
                                @error('tgl_bm')
                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4 mb-0">
                            <div class="form-group form-group-default">
                                <label><span class="text-primary">!</span>Keterangan</label>
                                <input type="text" name="keterangan"
                                    class="form-control @error ('keterangan') is-invalid @enderror">
                                @error('keterangan')
                                <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group fieldGroup">
                            <div class="row g-1">
                                <div class="col-sm-3 mb-0">
                                    <div class="form-group form-group-default">
                                        <label><span class="text-primary">!</span>Barcode</label>
                                        <select class="form-control mb-0 barcode select2 select" data-toggle="select2">
                                            <option disabled selected>Cari..</option>
                                            @foreach ($barang as $item)
                                            <option value="{{ $item->barcode }}">{{ $item->barcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3 mb-0">
                                    <div class="form-group form-group-default">
                                        <label><span class="text-primary">!</span>Kode Barang</label>
                                        <input name="kode_barang[]" type="text" min="0"
                                            class="form-control kodeBarang @error ('kode_barang[]') is-invalid @enderror mb-0"
                                            placeholder="Kode Barang">
                                        @error('kode_barang[]')
                                        <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-3 mb-0" hidden>
                                    <div class="form-group form-group-default">
                                        <label><span class="text-primary">!</span>ID Barang</label>
                                        <input name="id_barang[]" type="text" min="0"
                                            class="form-control idBarang @error ('id_barang[]') is-invalid @enderror mb-0"
                                            placeholder="ID Barang">
                                        @error('id_barang[]')
                                        <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-4 mb-0">
                                    <div class="form-group form-group-default">
                                        <label><span class="text-primary">!</span>Nama Barang</label>
                                        <input name="nama_barang[]" type="text" min="0"
                                            class="form-control namaBarang @error ('nama_barang[]') is-invalid @enderror mb-0"
                                            placeholder="Nama Barang Masuk">
                                        @error('nama_barang[]')
                                        <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-1 mb-0">
                                    <div class="form-group form-group-default">
                                        <label><span class="text-primary">!</span>Stok</label>
                                        <input name="qty[]" type="number" min="0"
                                            class="form-control @error ('qty[]') is-invalid @enderror mb-0"
                                            placeholder="0">
                                        @error('qty[]')
                                        <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-1 mb-0">
                                    <label><span class="text-primary"></span>Aksi</label><br>
                                    <div class="d-flex">
                                        <a data-bs-toggle="modal" data-bs-target="#daftarbarang"
                                            class="btn btn-sm btn-warning"><i class="bi bi-list-columns"></i></a>
                                        <a href="javascript:void(0)"
                                            class="btn btn-sm btn-success addMore text-white"><i
                                                class="mdi mdi-playlist-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-12 mb-0">
                            <div calass="form-group form-group-default">
                                <label>Catatan</label>
                                <textarea name="deskripsi" id="deskripsi" type="text" class="form-control"
                                    placeholder="Lainnya...."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-end">
                            <a href="{{ url('app/barang-masuk') }}" class="btn btn-sm text-white"
                                style="background: red;">Kembali <i class="bi bi-x-lg"></i></a>
                            <button type="submit" class="btn btn-sm btn-info">Simpan <i
                                    class="bi bi-check-lg"></i></button>
                        </div>

                    </div>

                </div>

            </div>

        </form>
    </div>


    <div class="form-group fieldGroupCopy" style="display: none;">
        <div class="row g-1">
            <input name="id_bm[]" type="hidden" class="form-control mb-0" placeholder="ID Barang Masuk" value="{{ $edit['id_bm'] ?? $id_bm }}" readonly>
            <div class="col-sm-3 mb-0">
                <div class="form-group form-group-default">
                    <label><span class="text-primary">!</span>Barcode</label>
                    <select class="form-control mb-0 barcode select2" data-toggle="select2">
                        <option disabled selected>Cari..</option>
                        @foreach ($barang as $item)
                        <option value="{{ $item->barcode }}">{{ $item->barcode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3 mb-0">
                <div class="form-group form-group-default">
                    <label><span class="text-primary">!</span>Kode Barang</label>
                    <input name="kode_barang[]" type="text" min="0"
                        class="form-control kodeBarang @error ('kode_barang[]') is-invalid @enderror mb-0"
                        placeholder="Kode Barang">
                    @error('kode_barang[]')
                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="col-sm-3 mb-0" hidden>
                <div class="form-group form-group-default">
                    <label><span class="text-primary">!</span>ID Barang</label>
                    <input name="id_barang[]" type="text" min="0"
                        class="form-control idBarang @error ('id_barang[]') is-invalid @enderror mb-0"
                        placeholder="ID Barang">
                    @error('id_barang')
                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="col-sm-4 mb-0">
                <div class="form-group form-group-default">
                    <label><span class="text-primary">!</span>Nama Barang</label>
                    <input name="nama_barang[]" type="text" min="0"
                        class="form-control namaBarang @error ('nama_barang[]') is-invalid @enderror mb-0"
                        placeholder="Nama Barang Masuk">
                    @error('nama_barang[]')
                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="col-sm-1 mb-0">
                <div class="form-group form-group-default">
                    <label><span class="text-primary">!</span>Stok</label>
                    <input name="qty[]" type="number" min="0"
                        class="form-control @error ('qty[]') is-invalid @enderror mb-0" placeholder="0">
                    @error('qty[]')
                    <span class="invalid-feedback" role="alert" style="font-size: 11px;">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="col-sm-1 mb-0">
                <label><span class="text-primary"></span>Aksi</label><br>
                <div class="d-flex flex-row">
                    <a data-bs-toggle="modal" data-bs-target="#daftarbarang" class="btn btn-sm btn-warning"><i
                            class="bi bi-list-columns"></i></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger remove text-white"
                        style="background: red;"><i class="mdi mdi-trash-can-outline"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>




@include('inventory.daftar-barang.detail-data')
<script src="{{ URL::to('assets/js/jquery-3.3.1.min.js') }}"></script>


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
    document.getElementById('tanggal_masuk').value = formattedDate;
</script>


<script>
    $(document).ready(function() {
        // membatasi jumlah inputan
        var maxGroup = 100;

        // In the beginning, initialize select2 on the original select element and hide it
        $('.barcode').select2({
            // Your select2 options (if any)
        }).hide();


        // melakukan proses multiple input
        $(".addMore").click(function() {
            if ($('body').find('.fieldGroup').length < maxGroup) {
                var fieldHTML = '<div class="form-group fieldGroup">' + $(".fieldGroupCopy").html() + '</div>';

                // Replace the select2 element in fieldHTML with a new select element to avoid duplication
                var clonedSelect = $('<select class="form-control mb-0 barcode select2" data-toggle="select2">' + $(".fieldGroupCopy").find('.barcode').html() + '</select>');

                // Append the new 'select' element to the last fieldGroup and initialize select2 on it
                $('body').find('.fieldGroup:last').after(fieldHTML);
                $('body').find('.fieldGroup:last .col-sm-3:first').html(clonedSelect);

                // Initialize select2 on the cloned select element
                clonedSelect.select2({
                    // Your select2 options (if any)
                });

                // Disable selected option in other select2 elements, excluding the last one
                updateSelect2DisabledOptions();
            } else {
                alert('Maximum ' + maxGroup + ' groups are allowed.');
            }
        });

        // remove fields group
        $("body").on("click", ".remove", function() {
            $(this).parents(".fieldGroup").remove();

            // Disable selected option in other select2 elements after removal
            updateSelect2DisabledOptions();
        });

        $(document).on('input', '.barcode', function() {
            var barcode = $(this).val();

            if (barcode) {
                var currentInput = $(this); // Store the reference to 'this'
                $.ajax({
                    url: '/caribarang/' + barcode,
                    type: "GET",
                    data: { "_token": "{{ csrf_token() }}" },
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            // Use the stored reference to 'this' inside the loop
                            currentInput.closest('.fieldGroup').find('.idBarang').empty();
                            currentInput.closest('.fieldGroup').find('.kodeBarang').empty();
                            currentInput.closest('.fieldGroup').find('.nama_barang').empty();
                            $.each(data, function(key, barang) {
                                currentInput.closest('.fieldGroup').find('.kodeBarang').val(barang.kode_barang);
                                currentInput.closest('.fieldGroup').find('.idBarang').val(barang.id_barang);
                                currentInput.closest('.fieldGroup').find('.namaBarang').val(barang.nama_barang);
                            });
                        } else {
                            // Use the stored reference to 'this'
                            currentInput.closest('.fieldGroup').find('.kodeBarang').empty();
                            currentInput.closest('.fieldGroup').find('.idBarang').empty();
                            currentInput.closest('.fieldGroup').find('.namaBarang').empty();
                        }
                    }
                });
            } else {
                // Use the stored reference to 'this'
                $(this).closest('.fieldGroup').find('.idBarang').empty();
                $(this).closest('.fieldGroup').find('.namaBarang').empty();
            }

            updateSelect2DisabledOptions();
        });


        function updateSelect2DisabledOptions() {
            // Disable selected option in other select2 elements
            var selectedValues = $('.selected-barcode').find('option:selected').map(function() {
                return this.value;
            }).get();

            $('.barcode').not('.selected-barcode').find('option').each(function() {
                var value = $(this).val();
                if (selectedValues.includes(value)) {
                    $(this).attr('disabled', true);
                } else {
                    $(this).attr('disabled', false);
                }
            });
        }
    });
</script>





@endsection
