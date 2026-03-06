@foreach ($formData as $data)
    <div class="col-12">
        <label @class(['form--label', 'required' => $data->is_required == 'required'])>{{ __($data->name) }}</label>
        @if ($data->type == 'text')
            <input type="text" class="form--control form-control" name="{{ $data->label }}" value="{{ old($data->label) }}" @required($data->is_required == 'required')>
        @elseif ($data->type == 'textarea')
            <textarea class="form--control form-control" name="{{ $data->label }}" rows="10" @required($data->is_required == 'required')>
                {{ old($data->label) }}
            </textarea>
        @elseif ($data->type == 'select')
            <select class="form--control form-control form-select" name="{{ $data->label }}" @required($data->is_required == 'required')>
                <option value="">@lang('Select One')</option>
                @foreach ($data->options as $item)
                    <option value="{{ $item }}" @selected($item == old($data->label))>
                        {{ __($item) }}
                    </option>
                @endforeach
            </select>
        @elseif ($data->type == 'checkbox')
            @foreach ($data->options as $option)
                <div class="form--check">
                    <input type="checkbox" class="form-check-input" name="{{ $data->label }}[]" value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}">
                    <label for="{{ $data->label }}_{{ titleToKey($option) }}" class="form-check-label">{{ $option }}</label>
                </div>
            @endforeach
        @elseif ($data->type == 'radio')
            @foreach ($data->options as $option)
                <div class="form--check">
                    <input type="radio" class="form-check-input" name="{{ $data->label }}" value="{{ $option }}" id="{{ $data->label }}_{{ titleToKey($option) }}" @checked($option == old($data->label))>
                    <label for="{{ $data->label }}_{{ titleToKey($option) }}" class="form-check-label">{{ $option }}</label>
                </div>
            @endforeach
        @elseif ($data->type == 'file')
            <input type="file" class="form--control form-control" name="{{ $data->label }}" @required($data->is_required == 'required') accept="@foreach (explode(',', $data->extensions) as $ext) {{ '.' . $ext }}@if (!$loop->last), @endif @endforeach">
            <pre class="text--base mt-2 mb-1">@lang('Supported mimes'): {{ $data->extensions }}</pre>
        @endif
    </div>
@endforeach
