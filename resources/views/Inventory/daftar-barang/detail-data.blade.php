<div class="modal fade" id="daftarbarang" tabindex="-1" role="dialog"
aria-hidden="true">
@php
    $gambar =  $data['gambar'] ?? 'upload.gif';
@endphp
<div class="modal-dialog modal-dialog-centered  modal-xl">
    <div class="modal-content">
        <div class="modal-body">
            <div class="px-4 py-2">

                <div class="d-flex justify-content-between mt-3">
                    <div>
                        <h5 class="text-uppercase mb-0"><i class="uil-box text-warning"></i> Daftar Barang</h5>
                    </div>

                    <a type="button" data-bs-dismiss="modal" class="text-danger" style="font-size: 25px;"><i class="uil-multiply"></i></a>
                </div>

                <div class="mb-3">
                    <hr class="new1">
                </div>

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
</div>
</div>
