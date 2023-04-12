<div class="row {{ $block->getClass() }}">
    @include('adminpanel::blocks.layout.index',['blocks'=>$block->getBlocks()])
</div>
