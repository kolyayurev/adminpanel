<div class="accordion {{ $block->getClass() }}" id="{{ $block->getId() }}">
    @include('adminpanel::blocks.layout.index',[
        'blocks'=>$block->getBlocks() ?? [],
        'accordionId' =>$block->getId(),
        'show' => $block->getBlocks()->count() == 1,
    ])
</div>
