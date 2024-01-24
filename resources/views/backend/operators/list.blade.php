@extends('backend.layouts.app')

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        <div class="box box-default panel-body">
            <div class="box-header with-border">
                <div class="panel-heading"><b>Operators</b> </div>
                <div class="row">
                    <div class="col-md-3 col-xs-7">
                        <select class="form-control input-sm form-control w-sm inline v-middle" name="sel_bulk_action" id="sel_bulk_action">
                            <option value="" disabled>Bulk Action list:</option>
                            <option value="enable_panic">&nbsp;&nbsp;Enable Panic</option>
                            <option value="disable_panic" selected>&nbsp;&nbsp;Disable Panic</option>
                            <option value="enable_user">&nbsp;&nbsp;Enable Operator</option>
                            <option value="disable_user">&nbsp;&nbsp;Disable Operator</option> 
                        </select>
                    </div>
                    <div class="col-md-1 col-xs-4">
                        <button type="button" class="btn btn-sm btn-default" id="btn_apply">Apply</button>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-3 col-xs-7">
                        <select class="form-control input-sm form-control w-sm inline v-middle" name="sel_new_parent_perator" id="sel_new_parent_perator">
                            <option value="" disabled>Possible Operators:</option>
                            @foreach($all_operator_info as $child_operator)
                            <option value="{{$child_operator->id}}" >&nbsp;&nbsp;{{$child_operator->username}}</option>
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="col-md-2 col-xs-5">
                        <button type="button" class="btn btn-sm btn-default" id="btn_change_operator">Change Parent Operator</button>
                    </div>
                </div> 
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="user-datatable" >
                            <thead>
                                <tr>
                                    <th class="big-checkbox dt-body-center dt-checkboxes-select-all sorting_asc"></th>
                                    <th class="text-center">Username</th>
                                    <th>Email</th>
                                    <th>Credit</th>
                                    <th>Last Login</th>
                                    <th>IP Address</th>
                                    <th>Enabled</th>
                                    <th>Panic</th>
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
                url: "{{ route('backend.operator.listpost') }}",
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
                // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: null, defaultContent: '', target:0 },
                {data: 'username', name: 'username', className: "text-left text-bold", bSortable: true, targets: 1},
                {data: 'email', name: 'email', className: "text-left", bSortable: true, targets: 2},
                {data: 'balance', name: 'balance', bSortable: false, searchable: false, target: 3},
                {data: 'last_online', name: 'last_online', bSortable: false, searchable: false, target: 4},
                {data: 'ip_address', name: 'ip_address', className: "text-ip text-center", bSortable: false, searchable: false, target: 5},

            ],
            columnDefs: [{
                targets: 0,
                checkboxes: {
                    selectRow: true
                }
            },{
                data: "enabled",
                targets: 6,
                render: function( data, type, row) {
                    return '<i class="fa fa-check text-' + data +'"></i>'
                }
            }, {
                data: "panic",
                targets: 7,
                render: function( data, type, row) {
                    return '<i class="fa fa-times text-' + data +'"></i>'
                }
            },{
                data:"id",
                targets: 8,
                visible: false,
            }, {
                data: "username",
                targets: 1,
                render: function(data, type, row) {
                    return '<a class="text-danger text-center" href="operator/permissions/' + row.id + '">' + row.username + "</a>"
                }
            }],

            select: {
                style: 'multi'
            },
        });    
        
        $('#btn_apply').on('click', function(e){
            obj_rows = user_table.rows('.selected').data();
            var arr = [];
            $.each(obj_rows, function(key, value) {
                arr.push(value);
                return;
            });
            if (arr.length == 0) {
                alert('Please select row(s).');
            }else{
                console.log(arr);
                myData ={};
                myData.sel_rows = arr;
                myData.sel_action = $('#sel_bulk_action').val();
                user_table.ajax.reload(null, false);
            }
           
        });   
        $('#btn_change_operator').on('click', function(e){
            obj_rows = user_table.rows('.selected').data()
            var arr = []
            $.each(obj_rows, function(key, value) {
                arr.push(value)
                return
            })
            if (arr.length == 0) {
                alert('Please select row(s).')
            }else{
                console.log(arr)
                myData ={};
                myData.sel_rows = arr
                myData.sel_new_parent_operator = parseInt( $('#sel_new_parent_perator').val())
                alert(typeof(myData.sel_new_parent_operator ))
                alert((myData.sel_new_parent_operator ))
                myData.sel_action = 'change_parent_operator'
                user_table.ajax.reload(null, false)
            }
        }); 
    </script>
@stop