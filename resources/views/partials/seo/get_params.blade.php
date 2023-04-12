@php $key = Str::random(10); @endphp
<tr class="item-container">
    <td>{{ $data['name'] ?? '' }}</td>
    <td>{{ $data['value'] ?? '' }}</td>
    <td>
        <a class="btn btn-xs btn-info" data-action="addGetParam" data-exist-data="{{ json_encode($data) }}">
            <i class="bi bi-pencil-square"></i>
        </a>
        <a data-action="deleteTableRow" class="btn btn-xs btn-danger">
            <i class="bi bi-trash3"></i>
        </a>
        <input type="hidden" name="get_params[{{$key}}][name]" value="{{ $data['name'] }}">
        <input type="hidden" name="get_params[{{$key}}][value]" value="{{ $data['value'] }}">
    </td>
</tr>
