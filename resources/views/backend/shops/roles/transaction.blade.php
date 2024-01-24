@extends('backend.layouts.user')

@section('content')
<div class="row wow fadeIn">

    <!--Grid column-->
    <div class="col-md-9 ">
        <section class="content-header">
            <div class="panel-heading"><b>Transactions</b> </div>
            <label for="" style="color: #047bf8!important;">Warning: search is only for the players.</label>
        </section>

        <section class="content">

            <div class="content-box">
                <div class="element-wrapper">
                    <div class="element-box-tp">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="trans-datatable">
                                        <thead>
                                            <tr>
                                                <th>Shop Name</th>
                                                <th>User Name</th>
                                                <th>In</th>
                                                <th>Out</th>
                                                <th>Date</th>
                                                <th>IP address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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
    var myData = {};

    var user_table = $('#trans-datatable').DataTable({
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
            url: "{{ route('backend.shop.transactionspost') }}",
            type: "POST",
            data: function(d) {
                return $.extend(d, myData);
            },
            statusCode: {
                200: function(response) {
                    console.log(response);
                }
            }
        },
        columns: [
            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {
                data: 'from_username',
                name: 'from_username',
                className: "text-left ",
                bSortable: true,
                searchable: false,
                targets: 0
            },
            {
                data: 'to_username',
                name: 'to_username',
                className: "text-left",
                bSortable: true,
                searchable: true,
                targets: 1
            },
            {
                data: 'in_amount',
                name: 'in_amount',
                className: "text-success ",
                bSortable: false,
                searchable: false,
                target: 2
            },
            {
                data: 'out_amount',
                name: 'out_amount',
                className: "text-danger ",
                bSortable: false,
                searchable: false,
                target: 3
            },
            {
                data: 'created_at',
                name: 'created_at',
                bSortable: false,
                searchable: false,
                target: 4
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                bSortable: false,
                searchable: false,
                target: 5
            },
        ],
        columnDefs: [],

    });
</script>
<style>
    #trans-datatable_wrapper{
        display: inline!important;
    }
</style>
@stop