@extends('backend.layouts.user')

@section('page-title', trans('app.add_user'))
@section('page-heading', trans('app.create_new_user'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        {!! Form::open(['route' => 'backend.user.storeuser', 'files' => true, 'id' => 'user-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                <div class="panel-heading"><b>@lang('app.add_user')</b> </div>
            </div>

            <div class="box-body">
                <div class="row">   

                    @include('backend.user.partials.create_user')

                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    @lang('app.add_user')
                </button>
            </div>
        </div>

        {!! Form::close() !!}

    </section>
@stop

@section('scripts')
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\CreateUserRequest', '#user-form') !!}

    <script>

        $("#role_id").change(function (event) {
            var role_id = parseInt($('#role_id').val());
            $("#parent > option").each(function() {
                var id = parseInt($(this).attr('role'));
                if( (id - role_id) != 1 ){
                    $(this).attr('hidden', true);
                } else{
                    $(this).attr('hidden', false);
                }
                $(this).attr('selected', false);
            });
            $('#parent option[value=""]').attr('selected', true);
        });

        $("#role_id").trigger('change');

    </script>
@stop