{{-- Мобильный гамбургер: виден только на узких экранах, открывает off-canvas sidebar. --}}
<button type="button" class="ap-sidebar-mobile-toggle" data-ap-sidebar-mobile aria-label="Меню">
    <x-adminpanel::icon name="list"/>
</button>
<div class="ap-sidebar-backdrop" data-ap-sidebar-mobile></div>

<aside class="ap-sidebar">
    <div class="ap-sidebar__brand">
        <a class="ap-sidebar__logo" href="{{ config('adminpanel.navbar.url') }}" title="{{ config('adminpanel.navbar.logo') }}">
            {{ config('adminpanel.navbar.logo') }}
        </a>
        <button type="button" class="ap-sidebar__toggle" data-ap-sidebar-toggle aria-label="Свернуть меню">
            <x-adminpanel::icon name="chevron-double-left"/>
        </button>
    </div>

    <nav class="ap-sidebar__nav">
        @include('adminpanel::menus.admin')
    </nav>

    <div class="ap-sidebar__footer">
        <div class="ap-sidebar__user" title="{{ Auth::user()->name }}">
            <x-adminpanel::icon name="person-circle" class="ap-menu__icon"/>
            <span class="ap-menu__label">{{ Auth::user()->name }}</span>
        </div>
        <a class="ap-menu__link" href="{{ url('/') }}" target="_blank" title="На главную">
            <x-adminpanel::icon name="box-arrow-up-right" class="ap-menu__icon"/>
            <span class="ap-menu__label">На главную</span>
        </a>
        <form action="{{ route('adminpanel.logout') }}" method="post">
            @csrf
            <button type="submit" class="ap-menu__link ap-menu__link--button" title="{{ __('adminpanel::common.buttons.logout') }}">
                <x-adminpanel::icon name="box-arrow-left" class="ap-menu__icon"/>
                <span class="ap-menu__label">{{ __('adminpanel::common.buttons.logout') }}</span>
            </button>
        </form>
    </div>
</aside>
