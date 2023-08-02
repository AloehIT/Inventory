@inject('carbon', 'Carbon\Carbon')
@extends('layouts.app')
@section('title', $title)
@section('content-page')
@php
    $today = $carbon::now()->isoFormat('dddd, D MMMM Y');
    $profile =  $edit['profile'] ?? 'profile.png';
    $roles =  $edit['role'] ?? '';
@endphp

<style>
    .rounded1{
        width: 150px;
        height: 150px;
        border-radius: 50%;
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
                            <h3 class="text-dark">{{ $title }} </h3>
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
                    <form action="{{ route('posts.usermanager') }}" method="POST" class="row" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $edit['id'] ?? '' }}">
                        <div class="col-xl-3 col-12 col-md-12">
                            <div class="d-flex mb-0 mt-3 mt-xl-0">
                                <div class="col-lg-6 col-md-3 col-6">
                                    <img id="blah" src="{{ asset('../storage/profile/'. $profile) }}" style="border-radius: 50%;" class="img-fluid" width="100">
                                </div>
                                <div class="col-lg-6 col-md-9 col-6">
                                    <div class="col-lg-12 col-md-4">
                                        <label for="" class="mb-2">Foto Profile</label>
                                        <input type="hidden" value="{{ $edit['profile'] ?? 'profile.png' }}" name="profil">
                                        <input name="profile" accept="image/*" type='file' id="imgInp" class="form-control mb-2 custom-file-input btn btn-sm btn-dark">
                                        <p class="text-secondary" style="font-size: 12px; font-weight: 200;">Max image size 100 kb format PNG, JPG, GIF.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $edit['username'] ?? '' }}" placeholder="Username">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ $edit['unique'] ?? '' }}" placeholder="Password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="username" class="form-label">Role Access</label>
                                <select class="form-control select2" data-toggle="select2" name="roles">
                                    <option value="" selected disabled>Access Users</option>
                                    @foreach ($role as $item)
                                        @if($roles == $item['name'] ?? '')
                                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xl-9">
                            <div class="row g-1">
                                <div class="col-xl-4 mb-2">
                                    <label for="perusahaan" class="form-label">Nama Pengguna</label>
                                    <input type="text" id="perusahaan" name="nama_users" class="form-control @error('nama_users') is-invalid @enderror" placeholder="Nama Pengguna" value="{{ $edit['nama_users'] ?? '' }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-xl-4 mb-2">
                                    <label for="email" class="form-label">Email (Opsional)</label>
                                    <input type="text" id="email" class="form-control" name="email" value="{{ $edit['email'] ?? '' }}" placeholder="Email">
                                </div>

                                <div class="col-xl-4 mb-2">
                                    <label for="phone" class="form-label">Telpon/Wa</label>
                                    <input type="text" id="telpon" class="form-control @error('telpon') is-invalid @enderror" name="telpon" value="{{ $edit['telpon'] ?? '' }}" placeholder="+62">
                                    @error('telpon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="4" placeholder="Alamat perusahaan">{{ $edit['alamat_users'] ?? '' }}</textarea>
                                    @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label for="deskripsi_users" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="deskripsi_users" rows="4" placeholder="Tuliskan teks disini..." >{{ $edit['deskripsi_users'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="col-xl-12 mt-2 text-end">
                            <a href="{{ url('app/usermanager') }}" class="btn btn-sm text-white" style="background: red;">Batal <i class="bi bi-x-lg"></i></a>
                            <button type="submit" class="btn btn-sm btn-info">Simpan <i class="bi bi-check-lg"></i></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

