@foreach ($barangMasuk as $view)
<div class="modal fade" id="detail{{ $view->id_bm }}" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered  modal-xl">
    <div class="modal-content">
        <div class="modal-body">
            <div class="px-4 py-2 mb-3">

                <div class="d-flex justify-content-between mt-3">
                    <div>
                        <h5 class="text-uppercase mb-0"><i class="uil-box text-warning"></i> {{ $data['id_bm'] ?? '' }}</h5>
                        <p>Daftar Transaksi</p>
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
                                <th>ID Transaksi</th>
                                <th>Keterangan</th>
                                <th>Tanggal Masuk</th>
                                <th>Qty</th>
                                <th>Tanggal</th>
                                <th style="width: 75px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarbarang as $no => $data)
                                @if($view->id_bm == $data->id_bm)
                                    <tr>
                                        <td>{{ $no+1 }}</td>
                                        <td>{{ $data['id_bm'] ?? '' }}</td>
                                        <td>{{ $data['id_barang'] ?? '' }}</td>
                                        <td>{{ $data['nama_barang'] ?? '' }}</td>
                                        <td>{{ $data['qty'] ?? '' }}</td>
                                        <td>{{ $data['tanggal'] ?? '' }}</td>
                                        <td><a href="{{ route('delete.detail-barang-masuk', $data->id) }}" type="button" onclick="return confirm('Apakah anda yakin ingin menghapus Barang : {{ $data['nama_barang'] }} dari transaksi ?')" class="action-icon" style="outline: none; border: none; background: none;"> <i class="mdi mdi-delete text-danger"></i></a></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endforeach
