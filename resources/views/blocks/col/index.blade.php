<div class="{{ $block->getColumns() }} {{ $block->getClass() }}">
    @include('adminpanel::blocks.layout.index',['blocks'=>$block->getBlocks() ?? []])
</div>
