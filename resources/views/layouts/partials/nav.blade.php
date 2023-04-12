<nav class="ap-navbar navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand pl-3" style="font-size: 1rem" href="{{ config('adminpanel.navbar.url') }}">{{ config('adminpanel.navbar.logo') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
            </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            @include('adminpanel::menus.admin')
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                <li class="nav-item dropdown">
                    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown dropstart">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="{{ url('/') }}" target="_blank"><x-adminpanel::icon name="box-arrow-right"/>На главную</a></li>
                                    <li>
                                        <form action="{{ route('adminpanel.logout') }}" method="post">
                                            @csrf
                                            <button type="submit" class="dropdown-item">  {{ __('adminpanel::common.buttons.logout') }}</button>
                                        </form>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

