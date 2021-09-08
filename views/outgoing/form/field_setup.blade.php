<div
    class="progress-indicator-container"
>
    <select
        id="{{ $field->getId('select') }}"
        name="setup_event_code"
        class="form-control"
        data-request="onLoadSetupInstructions"
        data-progress-indicator="@lang('admin::lang.text_loading')"
        autocomplete="off"
    >
        <option value="">@lang('admin::lang.text_please_select')</option>
        @foreach ($formModel->getDropdownOptions() as $value => $option)
            @php if (!is_array($option)) $option = [$option]; @endphp
            <option
                {!! $value == $field->value ? 'selected="selected"' : '' !!}
                value="{{ $value }}">
                {{ is_lang_key($option[0]) ? lang($option[0]) : $option[0] }}
                @isset($option[1]) - {{ $option[1] }}@endisset
            </option>
        @endforeach
    </select>
</div>
<div
    class="card card-body bg-white markdown mt-3"
    data-partial="setup-instructions-content"
>To view the documentation, select an event
</div>
