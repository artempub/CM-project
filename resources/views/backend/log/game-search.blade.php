@extends('backend.layouts.app')

@section('page-title', trans('app.game_log'))
@section('page-heading', trans('app.create_new_user'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        {!! Form::open(['route' => 'backend.game_log.view', 'files' => true, 'id' => 'game-log-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.game_log')</h3>
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

                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.select_date')</label>
                            <input type="text" class="form-control" id="date" name="date" placeholder="(@lang('app.date'))" value="{{date('m/d/Y')}}">
                        </div>
                    </div> -->

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('app.begin_time')</label>
                            <input type="text" class="form-control timepicker" id="begin_time" name="begin_time" placeholder="(@lang('app.begin_time'))" value="00-00-00">
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
                            <label>@lang('app.end_time')</label>
                            <input type="text" class="form-control timepicker" id="end_time" name="end_time" placeholder="(@lang('app.end_time'))" value="23-59-59">
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
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\Log\GameRequest', '#game-log-form') !!}
    <script>
      $('#start_date').datepicker();
      $('#end_date').datepicker();
      $('.timepicker').datetimepicker({
         format: 'HH-mm-ss',
      })
    </script>
@stop