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
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <input type="datetime" class="form-control" name="datetimes" id="daterange" />
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="tbl_cash" class="table table-striped table-bordered dataTable table-sm" style="width:100%">
                                        <thead>
                                            <tr role="row">
                                                <th>Username</th>
                                                <th>Name</th>
                                                <th class="text-center text-success sorting_disabled">In</th>
                                                <th class="text-center text-danger sorting_disabled">Out</th>
                                                <th class="text-center sorting_disabled">Sum</th>

                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $total_payout = 0;
                                            $total_in = 0;
                                            $total_out = 0;  ?>
                                            @foreach ($shops_all as $key =>$shop_from)
                                            <tr>
                                                <td><a href="#">{{$shop_from->shop_to_user->username}}</a> </td>
                                                <td><a href="#">{{$shop_from->shop_to_user->username}}</a> </td>

                                                <td class="text-center text-success">{{$shop_from->sum_in}}</td>
                                                <td class="text-center text-danger">{{$shop_from->sum_out}}</td>
                                                <td class="text-center">{{$shop_from->sum_in - $shop_from->sum_out}}</td>

                                            </tr>
                                            <?php
                                            $total_in += $shop_from->sum_in;
                                            $total_out += $shop_from->sum_out;
                                            ?>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" rowspan="1" style="text-align:right" class="text-right text-center">Total:</th>
                                                <th colspan="1" rowspan="1" class="text-center text-success">{{$total_in}}</th>
                                                <th colspan="1" rowspan="1" class="text-center text-danger">{{$total_out}}</th>
                                                <th colspan="1" rowspan="1" class="text-center">{{$total_in - $total_out}}</th>

                                            </tr>
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
{!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\CreateUserRequest', '#changes-form') !!}
<script>
    $("body").on("change", "#tbl_cash", function() {
        alert('data added dynamically!');
        var table_cash = $('#tbl_cash').DataTable({
            searchPanes: true,
            stateSave: true,
            "iDisplayLength": -1,
            "aoColumns": [{
                    "bSortable": true
                },
                {
                    "bSortable": true
                },
                {
                    "bSortable": true
                },
                {
                    "bSortable": true
                },
                {
                    "bSortable": false
                }
            ]
        });
        table_cash.searchPanes.container().prependTo(table.table().container());
        table_cash.searchPanes.resizePanes();
    });

    $(function() {
        $('input[name="datetimes"]').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            startDate: localStorage.getItem("startdate") === null ? moment().startOf('hour') : localStorage.getItem("startdate"),
            endDate: localStorage.getItem("enddate") === null ? moment().startOf('hour').add(32, 'hour') : localStorage.getItem("enddate"),
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
            "aoColumns": [{
                    "bSortable": true
                },
                {
                    "bSortable": true
                },
                {
                    "bSortable": false
                },

            ]
        });
        table_cash.searchPanes.container().prependTo(table.table().container());
        table_cash.searchPanes.resizePanes();
    });
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        var startdate = picker.startDate.format('YYYY-MM-DD hh:mm');
        var enddate = picker.endDate.format('YYYY-MM-DD hh:mm');

        $("#startdate").val(startdate);
        $("#enddate").val(enddate);
        localStorage.setItem('startdate', startdate);
        localStorage.setItem('enddate', enddate);

        $("#changes-form").submit();
    });
</script>
<style>
    #tbl_cash_wrapper {
        display: inline!important;
    }
</style>
@stop