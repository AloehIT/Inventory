<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.headcss')
    </head>

    <body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": false}'>

        <!-- Pre-loader -->
        @include('layouts.main.loader')
        <!-- End Preloader-->

        <div class="wrapper">
            {{-- Left Sidebar Start --}}
            <div class="leftside-menu">
                @include('layouts.main.sidebar')
            </div>
            {{-- Left Sidebar End --}}

            {{-- Content body start --}}
            <div class="content-page">
                <div class="content">
                    {{-- Navbar start --}}
                    @include('layouts.main.navbar')
                    {{-- Navbar end --}}

                    {{-- Content start --}}
                    @yield('content-page')
                    {{-- Content start --}}

                    {{-- Footer start --}}
                    @include('layouts.main.footer')
                    {{-- Footer end --}}
                </div>
            </div>
            {{-- Content body end --}}
        </div>

        @include('layouts.bodyjs')
    </body>
</html>
