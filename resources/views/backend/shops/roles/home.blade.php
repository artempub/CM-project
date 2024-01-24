@extends('backend.layouts.user')

@section('content')

<section class="content-header">
    @include('backend.partials.messages')
</section>
<!--Grid row-->
<div class="row wow fadeIn">

    <!--Grid column-->
    <div class="col-md-9 ">
        <div class="content-box">
            <div class="element-wrapper">
                <div class="element-box-tp">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover datatable table-bordered " style="width:100%; " id="shophome-datatable">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Credits</th>
                                            <th>Bonus</th>
                                            <th>Alarm</th>
                                            <th>In</th>
                                            <th>Out</th>
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

    <!--Grid column-->
    <div class="col-md-3 ">

        <div class="content-panel rightBarLogs" id="rightBarLogs" style="padding-top: 0px; padding:0px;">
            <div class="content-panel-close"><i class="os-icon os-icon-close"></i></div>
            <div id="logs">
                <div class="content-i content-i-2">
                    <div class="element-wrapper element-wrapper-2">
                        <div class="rowrightdiv text-center">
                            <div class="col-sm-12 b-r b-b">
                                <div class="el-tablo centered el-tabloPiso">
                                    <div class="value text-primary top_credits">
                                        <span id="shop_balance">{{$shop_balance}}</span>
                                        <input type="hidden" name="shop_balance" id="shop_balance_hidden" value="{{$shop_balance}}">

                                    </div>
                                    <div class="label">CREDITS</div>
                                </div>
                            </div>

                            <div class="col-sm-4 b-r b-b">
                                <div class="el-tablo centered el-tabloPiso">
                                    <div class="value text-success statsTop top_in"><span id="shop_total_in">{{$shop_total_in}}</span></div>
                                    <div class="label">In</div>
                                </div>
                            </div>
                            <div class="col-sm-4 b-r b-b">
                                <div class="el-tablo centered el-tabloPiso">
                                    <div class="value text-danger statsTop top_out"><span id="shop_total_out">{{$shop_total_out}}</span></div>
                                    <div class="label">-</div>
                                </div>
                            </div>
                            <div class="col-sm-4 b-r b-b">
                                <div class="el-tablo centered el-tabloPiso">
                                    <div class="value statsTop top_total"><span id="shop_total_sum">{{$shop_total_sum}}</span></div>
                                    <div class="label">Total</div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="rowrightdiv text-center mt-4">
                            <div class="col-md-12">
                                <a class="btn btn-success" href="{{ route('backend.start_shift') }}"> @lang('app.start_shift')</a></li>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="logs_2" style="padding-top: 15px;"></div>
            </div>
        </div>


    </div>
    <!--Grid column-->

</div>
<!--Grid row-->
<div class="modal" id="openAddModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-12 text-center">
                    <input id="in_username" type="text" class="value text-gray-dark nameinput" disabled>
                    <input id="in_credits" type="text" class="value text-gray-dark nameinput" disabled>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group row">

                    <div class="alert alert-danger  virtual_games none_none text-center container" role="alert" style="display: none;">
                        <h4 class="alert-heading">Warning</h4>
                        <p class="mb-0">This player has bets on VIRTUAL GAMES please try in few minutes</p>
                        <hr>
                    </div>


                    <div class="alert alert-danger  cancelbonusMessageIn none_none text-center container" role="alert" style="display: none;">
                        <h4 class="alert-heading">Warning</h4>
                        <p class="mb-0">Error this player got credits from bonus!</p>
                        <p class="mb-0">Please cash out first or play all credits.</p>
                        <hr>
                    </div>

                    <div class="alert alert-danger  none_none text-center container cancelButton" id="cancelButton" style="display: none;">
                        <h4 class="alert-heading">Warning</h4>
                        <p class="mb-0">Warning all BONUS / FREE SPINS uncollected wins will be LOST.</p>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelbonus" style="cursor: pointer;">Reset</button>
                    </div>

                    <div class="col-sm-12 text-center inamountDiv">
                        <button type="button" class="btn btn-primary btnIN" data-in="1.00">
                            +1.00</button>
                        <button type="button" class="btn btn-primary btnIN" data-in="5.00">
                            +5.00</button>
                        <button type="button" class="btn btn-primary btnIN" data-in="10.00">
                            +10.00</button>
                        <button type="button" class="btn btn-primary  d-xl-table-cell d-lg-table-cell d-md-table-cell btnIN" data-in="20.00">
                            +20.00</button>
                        <button type="button" class="btn btn-primary  d-xl-table-cell d-lg-table-cell d-md-table-cell btnIN" data-in="50.00">
                            +50.00</button>
                        <div class="mb-2"></div>
                    </div>
                </div>

                <div class="form-group row" id="scoreInputInModal">
                    <div class="col-sm-12 indiv text-center">
                        <label for="in_score"></label>
                        <input class="in_Input scoreinput" value="0" name="score" id="in_score" type="tel">
                        <input value="" name="in_userid" id="in_userid" type="hidden">

                    </div>
                </div>
                <div id="happyHour" class="text-center">
                    <h4 id="happyHourA" class="none_none" style="display: none;">Happy hour: <span class="text-success">Available</span></h4>
                    <h4 id="happyHourB" class="none_none" style="display: block;">Happy hour: <span class="text-danger">Not available</span></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_in_credit">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="openMinModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-12 text-center">
                    <input id="out_username" type="text" class="value text-gray-dark nameinput" disabled>
                    <input id="out_credits" type="text" class="value text-gray-dark nameinput" disabled>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group row">

                    <div class="alert alert-danger  virtual_games none_none text-center container" role="alert" style="display: none;">
                        <h4 class="alert-heading">Warning</h4>
                        <p class="mb-0">This player has bets on VIRTUAL GAMES please try in few minutes</p>
                        <hr>
                    </div>


                    <div class="alert alert-danger  cancelbonusMessageIn none_none text-center container" role="alert" style="display: none;">
                        <h4 class="alert-heading">Warning</h4>
                        <p class="mb-0">Error this player got credits from bonus!</p>
                        <p class="mb-0">Please cash out first or play all credits.</p>
                        <hr>
                    </div>

                    <div class="alert alert-danger  none_none text-center container cancelButton" id="cancelButton" style="display: none;">
                        <h4 class="alert-heading">Warning</h4>
                        <p class="mb-0">Warning all BONUS / FREE SPINS uncollected wins will be LOST.</p>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelbonus" style="cursor: pointer;">Reset</button>
                    </div>

                    <div class="col-sm-12 text-center inamountDiv">
                        <button type="button" class="btn btn-primary btnIN" data-in="1.00">
                            -1.00</button>
                        <button type="button" class="btn btn-primary btnIN" data-in="5.00">
                            -5.00</button>
                        <button type="button" class="btn btn-primary btnIN" data-in="10.00">
                            -10.00</button>
                        <button type="button" class="btn btn-primary  d-xl-table-cell d-lg-table-cell d-md-table-cell btnIN" data-in="20.00">
                            -20.00</button>
                        <button type="button" class="btn btn-primary  d-xl-table-cell d-lg-table-cell d-md-table-cell btnIN" data-in="50.00">
                            -50.00</button>
                        <div class="mb-2"></div>
                    </div>
                </div>

                <div class="form-group row" id="scoreInputOutModal">
                    <div class="col-sm-12 indiv text-center">
                        <label for="out_score"></label>
                        <input class="in_Input scoreinput" value="0" name="score" id="out_score" type="tel">
                        <input value="" name="out_userid" id="out_userid" type="hidden">
                    </div>
                </div>
                <!-- <div id="happyHour" class="text-center">
                            <h4 id="happyHourA" class="none_none" style="display: none;">Happy hour: <span class="text-success">Available</span></h4>
                            <h4 id="happyHourB" class="none_none" style="display: block;">Happy hour: <span class="text-danger">Not available</span></h4>
                        </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_out_credit">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">
                    User Detail:
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="balance">Username</label>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" id="selected_userId" name="selected_userId">
                            <input type="text" class="form-control" id="selected_username" name="selected_username">
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-2 control-label" for="balance">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="selected_password" name="selected_password" disabled>
                        </div>
                    </div> -->

                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <!-- <input type="button" class="btn btn-default" data-dismiss="modal" value="Close"> -->
                <input type="button" class="btn btn-danger" id="btn_delUser" value="Delete User">
                <input type="button" class="btn btn-primary" id="btn_editUser" value="Save User">
            </div>
        </div>
    </div>
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
    var credits_sum_per_click = 0;
    var url = "{{ route('backend.shop.homepost') }}";

    var user_table = $('#shophome-datatable').DataTable({
        processing: true,
        serverSide: true,
        // pagingType: "full_numbers",
        stateSave: true,
        paging: false,   
        ordering: true,
        info: true,
        order: [
            [1, "desc"]
        ],
        lengthMenu: [
            [10, 25, 50, 100, 100000],
            [10, 25, 50, 100, "All"]
        ],
        ajax: {
            url: url,
            type: "POST",
            data: function(d) {
                return $.extend(d, myData);
            },
            statusCode: {
                200: function(response) {
                    console.log(response);
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'shop_balance_changing': true
                        },
                        statusCode: {
                            429: function(msg) {
                                // alertify.error(ERROR + ' Too Many Requests', 'success', 5); // show alert
                            },
                            400: function(msg) {
                                // alertify.error(ERROR, 'success', 5); // show alert
                                alert('Unknown backend error. Please try later on.')
                            },
                            200: function(response) {

                                $("#shop_balance").text(response.shop_balance);
                                $("#shop_total_in").text(response.shop_total_in);
                                $("#shop_total_out").text(response.shop_total_out);
                                $("#shop_total_sum").text(response.shop_total_sum);

                            }
                        },
                    });
                }
            }
        },
        columns: [
            // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {
                data: 'username',
                name: 'username',
                className: "text-left",
                bSortable: false,
                targets: 0
            },
            {
                data: 'credits',
                name: 'credits',
                bSortable: false,
                searchable: false,
                target: 1
            },
            {
                data: 'bonus',
                name: 'bonus',
                bSortable: false,
                searchable: false,
                target: 2
            },
            // {
            //     data: 'title',
            //     name: 'title',
            //     bSortable: false,
            //     searchable: false,
            //     target: 2
            // },
        ],
        columnDefs: [{
            data: "username",
            targets: 0,
            render: function(data, type, row) {
                return '<a class="text-success" href="#" onclick="invokeUserModal(' + row.id + ', `' + row.username + '`)" >' + row.username + "</a>"
            }
        }, {
            data: "enabled",
            targets: 3,
            width: 18,
            render: function(data, type, row) {
                var alarm = 'times';
                var btn_class = 'success';
                var user_stat = 'success';
                if (data == 'danger') {
                    alarm = 'check';
                    btn_class = data;
                    user_stat = data;
                }
                return '<button type="button" class="btn btn-' + btn_class + ' btn-alarm" data-id="' + row.id + '" onclick="user_alarm(' + row.id + ', `' + user_stat + '`)"><i class="fa fa-' + alarm + '"></i></button>';
            }
        }, {
            targets: 4,
            width: 8,
            render: function(data, type, row) {
                return '<button type="button" class="btn btn-primary pointer" onclick="in_modal(' + row.id + ', `' + row.username + '`, `' + row.credits + '`)"><i class="fa fa-plus"></i></button>';
            }
        }, {
            targets: 5,
            width: 8,
            render: function(data, type, row) {
                return '<button type="button" class="btn btn-danger pointer" onclick="out_modal(' + row.id + ', `' + row.username + '`, `' + row.credits + '`)"><i class="fa fa-minus"></i></button>';
            }
        }, {
            data: "id",
            targets: 6,
            visible: false,
        }],

    });

    function invokeUserModal(user_id, username) {
        $('#selected_userId').val(user_id)
        $('#selected_username').val(username)
        $('#edit_user_modal').modal('show')
    }

    $('#btn_editUser').on('click', function(e) {
        myData = {}
        myData.sel_userid = $('#selected_userId').val()
        myData.username = $('#selected_username').val()
        myData.sel_action = 'edit_user'
        console.log(myData)
        user_table.ajax.reload(null, false)
        $('#edit_user_modal').modal('hide')
    })
    $('#btn_delUser').on('click', function(e) {
        myData = {}
        myData.sel_userid = $('#selected_userId').val()
        myData.username = $('#selected_username').val()
        myData.sel_action = 'del_user'
        console.log(myData)
        user_table.ajax.reload(null, false)
        $('#edit_user_modal').modal('hide')
    })

    function user_alarm(user_id, user_stat) {
        console.log(user_stat);
        myData.sel_userid = user_id;
        myData.sel_action_alarm = user_stat;

        user_table.ajax.reload(null, false);
    }

    function in_modal(user_id, username, credits) {
        console.log(user_id);
        $('#in_userid').val(user_id);
        $('#in_username').val(username);
        $('#in_credits').val(credits);
        $('.scoreinput').val(0);
        credits_sum_per_click = 0;
        $('#openAddModal').modal('show');
    }

    function out_modal(user_id, username, credits) {
        console.log(user_id);
        $('#out_userid').val(user_id);
        $('#out_username').val(username);
        $('#out_credits').val(credits);
        $('.scoreinput').val(0);
        credits_sum_per_click = 0;
        $('#openMinModal').modal('show');
    }
    $('.btnIN').on('click', function(e) {
        console.log(typeof($(this).data('in')));

        credits_sum_per_click += parseInt($(this).data('in'));

        $('.scoreinput').val(credits_sum_per_click);
    });
    $('.scoreinput').onkeydown = function() {
        var key = event.keyCode || event.charCode;

        if (key == 8 || key == 46) {

        }

    };
    $('.scoreinput').mousedown(function(event) {
        credits_sum_per_click = '';
        $('.scoreinput').val(credits_sum_per_click);
    });

    $('#btn_in_credit').on('click', function(e) {
        myData.sel_userid = $('#in_userid').val();
        myData.sel_credit = parseInt($('#in_score').val());
        if (myData.sel_credit > parseInt($('#shop_balance_hidden').val())) {
            alert('Shop credit is not enough. Please contact your Operator.')
            return            
        }
        myData.sel_action = 'in_credits'
        user_table.ajax.reload(null, false);
        $('#openAddModal').modal('hide');


    })
    $('#btn_out_credit').on('click', function(e) {
        myData.sel_userid = $('#out_userid').val();
        myData.sel_credit = parseInt($('#out_score').val());
        if (myData.sel_credit > parseInt($('#out_credits').val())) {
            alert('User credit is not enough right now. Please lower the Out amount.')
            return            
        }
        myData.sel_action = 'out_credits'
        user_table.ajax.reload(null, false);
        $('#openMinModal').modal('hide');

    })
</script>
<style>
    .btn-primary {
        color: #fff;
        background-color: #4285f4 !important;
    }

    .btn-success {
        color: #fff;
        background-color: #90be2e !important;
        border-color: #90be2e !important;
    }

    .button-success:hover {
        background-color: #556B2F !important;
    }

    .btn-danger {
        color: #fff;
        background-color: #e65252 !important;
        border-color: #e65252 !important;
    }

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

    /* for modal */
    .modal {
        text-align: center;
        -webkit-animation-name: fadeIn;
        /* Fade in the background */
        -webkit-animation-duration: 1.2s;
    }

    @media screen and (min-width: 768px) {
        .modal:before {
            display: inline-block;
            vertical-align: middle;
            content: " ";
            height: 100%;
        }
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }

    /* Modal Content */
    .modal-content {

        -webkit-animation-name: slideIn;
        -webkit-animation-duration: 1s;
        border-radius: 16px 16px 0px 0px !important;
    }

    .nameinput {
        background-color: #88ace7;
        /* color: red; */
        text-align: center;
        border: 0;
        font-size: 25px;
        size: 24px;
        width: 41%;
        margin: 7px;
        border-radius: 10px;
        opacity: 1;
    }

    .scoreinput {
        /* height: 50px; */
        background-color: #88ace7;
        color: red;
        text-align: center;
        border: 0;
        font-size: 25px;
        size: 24px;
        width: 70%;
        font-weight: bold;
        font-size: 30px;
        padding-bottom: 5px;
        opacity: 1;
    }
    #shophome-datatable_wrapper{
        display: inline!important;
        
    }
   
</style>
@stop