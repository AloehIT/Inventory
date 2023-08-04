@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', 'Kategori Barang')
@section('content-page')
<style>
    .dataTables_filter {
        display: none; /* Menyembunyikan kotak pencarian */
    }
</style>
<div class="container-fluid">
    @include('layouts.main.breadcrumb')

    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-lg-6 col-md-6  col-12 py-3">
                            <h3 class="text-dark">@yield('title') ERP</h3>
                            <p class="mb-0">Data Seluruh @yield('title') yang terdaftar pada system</p>
                            <p>Akses saat ini : <b>{{ $auth['role'] ?? '' }}</b> <i
                                    class="bi bi-key-fill text-warning"></i></p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 text-end">
                            <img src="{{ asset('assets/icon/icon-filterbarang.png') }}" class="img-fluid m-3" width="80">
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
                                    <i class="mdi mdi-menu"></i> Manajemen Kategori
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add"><i class="uil-plus"></i> Tambah Kategori Baru</a>
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
                                                <th>Nama</th>
                                                <th>Ditambahkan</th>
                                                <th style="width: 75px;">Action</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" placeholder="Search Nama" />
                                                </th>
                                                <th>
                                                    <input type="date" class="form-control form-control-sm" placeholder="Search Ditambahkan" />
                                                </th>
                                                <th>
                                                    <input type="text" class="form-control form-control-sm" readonly>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>

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


<div class="modal fade" id="add" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-start mt-4 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="uil-filter text-warning"></i> Tambah @yield('title')</h5>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <form class="ps-3 pe-3" action="{{ route('posts.kategoribarang') }}" method="POST">
                    @csrf
                    <div class="">
                        <div class="col-xl-12">
                            <div class="mb-3">
                                <div class="form-group fieldGroup">
                                    <div class="input-group">
                                        <input type="text" name="name_kategori[]" class="form-control @error('name_kategori.*') is-invalid @enderror" placeholder="Nama Kategori" autocomplete="off">
                                        <input type="hidden" name="guard_config[]" class="form-control" value="Barang" readonly>

                                        <div class="input-group-addon ml-3">
                                            <a href="javascript:void(0)" class="btn btn-success addMore text-white"><i class="mdi mdi-playlist-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 text-end">
                                <button class="btn btn-sm text-white" style="background: red;" type="button" data-bs-dismiss="modal">Batal <i class="bi bi-x-lg"></i></button>
                                <button class="btn btn-sm btn-info" type="submit">Simpan <i class="bi bi-check-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="">
                    <div class="form-group fieldGroupCopy" style="display: none;">
                        <div class="input-group mt-3">
                            <input type="text" name="name_kategori[]" class="form-control @error('name_kategori.*') is-invalid @enderror" placeholder="Nama Kategori" autocomplete="off">
                            <input type="hidden" name="guard_config[]" class="form-control" value="Barang" readonly>

                            <div class="input-group-addon">
                                <a href="javascript:void(0)" class="btn btn-danger remove text-white"
                                    style="background: red;"><i class="mdi mdi-trash-can-outline"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@foreach ($kategori as $item)
<div id="edit{{ $item->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <div class="text-start mt-4 mb-2 mx-3">
                    <div class="d-flex justify-content-between mt-3">
                        <div>
                            <h5 class="text-uppercase mb-0"><i class="uil-filter text-warning"></i> Ubah @yield('title')</h5>
                        </div>

                        <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                   </div>
                </div>

                <form class="ps-3 pe-3" action="{{ route('upposts.kategoribarang') }}" method="POST">
                    @csrf
                    <div class="">
                        <div class="mb-3">
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <label for="name_kategori" class="form-label">Nama Kategori</label>
                            <input class="form-control @error('name_kategori') is-invalid @enderror" type="text" id="name_kategori" name="name_kategori" placeholder="Nama Kategori" value="{{ $item['name_kategori'] ?? '' }}">
                        </div>

                        <div class="mb-3" hidden>
                            <label for="guard_config" class="form-label">Guard Name</label>
                            <input class="form-control" type="text" id="guard_config" name="guard_config" value="Barang" readonly>
                        </div>

                        <div class="mb-3 text-end">
                            <button class="btn btn-sm text-white" style="background: red;" type="button" data-bs-dismiss="modal">Batal <i class="bi bi-x-lg"></i></button>
                            <button class="btn btn-sm btn-info" type="submit">Simpan <i class="bi bi-check-lg"></i></button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endforeach


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

<script>
    $(document).ready(function() {
        var table = $('.basic-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('data.kategori') !!}',
            columns: [
                { data: 'name_kategori', name: 'name_kategori' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search...',
            }
        });

        // Apply search for each column
        $('.basic-datatable thead th input').on('keyup change', function() {
            table.column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });
    });
</script>
@endsection
