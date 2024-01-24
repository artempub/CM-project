@extends('backend.layouts.app')

@section('page-title', trans('app.cash'))
@section('page-heading', trans('app.cash'))

@section('content')

    <section class="content-header">
        <!-- @include('backend.partials.messages') -->
    </section>

    <section class="content">

        <div class="box box-default panel-body">
            <div class="box-header with-border">
                <div class="panel-heading"><b>Cash</b> </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Date range</label>
                        <input type="datetime" class="form-control" name="datetimes" id="daterange" />
                    </div>
                    <div class="col-md-3">
                        <label for="">Operators</label>
                        <select class="form-control" name="opeator_owned" id="opeator_owned">
                            <option value="" disabled>Operators</option>
                            <option value="{{ auth()->user()->id }}"
                                {{ $recent_operator_id == auth()->user()->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;{{ auth()->user()->username }}</option>
                            @foreach ($operators as $operator)
                                <option value="{{ $operator['id'] }}"
                                    {{ $operator['id'] == $recent_operator_id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;{{ $operator['username'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">Game Providers</label>
                        <select class="form-control" name="providers_owned" id="providers_owned">
                            <option value="all">All Providers</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->href }}" {{ $cat->href == $recent_provider ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <br><br>
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered" style="width:100%" id="cash-datatable">
                                <thead>
                                    <tr>
                                        <th>Player Name</th>
                                        <th>Game Provider</th>
                                        <th>Game Name</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Sum</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right;"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right;">Total over all pages:</th>
                                        <th id="inTotal_all" class="text-center text-success"></th>
                                        <th id="outTotal_all" class="text-center text-danger"></th>
                                        <th id="sumTotal_all"></th>
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
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        $.fn.dataTable.ext.errMode = "none";
        var myData = {};

        var cash_table = $('#cash-datatable').DataTable({
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            stateSave: true,
            paging: true,
            ordering: true,
            info: true,
            searching: true,
            order: [
                [1, "desc"]
            ],
            lengthMenu: [
                [10, 25, 50, 100, 100000],
                [10, 25, 50, 100, "All"]
            ],
            ajax: {
                url: "{{ route('backend.cashlist') }}",
                type: "POST",
                data: function(d) {
                    return $.extend(d, myData);
                },
                statusCode: {
                    200: function(response) {
                        console.log(response);
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('backend.cashlist') }}",
                            data: {
                                'action': 'get_total_values'
                            },
                            statusCode: {
                                200: function(response) {
                                    console.log(response);
                                    $('#inTotal_all').html(response.inTotal_all.toFixed(4))
                                    $('#outTotal_all').html(response.outTotal_all.toFixed(4))
                                    $('#sumTotal_all').html(response.sumTotal_all.toFixed(4))

                                }
                            },
                        });
                    }
                }
            },
            columns: [{
                    data: 'username',
                    name: 'username',
                    className: "text-center",
                    bSortable: true,
                    targets: 0
                },
                {
                    data: 'game_provider',
                    name: 'game_provider',
                    className: "text-center",
                    bSortable: true,
                    targets: 1
                },
                {
                    data: 'game_name',
                    name: 'game_name',
                    className: "text-center",
                    bSortable: true,
                    targets: 2
                },
                {
                    data: 'in_amount',
                    name: 'in_amount',
                    className: "text-center text-success",
                    bSortable: false,
                    searchable: false,
                    target: 3
                },
                {
                    data: 'out_amount',
                    name: 'out_amount',
                    className: "text-center text-danger",
                    bSortable: false,
                    searchable: false,
                    target: 4
                },
            ],
            columnDefs: [{
                data: "in_amount",
                targets: 3,
                render: function(data, type, row) {
                    var in_amount = (row.in_amount).toFixed(4)
                    return '<span>' + in_amount + '</span>'
                }
            }, {
                data: "out_amount",
                targets: 4,
                render: function(data, type, row) {
                    var out_amount = (row.out_amount).toFixed(4)
                    return '<span>' + out_amount + '</span>'
                }
            }, {
                data: "sum",
                targets: 5,
                render: function(data, type, row) {
                    var sum = (row.in_amount - row.out_amount).toFixed(4)
                    return '<span>' + sum + '</span>'
                }
            }, {
                data: "created_at",
                targets: 6,
                render: function(data, type, row) {
                    var created_at = row.created_at
                    return '<span>' + created_at + '</span>'
                }
            }],
            // footerCallback: function ( row, data, start, end, display ) {
            //     var api = this.api(), data;
            //     // Remove the formatting to get integer data for summation
            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ?
            //             i.replace(/[\$,]/g, '')*1 :
            //             typeof i === 'number' ?
            //                 i : 0;
            //     };
            //     // Total over all pages
            //     data = api.column( 4 ).data();
            //     total = data.length ?
            //         data.reduce( function (a, b) {
            //                 return intVal(a) + intVal(b);
            //         } ) :
            //         0;
            //     // Total over this page
            //     data = api.column( 4, { page: 'current'} ).data();
            //     pageTotal = data.length ?
            //         data.reduce( function (a, b) {
            //                 return intVal(a) + intVal(b);
            //         } ) :
            //         0;
            //     // Update footer
            //     $( api.column( 4 ).footer() ).html(
            //         '$'+pageTotal +' ( $'+ total +' total)'
            //     );
            // },
            footerCallback: function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var json = api.ajax.json();

                // converting to interger to find total
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                var inTotal_page = api
                    .column(3, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b)).toFixed(4);
                    }, 0);

                var outTotal_page = api
                    .column(4, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return (intVal(a) + intVal(b)).toFixed(4);
                    }, 0);
                var sumTotal_page = (inTotal_page - outTotal_page).toFixed(4);
                // var sumTotal_page = api
                //     .column( 5 )
                //     .data()
                //     .reduce( function (a, b) {
                //         return (intVal(a) + intVal(b)).toFixed(4);
                //     }, 0 );

                // Update footer by showing the total with the reference of the column index
                $(api.column(2).footer()).html('Total in this page: ');
                $(api.column(3).footer()).html(inTotal_page);
                $(api.column(4).footer()).html(outTotal_page);
                $(api.column(5).footer()).html(sumTotal_page);
            },
        });

        $(function() {
            $('input[name="datetimes"]').daterangepicker({
                showWeekNumbers: false,
                showISOWeekNumbers: false,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                alwaysShowCalendars: true,

                ranges: {
                    Today: [moment().startOf("day"), moment().endOf("day")],
                    Yesterday: [moment().startOf("day").subtract(1, "days"), moment().endOf("day").subtract(
                        1, "days")],
                    "Last 7 Days": [moment().startOf("day").subtract(6, "days"), moment().endOf("day")],
                    "Last 30 Days": [moment().startOf("day").subtract(29, "days"), moment().endOf("day")],
                    "This Month": [moment().startOf("day").startOf("month"), moment().endOf("month").endOf(
                        "day")],
                    "Last Month": [moment().startOf("day").subtract(1, "month").startOf("month"), moment()
                        .endOf("day").subtract(1, "month").endOf("month")
                    ],
                    Total: [moment().startOf("day").subtract(5, "year").startOf("year"), moment().endOf(
                        "day")]
                },
                startDate: moment().startOf("day"),
                endDate: moment().endOf("day"),
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });
        });
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            var startdate = picker.startDate.format('YYYY-MM-DD HH:mm:ss');
            var enddate = picker.endDate.format('YYYY-MM-DD HH:mm:ss');

            myData = {}
            myData.startdate = startdate
            myData.enddate = enddate
            cash_table.ajax.reload(null, false)
        });
        $('#opeator_owned').on('change', function() {
            myData = {}
            myData.operator_id = parseInt(this.value)
            cash_table.ajax.reload(null, false)
        });
        $('#providers_owned').on('change', function() {
            myData = {}
            myData.game_provider = this.value
            cash_table.ajax.reload(null, false)
        });
    </script>
@stop
