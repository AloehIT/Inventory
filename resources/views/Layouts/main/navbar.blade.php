<div class="navbar-custom">
    <ul class="list-unstyled topbar-menu float-end mb-0">


        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#"
                role="button" aria-haspopup="false" aria-expanded="false">
                <span class="account-user-avatar">
                    <img src="{{asset('storage/profile/'. $auth->profile ) }}" alt="user-image" class="rounded-circle">
                </span>
                <span>
                    <span class="account-user-name text-capitalize">{{ auth()->user()->username }}</span>
                    <span class="account-position text-capitalize">{{ auth()->user()->role }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                <a href="{{-- route('profile.update', auth()->user()->id) --}}" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle me-1"></i>
                    <span>Edit Profile</span>
                </a>

                <!-- item-->
                <form action="{{ route('logout.access') }}" method="GET">
                    {{ csrf_field() }}
                    <button type="submit" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout me-1"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </li>

    </ul>

    <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
    </button>
</div>
