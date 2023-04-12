<ul class="navbar-nav me-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Контент
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li>
                <a class="dropdown-item @if(request('name') === 'main') active @endif" href="{{ route('adminpanel.settings', 'main') }}">Главная</a>
            </li>
            @can('view_dev')
            <li>
                <a class="dropdown-item @if(request('name') === 'test') active @endif" href="{{ route('adminpanel.settings', 'test') }}">Тест</a>
            </li>
            @endcan
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarSeoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            SEO
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarSeoDropdown">
            @can('list',AdminPanel::modelClass('Seo'))
            <li>
                <a class="dropdown-item @if(request()->routeIs('adminpanel.seo*')) active @endif" href="{{ route('adminpanel.seo.index') }}">Мета-информация для страниц</a>
            </li>
            @endcan
            @can('list',AdminPanel::modelClass('Redirect'))
            <li>
                <a class="dropdown-item @if(request()->routeIs('adminpanel.redirects*')) active @endif" href="{{ route('adminpanel.redirects.index') }}">Редиректы</a>
            </li>
            @endcan
            @can('list',AdminPanel::modelClass('Sef'))
            <li>
                <a class="dropdown-item @if(request()->routeIs('adminpanel.sef*')) active @endif" href="{{ route('adminpanel.sef.index') }}">ЧПУ</a>
            </li>
            @endcan

        </ul>
    </li>
    @can('list',AdminPanel::modelClass('User'))
    <li class="nav-item dropdown">
        <a class="nav-link" href="{{ route('adminpanel.users.index') }}">Пользователи</a>
    </li>
    @endcan
    @can('list',AdminPanel::modelClass('Role'))
    <li class="nav-item dropdown">
        <a class="nav-link" href="{{ route('adminpanel.roles.index') }}">Роли</a>
    </li>
    @endcan
    @can('list',AdminPanel::modelClass('Test'))
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('adminpanel.test.index') }}">Тест</a>
        </li>
    @endcan
    @can('view_dev')
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('adminpanel.tools.docs') }}">Docs</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('adminpanel.media.index') }}">Media</a>
        </li>
    @endcan
</ul>
