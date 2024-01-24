@extends('backend.layouts.app')

@section('page-title', trans('app.p/l'))
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
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('app.date_start') </label>
                                <input type="datetime-local" class="form-control" name="start_date"
                                    value="{{ Request::get('start_date') ?? date('Y-m-d\T00:00:00') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label>@lang('app.date_end')</label>
                                <input type="datetime-local" class="form-control" name="end_date"
                                    value="{{ Request::get('end_date') ?? date('Y-m-d\T23:59')  }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        @lang('app.filter') 
                    </button>
                        @if(Request::get('start_date') || Request::get('end_date'))
                        (@lang('app.results_date'):<span class="text-blue text-badge">{{date('Y-m-d h:i A', strtotime(Request::get('start_date'))) ." to ".date('Y-m-d h:i A', strtotime(Request::get('end_date'))) }}</span> )
                        
                        @endif
                </div>
            </div>
        </form>


        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.company_stats') 
                    </h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="agents-table">
                    <thead class="bg-primary">
                        <tr>
                            <th>@lang('app.total_bets')</th>
                            <th>@lang('app.company_tax')</th>
                            <th>@lang('app.game_bank_in')</th>
                            <th>@lang('app.game_bank_out')</th>
                            <th>@lang('app.game_bank_available')</th>
                            <th>@lang('app.jp_in')</th>
                            <th>@lang('app.jp_out')</th>
                            <th>@lang('app.fixed_profit')</th>
                            <th>@lang('app.fixed_game_bank')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{number_format($total_bet, 2)}} </td>
                            <td>{{number_format($company_tax, 2)}} </td>
                            <td>{{number_format($game_bank_in, 2)}} </td>
                            <td>{{number_format($bank_out, 2)}} </td>
                            <td> {{number_format($bank_available, 2)}}</td>
                            <td>{{number_format($jp_in, 2)}} </td>
                            <td>{{number_format($jp_out, 2)}} </td>
                            <td>{{number_format($company_tax, 2)}} </td>
                            <td>{{number_format($fixed_gae_bank, 2)}} </td>
                            
                        </tr>
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
