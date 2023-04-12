<div class="tab-pane @if(!empty($active)) show active @endif" id="{{ $block->getId() }}-tab-pane" role="tabpanel" aria-labelledby="{{ $block->getId() }}-tab" tabindex="0">
    @include('adminpanel::blocks.layout.index',['blocks'=> $block->getBlocks() ?? []])
</div>
