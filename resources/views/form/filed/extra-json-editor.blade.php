<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <textarea name="{{$name}}" class="form-control {{$class}}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

        {!! $append !!}

        @include('admin::form.help-block')

    </div>
</div>
<script src="/vendor/laravel-admin-extras/jsoneditor/dist/jsoneditor.js"></script>
<script>
    $(".{{$name}}").on('click',function (){
        $('#{{$name}}_json_editor_modal').modal();
    });
</script>
