<div class="form-group {{$errors->has($name) ? 'has-error has-feedback' : '' }}">
    @if (in_array($type, ['text', 'password', 'date', 'email','number']))
    <label for="{{$name}}" class="placeholder"><b>{{$label}}</b></label>
    @endif

    @if (in_array($type,['password']))
    <div class="position-relative">
        <input id="{{$name}}" value="{{$value ?? ''}}" name="{{$name}}" wire:model="{{$name}}" type="{{$type ?? 'text'}}" class="form-control" {{isset($readonly) ? 'readonly' : '' }}>
        <div class="show-password">
            <i class="icon-eye"></i>
        </div>
    </div>
    @else
    <input id="{{$name}}" value="{{$value ?? ''}}" name="{{$name}}" wire:model="{{$name}}" type="{{$type ?? 'text'}}" class="form-control" {{isset($readonly) ? 'readonly' : '' }}>
    @endif
    <small id="helpId" class="text-danger">{{ $errors->has($name) ? $errors->first($name) : '' }}</small>
</div>