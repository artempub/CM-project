@extends('backend.layouts.app')

@section('page-title', trans('app.my_users'))
@section('page-heading', trans('app.pincodes'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        <form action="" method="GET">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('app.filter')</h3>
                    <!-- <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-plus"></i></button>
                    </div> -->
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('app.name')</label>
                                <input type="text" class="form-control" name="username"
                                    value="{{ Request::get('username') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- <div class="form-group">
                                <label>@lang('app.date')</label>
                                <input type="text" class="form-control dates" name="dateline"
                                    value="{{ Request::get('dateline') }}">
                            </div> -->
                            <div class="form-group">
                                <label>@lang('app.date') </label>
                                <input type="datetime-local" class="form-control" name="from_date"
                                    value="{{ Request::get('from_date') ?? date('Y-m-d\T00:00:00')}}">
                            </div>
                            <div class="form-group">
                                <!-- <label>@lang('app.date')</label> -->
                                <input type="datetime-local" class="form-control" name="to_date"
                                    value="{{ Request::get('to_date') ?? date('Y-m-d\T23:59')   }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        @lang('app.filter')
                    </button>
                    @if(Request::get('from_date') || Request::get('to_date'))
                     (@lang('app.results_date'):<span class="text-blue">{{date('Y-m-d h:i A', strtotime(Request::get('from_date'))) ." to ".date('Y-m-d h:i A', strtotime(Request::get('to_date'))) }}</span> )
                     @endif
                </div>
            </div>
        </form>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.my_players') 
                    <!-- <br />
                    <span class="badge bg-warning text-badge mt-2"> 
                        {{win_loss($result->sum('pl'))}}
                    </span><br /> -->
                </h3>
                <div class="pull-right box-tools">
                    @permission('users.add')
                    <a href="{{ route('backend.user.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add_user')</a>
                    @endpermission
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="agents-table">
                    <thead class="bg-primary">
                        <tr>
                            <th>@lang('app.username')</th>
                            <th>@lang('app.name')</th>
                            <!-- <th>@lang('app.total')@lang('app.bet')</th>
                            <th>@lang('app.total')@lang('app.win')</th> -->
                            <th>@lang('app.score')</th>
                            @if(auth()->user()->role_id != 10)
                            <th style="width: 30%;"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($result as $key => $value)
                            @if(($value->total_bet != 0 || $value->total_win != 0 || $value->pl != 0) || ( !Request::get('username') && !Request::get('from_date') && !Request::get('to_date') ) )
                            <tr>
                                <td><a href="{{route('backend.user.edit', $value->id)}}">{{ $value->username }}</a></td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->balance }}</td>
                                <!-- <td class="{{$value->total_bet >= 0 ?'text-green' : 'text-red'}}">{{ $value->total_bet }}</td>
                                <td class="{{$value->total_win >= 0 ?'text-green' : 'text-red'}}">{{ $value->total_win }}</td> -->
                                <!-- <td class="{{$value->pl >= 0 ?'text-green' : 'text-red'}}">{{ $value->pl }}</td> -->
                                @if(auth()->user()->role_id != 10)
                                <td class="d-flex justify-content-space padd-3">
                                    <a href="" class="btn btn-block btn-warning btn-sm ms-2" data-target="#set_score{{$value->id}}" data-toggle="modal">
                                    @lang('app.set_score')
                                    </a>

                                    <a href="{{route('backend.agent.log_filter', $value->id)}}" class="btn btn-block btn-warning btn-sm mt-0 ms-2">
                                    @lang('app.score_log')
                                    </a>

                                    <a href="{{route('backend.user.edit', $value->id)}}" class="btn btn-block btn-warning btn-sm ms-2 mt-0 ">
                                    @lang('app.edit_a_p')
                                    </a>

                                    <div class="modal fade" id="set_score{{$value->id}}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('backend.agent.balance') }}" method="POST">
                                                    @csrf
                                                    @method('put')
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title"> @lang('app.set_score')  </h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="balance{{$value->id}}"> @lang('app.current_score') :
                                                                <span class="badge bg-warning text-badge"> 
                                                                    {{number_format($value->balance, 2)}}
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="balance{{$value->id}}"> @lang('app.set_score') 
                                                                <span class="badge bg-danger text-badge"> 
                                                                    Max value: {{max_score()}}
                                                                </span>
                                                            </label>
                                                            @if(auth()->user()->role_id == 3)
                                                            <input type="number"  step="0.01" class="form-control" id="balance{{$value->id}}" name="balance" placeholder="@lang('app.sum')" required>
                                                            @else
                                                            <input type="number" max="{{max_score()}}" step="0.01" class="form-control" id="balance{{$value->id}}" name="balance" placeholder="@lang('app.sum')" required>
                                                            @endif
                                                            <input type="hidden" id="AddId" name="user_id" value="{{$value->id}}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('app.close')</button>
                                                        <button type="submit" class="btn btn-primary">@lang('app.pay_in')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6">@lang('app.no_data')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop

@section('scripts')
<script>
    $(function() {
        $('.dates').daterangepicker({
            //autoUpdateInput: false,
            timePicker: true,
            timePicker24Hour: true,
           
            locale: {
                format: 'YYYY-MM-DD HH:mm'
            }
        });
        $('.dates').on('cancel.daterangepicker', function(ev, picker) {
            $(picker.element).val('');
        });

//        $('#agents-table').dataTable();
    });

    $('.btn-box-tool').click(function(event) {
        if ($('.pin_show').hasClass('collapsed-box')) {
            $.cookie('pin_show', '1');
        } else {
            $.removeCookie('pin_show');
        }
    });

    if ($.cookie('pin_show')) {
        $('.pin_show').removeClass('collapsed-box');
        $('.pin_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
    }

</script>
@stop
