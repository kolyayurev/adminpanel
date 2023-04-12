<div class="card mb-2 {{ $block->getClass() }}">
    @if($block->hasHeader())
        <div class="card-header" id="heading-{{ $block->getId() }}">
            <button type="button" class="btn btn-link position-relative" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $block->getId() }}"
                    aria-expanded="true" aria-controls="collapse-{{ $block->getId() }}">
                {{ $block->getHeader() }}
                @if($errors->hasAny($block->getFieldsName()->toArray()))
                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle z-1">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                @endif
            </button>
        </div>
    @endif
    <div id="collapse-{{ $block->getId() }}"
         class="collapse @if(!empty($show)) show @endif"
         aria-labelledby="heading-{{ $block->getId() }}" data-bs-parent="#{{ $accordionId ?? '' }}">
        <div class="card-body container-fluid">
            @include('adminpanel::blocks.layout.index',['blocks'=>$block->getBlocks() ?? []])
        </div>
    </div>
</div>
