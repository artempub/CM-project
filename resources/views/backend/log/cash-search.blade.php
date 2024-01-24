@extends('backend.layouts.app')

@section('page-title', trans('app.cash_in_out_logs'))
@section('page-heading', trans('app.create_new_user'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        {!! Form::open(['route' => 'backend.cash_log.view', 'files' => true, 'id' => 'cash-log-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.cash_in_out_logs')</h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.username')</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="(@lang('app.require'))" value="{{Request::get('username')}}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.start_date')</label>
                            <input type="text" class="form-control datepicker" id="start_date" name="start_date" placeholder="(@lang('app.start_date'))" value="{{date('m/d/Y')}}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.time_picker')</label>
                            <input type="text" class="form-control timepicker" id="start_time" name="start_time" placeholder="(@lang('app.time_picker'))" value="00-00-00">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.end_date')</label>
                            <input type="text" class="form-control datepicker" id="end_date" name="end_date" placeholder="(@lang('app.end_date'))" value="{{date('m/d/Y')}}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.time_picker')</label>
                            <input type="text" class="form-control timepicker" id="end_time" name="end_time" placeholder="(@lang('app.time_picker'))" value="23-59-59">
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
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\Log\CashRequest', '#cash-log-form') !!}
    <script>
      $('.datepicker').datepicker();
      $('.timepicker').datetimepicker({
         format: 'HH-mm-ss',
      })
    </script>
@stop