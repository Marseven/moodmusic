<select class="form-control mb-2 mr-3" name="{{ $name }}" @if(isset($submit) && $submit)  @endif onchange="this.form.submit()">
    @if(isset($optional) && $optional)<option value> ----- </option>@endif
    @foreach($items as $key => $value)
        @if(is_numeric($key))
            <option value="{{ $value }}" @if(isset($selected) && $selected === $value) selected="selected" @endif>{{ $value }}</option>
        @else
            @if($name == 'language')
                <option value="{{ $key }}" @if(isset($selected) && $selected === $key) selected="selected" @endif>{{ trans('langcode.' . $value) }}</option>
            @else
                <option value="{{ $key }}" @if(isset($selected) && $selected === $key) selected="selected" @endif>{{ $value }}</option>
            @endif
        @endif
    @endforeach
</select>
