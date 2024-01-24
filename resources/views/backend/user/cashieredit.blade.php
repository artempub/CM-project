@extends('backend.layouts.cashier')

@section('page-title', trans('app.edit_user'))
@section('page-heading', $user->present()->username)

@section('content')


<link rel="stylesheet" href="/cashier/cashieredit.css">
<section class="sidebar">
    <div class="search-box" style="position:relative;">
        <i class="fa fa-search" style="position:absolute; padding:5%;font-size:15px; opacity:0.5;"></i>
        <input type="text" class="form-control" name="nav-search" id="nav-search" placeholder="Search...">
    </div>
    <div class="side-list-box" style="position:relative;">
        <div class="side-list-header">Users</div>
        <div class="side-list-body">
            @if (count($users))
            @foreach ($users as $cuser)
            <a href="{{ route('backend.user.edit', $cuser->id) }}">
                <div class="side-list-cell @if($cuser->id==$user->id) active @endif">
                    <div class="side-cell-data">{{$cuser->username}}</div>
                    <div class="side-cell-icon">
                        <span class="fa fa-chevron-right"></span>
                    </div>
                </div>
            </a>
            @endforeach
            @endif
        </div>
    </div>
</section>

<section class="content-header">
    @include('backend.cashier.messages')
</section>

<section class="content">
        <div id="itemData" role="main" class="ui-content" >
			<div id="itemTitle" class="ui-bar ui-bar-a">
				<span class="name" >{{ $user->present()->username }}</span>
				<span class="login"> </span>
			</div>
			<div id="itemBalance" class="ui-bar ui-bar-b">
				<span style="font-size: 16px;" >Current balance</span>: <span class="value" style="font-size: 19.2px;"  >{{ $user->present()->balance }}  {{$user->present()->currency}}</span>
			</div>
            <input value={{ $user->present()->balance }} hidden id="userbalance" type="text">
			<br />

			<div id="inOutButtons" class="ui-grid-a ui-responsive">
				<div class="ui-block-a">
                    <div class="ui-bar ui-bar-b">
                        <a id="btnCashOut" href="#" data-theme="a" data-iconpos="left" data-icon="arrow-l" data-mini="true" l10n="button.CashOut" class="ui-link ui-btn ui-btn-a ui-icon-arrow-l ui-btn-icon-left ui-shadow ui-corner-all ui-mini newPayment outPayment" role="button"  data-id="{{ $user->id }}" data-username="{{ $user->username }}">
                        <div class="image_arrow_left">
                            <img src="{{url('/arrow.svg')}}"  alt="arrow Icon"/>
                        </div> 
                            Cash Out</a>
                    </div>
                </div>
                
                <div class="ui-block-b">
                    <div class="ui-bar ui-bar-b">
                        <a id="btnCashIn" href="#" data-theme="a" data-iconpos="right" data-icon="arrow-r" data-mini="true"  l10n="button.CashIn" class="ui-link ui-btn ui-btn-a ui-icon-arrow-r ui-btn-icon-right ui-shadow ui-corner-all ui-mini newPayment addPayment" role="button"   data-id="{{ $user->id }}" data-username="{{ $user->username }}">
                            <div class="image_arrow_right">
                            <img  src="{{url('/arrow.svg')}}" alt="arrow Icon"/>
                            </div>Cash In</a>
                    </div>
                </div>
        </div>
        <div id="cashIn" class="ui-content ui-grid-a" style="display: none;">
            <div class="ui-block-a">
                <form  id="inForm"  action="/backend/terminal/balance/add" method="POST" autocomplete="off">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="type" value="add">
                    <label for="txtinp-cashin"><span l10n="input.label.CashIn" style="font-weight:400; font-size:16px;">Cash In</span>:</label>
                    <div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset ui-input-has-clear">
                        <input id="txtinp-cashin" name="amount" type="text"  required="required" data-clear-btn="true" class="txtlip-cashin-i" placeholder="0 EUR">
                    </div>
                    <div data-role="navbar" class="ui-navbar" role="navigation">
                        <ul class="ui-grid-duo ui-grid-a">
                            <li class="ui-block-a"><a id="btn0" class="text-big ui-link ui-btn" href="#" onclick="clear_cin();">C</a></li>
                            <li class="ui-block-b"><a id="btn1" class="text-big ui-link ui-btn" href="#" onclick="cin(1);"><small>+</small>1</a></li>
                            <li class="ui-block-a"><a id="btn5" class="text-big ui-link ui-btn" href="#" onclick="cin(5);"><small>+</small>5</a></li>
                            <li class="ui-block-b"><a id="btn10" class="text-big ui-link ui-btn" href="#" onclick="cin(10);"><small>+</small>10</a></li>
                            <li class="ui-block-a"><a id="btn20" class="text-big ui-link ui-btn" href="#" onclick="cin(20);"><small>+</small>20</a></li>
                            <li class="ui-block-b"><a id="btn50" class="text-big ui-link ui-btn" href="#" onclick="cin(50);"><small>+</small>50</a></li>
                            <li class="top-space ui-block-a"><button id="btnIn" l10n="dialog.button.CashIn" class="text-big ui-link ui-btn" href="#" type="submit" style="width:100%">Ok</button></li>
                            <li class="top-space ui-block-b"><a id="btnInCancel" href="#" l10n="dialog.button.Cancel" class="text-big ui-link ui-btn btnInCancel " >Cancel</a></li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
        <div id="cashOut" class="ui-content ui-grid-a" style="display: none;">
				<div class="ui-block-a">
					<form  id="inForm"  action="/backend/terminal/balance/out" method="POST" autocomplete="off">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="type" value="out">
                    {{-- <input type="hidden" name="outall"> --}}
						<label for="txtinp-cashout"><span l10n="input.label.CashOut" style="font-weight:400; font-size:16px;">Cash Out</span>:</label>
						<div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset ui-input-has-clear">
                            <input id="txtinp-cashout" name="amount" readonly type="text" value="{{ $user->present()->balance  }}" data-clear-btn="true" class="txtlip-cashin-i">
                        </div>
						<div data-role="navbar" class="ui-navbar" role="navigation">
							<ul class="ui-grid-a">
								<li class="ui-block-a"><button id="btnOutAll" type="submit" l10n="dialog.button.CashOutAll" class="text-big ui-link ui-btn" style="width:100%">Cash Out All</button></li>
								
								<li class="ui-block-b"><a id="btnOutCancel" href="#" l10n="dialog.button.Cancel" class="text-big ui-link ui-btn">Cancel</a></li>
							</ul>
						</div>
						
					</form>
				</div>
			</div>
        </div>
</section>


<section class="content" style="display:none">
    {!! Form::open(['route' => ['backend.user.update.details', $user->id], 'method' => 'PUT', 'id' => 'details-form']) !!}

    <div class="row">
        @include('backend.user.partials.info')
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li @if(!Request::get('date') && !Request::get('page')) class="active" @endif>
                        <a id="details-tab" data-toggle="tab" href="#details">
                            @lang('app.edit_user')
                        </a>
                    </li>
                    @permission('users.activity')
                    <li @if(Request::get('page')) class="active" @endif>
                        <a id="authentication-tab" data-toggle="tab" href="#login-details">
                            @lang('app.latest_activity')
                        </a>
                    </li>
                    @endpermission

                </ul>

                <div class="tab-content" id="nav-tabContent">
                    <div class="@if(!Request::get('date') && !Request::get('page')) active @endif tab-pane" id="details">
                        @include('backend.user.partials.edit')
                    </div>

                    @permission('users.activity')
                    <div class="tab-pane @if(Request::get('page')) active @endif" id="login-details">
                        @if (count($userActivities))
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('app.date')</th>
                                    <th>@lang('app.more_info')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format(config('app.date_time_format')) }}</td>
                                    <td>
                                        <b> @lang('app.country')</b>: {{ $activity->country }} <br>
                                        <b> @lang('app.city')</b>: {{ $activity->city }} <br>
                                        <b> @lang('app.os')</b>: {{ $activity->os }} <br>
                                        <b> @lang('app.device')</b>: {{ $activity->device }} <br>
                                        <b> @lang('app.browser')</b>: {{ $activity->browser }} <br>
                                        <b> @lang('app.ip')</b>: {{ $activity->ip_address }} <br>
                                        <b> @lang('app.user_agent')</b>: {{ $activity->user_agent }} <br>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {!! $userActivities->links() !!}
                        @else
                        <p class="text-muted font-weight-light"><em>@lang('app.no_activity_from_this_user_yet')</em></p>
                        @endif
                    </div>
                    @endpermission

                    <div class="tab-pane @if(Request::get('date')) active @endif" id="bonus-details">

                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('app.date')</label>
                                        <input type="text" class="form-control" name="date" value="{{ Request::get('date') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">
                                            @lang('app.filter')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>


                </div>

            </div>
        </div>
    </div>
    {!! Form::close() !!}


    @if(!$user->hasRole('admin'))
    @include('backend.user.partials.modals', ['user' => $user])
    @endif


</section> 
@include('backend.user.partials.modals', ['user' => $user])
@stop

@section('scripts')

<script>
    $('#btnCashIn').on('click', function(){
        document.getElementById( 'cashIn' ).style.display = 'block';
        document.getElementById( 'inOutButtons' ).style.display = 'none';
    })
    $('#btnCashOut').on('click', function(){ 
        if(Number(document.getElementById("userbalance").value) != 0){
            document.getElementById('cashOut').style.display='block';
            document.getElementById('inOutButtons').style.display='none';}
        }
    )
    $('#btnInCancel').on('click',function(){
        document.getElementById('cashIn').style.display="none";
        document.getElementById('inOutButtons').style.display="block";
    })
    $('#btnOutCancel').on('click',function(){
        document.getElementById('cashOut').style.display="none";
        document.getElementById('inOutButtons').style.display="block";
    })
    $('#cashIn .ui-navbar .text-big.ui-link.ui-btn').on('click', function() {
        $("#cashIn .text-big.ui-link.ui-btn").removeClass("ui-btn-active");
        $(this).addClass("ui-btn-active");
    })
    $("#cashOut .ui-btn").on('click', function() {
        $("#cashOut .text-big.ui-link.ui-btn").removeClass("ui-btn-active");
        $(this).addClass("ui-btn-active");
    })
    
   
    function cin(para){
        let oldValue=document.getElementById('txtinp-cashin').value;
        oldValue=(oldValue)?oldValue:0;
        let changeValue=Number(oldValue)+para;
        document.getElementById('txtinp-cashin').value=changeValue;
    }
    function clear_cin(){
        document.getElementById('txtinp-cashin').value=0;
    }
    function confirm_cash(){
        let confirmValue=document.getElementById('txtinp-cashin').value;
    }
    // $('.txtinp-cashin').on('click');
    
</script>
{!! HTML::script('/back/js/as/app.js') !!}
{!! HTML::script('/back/js/as/btn.js') !!}
{!! HTML::script('/back/js/as/profile.js') !!}
{!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\UpdateDetailsRequest', '#details-form') !!}
{!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\UpdateLoginDetailsRequest', '#login-details-form') !!}
@stop
