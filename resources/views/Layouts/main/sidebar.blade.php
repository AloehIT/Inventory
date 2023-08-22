<!-- LOGO -->
<a href="{{ url('app/dashboard') }}" class="logo text-center logo-light">
    <span class="logo-lg text-white">
        <i class="bi bi-clipboard2-fill"></i> GUDANG INVENTORY
    </span>
    <span class="logo-sm text-white">
        <i class="bi bi-clipboard2-fill"></i>
    </span>
</a>

<!-- LOGO -->
<a href="{{ url('app/dashboard') }}" class="logo text-center logo-dark">
    <span class="logo-lg">
        <img src="{{ URL::to('assets/images/logo-dark.png') }}" alt="" height="16">
    </span>
    <span class="logo-sm">
        <img src="{{ URL::to('assets/images/logo_sm_dark.png') }}" alt="" height="16">
    </span>
</a>

@php
    $dashboardPermission    = $access->where('name_permission', 'dashboard')->first();
    $pengaturanPermission   = $access->where('name_permission', 'pengaturan')->first();
    $userRolePermission     = $access->where('name_permission', 'user role')->first();
@endphp

<div class="h-100" id="leftside-menu-container" data-simplebar="">

    <!--- Sidemenu -->
    <ul class="side-nav">

        <li class="side-nav-title side-nav-item">Menu</li>

        @if($dashboardPermission)
        <li class="side-nav-item">
            <a href="{{ url('app/dashboard') }}" class="side-nav-link">
                <i class="uil-home-alt"></i>
                <span> Dashboards </span>
            </a>
        </li>
        @endif


        @if($pengaturanPermission)
        <li class="side-nav-item">
            <a href="{{ url('app/pengaturan') }}" class="side-nav-link {{ Request::is('app/pengaturan-invoice', 'app/akun-bank', 'app/whatsapp-api', 'app/payment-api') ? 'text-white' : '' }}">
                <i class="uil-cog"></i>
                <span> Pengaturan </span>
            </a>
        </li>
        @endif


        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false" aria-controls="sidebarUsers"
                class="side-nav-link {{ Request::is('app/group/*', 'app/usersroles/*', 'app/usermanager/*') ? 'text-white' : '' }}">
                <i class="uil-user-plus"></i>
                <span> User Permission </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse {{ Request::is('app/group/*', 'app/usersroles/*', 'app/usermanager/*', 'app/roles/permission/*') ? 'show' : '' }}" id="sidebarUsers">
                <ul class="side-nav-second-level">
                    @if($userRolePermission)
                    <li>
                        <a href="{{ url('app/usersroles') }}" class="{{ Request::is('app/usersroles/*', 'app/roles/permission/*') ? 'text-white' : '' }}">Role Access</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ url('app/usermanager') }}" class="{{ Request::is('app/usermanager/*') ? 'text-white' : '' }}">User</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarMasterData" aria-expanded="false" aria-controls="sidebarMasterData"
                class="side-nav-link {{ Request::is('app/daftar-barang/*', 'app/lokasi-gudang/*', 'app/kategori-barang/*', 'app/satuans/*', 'app/perangkat/*', 'app/lokasi-perangkat/*', 'app/pelanggan/*', 'app/data-teknisi/*', 'app/data-paket/*', 'app/kartu-stok/*') ? 'text-white' : '' }}">
                <i class="uil-database-alt"></i>
                <span> Mater Data </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse {{ Request::is('app/daftar-barang/*', 'app/lokasi-gudang/*', 'app/kategori-barang/*', 'app/satuans/*', 'app/perangkat/*', 'app/lokasi-perangkat/*', 'app/pelanggan/*', 'app/data-teknisi/*', 'app/data-paket/*') ? 'show' : '' }}" id="sidebarMasterData">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ url('app/kategori-barang') }}" class="{{ Request::is('app/kategori-barang/*') ? 'text-white' : '' }}">Kategori Barang</a>
                    </li>
                    <li>
                        <a href="{{ url('app/satuans') }}" class="{{ Request::is('app/satuans/*') ? 'text-white' : '' }}">Satuan Barang</a>
                    </li>
                    <li>
                        <a href="{{ url('app/daftar-barang') }}" class="{{ Request::is('app/daftar-barang/*') ? 'text-white' : '' }}">Daftar Barang</a>
                    </li>

                    <li>
                        <a href="{{ url('app/kartu-stok') }}" class="{{ Request::is('app/kartu-stok/*') ? 'text-white' : '' }}">Kartu Stok</a>
                    </li>
                </ul>
            </div>
        </li>



        <li class="side-nav-title side-nav-item">Gudang Acc</li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#transaksiBarang" aria-expanded="false" aria-controls="transaksiBarang"
                class="side-nav-link {{ Request::is('app/barang-masuk/*', 'app/barang-keluar/*', 'app/opname/*') ? 'text-white' : '' }}">
                <i class="uil-truck-loading"></i>
                <span> Transaksi Gudang </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse {{ Request::is('app/barang-masuk/*', 'app/barang-keluar/*', 'app/opname/*') ? 'show' : '' }}" id="transaksiBarang">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ url('app/barang-masuk') }}" class="{{ Request::is('app/barang-masuk/*') ? 'text-white' : '' }}">Barang Masuk</a>
                    </li>
                    <li>
                        <a href="{{ url('app/barang-keluar') }}" class="{{ Request::is('app/barang-keluar/*') ? 'text-white' : '' }}">Barang Keluar</a>
                    </li>
                    <li>
                        <a href="{{ url('app/opname') }}" class="{{ Request::is('app/opname/*') ? 'text-white' : '' }}">Opname</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#laporanBarang" aria-expanded="false" aria-controls="laporanBarang"
                class="side-nav-link {{ Request::is('app/laporan/barang-masuk/*', '#', '#') ? 'text-white' : '' }}">
                <i class="uil-clipboard-alt"></i>
                <span> Laporan </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse {{ Request::is('app/laporan/barang-masuk/*', 'app/laporan/barang-keluar/*', 'app/laporan/opname/*') ? 'show' : '' }}" id="laporanBarang">
                <ul class="side-nav-second-level">
                    <li>
                        <a href="{{ url('app/laporan/barang-masuk') }}" class="{{ Request::is('app/laporan/barang-masuk/*') ? 'text-white' : '' }}">Barang Masuk</a>
                    </li>
                    <li>
                        <a href="{{ url('app/laporan/barang-keluar') }}" class="{{ Request::is('app/laporan/barang-keluar/*') ? 'text-white' : '' }}">Barang Keluar</a>
                    </li>
                    <li>
                        <a href="{{ url('app/laporan/opname') }}" class="{{ Request::is('app/laporan/opname') ? 'text-white' : '' }}">Opname</a>
                    </li>
                </ul>
            </div>
        </li>


        <li class="side-nav-item">
            <a href="{{ url('app/log') }}" class="side-nav-link {{ Request::is('app/log/*') ? 'text-white' : '' }}">
                <i class="uil-cog"></i>
                <span> Log Activitas </span>
            </a>
        </li>
    </ul>



    <div class="clearfix"></div>
</div>


