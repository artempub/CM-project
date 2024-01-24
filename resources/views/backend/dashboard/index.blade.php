@extends('backend.layouts.app')

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        <div class="box box-default panel-body">
            <div class="box-header with-border">
                <div class="panel-heading"><b>Dashboard</b> </div>
                <div class="row" id="stats1">
                    <div class="col-md-12">
                    <div class="row row-sm text-center">
                    <div class="col-xs-3">
                    <div class="panel padder-v item bg-default">
                    <div class="h1 text-info  h3 c_users">{{$users_count}}</div>
                    <span title="Total active this month" data-original-title="top" class=" ">Users
                    </span>
                    </div>
                    </div>
                    <div class="col-xs-3">
                    <div class="panel padder-v item bg-primary">
                    <div class="h1 text-info  h3 c_shops">{{$shops_count}}</div>
                    <span title="Total active this month" data-original-title="top" class="text-muted ">Shops
                    </span>
                    </div>
                    </div>
                    <div class="col-xs-3">
                    <div class="panel padder-v item bg-info">
                    <div class="h1 text-white  h3 c_ops">{{$operators_count}}</div>
                    <span title="Total active this month" data-original-title="top" class="text-muted ">Operators
                    </span>
                    </div>
                    </div>
                    <div class="col-xs-3">
                    <div class="panel padder-v item bg-danger">
                    <div class="h1 text-info  h3 c_total">{{$users_count + $shops_count + $operators_count}}</div>
                    <span title="Total active this month" data-original-title="top" class="text-muted ">Total
                    </span>
                    </div>
                    </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" style="width:100%" id="user-datatable" >
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box-footer"></div>
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
        var myData ={};
        

        var user_table = $('#user-datatable').DataTable({
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
                url: "{{ route('backend.homelist') }}",
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
                {data: 'from_username', name: 'from_username', className: "text-center", bSortable: true, targets: 1},
                {data: 'to_username', name: 'to_username', className: "text-center", bSortable: true, targets: 2},
                {data: 'in_amount', name: 'in_amount', className: "text-center text-success", bSortable: false, searchable: false, target: 3},
                {data: 'out_amount', name: 'out_amount', className: "text-center text-danger", bSortable: false, searchable: false, target: 4},
                {data: 'created_at', name: 'created_at', className: "text-center", bSortable: true, searchable: false, target: 5},
            ],
            
        });

        $('<div class="pull-left">' +
            '<select class="form-control input-sm" id="sel_role" name="sel_role">'+
            '<option value="all">All</option>'+
            '<option value="user">Users</option>'+
            '<option value="shop">Shops</option>'+
            '<option value="operator">Operators</option>'+
            '</select>' + 
            '</div>').appendTo("#user-datatable_wrapper .dataTables_filter"); //example is our table id

        $(".dataTables_filter label").removeClass("pull-right");
        $(".dataTables_filter label").addClass("pull-left");

        $('#sel_role').on('change', function(e){
            myData.sel_role = $('#sel_role').val();
            user_table.ajax.reload(null, false);
        }); 
    </script>
@stop