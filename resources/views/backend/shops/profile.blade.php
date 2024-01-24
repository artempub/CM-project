@extends('backend.layouts.app')
@section('content')
    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        <div class="box box-default panel-body">
            <div class="box-header with-border">
                <div class="panel-heading"><b>Shop "<b class="text-danger"> {{ $shop_info->name }}</b>" Details</b>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="panel panel-default">

                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#op_profile" class="nav-link" aria-controls="op_profile" role="tab"
                                        data-toggle="tab">Profile
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#op_atm" class="nav-link" aria-controls="op_atm" role="tab"
                                        data-toggle="tab">ATM
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#op_jpg" class="nav-link" aria-controls="op_jpg" role="tab"
                                        data-toggle="tab">Jackpots
                                    </a>
                                </li>
                                @if (isset($flag_1operator) && $flag_1operator == true)
                                    {{-- <li role="presentation"  class="">
                                <a href="#category_allowcation" class="nav-link" aria-controls="category_allowcation" role="tab" data-toggle="tab">Categories
                                </a>
                            </li> --}}
                                    <li role="presentation" class="">
                                        <a href="#provider_allowcation" class="nav-link"
                                            aria-controls="provider_allowcation" role="tab" data-toggle="tab">Categories
                                        </a>
                                    </li>
                                @endif
                            </ul>

                            <div class="tab-content panel-body">
                                <div class="tab-pane active" id="op_profile">
                                    <br>
                                    <div class="form-horizontal">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <label class="col-md-2 control-label">
                                                Last reset
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip" data-original-title="Last reset"></span>
                                            </label>
                                            <div class="col-md-2">
                                                <div class="block panel  bg-primary item">
                                                    <span class="text-white font-thin h4 block" id="in">In
                                                        {{ $shop_total_in }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="block panel  bg-danger item">
                                                    <span class="text-white font-thin h4 block" id="out"> Out
                                                        {{ $shop_total_out }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="block panel  bg-success item">
                                                    <span class="text-white font-thin h4 block" id="sum">Sum
                                                        {{ $shop_total_sum }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="block panel  bg-info item">
                                                    <span class="text-white font-thin h4 block" id="scoresum">Users Credits
                                                        {{ $shop_total_user_credits }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="form-horizontal">
                                                                        <div class="form-group" style="margin-bottom: 0px;">

                                                                            <label class="col-md-2 control-label">
                                                                                Cash This Month
                                                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Cash This Month"></span>
                                                                            </label>
                                                                            <div class="col-md-2">
                                                                                <div class="block panel  bg-primary item">
                                                                                    <span class="text-white font-thin h4 block">In 0.00</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <div class="block panel  bg-danger item">
                                                                                    <span class="text-white font-thin h4 block">Out 0.00</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <div class="block panel  bg-success item">
                                                                                    <span class="text-white font-thin h4 block">Sum 0.00</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="block panel  bg-info item">
                                                                                    <span class="text-white font-thin h4 block" id="scoresum">Payout 0%
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->

                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                <a href="" class="text-primary">
                                                    Reset Shop?
                                                </a>
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="When pressing reset button, if you enable Reset Cashier option the IN - OUT - SUM fields on the cashier page will also be reset">
                                                </span>
                                            </label>
                                            <div class="col-md-1">
                                                <div class="col-md-1" id="">
                                                    <label class="i-checks">
                                                        <input type="checkbox" checked name="reset_shop_credits"
                                                            id="reset_shop_credits" value="1" checked><i></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <label class="col-md-2 control-label text-ce">
                                                Reset Start Credit?
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="When pressing reset button, if you enable Reset Start Credit option, the Start Credit will be zero"></span>
                                            </label>
                                            <div class="col-md-1">
                                                <div class="col-md-1" id="">
                                                    <label class="i-checks">
                                                        <input type="checkbox" name="reset_shop_startCredits"
                                                            id="reset_shop_startCredits" value="1"><i></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="col-lg-offset-2 col-lg-10 text-right">
                                                    <button class="btn btn-danger " id="btn_reset_shop">
                                                        Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Start Credit
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="Initial credit.each time you add or remove credits, this field is changed accordingly. This field can be reset if you check  the reset start credit checkbox!">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <input type="text" disabled=""
                                                    value="{{ $shop_info->start_credits }}"
                                                    class="text-center form-control" id="shop_startCredits">
                                            </div>
                                            <!-- <div class="col-md-3">
                                                                                <input type="text" disabled="" value="Payout:70.3%" class="text-center form-control">
                                                                            </div> -->
                                        </div>
                                    </div>

                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Credits
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip" data-original-title="Credits">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <input type="text" disabled="" value="{{ $shop_balance }}"
                                                    class="text-center form-control" id="shop_credits">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-danger" type="button" id="btn_credit_out"
                                                            style="width: 70px;">
                                                            <span class="glyphicon glyphicon-minus"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </span>
                                                    <input type="text" id="credits_inout" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" type="button" id="btn_credit_in"
                                                            style="width: 70px;">
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
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="If you want to allow this shop to create players you have to share accounts with this shop. For example if you want this shop to create up to 5 users you should share with him 5 accounts.">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <label for="account_limits" class="none"></label>
                                                <input type="text" disabled id="shop_account_limits"
                                                    value="{{ $shop_info->account_limit }}"
                                                    class="text-center form-control" style="margin-top:-20px;">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-danger" type="button"
                                                            id="btn_account_out" style="width: 70px;">
                                                            <span class="glyphicon glyphicon-minus"
                                                                aria-hidden="true"></span>
                                                        </button>
                                                    </span>
                                                    <label for="accounts" class="none"></label>
                                                    <input type="text" id="accounts_inout" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" type="button"
                                                            id="btn_account_in" style="width: 70px;">
                                                            <span class="glyphicon glyphicon-plus"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-horizontal">
                                        {!! Form::open(['route' => 'backend.user.change_pw', 'files' => true, 'id' => 'change-pw-form']) !!}
                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ $shop_info->user_id }}">
                                        <input type="hidden" name="username" id="username"
                                            value="{{ $shop_info->creator->username }}">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Password
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip" data-original-title="Password">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="password" name="password" id="password"
                                                        class="form-control" placeholder="password">
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

                                    <!-- <input type="hidden" name="user_id" id="user_id" value="{{ $shop_info->user_id }}"> -->

                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                TimeZone
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="Timezone for cashier logs and cash / shifts reports">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="form-control timezone" name="timezone" id="shop_timezone"
                                                    data-live-search="true">
                                                    @foreach ($timezones as $timezone)
                                                        <option value="{{ $timezone->name }}"
                                                            {{ $timezone->name == $shop_info->timezone ? 'selected' : '' }}>
                                                            {{ $timezone->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Currency
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="Timezone for cashier logs and cash / shifts reports">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="form-control timezone" name="currency" id="shop_currency"
                                                    data-live-search="true">
                                                    @foreach ($currencies as $currency)
                                                        <option value="{{ $currency }}"
                                                            {{ $currency == $shop_info->currency ? 'selected' : '' }}>
                                                            {{ $currency }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Bonus
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="Timezone for cashier logs and cash / shifts reports">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="form-control timezone" name="bonus" id="shop_bonus"
                                                    data-live-search="true">
                                                    <option value="0" {{ $shop_info->bonus == 0 ? 'selected' : '' }}>
                                                        0%
                                                    </option>
                                                    <option value="10"
                                                        {{ $shop_info->bonus == 10 ? 'selected' : '' }}>
                                                        10%
                                                    </option>
                                                    <option value="15"
                                                        {{ $shop_info->bonus == 15 ? 'selected' : '' }}>
                                                        15%
                                                    </option>
                                                    <option value="20"
                                                        {{ $shop_info->bonus == 20 ? 'selected' : '' }}>
                                                        20%
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Bank
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip"
                                                    data-original-title="Timezone for cashier logs and cash / shifts reports">
                                                </span>
                                            </label>
                                            <div class="col-md-3">
                                                <select class="form-control timezone" name="bank" id="shop_bank"
                                                    data-live-search="true">
                                                    <option value="85" {{ $shop_info->bank == 85 ? 'selected' : '' }}>
                                                        55%
                                                        - 65%</option>
                                                    <option value="87" {{ $shop_info->bank == 87 ? 'selected' : '' }}>
                                                        65%
                                                        - 70%</option>
                                                    <option value="90" {{ $shop_info->bank == 90 ? 'selected' : '' }}>
                                                        70%
                                                        - 75%</option>
                                                    <option value="93" {{ $shop_info->bank == 93 ? 'selected' : '' }}>
                                                        75%
                                                        - 85%</option>
                                                    <option value="95" {{ $shop_info->bank == 95 ? 'selected' : '' }}>
                                                        85%
                                                        - 90%</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">
                                                Family
                                                <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip"
                                                    data-toggle="tooltip" data-original-title=" Family">
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
                                                <button type="button" id="btn_edit_shop_profile"
                                                    class="btn btn-sm btn-primary"
                                                    style="margin-right: 16.667px;">Save</button>
                                            </div>

                                        </div>
                                        <!-- {!! Form::close() !!} -->
                                        <br>
                                        {!! Form::open(['route' => 'backend.user.remove', 'files' => true, 'id' => 'user-remove-form']) !!}

                                        <div class="line line-lg b-b b-light"></div>
                                        <div class="col-md-12 text-right" style="padding-top: 20px;">
                                            <input type="hidden" name="remove_user_id"
                                                value="{{ $shop_info->user_id }}">
                                            <input type="hidden" name="remove_shop_id" value="{{ $shop_info->id }}">
                                            <input type="hidden" name="remove_user_role" value="shop">
                                            <button type="button" id="btn_submit_del_user" class="btn-atag">Delete this
                                                Shop</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                <div class="tab-pane" id="op_atm">
                                    <div class="">
                                        Here will be the ATM page to be inquired in the meantime...
                                        <br><br><br>
                                    </div>
                                </div>
                                <div class="tab-pane table-responsive" id="op_jpg">
                                    <div class="row">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-6">
                                            @if ($jpg_permission_operator)
                                                <input type="button" class="btn btn-info" id="btn_showModal"
                                                    style="float: right; margin:6px;" value="Edit Jackpot">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-sm table-hover dataTable no-footer"
                                                style="width:100%" id="jpg-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Start Balance</th>
                                                        <th class="text-center">Trigger</th>
                                                        <th class="text-center">Percent</th>
                                                        <th class="big-checkbox dt-body-center dt-checkboxes-select-all">
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
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
                                    <div class="tab-pane" id="provider_allowcation">
                                        <div class="panel-body">
                                            <div class="">
                                                <div class="panel-group" id="accordion">
                                                    {{-- <div class="faqHeader">Provider allocation per Category</div><br> --}}
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
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer"></div>
            <!-- Modal for jackpot -->

            <div class="modal fade" id="edit_jpg_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">
                                Edit <span id="jackpot_name" style="color: lightskyblue;font-weight:bold;">Jackpot</span>
                            </h4>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="start_balance">Start Balance</label>
                                    <div class="col-sm-10">
                                        <input type="text"class="form-control" id="start_balance"
                                            name="start_balance" value="20">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="jpg_trigger">Trigger</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="jpg_trigger" name="jpg_trigger"
                                            value="250" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="percent">Percent</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="percent" id="jpg_percent">
                                            <!-- <option value="" selected="selected">---</option> -->
                                            <option value="1.00" selected>1.00</option>
                                            <option value="0.90">0.95</option>
                                            <option value="0.90">0.90</option>
                                            <option value="0.80">0.85</option>
                                            <option value="0.80">0.80</option>
                                            <option value="0.70">0.75</option>
                                            <option value="0.70">0.70</option>
                                            <option value="0.60">0.65</option>
                                            <option value="0.60">0.60</option>
                                            <option value="0.50">0.55</option>
                                            <option value="0.50">0.50</option>
                                            <option value="0.40">0.45</option>
                                            <option value="0.40">0.40</option>
                                            <option value="0.30">0.35</option>
                                            <option value="0.30">0.30</option>
                                            <option value="0.20">0.25</option>
                                            <option value="0.20">0.20</option>
                                            <option value="0.10">0.15</option>
                                            <option value="0.10">0.10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">
                            <input type="button" class="btn btn-primary" id="btn_editJpg" value="Save Jackpot">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for del confrimation -->
            <div class="modal fade" id="del_confirm_Modal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </section>
@stop

@section('scripts')
    <script>
        var myData = {}
        var url = "{{ route('backend.shop.edit_profile') }}"
        var url_jpg = "{{ route('backend.shop.jpglistpost') }}"
        var url_gamecategory = "{{ route('backend.shop.categorylistpost') }}"
        var url_gameprovider = "{{ route('backend.shop.providerlistpost') }}"
        myData.user_id = parseInt($('#user_id').val())
        myData.sel_location = 'from_shop_profile'
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
        var jpg_table = $('#jpg-datatable').DataTable({
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            stateSave: true,
            paging: false,
            ordering: false,
            info: true,
            searching: false,

            ajax: {
                url: url_jpg,
                type: "POST",
                data: function(d) {
                    return $.extend(d, myData);
                },
                statusCode: {
                    200: function(response) {
                        console.log(response);
                        $('#edit_jpg_modal').modal('hide')
                    }
                }
            },
            columns: [
                // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {
                    data: 'name',
                    name: 'name',
                    className: "text-center",
                    bSortable: true,
                    targets: 0
                },
                {
                    data: 'start_balance',
                    name: 'start_balance',
                    className: "text-center",
                    bSortable: true,
                    targets: 1
                },
                {
                    // data: 'jpg_trigger',
                    data: 'pay_sum',
                    name: 'jpg_trigger',
                    className: "text-center",
                    bSortable: false,
                    searchable: false,
                    target: 2
                },
                {
                    data: 'percent',
                    name: 'percent',
                    className: "text-center",
                    bSortable: false,
                    searchable: false,
                    target: 3
                },
                {
                    data: null,
                    defaultContent: '',
                    target: 4
                },
            ],
            columnDefs: [{
                targets: 4,
                checkboxes: {
                    selectRow: true
                }
            }, {
                data: "id",
                targets: 5,
                visible: false,
            }],

            select: {
                style: 'single'
            },
        });
        // var gamecategories_table = $('#category_allowcation-datatable').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     pagingType: "full_numbers",
        //     stateSave: true,
        //     paging: false,
        //     ordering: false,
        //     info: true,
        //     searching: false,

        //     ajax: {
        //         url: url_gamecategory,
        //         type: "POST",
        //         data: function(d) {
        //             return $.extend(d, myData);
        //         },
        //         statusCode: {
        //             200: function(response) {
        //                 console.log(response);
        //             }
        //         }
        //     },
        //     columns: [
        //         // {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        //         {
        //             data: 'title',
        //             name: 'title',
        //             className: "text-center",
        //             bSortable: true,
        //             targets: 0
        //         },
        //         {
        //             data: 'providers',
        //             name: 'providers',
        //             className: "text-center",
        //             bSortable: true,
        //             targets: 1
        //         },
        //         {
        //             data: 'per_shop_count',
        //             name: 'per_shop_count',
        //             className: "text-center",
        //             defaultContent: '',
        //             target: 2
        //         },
        //     ],
        //     columnDefs: [
        //         {
        //             data: "per_shop_count",
        //             targets: 2,
        //             className: "text-center",
        //             bSortable: false,
        //             render: function(data, type, row) {
        //                 var status = {
        //                     1: {
        //                         'xxx': '<label class="switch"><input type="checkbox" onclick="categoryOnOff(' + row.id + ',0)"  checked><span class="slider round"></span></label>'
        //                     },
        //                     0: {
        //                         'xxx': '<label class="switch"><input type="checkbox" onclick="categoryOnOff(' + row.id + ',1)" ><span class="slider round"></span></label>'
        //                     },
        //                 };
        //                 return status[data].xxx;
        //             },
        //         },
        //     ],
        // });
        function categoryOnOff(gamecategory_id, state) {
            myData = {}
            myData.user_id = parseInt($('#user_id').val())
            myData.sel_action = 'edit_gamecategory_list'
            myData.sel_category = gamecategory_id
            myData.sel_status = state
            myData.sel_location = 'from_shop_profile'
            console.log(myData.user_id)

            gamecategories_table.ajax.reload(null, false)
        }
        $('#btn_showModal').on('click touchstart', function(e) {

            console.log('clicked');
            //check if there is clicked row(s)
            obj_rows = jpg_table.rows('.selected').data();
            var arr = [];
            $.each(obj_rows, function(key, value) {
                arr.push(value);
                return;
            });
            if (arr.length == 0 || arr.length > 1) {
                alert('Please select one Jackpot.')
                return
            } else {
                console.log(arr);
                myData = {}
                myData.sel_rows = arr;
                $('#jackpot_name').html(arr[0].name)
                $('#start_balance').val(arr[0].start_balance)
                // $('#jpg_trigger').val(arr[0].jpg_trigger)
                $('#jpg_trigger').val(arr[0].pay_sum)
                $('#jpg_percent').val(arr[0].percent)
                $('#edit_jpg_modal').modal('show');
            }
        })
        $('#start_balance').on('input', function(e) {
            console.log(parseInt($(this).val()))
            var startBalance = parseInt($(this).val()) * 10
            $('#jpg_trigger').val(startBalance)
        })
        $('#btn_editJpg').on('click touchstart', function(e) {

            if (parseInt($('#start_balance').val()) < 0 || isNaN(parseInt($('#start_balance').val()))) {
                alert('Inserisci un valore valido nel campo di immissione Saldo iniziale.')
                return
            }
            myData.start_balance = parseInt($('#start_balance').val())
            // myData.jpg_trigger = parseInt($('#jpg_trigger').val())
            myData.pay_sum = parseInt($('#jpg_trigger').val())
            myData.percent = parseFloat($('#jpg_percent').val())
            myData.user_id = parseInt($('#user_id').val())
            myData.sel_action = 'edit_jpg_list'
            console.log('post data:', myData)

            jpg_table.ajax.reload(null, false)

        })

        $('#btn_edit_shop_profile').on('click', function(e) {
            //check if the reset_credit and  reset_startCredits checkbox is true
            var btn_clicked = $(this)
            myData = {}
            myData.user_id = parseInt($('#user_id').val())
            myData.edit_shop_profile = true
            myData.timezone = $('#shop_timezone').val()
            console.log(myData.timezone)
            myData.currency = $('#shop_currency').val()
            myData.bonus = $('#shop_bonus').val()
            myData.bank = $('#shop_bank').val()
            btn_clicked.prop("disabled", true)
            $.ajax({
                type: 'POST',
                url: url,
                data: myData,
                statusCode: {
                    200: function(response) {
                        console.log(response)
                        $('#shop_timezone').val(response.timezone)
                        $('#shop_currency').val(response.currency)
                        $('#shop_bonus').val(response.bonus)
                        $('#shop_bank').val(response.bank)
                        btn_clicked.prop("disabled", false)
                    }
                },
            });
        });

        $('#btn_reset_shop').on('click', function(e) {
            //check if the reset_credit and  reset_startCredits checkbox is true
            var btn_clicked = $(this)
            myData = {}
            myData.user_id = parseInt($('#user_id').val())
            myData.reset_shop_credits = $('#reset_shop_credits').is(":checked")
            myData.reset_shop_startCredits = $('#reset_shop_startCredits').is(":checked")
            btn_clicked.prop("disabled", true)
            $.ajax({
                type: 'POST',
                url: url,
                data: myData,
                statusCode: {
                    200: function(response) {
                        if (response.shop_credits_reset) {
                            $("#shop_credits").val('0')
                        }
                        if (response.shop_startCredits_reset) {
                            $("#shop_startCredits").val('0')
                        }
                        btn_clicked.prop("disabled", false)
                    }
                },
            });
        });

        $('#btn_credit_in').on('click', function(e) {
            //check if the reset_credit and  reset_startCredits checkbox is true
            var btn_clicked = $(this)
            var credits_in = parseFloat($('#credits_inout').val())
            console.log(credits_in);
            console.log(typeof(credits_in));
            myData = {}
            myData.user_id = parseInt($('#user_id').val())

            myData.shop_credits_in = credits_in
            btn_clicked.prop("disabled", true)
            $.ajax({
                type: 'POST',
                url: url,
                data: myData,
                statusCode: {
                    200: function(response) {
                        if (response.status == 'lack_operator_credits') {
                            alert('Mancanza di crediti Operatore!')
                            btn_clicked.prop("disabled", false)
                        } else {
                            console.log(response.shop_credits)
                            $("#shop_credits").val(response.shop_credits)
                            btn_clicked.prop("disabled", false)
                        }
                    }
                },
            });
        });
        $('#btn_credit_out').on('click', function(e) {
            //check if the reset_credit and  reset_startCredits checkbox is true
            var btn_clicked = $(this)
            var credits_out = parseFloat($('#credits_inout').val())
            var shop_credits = parseFloat($('#shop_credits').val())
            if (credits_out > shop_credits) {
                alert("Si prega di ridurre l'importo Out.")
                return
            }
            myData = {}
            myData.user_id = parseInt($('#user_id').val())
            myData.shop_credits_out = credits_out
            btn_clicked.prop("disabled", true)
            $.ajax({
                type: 'POST',
                url: url,
                data: myData,
                statusCode: {
                    200: function(response) {
                        if (response.status == 'lack_user_credits') {
                            alert('Lack of User credit')
                            btn_clicked.prop("disabled", false)

                        } else {
                            console.log(response.shop_credits)
                            $("#shop_credits").val(response.shop_credits)
                            btn_clicked.prop("disabled", false)
                        }
                    }
                },
            });
        });

        $('#btn_account_in').on('click', function(e) {
            //check if the reset_credit and  reset_startCredits checkbox is true
            var btn_clicked = $(this)
            var account_in = parseInt($('#accounts_inout').val())
            var shop_account_limits = parseInt($('#shop_account_limits').val())
            myData = {}
            myData.user_id = parseInt($('#user_id').val())
            myData.shop_account_in = account_in
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
                            console.log(response.shop_account_limits)
                            $("#shop_account_limits").val(response.shop_account_limits);
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
            var shop_account_limits = parseInt($('#shop_account_limits').val())
            if (account_out > shop_account_limits) {
                alert('Please lower the Account Out amount.')
                return
            }
            myData = {}
            myData.user_id = parseInt($('#user_id').val())
            myData.shop_account_out = account_out
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
                            console.log(response.shop_account_limits)
                            $("#shop_account_limits").val(response.shop_account_limits)
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

        .nameinput {
            background-color: #88ace7;
            text-align: center;
            border: 0;
            font-size: 25px;
            size: 24px;
            width: 41%;
            margin: 7px;
            border-radius: 10px;
            opacity: 1;
        }
    </style>

@stop
