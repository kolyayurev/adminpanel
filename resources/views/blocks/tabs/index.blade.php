<ul class="nav nav-tabs mb-4 {{ $block->getClass() }} " role="tablist">
    @foreach($block->getBlocks() ?? [] as $tab)
        @if($tab instanceof \KY\AdminPanel\Blocks\Tab)
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($loop->first) active @endif position-relative"
                        id="{{ $tab->getId() }}-tab"
                        type="button"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $tab->getId() }}-tab-pane"
                        aria-controls="{{ $tab->getId() }}-tab-pane"
                        aria-selected="true">
                        {{ $tab->getHeader() }}
                        @if($errors->hasAny($tab->getFieldsName()->toArray()))
                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle z-1">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                        @endif
                </button>
            </li>
        @endif
    @endforeach
</ul>
<div class="tab-content">
    @foreach($block->getBlocks() ?? [] as $tab)
        @if($tab instanceof \KY\AdminPanel\Blocks\Tab)
            @include($tab->getTemplate(),[
                'block'=> $tab,
                'active' => $loop->first,
            ])
        @endif
    @endforeach
</div>
