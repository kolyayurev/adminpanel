<ul class="ap-menu">
    @foreach(AdminPanel::getMenu('admin') as $item)
        @if($item instanceof \KY\AdminPanel\Menus\MenuGroup)
            <li class="ap-menu__group">
                <details @if($item->isActive()) open @endif>
                    <summary class="ap-menu__link">
                        <x-adminpanel::icon name="{{ $item->getIcon() }}" class="ap-menu__icon"/>
                        <span class="ap-menu__label">{{ $item->getTitle() }}</span>
                        <x-adminpanel::icon name="chevron-down" class="ap-menu__chevron"/>
                    </summary>
                    <ul class="ap-menu__sub">
                        @foreach($item->getItems() as $subItem)
                            <li>
                                <a class="ap-menu__link @if($subItem->isActive()) active @endif"
                                   href="{{ $subItem->getUrl() }}"
                                   title="{{ $subItem->getTitle() }}">
                                    <span class="ap-menu__label">{{ $subItem->getTitle() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </li>
        @else
            <li class="ap-menu__item">
                <a class="ap-menu__link @if($item->isActive()) active @endif"
                   href="{{ $item->getUrl() }}"
                   title="{{ $item->getTitle() }}">
                    @if($item->getIcon())
                        <x-adminpanel::icon name="{{ $item->getIcon() }}" class="ap-menu__icon"/>
                    @endif
                    <span class="ap-menu__label">{{ $item->getTitle() }}</span>
                </a>
            </li>
        @endif
    @endforeach
</ul>
