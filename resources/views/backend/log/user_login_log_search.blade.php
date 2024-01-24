@extends('backend.layouts.app')

@section('page-title', trans('app.add_user'))
@section('page-heading', trans('app.create_new_user'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        {!! Form::open(['route' => 'backend.user_login_log.view', 'files' => true, 'id' => 'user-login-log-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.user_login_logo')</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.username')</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="(@lang('app.require'))" value="{{Request::get('username')}}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">
                    @lang('app.ok')
                </button>
            </div>
        </div>

        {!! Form::close() !!}

    </section>
@stop

@section('scripts')
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\Log\UserLoginLogRequest', '#user-login-log-form') !!}
    <script>
  
    </script>
@stop