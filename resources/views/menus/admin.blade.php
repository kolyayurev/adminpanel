<ul class="navbar-nav me-auto">
    @if(count(AdminPanel::getPageTypes()))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Контент
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            @foreach(AdminPanel::getPageTypes() as $pageType)
                <li><a class="dropdown-item @if(request('name') === $pageType->getSlug()) active @endif" href="{{ route('adminpanel.settings', $pageType->getSlug()) }}">{{ $pageType->getTitle() }}</a></li>
            @endforeach
        </ul>
    </li>
    @endif
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
    @foreach(AdminPanel::getDataTypes() as $dataType)
        @if(!in_array($dataType->getSlug(),['seo','sef','redirects']))
        @can('list',$dataType->getModel())
        <li class="nav-item ">
            <a class="nav-link @if(request()->routeIs('adminpanel.'.$dataType->getSlug().'*')) active @endif"  href="{{ route('adminpanel.'.$dataType->getSlug().'.index') }}">{{ $dataType->getPluralTitle() }}</a>
        </li>
        @endcan
        @endif
    @endforeach
    @can('view_tools')
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('adminpanel.tools.index') }}"> Tools </a>
        </li>
    @endcan
</ul>
