@extends('backend.layouts.app')

@section('content')

<section class="content-header">
    @include('backend.partials.messages')
</section>

<section class="content">

    <div class="box box-default panel-body">
        <div class="box-header with-border">
            <div class="panel-heading"><b>Operator "<b class="text-danger"> {{ $operator_name }}</b>" Details</b>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" id="profile_tab" class="">
                                <a href="#op_profile" class="nav-link" aria-controls="op_profile" role="tab" data-toggle="tab">Profile
                                </a>
                            </li>
                            <li role="presentation" id="permission_tab" class="active">
                                <a href="#op_permission" class="nav-link" aria-controls="op_permission" role="tab" data-toggle="tab">Permissions
                                </a>
                            </li>
                            @if (isset($flag_1operator) && $flag_1operator == true)
                            {{-- <li role="presentation"  class="">
                                <a href="#category_allowcation" class="nav-link" aria-controls="category_allowcation" role="tab" data-toggle="tab">Categories
                                </a>
                            </li> --}}
                            {{-- <li role="presentation" class="">
                                        <a href="#provider_allowcation" class="nav-link"
                                            aria-controls="provider_allowcation" role="tab" data-toggle="tab">Categories
                                        </a>
                                    </li> --}}
                            @endif
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane " id="op_profile">
                                <br>
                                <form action="" method="post" class="form-horizontal">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label text-ce">
                                                Start Credit
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Initial credit.each time you add or remove credits, this field is changed accordingly. This field can be reset if you check  the reset start credit checkbox!"></span>
                                            </label>
                                            <div class="col-md-3">
                                                <input type="text" disabled="" value="{{ $operator_info->start_credit }}" class="text-center form-control" id="operator_startCredits">
                                            </div>
                                            <label class="col-md-3 control-label text-ce">
                                                Reset Start Credit?
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="When pressing reset button, if you enable Reset Start Credit option, the Start Credit will be zero"></span>
                                            </label>
                                            <div class="col-md-2">
                                                <div class="col-md-1" id="">
                                                    <label class="i-checks">
                                                        <input type="checkbox" name="reset_operator_startCredits" id="reset_operator_startCredits"><i></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="col-lg-offset-2 col-lg-10 text-right">
                                                    <button class="btn btn-danger resetOp" id="btn_reset_operator">
                                                        Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Credits
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Credits">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" disabled="" value="{{ $operator_balance }}" class="text-center form-control" id="operator_credits">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btn_credit_out" style="width: 70px;">
                                                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                                <input type="text" id="credits_inout" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btn_credit_in" style="width: 70px;">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Accounts Limit
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="If you want to allow this shop to create players you have to share accounts with this shop. For example if you want this shop to create up to 5 users you should share with him 5 accounts.">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="account_limits" class="none"></label>
                                            <input type="text" disabled id="operator_account_limits" value="{{ $operator_info->account_limit }}" class="text-center form-control" style="margin-top:-20px;">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger" type="button" id="btn_account_out" style="width: 70px;">
                                                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                                    </button>
                                                </span>
                                                <label for="accounts" class="none"></label>
                                                <input type="text" id="accounts_inout" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" id="btn_account_in" style="width: 70px;">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-horizontal">
                                    {!! Form::open(['route' => 'backend.user.change_pw', 'files' => true, 'id' => 'change-pw-form']) !!}
                                    <input type="hidden" name="user_id" id="user_id" value="{{ $operator_info->user_id }}">
                                    <input type="hidden" name="username" id="username" value="{{ $operator_info->operator_user->username }}">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Password
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Password">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" class="form-control" placeholder="password">
                                                <span class="input-group-btn">
                                                    <button id="btn_changePw" class="btn btn-primary" type="submit">
                                                        Save
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            TimeZone
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Timezone for cashier logs and cash / shifts reports">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <select class="form-control timezone" id="operator_timezone" name="timezone" data-live-search="true">
                                                @foreach ($timezones as $timezone)
                                                <option value="{{ $timezone->name }}" {{ $timezone->name == $operator_info->timezone ? 'selected' : '' }}>
                                                    {{ $timezone->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Currency
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Timezone for cashier logs and cash / shifts reports">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <select class="form-control timezone" id="operator_currency" name="currency" data-live-search="true">
                                                @foreach ($currencies as $currency)
                                                <option value="{{ $currency }}" {{ $currency == $operator_info->currency ? 'selected' : '' }}>
                                                    {{ $currency }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Api Userhash
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Api Userhash">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" disabled="" value="{{ $operator_info->api_hash }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            IP
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="IP">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" disabled="" value="{{ $operator_info->ip_address }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Last login
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Last login">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" disabled="" value="{{ $operator_lastLogin }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            URL
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="URL">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" disabled="" value="{{ $operator_info->url }}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Percentage
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="This field is to calculate the total cash of the percentage on cash page">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="userPercentage" class="none"></label>
                                            <input type="text" id="operator_percentage" name="percentage" value="{{ $operator_info->percentage }}" class="form-control" style="margin-top: -20px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Family
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title=" Family">
                                            </span>
                                        </label>
                                        <div class="col-md-10">
                                            <ol class="breadcrumb" style="padding-right:20px;">
                                                <li class="breadcrumb-item">
                                                    Administrator
                                                </li>
                                                @if (isset($parent_username_array))
                                                @foreach ($parent_username_array as $parent)
                                                <li class="breadcrumb-item">
                                                    {{ $parent }}
                                                </li>
                                                @endforeach
                                                @endif
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10 text-right" style="padding-right:20px;">
                                            <button type="button" id="btn_edit_operator_profile" class="btn btn-sm btn-primary" style="margin-right: 16.667px;">Save</button>
                                        </div>
                                    </div>
                                    <br>
                                    {!! Form::open(['route' => 'backend.user.remove', 'files' => true, 'id' => 'user-remove-form']) !!}

                                    <div class="line line-lg b-b b-light"></div>
                                    <div class="col-md-12 text-right" style="padding-top: 20px;">
                                        <input type="hidden" name="remove_user_id" value="{{ $operator_info->user_id }}">
                                        <input type="hidden" name="remove_operator_id" value="{{ $operator_info->id }}">
                                        <input type="hidden" name="remove_user_role" value="operator">
                                        <button type="button" id="btn_submit_del_user" class="btn-atag">Delete this
                                            Operator</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                            </div>
                            <div class="tab-pane active" id="op_permission">
                                <div class="">
                                    <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="permission-datatable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Permissions</th>
                                                <th class="text-center">Description</th>
                                                <th class="text-center">Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if (isset($flag_1operator) && $flag_1operator == true)
                            {{-- <div class="tab-pane table-responsive" id="category_allowcation">
                                <div class="row ">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="category_allowcation-datatable">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Game Category</th>
                                                    <th class="text-center">Game Providers owned</th>
                                                    <th class="text-center">Enabled</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="tab-pane" id="provider_allowcation">
                                        <div class="panel-body">
                                            <div class="">
                                                <div class="panel-group" id="accordion">
                                                    <div class="faqHeader">Provider allocation per Category</div><br>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="accordion-toggle" data-toggle="collapse"
                                                                    data-parent="#accordion" href="#001">Casino</a>
                                                            </h4>
                                                        </div>
                                                        <div id="001" class="panel-collapse collapse in">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    @foreach ($categories as $cat)
                                                                        <div class="col-md-2">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    class="checkbox-provider"
                                                                                    name="{{ $cat->href }}"
                            value="{{ $cat->href }}"
                            {{ $cat->enabled ? 'checked' : '' }} />
                            &nbsp;{{ $cat->title }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
    </div>
    </div> --}}
    @endif

    </div>

    </div>
    </div>
    </div>

    </div>
    <div class="box-footer"></div>
    <!-- Modal -->
    <div class="modal fade" id="del_confirm_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="exampleModalLabel">Warning!</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure to delete the account?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="del_sure">Sure</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="box box-default panel-body">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#logs_tab" class="nav-link" aria-controls="logs_tab" role="tab" data-toggle="tab">Logs
                                </a>
                            </li>
                            <li role="presentation class="">
                                    <a href=" #operator_tab" class="nav-link" aria-controls="operator_tab" role="tab" data-toggle="tab">Operators
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#shops_tab" class="nav-link" aria-controls="shops_tab" role="tab" data-toggle="tab">Shops
                                </a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#users_tab" class="nav-link" aria-controls="tab" role="tab" data-toggle="tab">Users
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="logs_tab">
                                <ul class="list-group no-borders">
                                    <li class="list-group-item">
                                        <div id="log22s" class="well" style="background-color: black;height: 300px; overflow: auto;">
                                            <div class="row" style="margin-top: -15px;">
                                                <kbd style="width: 100%; height: 200px; overflow-x: auto;" id="logs">
                                                    @foreach($gameLogs as $item)
                                                    <span class="text-success"> {{$item->user->username}}</span>
                                                    <span class="text-success"> IP:{{$item->user->ip_address}}</span>
                                                    <span class="text-white"> {{$item->date_time}}</span>
                                                    @if($item->win > 0)
                                                    <span class="text-primary"> win in: {{$item->game}} balance: {{$item->balance}}(+ {{$item->win}})</span>
                                                    @else
                                                    <span class="text-primary"> bet in: {{$item->game}}</span>
                                                    @endif
                                                    <br>
                                                    @endforeach
                                                </kbd>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane" id="operator_tab">
                                <div class="row">
                                    <div class="col-md-3 col-xs-7">
                                        <select class="form-control input-sm form-control w-sm inline v-middle" name="sel_bulk_action-operator" id="sel_bulk_action-operator">
                                            <option value="" disabled>Bulk Action list:</option>
                                            <option value="enable_panic">&nbsp;&nbsp;Enable Panic</option>
                                            <option value="disable_panic" selected>&nbsp;&nbsp;Disable Panic</option>
                                            <option value="enable_user">&nbsp;&nbsp;Enable Operator</option>
                                            <option value="disable_user">&nbsp;&nbsp;Disable Operator</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-xs-4">
                                        <button type="button" class="btn btn-sm btn-default" id="btn_apply-operator">Apply</button>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="operator-datatable">
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
                            </div>


                            <div class="tab-pane" id="shops_tab">
                                <div class="row">
                                    <div class="col-md-3 col-xs-7">
                                        <select class="form-control input-sm form-control w-sm inline v-middle" name="sel_bulk_action-shop" id="sel_bulk_action-shop">
                                            <option value="" disabled>Bulk Action list:</option>
                                            <option value="enable_panic">&nbsp;&nbsp;Enable Panic</option>
                                            <option value="disable_panic" selected>&nbsp;&nbsp;Disable Panic</option>
                                            <option value="enable_user">&nbsp;&nbsp;Enable Shop</option>
                                            <option value="disable_user">&nbsp;&nbsp;Disable Shop</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-xs-4">
                                        <button type="button" class="btn btn-sm btn-default" id="btn_apply-shop">Apply</button>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="shop-datatable">
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
                            </div>


                            <div class="tab-pane" id="users_tab">
                                <div class="row">
                                    <div class="col-md-3 col-xs-7">
                                        <select class="form-control input-sm form-control w-sm inline v-middle" name="sel_bulk_action-user" id="sel_bulk_action-user">
                                            <option value="" disabled>Bulk Action list:</option>
                                            <option value="enable_panic">&nbsp;&nbsp;Enable Panic</option>
                                            <option value="disable_panic" selected>&nbsp;&nbsp;Disable Panic</option>
                                            <option value="enable_user">&nbsp;&nbsp;Enable User</option>
                                            <option value="disable_user">&nbsp;&nbsp;Disable User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-xs-4">
                                        <button type="button" class="btn btn-sm btn-default" id="btn_apply-user">Apply</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered" style="width:100%" id="user-datatable">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
</section>
@stop

@section('scripts')
<script>
    var myData = {}
    var url = "{{ route('backend.operator.edit_profile') }}"
    var url_gamecategory = "{{ route('backend.shop.categorylistpost') }}"
    var url_gameprovider = "{{ route('backend.shop.providerlistpost') }}"
    myData.user_id = parseInt($('#user_id').val())
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });
    $('.checkbox-provider').change(function() {
        var providerChecked = this.value
        var status = 'disabled' //add the provider row to the table
        if (this.checked) {
            //remove the provider name row from the table
            status = 'enabled'
        }
        console.log(status)
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.providerChecked = providerChecked
        myData.sel_action = status
        $.ajax({
            type: 'POST',
            url: url_gameprovider,
            data: myData,
            statusCode: {
                200: function(response) {
                    console.log(response.status)
                }
            },
        });
    });

    function permissionOnOff(permission_id, state) {
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.sel_permission = permission_id
        myData.sel_status = state
        console.log(myData.user_id)

        permission_table.ajax.reload(null, false)
    }

    $('#btn_edit_operator_profile').on('click', function(e) {
        var btn_clicked = $(this)
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.edit_operator_profile = true

        myData.timezone = $('#operator_timezone').val()
        console.log(myData.timezone)
        myData.currency = $('#operator_currency').val()
        myData.percentage = $('#operator_percentage').val()

        btn_clicked.prop("disabled", true)
        $.ajax({
            type: 'POST',
            url: url,
            data: myData,
            statusCode: {
                200: function(response) {
                    console.log(response)
                    $('#operator_timezone').val(response.timezone)
                    $('#operator_currency').val(response.currency)
                    $('#operator_percentage').val(response.bonus)

                    btn_clicked.prop("disabled", false)
                }
            },
        });
    });

    $.fn.dataTable.ext.errMode = "none"

    var permission_table = $('#permission-datatable').DataTable({
        processing: true,
        serverSide: true,
        pagingType: "full_numbers",
        stateSave: true,
        paging: false,
        ordering: true,
        info: true,
        searching: false,
        // scrollY: 200,
        // scrollCollapse: true,
        order: [
            [1, "desc"]
        ],
        lengthMenu: [
            [10, 25, 50, 100, 100000],
            [10, 25, 50, 100, "All"]
        ],
        ajax: {
            url: "{{ route('backend.operator.edit_permission') }}",
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
        columns: [{
                data: 'title',
                className: "text-center",
                bSortable: true,
                targets: 0
            },
            {
                data: 'description',
                className: "text-center",
                bSortable: true,
                targets: 1
            },
            {
                data: 'per_op_count',
                name: 'per_op_count',
                className: "text-center",
                defaultContent: '',
                target: 2
            },
        ],
        columnDefs: [

            {
                data: "per_op_count",
                targets: 2,
                className: "text-center",
                bSortable: false,
                render: function(data, type, row) {
                    var status = {
                        1: {
                            'xxx': '<label class="switch"><input type="checkbox" onclick="permissionOnOff(' +
                                row.id + ',0)"  checked><span class="slider round"></span></label>'
                        },
                        0: {
                            'xxx': '<label class="switch"><input type="checkbox" onclick="permissionOnOff(' +
                                row.id + ',1)" ><span class="slider round"></span></label>'
                        },
                    };
                    return status[data].xxx;
                },
            },
        ],
    });

    $('#btn_reset_operator').on('click', function(e) {
        //check if reset_startCredits checkbox is true
        var btn_clicked = $(this)
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.reset_operator_credits = true //always in this UI
        myData.reset_operator_startCredits = $('#reset_operator_startCredits').is(":checked")
        btn_clicked.prop("disabled", true)
        $.ajax({
            type: 'POST',
            url: url,
            data: myData,
            statusCode: {
                200: function(response) {

                    if (response.operator_startCredits_reset) {
                        $("#operator_startCredits").val('0')
                        $("#operator_credits").val('0')
                    }
                    btn_clicked.prop("disabled", false)
                }
            },
        });
    });

    $('#btn_credit_in').on('click', function(e) {
        var btn_clicked = $(this)
        var credits_in = parseFloat($('#credits_inout').val())
        console.log(myData.user_id)
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.operator_credits_in = credits_in
        btn_clicked.prop("disabled", true)
        $.ajax({
            type: 'POST',
            url: url,
            data: myData,
            statusCode: {
                200: function(response) {
                    if (response.status == 'lack_operator_credits') {
                        alert('Lack of Operator credits!')
                        btn_clicked.prop("disabled", false)
                    } else {
                        console.log(response)
                        $("#operator_credits").val(response.operator_credits)
                        btn_clicked.prop("disabled", false)
                    }
                }
            },
        });
    });
    $('#btn_credit_out').on('click', function(e) {
        var btn_clicked = $(this)
        var credits_out = parseFloat($('#credits_inout').val())
        var operator_credits = parseFloat($('#operator_credits').val())
        if (credits_out > operator_credits) {
            alert('Please lower the Out amount.')
            return
        }
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.operator_credits_out = credits_out
        btn_clicked.prop("disabled", true)
        $.ajax({
            type: 'POST',
            url: url,
            data: myData,
            statusCode: {
                200: function(response) {
                    if (response.status == 'lack_user_credits') {
                        alert('Mancanza di crediti Operatore!')
                        btn_clicked.prop("disabled", false)

                    } else {
                        console.log(response.operator_credits)
                        $("#operator_credits").val(response.operator_credits)
                        btn_clicked.prop("disabled", false)
                    }
                }
            },
        });
    });

    $('#btn_account_in').on('click', function(e) {
        var btn_clicked = $(this)
        var account_in = parseInt($('#accounts_inout').val())
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.operator_account_in = account_in
        btn_clicked.prop("disabled", true)
        $.ajax({
            type: 'POST',
            url: url,
            data: myData,
            statusCode: {
                200: function(response) {
                    if (response.status == 'lack_operator_ability') {
                        alert('Lack of Operator ability!')
                        btn_clicked.prop("disabled", false)

                    } else {
                        console.log(response.operator_account_limits)
                        $("#operator_account_limits").val(response.operator_account_limits);
                        btn_clicked.prop("disabled", false)
                    }
                }
            },
        });
    });
    $('#btn_account_out').on('click', function(e) {
        //check if the reset_credit and  reset_startCredits checkbox is true
        var btn_clicked = $(this)
        var account_out = parseInt($('#accounts_inout').val())
        var operator_account_limits = parseInt($('#operator_account_limits').val())
        if (account_out > operator_account_limits) {
            alert('Please lower the Account Out amount.')
            return
        }
        myData = {}
        myData.user_id = parseInt($('#user_id').val())
        myData.operator_account_out = account_out
        btn_clicked.prop("disabled", true)
        $.ajax({
            type: 'POST',
            url: url,
            data: myData,
            statusCode: {
                200: function(response) {
                    if (response.status == 'lack_operator_ability') {
                        alert('Lack of Operator ability!')
                        btn_clicked.prop("disabled", false)

                    } else {
                        console.log(response.operator_account_limits)
                        $("#operator_account_limits").val(response.operator_account_limits)
                        btn_clicked.prop("disabled", false)
                    }
                }
            },
        });
    });

    $('#btn_submit_del_user').on('click', function(e) {
        e.preventDefault()
        $('#del_confirm_Modal').modal('show')
    })
    $('#del_sure').on('click', function(e) {
        $('form#user-remove-form').submit();
        $('#del_confirm_Modal').modal('hide')

    })
    var operatorData = {};

    var operator_table = $('#operator-datatable').DataTable({
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
        ajax: {
            url: "{{ route('backend.operator.listpost') }}?id={{$operator_info->user_id}}",
            type: "POST",
            data: function(d) {
                return $.extend(d, operatorData);
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
                data: null,
                defaultContent: '',
                width: 50,
                target: 0
            },
            {
                data: 'username',
                name: 'username',
                className: "text-left text-bold",
                width: 120,
                bSortable: true,
                targets: 1
            },
            {
                data: 'email',
                name: 'email',
                className: "text-left",
                bSortable: true,
                width: 120,
                targets: 2
            },
            {
                data: 'balance',
                name: 'balance',
                bSortable: false,
                searchable: false,
                width: 100,
                target: 3
            },
            {
                data: 'last_online',
                name: 'last_online',
                bSortable: false,
                searchable: false,
                width: 120,
                target: 4
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                className: "text-ip text-center",
                bSortable: false,
                searchable: false,
                width: 120,
                target: 5
            },

        ],
        columnDefs: [{
            targets: 0,
            checkboxes: {
                selectRow: true
            }
        }, {
            data: "enabled",
            targets: 6,
            render: function(data, type, row) {
                return '<i class="fa fa-check text-' + data + '"></i>'
            }
        }, {
            data: "panic",
            targets: 7,
            render: function(data, type, row) {
                return '<i class="fa fa-times text-' + data + '"></i>'
            }
        }, {
            data: "id",
            targets: 8,
            visible: false,
        }, {
            data: "username",
            targets: 1,
            render: function(data, type, row) {
                return '<a class="text-danger text-center" href="/backend/operator/permissions/' + row.id + '">' + row.username + "</a>"
            }
        }],

        select: {
            style: 'multi'
        },
    });

    $('#btn_apply-operator').on('click', function(e) {
        obj_rows = operator_table.rows('.selected').data();
        var arr = [];
        $.each(obj_rows, function(key, value) {
            arr.push(value);
            return;
        });
        if (arr.length == 0) {
            alert('Please select row(s).');
        } else {
            console.log(arr);
            operatorData = {};
            operatorData.sel_rows = arr;
            operatorData.sel_action = $('#sel_bulk_action-operator').val();
            operator_table.ajax.reload(null, false);
        }

    });

    var shopData = {};

    var shop_table = $('#shop-datatable').DataTable({
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
        ajax: {
            url: "{{ route('backend.shop.listpost') }}?id={{$operator_info->user_id}}",
            type: "POST",
            data: function(d) {
                return $.extend(d, shopData);
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
                data: null,
                defaultContent: '',
                width: 50,
                target: 0
            },
            {
                data: 'username',
                name: 'username',
                className: "text-left text-bold",
                width: 120,
                bSortable: true,
                targets: 1
            },
            {
                data: 'email',
                name: 'email',
                className: "text-left",
                bSortable: true,
                width: 120,
                targets: 2
            },
            {
                data: 'balance',
                name: 'balance',
                bSortable: false,
                searchable: false,
                width: 100,
                target: 3
            },
            {
                data: 'last_online',
                name: 'last_online',
                bSortable: false,
                searchable: false,
                width: 120,
                target: 4
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                className: "text-ip text-center",
                bSortable: false,
                searchable: false,
                width: 120,
                target: 5
            },
        ],
        columnDefs: [{
            targets: 0,
            checkboxes: {
                selectRow: true
            }
        }, {
            data: "enabled",
            targets: 6,
            render: function(data, type, row) {
                return '<i class="fa fa-check text-' + data + '"></i>'
            }
        }, {
            data: "panic",
            targets: 7,
            render: function(data, type, row) {
                return '<i class="fa fa-times text-' + data + '"></i>'
            }
        }, {
            data: "id",
            targets: 8,
            visible: false,
        }, {
            data: "username",
            targets: 1,
            render: function(data, type, row) {
                return '<a class="text-success text-center" href="/backend/shop/profile/' + row.id + '">' + row.username + "</a>"
            }
        }],

        select: {
            style: 'multi'
        },
    });

    $('#btn_apply-shop').on('click', function(e) {
        obj_rows = shop_table.rows('.selected').data();
        var arr = [];
        $.each(obj_rows, function(key, value) {
            arr.push(value);
            return;
        });
        if (arr.length == 0) {
            alert('Please select row(s).')
        } else {
            console.log(arr);
            shopData = {};
            shopData.sel_rows = arr;
            shopData.sel_action = $('#sel_bulk_action-shop').val();
            shop_table.ajax.reload(null, false);
        }
    });

    var userData = {};

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
        ajax: {
            url: "{{ route('backend.user.listpost') }}?id={{$operator_info->user_id}}",
            type: "POST",
            data: function(d) {
                return $.extend(d, userData);
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
                data: null,
                defaultContent: '',
                width: 50,
                target: 0
            },
            {
                data: 'username',
                name: 'username',
                className: "text-left text-bold",
                width: 120,
                bSortable: true,
                targets: 1
            },
            {
                data: 'email',
                name: 'email',
                className: "text-left",
                bSortable: true,
                width: 120,
                targets: 2
            },
            {
                data: 'balance',
                name: 'balance',
                bSortable: false,
                searchable: false,
                width: 100,
                target: 3
            },
            {
                data: 'last_online',
                name: 'last_online',
                bSortable: false,
                searchable: false,
                width: 120,
                target: 4
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                className: "text-ip text-center",
                bSortable: false,
                searchable: false,
                width: 120,
                target: 5
            },
        ],
        columnDefs: [{
            targets: 0,
            checkboxes: {
                selectRow: true
            }
        }, {
            data: "enabled",
            targets: 6,
            render: function(data, type, row) {
                return '<i class="fa fa-check text-' + data + '"></i>'
            }
        }, {
            data: "panic",
            targets: 7,
            render: function(data, type, row) {
                return '<i class="fa fa-times text-' + data + '"></i>'
            }
        }, {
            data: "id",
            targets: 8,
            visible: false,
        }, {
            data: "username",
            targets: 1,
            render: function(data, type, row) {
                return '<a class="text-info text-center" href="/backend/user/profile/' + row.id + '">' + row.username + "</a>"
            }
        }],

        select: {
            style: 'multi'
        },
    });

    $('#btn_apply-user').on('click', function(e) {
        obj_rows = user_table.rows('.selected').data();
        var arr = [];
        $.each(obj_rows, function(key, value) {
            arr.push(value);
            e.preventDefault;
            return;
        });
        if (arr.length == 0) {
            alert('Please select row(s).')
        } else {
            console.log(arr);
            userData.sel_rows = arr;
            userData.sel_action = $('#sel_bulk_action-user').val();
            user_table.ajax.reload(null, false);
        }
    });
</script>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
@stop