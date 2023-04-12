<div class="card {{ $block->getClass() }}">
    @if($block->hasHeader())
    <div class="card-header">
        {{ $block->getHeader() }}
    </div>
    @endif
    <div class="card-body">
        @include('adminpanel::blocks.layout.index',['blocks'=>$block->getBlocks() ?? []])
    </div>
</div>
