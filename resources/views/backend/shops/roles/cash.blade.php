@extends('backend.layouts.user')

@section('content')

<div class="row wow fadeIn">

    <!--Grid column-->
    <div class="col-md-9 ">
        <section class="content-header">
            <!-- @include('backend.partials.messages') -->
        </section>

        <section class="content">
            {!! Form::open(['route' => 'backend.shop.showcashpost', 'files' => true, 'id' => 'changes-form']) !!}
            <input type="hidden" class="form-control" name="startdate" id="startdate" />
            <input type="hidden" class="form-control" name="enddate" id="enddate" />
            <input type="hidden" class="form-control" name="operator_id" id="operator_id" />
            <input type="hidden" class="form-control" name="currency" id="currency" />

            {!! Form::close() !!}
            <div class="content-box">
                <div class="element-wrapper">
                    <div class="element-box-tp">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <input type="datetime" class="form-control text-center" name="datetimes" id="daterange" />
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover datatable table-bordered" style="width:100%" id="cash-datatable" >
                                        <thead>
                                            <tr>
                                                <th>Player Name</th>
                                                <th>Game Name</th>
                                                <th>In</th>
                                                <th>Out</th>
                                                <th>Sum</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot >
                                            <tr><th></th><th style="text-align: right;"></th><th></th><th></th><th></th></tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!--Grid column-->

</div>
@stop

@section('scripts')
<script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        $.fn.dataTable.ext.errMode = "none";
        var myData ={};

        var cash_table = $('#cash-datatable').DataTable({
            processing: true,
            serverSide: true,
            // pagingType: "full_numbers",
            stateSave: true,
            paging: false,
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
                url: "{{ route('backend.shop.showcashpost') }}",
                type: "POST",
                data: function(d){
                    return  $.extend(d, myData);
                },
                statusCode: {
                    200: function(response) {
                        console.log(response);
                    }
                }
            },
            columns: [
                {data: 'username', name: 'username', className: "text-center", bSortable: true, targets: 0},
                {data: 'game_name', name: 'game_name', className: "text-center", bSortable: true, targets: 1},
                {data: 'in_amount', name: 'in_amount', className: "text-center text-success", bSortable: false, searchable: false, target: 2},
                {data: 'out_amount', name: 'out_amount', className: "text-center text-danger", bSortable: false, searchable: false, target: 3},
            ],
            columnDefs: [{
                data: "sum",
                targets: 4,
                render: function(data, type, row) {
                    var sum = (row.in_amount - row.out_amount).toFixed(2)
                    return '<span>'+ sum +'</span>'
                }
            }, {
                data: "created_at",
                targets: 5,
                render: function(data, type, row) {
                    var created_at = row.created_at
                    return '<span>'+ created_at +'</span>'
                }
            }],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // converting to interger to find total
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // computing column Total of the complete result 
                var inTotal = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return (intVal(a) + intVal(b)).toFixed(2);
                    }, 0 );
                    
                var outTotal = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return (intVal(a) + intVal(b)).toFixed(2);
                    }, 0 );
                    
                // var sumTotal = api
                //     .column( 4 )
                //     .data()
                //     .reduce( function (a, b) {
                //         return (intVal(a) + intVal(b)).toFixed(2);
                //     }, 0 );                
                    
                // Update footer by showing the total with the reference of the column index 
                $( api.column( 1 ).footer() ).html('Total: ');
                $( api.column( 2 ).footer() ).html(inTotal);
                $( api.column( 3 ).footer() ).html(outTotal);
                $( api.column( 4 ).footer() ).html((inTotal - outTotal).toFixed(2));
               
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
                    Yesterday: [moment().startOf("day").subtract(1, "days"), moment().endOf("day").subtract(1, "days")],
                    "Last 7 Days": [moment().startOf("day").subtract(6, "days"), moment().endOf("day")],
                    "Last 30 Days": [moment().startOf("day").subtract(29, "days"), moment().endOf("day")],
                    "This Month": [moment().startOf("day").startOf("month"), moment().endOf("month").endOf("day")],
                    "Last Month": [moment().startOf("day").subtract(1, "month").startOf("month"), moment().endOf("day").subtract(1, "month").endOf("month")],
                    Total: [moment().startOf("day").subtract(5, "year").startOf("year"), moment().endOf("day")]
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
            myData.operator_id = this.value
            cash_table.ajax.reload(null, false)
        });
        
    </script>
<style>
    
    table.dataTable td button .fa {
        border-radius: 50%;
        border: 1px solid #fff;
        padding: 3px 5px;
        font-size: 10px;
    }

    .content-i {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .content-i-2 {
        margin-bottom: -34px;
    }

    .element-wrapper-2 {
        margin-bottom: -30px;
    }

    .element-wrapper {
        padding-bottom: 4rem;
    }

    .rowrightdiv {
        display: flex;
        flex-wrap: wrap;
    }

    .element-wrapper .rowrightdiv {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .element-wrapper .rowrightdiv .el-tablo {
        padding-right: 5px;
    }

    .element-wrapper .rowrightdiv .el-tablo .value {
        font-size: 2.60rem;
        font-weight: 500;
        letter-spacing: -1px;
        line-height: 1.2;
        display: inline-block;
        vertical-align: middle;
    }

    .text-primary {
        color: #4285f4 !important;
    }

    .element-wrapper .rowrightdiv .el-tablo .label {
        display: block;
        font-size: 1.50rem;
        text-transform: uppercase;
        color: rgba(0, 0, 0, 0.4);
    }

    .b-b {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .b-r {
        border-right: 1px solid rgba(0, 0, 0, 0.1);
    }

    #cash-datatable_wrapper{
        display: inline!important;
        
    }
   
</style>
@stop