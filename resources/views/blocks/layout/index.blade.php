@foreach($blocks as $block)
    @if($block instanceof \KY\AdminPanel\Contracts\BlockContract)
{{--        TODO: fix visible--}}
        @if(fields_in_block($fields,$block))
            @include($block->getBeforeTemplate())
            @include($block->getTemplate())
            @include($block->getAfterTemplate())
            @if ($block->getInstruction())
                <div class="col">
                    <x-adminpanel::instruction :text="$block->getInstruction()"></x-adminpanel::instruction>
                </div>
            @endif
        @endif
    @else
        @include($content)
    @endif
@endforeach
