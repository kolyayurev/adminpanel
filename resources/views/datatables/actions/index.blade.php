<div class="btn-group btn-group-sm" role="group" aria-label="Action group">
    @foreach($dataType->getActions() as $action)
        @php
            $action->setup($dataType, $model);
        @endphp
        @include($action->getTemplate(), [
            'dataType' => $dataType,
            'model' => $model,
            'action' => $action
        ])
    @endforeach
</div>
