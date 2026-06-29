@php
    // Активна ли группа «Контент» (любой из page-type'ов выбран).
    $contentActive = collect(AdminPanel::getPageTypes())->contains(fn ($pt) => request('name') === $pt->getSlug());
    // Активна ли группа SEO.
    $seoActive = request()->routeIs('adminpanel.seo*')
        || request()->routeIs('adminpanel.redirects*')
        || request()->routeIs('adminpanel.sef*');
@endphp
<ul class="ap-menu">
    @if(count(AdminPanel::getPageTypes()))
        <li class="ap-menu__group">
            <details @if($contentActive) open @endif>
                <summary class="ap-menu__link">
                    <x-adminpanel::icon name="file-earmark-text" class="ap-menu__icon"/>
                    <span class="ap-menu__label">Контент</span>
                    <x-adminpanel::icon name="chevron-down" class="ap-menu__chevron"/>
                </summary>
                <ul class="ap-menu__sub">
                    @foreach(AdminPanel::getPageTypes() as $pageType)
                        <li>
                            <a class="ap-menu__link @if(request('name') === $pageType->getSlug()) active @endif"
                               href="{{ route('adminpanel.settings', $pageType->getSlug()) }}"
                               title="{{ $pageType->getTitle() }}">
                                <span class="ap-menu__label">{{ $pageType->getTitle() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </details>
        </li>
    @endif

    @if(
        Gate::allows('list', AdminPanel::modelClass('Seo'))
        || Gate::allows('list', AdminPanel::modelClass('Redirect'))
        || Gate::allows('list', AdminPanel::modelClass('Sef'))
    )
        <li class="ap-menu__group">
            <details @if($seoActive) open @endif>
                <summary class="ap-menu__link">
                    <x-adminpanel::icon name="search" class="ap-menu__icon"/>
                    <span class="ap-menu__label">SEO</span>
                    <x-adminpanel::icon name="chevron-down" class="ap-menu__chevron"/>
                </summary>
                <ul class="ap-menu__sub">
                    @can('list', AdminPanel::modelClass('Seo'))
                        <li><a class="ap-menu__link @if(request()->routeIs('adminpanel.seo*')) active @endif"
                               href="{{ route('adminpanel.seo.index') }}" title="Мета-информация для страниц">
                            <span class="ap-menu__label">Мета-информация для страниц</span></a></li>
                    @endcan
                    @can('list', AdminPanel::modelClass('Redirect'))
                        <li><a class="ap-menu__link @if(request()->routeIs('adminpanel.redirects*')) active @endif"
                               href="{{ route('adminpanel.redirects.index') }}" title="Редиректы">
                            <span class="ap-menu__label">Редиректы</span></a></li>
                    @endcan
                    @can('list', AdminPanel::modelClass('Sef'))
                        <li><a class="ap-menu__link @if(request()->routeIs('adminpanel.sef*')) active @endif"
                               href="{{ route('adminpanel.sef.index') }}" title="ЧПУ">
                            <span class="ap-menu__label">ЧПУ</span></a></li>
                    @endcan
                </ul>
            </details>
        </li>
    @endif

    @foreach(AdminPanel::getDataTypes() as $dataType)
        @if(!in_array($dataType->getSlug(), ['seo', 'sef', 'redirects']))
            @can('list', $dataType->getModel())
                <li class="ap-menu__item">
                    <a class="ap-menu__link @if(request()->routeIs('adminpanel.'.$dataType->getSlug().'*')) active @endif"
                       href="{{ route('adminpanel.'.$dataType->getSlug().'.index') }}"
                       title="{{ $dataType->getPluralTitle() }}">
                        <x-adminpanel::icon name="collection" class="ap-menu__icon"/>
                        <span class="ap-menu__label">{{ $dataType->getPluralTitle() }}</span>
                    </a>
                </li>
            @endcan
        @endif
    @endforeach

    @can('view_tools')
        <li class="ap-menu__item">
            <a class="ap-menu__link @if(request()->routeIs('adminpanel.tools*')) active @endif"
               href="{{ route('adminpanel.tools.index') }}" title="Tools">
                <x-adminpanel::icon name="tools" class="ap-menu__icon"/>
                <span class="ap-menu__label">Tools</span>
            </a>
        </li>
    @endcan
</ul>
