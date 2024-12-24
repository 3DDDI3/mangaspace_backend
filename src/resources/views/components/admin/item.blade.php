@php
    $_data = '';
    foreach ($data as $key => $val) {
        $_data .= "data-$key=$val ";
    }
@endphp

<li class="list-group-item">
    <input id="{{ $id }}" class="form-check-input me-1" type="checkbox" aria-label="{{ $ariaLabel }}"
        {{ $_data }}>
    <label for="checkbox-1">{{ $value }}</label>
</li>
