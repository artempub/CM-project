@extends('backend.layouts.cashier')

@section('page-title', trans('app.terminal'))
@section('page-heading', trans('app.terminal'))

@section('content')
<style>
    section.content-header,
    section.content {
        margin-left: 272px;
    }
</style>
<link rel="stylesheet" href="/cashier/cashieredit.css">
<section class="sidebar">
    <div class="search-box" style="position:relative;">
        <i class="fa fa-search" style="position:absolute; padding:5%;font-size:15px; opacity:0.5;"></i>
        <input type="text" class="form-control" name="nav-search" id="nav-search" placeholder="Search...">
    </div>
    <div class="side-list-box" style="position:relative;">
        <div class="side-list-header">Terminals</div>
        <div class="side-list-body">
            @if (count($response['terminals'])>0)
            @foreach ($response['terminals'] as $key=>$item)
            <a href="{{url('backend/terminal/details/'.encoded($item->id))}}">
                <div class="side-list-cell @if($item->id==$response['terminal']->id) active @endif">
                    <div class="side-cell-data">{{$key+1}} {{$item->username}}</div>
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
				<span class="name" >{{$response['terminal']->username}}</span>
				<span class="login"> </span>
			</div>
			<div id="itemBalance" class="ui-bar ui-bar-b">
				<span style="font-size: 16px;" >Current balance</span>: <span class="value" style="font-size: 19.2px;"  >{{ number_format(floatval($response['terminal']->balance), 2, '.', '') }} EUR</span>
			</div>
            <input value="{{ $response['terminal']->balance }}" hidden id="userbalance" type="text">
			<br />
    <div id="inOutButtons" class="ui-grid-a ui-responsive">
		<div class="ui-block-a">
            <div class="ui-bar ui-bar-b">
                <a id="btnCashOut" href="#" data-theme="a" data-iconpos="left" data-icon="arrow-l" data-mini="true" l10n="button.CashOut"
                         class="ui-link ui-btn ui-btn-a ui-icon-arrow-l ui-btn-icon-left ui-shadow ui-corner-all ui-mini newPayment outPayment" role="button"  >
            <div class="image_arrow_left">
                <img src="{{url('/arrow.svg')}}"  alt="arrow Icon"/>
                        </div> 
                            Cash Out</a>
            </div>
        </div>
                
        <div class="ui-block-b">
            <div class="ui-bar ui-bar-b">
                <a id="btnCashIn" href="#" data-theme="a" data-iconpos="right" data-icon="arrow-r" data-mini="true"  l10n="button.CashIn" 
                        class="ui-link ui-btn ui-btn-a ui-icon-arrow-r ui-btn-icon-right ui-shadow ui-corner-all ui-mini newPayment addPayment" role="button"   >
                            <div class="image_arrow_right">
                            <img  src="{{url('/arrow.svg')}}" alt="arrow Icon"/>
                            </div>Cash In</a>
            </div>
        </div>
    </div>
     <div id="cashIn" class="ui-content ui-grid-a" style="display: none;">
            <div class="ui-block-a">
                <form  id="inForm"  action="/backend/terminal/balance/add" method="POST" autocomplete="off">
                    @csrf
                     <input type="hidden" name="user_id" value="{{$response['terminal']->id}}">
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="type" value="add"> --}}
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
                        @csrf
                         <input type="hidden" name="user_id" value="{{$response['terminal']->id}}">
                        {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="type" value="out"> --}}
                    {{-- <input type="hidden" name="outall"> --}}
						<label for="txtinp-cashout"><span l10n="input.label.CashOut" style="font-weight:400; font-size:16px;">Cash Out</span>:</label>
						<div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset ui-input-has-clear">
                            <input id="txtinp-cashout" name="amount" readonly type="text" value="{{ number_format(floatval($response['terminal']->balance), 2, '.', '') }}" data-clear-btn="true" class="txtlip-cashin-i">
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


</section>










































































@include('backend.terminal.modals.terminal_add')

</section>

    {{-- <div class="mt-2">
        <div class="terminalsummary">
            <table class="table vm">
                <tr>
                    <td>
                        <div>
                            <p class=""></p> <!-- comment this line -->
                            <!--<p class="usrimg"><img src="/back/img/novostarSL2.png" alt=""></p>-->
                            <p class="usrimg"><img src="/back/img/10.png" alt=""></p>
                            <p class="usrname">{{$response['terminal']->username}}</p>
                        </div>
                    </td>
                    <td>
                        <div>
                            <p>Balance</p>
                            <p>{{ number_format(floatval($response['terminal']->balance), 2, '.', '') }}</p>
                        </div>
                    </td>
                    <td>
                        <div>
                            <p>Total In</p>
                            <p>{{ number_format(floatval($response['terminal']->total_in), 2, '.', '') }}</p>
                        </div>
                    </td>
                    <td>
                        <div>
                            <p>Total Out</p>
                            <p>{{ number_format(floatval($response['terminal']->total_out), 2, '.', '') }}</p>
                        </div>
                    </td>
                    <td>
                        <div>
                            <p>Total</p>
                            <p>{{ number_format(floatval($response['terminal']->count_balance), 2, '.', '') }}</p>
                        </div>
                    </td>
                    @if (Auth::user()->hasRole('admin'))
                    <td>
                        <p>
                            <a type="button" class="btn btn-success text-uppercase fw-bold text-white" data-toggle="modal" data-target="#addCredit">
                                <i class="fa fa-plus-square"></i> Add
                            </a>
                        </p>
                        <p>
                            <a type="button" class="btn btn-danger text-uppercase fw-bold text-white" data-toggle="modal" data-target="#outCredit">
                                <i class="fa fa-minus-square"></i> Out
                            </a>
                        </p>
                    </td>
                    @endif
                </tr>
            </table>
        </div>
    </div>

    <div class="mt-1">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a id="details-tab" class="fw-bold" data-toggle="tab" href="#details" aria-expanded="false">
                        Edit @lang('app.terminal') </a>
                </li>
                <li>
                    <a id="authentication-tab" class="fw-bold" data-toggle="tab" href="#login-details" aria-expanded="true">
                        Activity </a>
                </li>
                <li>
                    <a id="authentication-tab" class="fw-bold" data-toggle="tab" href="#ticketDetails" aria-expanded="true">
                        Tickets </a>
                </li>
            </ul>

            <div class="tab-content" id="nav-tabContent">
                <!-- Edit user -->
                <div class="tab-pane active terminaldetails " id="details">
                    <form action="" method="POST">
                        @csrf
                        <table class="table vm">
                            <tr>
                                <td>Shops</td>
                                <td class="w300"><input type="text" name="name" disabled class="form-control" value="{{$response['shop']->name}}"></td>

                                <td class="text-right">Username</td>
                                <td class=""><input type="text" name="username" class="form-control w250" value="{{$response['terminal']->username}}"></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <select name="status" class="form-control w250">
                                        @foreach ($response['statuses'] as $status)
                                        <option value="{{$status}}" <?=($response['terminal']->status==$status)?'selected':''?>>{{$status}}
                                        </option>
                                        @endforeach
                                    </select>
                                <td class="text-right">Language</td>
                                <td>
                                    <select name="language" class="form-control w250">
                                        @foreach ($response['langs'] as $language=>$value)
                                        <option value="{{$language}}" <?=($response['terminal']->language==$language)?'selected':''?>>
                                            {{$language}}
                                        </option>
                                        @endforeach
                                    </select>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td colspan="3"><input type="text" name="password" class="form-control w200" value="{{$response['terminal']->password}}"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="3">
                                    <button type="submit" class="btn btn-primary" id="update-details-btn">
                                        Update @lang('app.terminal') </button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <!-- Activity -->
                <div class="tab-pane" id="login-details">
                    @if (count($response['userActivity'])>0)
                    <table class="table text-center table-bordered vm">
                        <thead>
                            <td>Date</td>
                            <td>IP Address</td>
                            <td>Country</td>
                            <td>City</td>
                            <td>Device</td>
                            <td>OS</td>
                            <td>Browser</td>
                            <td class="text-left">User Agent</td>
                        </thead>
                        <tbody>
                            @foreach ($response['userActivity'] as $item)
                            <tr>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->ip_address}}</td>
                                <td>{{$item->country}}</td>
                                <td>{{$item->city}}</td>
                                <td>{{$item->device}}</td>
                                <td>{{$item->os}}</td>
                                <td>{{$item->browser}}</td>
                                <td>{{$item->user_agent}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="noData">No activity from this user yet.</p>
                    @endif
                </div>

                <!-- Tickets -->
                <div class="tab-pane ticketDetails" id="ticketDetails">
                    @if (count($response['payTickets'])>0)
                    <table class="table text-center table-bordered vm">
                        <thead>
                            <td class="text-left">PIN</td>
                            <td class="w150">Amount</td>
                            <td class="w150">Status</td>
                            <td class="w150">Updated On</td>
                            <td class="w150">Created On</td>
                        </thead>
                        <tbody>
                            @foreach ($response['payTickets'] as $item)
                            <tr>
                                <td class="fw-bold text-left fs-20">{{$item->ticket_pin}}</td>
                                <td class="fs-20 fw-bold">
                                    {{ number_format(floatval($item->ticket_amount), 2, '.', '') }}</td>
                                <td>
                                    <span class="<?=($item->ticket_status==1)?'success':'pending'?>">
                                        <?=($item->ticket_status==1)?'Success':'Pending'?>
                                    </span>
                                </td>
                                <td>{{$item->updated_at}}</td>
                                <td>{{$item->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="noData">No tickets from this user yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('backend.terminal.modals.add_credit')
    @include('backend.terminal.modals.out_credit')
    @include('backend.terminal.modals.terminal_add')
</section>
@stop

@section('scripts')
<script>
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
triggerTabList.forEach(function (triggerEl) {
  var tabTrigger = new bootstrap.Tab(triggerEl)

  triggerEl.addEventListener('click', function (event) {
    event.preventDefault()
    tabTrigger.show()
  })
})
</script> --}}
@stop