@extends('backend.layouts.app')

@section('page-title', trans('app.cash'))
@section('page-heading', trans('app.cash'))

@section('content')

    <section class="content-header">
        <!-- @include('backend.partials.messages') -->
    </section>

    <section class="content">
    {!! Form::open(['route' => 'backend.range.show', 'files' => true, 'id' => 'changes-form']) !!}
    <input type="hidden" class="form-control" name="startdate" id="startdate" />
    <input type="hidden" class="form-control" name="enddate" id="enddate" />
    <input type="hidden" class="form-control" name="operator_id" id="operator_id" />
    <input type="hidden" class="form-control" name="currency" id="currency" />

    {!! Form::close() !!}

        <div class="box box-default panel-body">
            <div class="box-header with-border">
                <div class="panel-heading"><b>@lang('app.cash')</b> </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Date range</label>
                        <input type="datetime" class="form-control" name="datetimes" id="daterange" />
                    </div> 
                    <div class="col-md-6">
                        <label for="">Operators</label>
                        <select class="form-control" name="opeator_owned" id="opeator_owned">
                            <option value="" disabled>Operators</option>
                            <option value="{{auth()->user()->id}}" {{ ($recent_operator_id == auth()->user()->id)? "selected" : "" }}>&nbsp;&nbsp;&nbsp;{{auth()->user()->username}}</option>
                            @foreach ($operators as $operator)                     
                                <option value="{{$operator['id']}}" {{ ($operator['id']==$recent_operator_id)? "selected" : "" }}>&nbsp;&nbsp;&nbsp;{{$operator['username']}}</option>
                            @endforeach
                        </select>
                    </div>                       
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        @foreach ($currencies as $currency)        
                            <label class="radio-inline"><input type="radio" name="currencyradio" value="{{$currency['name']}}" {{ ($currency['name']==$recent_currency)? "checked" : "" }} >{{$currency['name']}}</label>             
                        @endforeach
                    </div>
                </div>
                <br><br>
                <div class="table-responsive"> 
                    <div class="row">
                        <div class="col-md-12">
                            <table id="tbl_cash" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr role="row">
                                        <th class="text-center" colspan="7" rowspan="1"></th>
                                        <th class="text-center hidden-sm hidden-xs afterreset ahide" colspan="7" rowspan="1" style="display: table-cell;">Last Reset</th>
                                    </tr>
                                    <tr role="row">
                                        <th>Shops</th>
                                        <th>Operator</th>
                                        <th>Currency</th>
                                        <th>Payout</th>
                                        <th class="text-center text-success sorting_disabled">In</th>
                                        <th class="text-center text-danger sorting_disabled">Out</th>
                                        <th class="text-center sorting_disabled">Sum</th>
                                        <th class="text-center sorting_disabled ahide">In</th>
                                        <th class="text-center sorting_disabled ahide">Out</th>    
                                        <th class="text-center sorting_disabled ahide">Sum</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php $total_payout = 0; $total_in = 0; $total_out = 0; $lastRest_in =0; $lastRest_out = 0; ?>
                                    @foreach ($shops_all as $key =>$shop_from)   
                                    <tr>
                                        <td><a href="#"  class="text-success">{{$shop_from->shop_user->username}}</a> </td>
                                        <td><a href="#" class="text-danger">{{$shop_from->shop_user->parent_operator->username}}</a> </td> 
                                        <td>{{$shop_from->currency}}</td>
                                        <td class="text-center">{{$shop_from->sum_payout === null? 0: $shop_from->sum_payout}}</td>
                                        <td class="text-center text-success">{{$shop_from->sum_in}}</td>
                                        <td class="text-center text-danger">{{$shop_from->sum_out}}</td>
                                        <td class="text-center">{{$shop_from->sum_in - $shop_from->sum_out}}</td>
                                        
                                        <td class="text-center text-success ahide">{{$shops_last_reset[$key]->sum_in}}</td>
                                        <td class="text-center text-danger ahide">{{$shops_last_reset[$key]->sum_out}}</td>
                                        <td class="text-center ahide">{{$shops_last_reset[$key]->sum_in - $shops_last_reset[$key]->sum_out}}</td>

                                    </tr>     
                                    <?php 
                                        $total_payout += $shop_from->sum_payout === null? 0: $shop_from->sum_payout;
                                        $total_in += $shop_from->sum_in;
                                        $total_out += $shop_from->sum_out; 
                                        $lastRest_in += $shops_last_reset[$key]->sum_in; 
                                        $lastRest_out += $shops_last_reset[$key]->sum_out; ?>
                                    @endforeach
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" rowspan="1" style="text-align:right" class="text-right text-center">Total:</th>
                                        <th colspan="1" rowspan="1" class="text-center">{{$total_payout}}</th>
                                        <th colspan="1" rowspan="1" class="text-center text-success">{{$total_in}}</th>
                                        <th colspan="1" rowspan="1" class="text-center text-danger">{{$total_out}}</th>
                                        <th colspan="1" rowspan="1" class="text-center">{{$total_in - $total_out}}</th>
                                        <th colspan="1" rowspan="1" class="text-center ahide">{{$lastRest_in}}</th>
                                        <th colspan="1" rowspan="1" class="text-center ahide">{{$lastRest_out}}</th>
                                        <th colspan="1" rowspan="1" class="text-center ahide">{{$lastRest_in - $lastRest_out}}</th>
                                    </tr>
                                </tfoot>
                            
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer">
               
            </div>
        </div>


    </section>
@stop

@section('scripts')
{!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\CreateUserRequest', '#changes-form') !!}
    <script>
        $( "body" ).on( "change", "#tbl_cash", function() {
            alert('data added dynamically!');
            var table_cash = $('#tbl_cash').DataTable({
                searchPanes: true,
                stateSave: true,
                "iDisplayLength": -1,
                "aoColumns":[
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": false}
                ]
            }
            );
            table_cash.searchPanes.container().prependTo(table.table().container());
            table_cash.searchPanes.resizePanes();
        });

        $(function() {
            $('input[name="datetimes"]').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                startDate: localStorage.getItem("startdate") === null? moment().startOf('hour') : localStorage.getItem("startdate"),
                endDate: localStorage.getItem("enddate") === null? moment().startOf('hour').add(32, 'hour') : localStorage.getItem("enddate"),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                locale: {
                format: 'YYYY-MM-DD hh:mm'
                }
            });
            var table_cash = $('#tbl_cash').DataTable({
                processing: false,
                serverSide: true,
                pagingType: "full_numbers",
                stateSave: true,
                paging: true,
                ordering: true,
                info: true,
                searching: true,
                buttons: ["copy", "csv", "excel", "pdf", "print"],
                order: [
                    [1, "desc"]
                ],
                lengthMenu: [
                    [10, 25, 50, 100, 100000],
                    [10, 25, 50, 100, "All"]
                ],

                searchPanes: true,
                "iDisplayLength": -1,
                "aoColumns":[
                    {"bSortable": true},    
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": true},
                    {"bSortable": false}
                ]
            }
            );
            table_cash.searchPanes.container().prependTo(table.table().container());
            table_cash.searchPanes.resizePanes();
        });
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            var startdate = picker.startDate.format('YYYY-MM-DD hh:mm');
            var enddate = picker.endDate.format('YYYY-MM-DD hh:mm');

            $( "#startdate" ).val(startdate);
            $( "#enddate" ).val(enddate);
            localStorage.setItem('startdate',startdate); 
            localStorage.setItem('enddate',enddate); 
    
            if (localStorage.getItem("operator_id") != null) {
                $( "#operator_id" ).val(localStorage.getItem("operator_id"));
            }
            if (localStorage.getItem("currency") != null) {
                $( "#currency" ).val(localStorage.getItem("currency"));
            }

            $( "#changes-form" ).submit();
            // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            // $.ajax({
            //     type: 'POST',
            //     url: '/backend/cashrange',
            //     data: {_token: CSRF_TOKEN, startdate:startdate, enddate:enddate},
            //     dataType: 'JSON',
            //     // cache: false,
            //     // contentType: false,
            //     // processData: false,
            //     success: function(res) {
            //         console.log("A");
            //         alert(res.msg);
            //     }
            // })
        });
        $('#opeator_owned').on('change', function() {
            $( "#operator_id" ).val(this.value);
            localStorage.setItem('operator_id', this.value);

            if (localStorage.getItem("startdate") != null) {
                $( "#startdate" ).val(localStorage.getItem("startdate"));
            }
            if (localStorage.getItem("enddate") != null) {
                $( "#enddate" ).val(localStorage.getItem("enddate"));
            }
            if (localStorage.getItem("currency") != null) {
                $( "#currency" ).val(localStorage.getItem("currency"));
            }

            $( "#changes-form" ).submit();
            
        });
        $('input[type=radio][name=currencyradio]').change(function() {
            $( "#currency" ).val(this.value);
            localStorage.setItem('currency', this.value);

            if (localStorage.getItem("startdate") != null) {
                $( "#startdate" ).val(localStorage.getItem("startdate"));
            }
            if (localStorage.getItem("enddate") != null) {
                $( "#enddate" ).val(localStorage.getItem("enddate"));
            }
            if (localStorage.getItem("operator_id") != null) {
                $( "#operator_id" ).val(localStorage.getItem("operator_id"));
            }

            $( "#changes-form" ).submit();
        });
    </script>
@stop