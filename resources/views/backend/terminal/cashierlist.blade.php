@extends('backend.layouts.cashier')

@section('page-title', trans('app.terminal'))
@section('page-heading', trans('app.terminal'))

@section('content')
<style>
    section.content-header,
    section.content {
        margin-left: 272px;
    }
    
    .box.box-primary {
        margin-bottom: 2px;
    }

    .grid-container {
        display: grid;
        width: calc(100% - 4px);
        height: 4em;
        grid-template-columns: 34% 33% 33%;
        gap: 2px;
        margin-top: 2px;
    }

    .grid-container>div {
        text-align: center;
        font-size: 20px;
        font-weight: normal;
        padding: 0px 2px;
        border-radius: 3px;
    }

    .grid-balance {
        display: grid;
        grid-template-columns: 34% 33% 33%;
        gap: 2px;
        margin-top: 28px;
        width: calc(100% - 4px);

    }

    .grid-balance>div {
        display: flex;
        background-color: #38c;
        text-align: center;
        align-items: center;
        font-size: 20px;
        justify-content: center;
        font-weight: normal;
        color: #fff;
        height: 32px;
    }

    .grid-balance-content {
        grid-template-columns: 34% 33% 33%;
        display: grid;
        gap: 2px;
        width: calc(100% - 4px);
        height: 37px;
        align-items: center;
        margin-bottom:15px;
        /* background-color: white; */
    }

    .grid-balance-content>div {
        display: flex;
        justify-content: center;
        text-align: center;
        font-size: 18px;
        font-weight:100px;
        padding:5px;
        color: #333;
        background-color: white;

    }


    .grid-date {
        display: flex;
        justify-content: space-between;
    }

    .balance-tap {
        background-color: #38c;
    }

    .ui-bar-b {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #ddd;
        border-color: #eee;
        color: #333;
        font-weight: normal;
    }

    .date-from.grid-date.input-group.date .data-display-form {
        background-color: inherit;
        outline: none;
        border: none;
        font-size: 18px;
        font-weight: normal;
        padding-left: 0.475em;
        display: flex;
        align-items: center;
        height: unset;
        box-shadow: none;
    }


    .grid-time {
        background-color: white;
        display: flex;
        justify-content: space-between;
    }

    .grid-time .time_pick {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .balance-val {
        font-size: 1.2em;
        background-color: #fff;
    }

    .grid-time .input-group-addon {
        background-color: white;
        border: 0;
        position: absolute;
        border: 0;
        width: fit-content;
        display: flex;
        align-items: center;
        top: 0;
        right: 0;
        z-index: 2;
        pointer-events: none;
    }

    .img-clock {
        content: "";
        /* position: absolute; */
        display: block;
        width: 22px;
        height: 22px;
    }

    .grid-container .date-from {
        background-color: #fff;
        border: 1px solid white;
        border-radius: 5px;
        box-shadow: inset 0 1px 3px rgb(0 0 0 / 20%);
    }

    .grid-container .date-from.grid-date .input-group-addon {
        position: relative;
        border: 0;
        width: fit-content;
        display: flex;
        align-items: center;
    }

    .grid-container .date-from.grid-date .input-group-addon .datepicker-icon {
        background-color: #acacac;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .grid-container .date-from.grid-date .input-group-addon .datepicker-icon::after {
        background-image: url('/cashier/calendar.svg');
        top: 50%;
        margin-top: -11px;
        content: "";
        position: absolute;
        display: block;
        width: 17px;
        height: 23px;
        background-position: center center;
        background-repeat: no-repeat;
    }

    .group-icon-clock {
        background: grey;
        position: relative;
    }

    .grid-container .input-group-addon .img-icon-clock {
        background-color: #acacac;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .grid-container .input-group-addon .img-icon-clock::after {
        background-image: url('/cashier/clock.svg');
        top: 50%;
        margin-top: -11px;
        content: "";
        display: block;
        width: 17px;
        position: absolute;
        height: 23px;
        background-position: center center;
        background-repeat: no-repeat;
    }

    .datepicker-icon:focus,
    .datepicker-icon:active {
        box-shadow: 0 0 12px #38c;
    }

    .timepicker_wrap .arrow_top {
        display: none;
    }

    .input-group .time .input-group-addon {
        cursor: pointer;
        background-color: inherit;
        outline: none;
        border: none;
        font-size: 18px;
        font-weight: normal;
        padding-left: 0.475em;
        display: flex;
        align-items: center;
        height: unset;
        box-shadow: none;
    }


    .datepicker th.next,
    .datepicker th.prev {
        height: 100%;

    }

    .time,
    .mins,
    .meridian {
        width: 31%;
        margin: 0 2px;
        padding: 10px 0px;
        background: #f6f6f6;
        font-size: 20px;
        font-weight: 700;
        border: 1px solid #999;
    }

    .ti_tx,
    .mi_tx,
    .mer_tx {
        width: 100%;
        text-align: center;
        margin: 10px 0;
    }

    ul.minical {
        width: 320px;
        border-radius: 10px;
    }

    .minical table thead th {
        padding: 2.4px 2px !important;
    }

    ul.minical li article header {
        background: white;
        color: #000000;
        border: none;
        padding: 2px 0;
        position: relative;
        margin: 0 -9px;
        font-size: 24px;
    }

    ul.minical li article section table tbody tr td {
        font-size: 18px;
    }

    ul.minical li article section table thead tr th {
        font-size: 16px;
        background: white !important;
        font-weight: 600;
    }

    ul.minical li article section table tbody tr td.minical_past_month a,
    ul.minical li article section table tbody tr td.minical_future_month a {
        background: white;
        border: none;
    }

    ul.minical li article section table tbody tr td a {
        background: #e6e6e6;
        border-radius: 2px
    }

    ul.minical li article header a.minical_next:after,
    ul.minical li article header a.minical_prev:after {
        content: "";
    }

    ul.minical li article header a.minical_next,
    ul.minical li article header a.minical_prev {
        padding: 14px;
        border: 1px solid #ccc;
        background-size: 70% 70%;
        background-repeat: no-repeat;
        background-position: 50% 50%;
        background-color: #acacac;
        cursor: pointer;
        margin: auto;
        /* background-image: url('/cashier/plus.svg'); */
        border-radius: 50%;
        content: "";
        display: block;
        width: 22px;
        height: 22px;
        top: 0;
    }

    ul.minical li {
        padding: 36px 10px 0 10px;
    }

    ul.minical li article header a.minical_next:focus,
    ul.minical li article header a.minical_prev:focus {
        box-shadow: 0 0 12px #38c;
    }

    ul.minical li article header a.minical_next {
        background-image: url('/cashier/plus.svg');
    }

    ul.minical li article header a.minical_prev {
        background-image: url('/cashier/minus.svg');
    }

</style>

<section class="sidebar">
    <div class="search-box" style="position:relative;">
        <i class="fa fa-search" style="position:absolute; padding:5%;font-size:15px; opacity:0.5;"></i>
        <input type="text" class="form-control" name="nav-search" id="nav-search" placeholder="Search...">
    </div>
    <div class="side-list-box" style="position:relative;">
        <div class="side-list-header">Users</div>
        <div class="side-list-body">
            @if (count($response['terminals'])>0)
            @foreach ($response['terminals'] as $key=>$item)
            <a href="{{url('backend/terminal/details/'.encoded($item->id))}}">
                <div class="side-list-cell">
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
@if(auth()->user()->hasRole('cashier') &&
    $openshift = \VanguardLTE\OpenShift::where(['shop_id' => auth()->user()->shop_id, 'end_date' => NULL])->first())

    @php $summ = \VanguardLTE\User::where(['shop_id' => auth()->user()->shop_id, 'role_id' => 1])->sum('balance');
    @endphp
    @endif

    <div class="box box-primary">
        <div class="box-header with-border" style="display:flex; justify-content: center; align-items:center;">
            <h3 class="box-title" style="font-size: 24px; display:flex; justify-content:center;">@lang('app.period')</h3>
        </div>
    </div>
    {{-- <input type='text' id="ex" class="form-control"> --}}
    <div class="grid-container" id="dateFromPicker">
        <div class="ui-bar-b">
            From
        </div>
        <div class="date-from grid-date input-group date">
            <input id="dateFrom" type="text" class="data-display-form form-control" value="" readonly>
            <div class="input-group-addon">
                <div class="datepicker-icon"></div>
            </div>
        </div>
        <div class="grid-time input-group date">
            <input id="timeFrom" type="text" name="time" class="time-display-form form-control" value="" readonly style="background-color: inherit;
        outline: none; border: none; font-size: 18px; font-weight: normal; padding-left: 0.475em; display: flex;
        align-items: center; height: unset; box-shadow: none;">
            <div class="input-group-addon" id="time-picki-from" style="cursor: pointer;">
                <div class="img-icon-clock"></div>
            </div>
        </div>
    </div>
    <div class="grid-container" id="dataTopicker" style="margin-top: 6px;">
        <div class="ui-bar-b">
            To
        </div>
        <div class="date-from grid-date input-group date">
            <input id="dateTo" class="data-display-form form-control" value="" readonly>
            <div class="input-group-addon">
                <div class="datepicker-icon"></div>
            </div>
        </div>
        <div class="grid-time input-group date">
            <input id="timeTo" type="text" name="time" class="time-display-form form-control" style="background-color: inherit;
        outline: none; border: none; font-size: 18px; font-weight: normal; padding-left: 0.475em; display: flex;
        align-items: center; height: unset; box-shadow: none" value="" readonly>
            <div class="input-group-addon" id="time-picki-to">
                <div class="img-icon-clock"></div>
            </div>
        </div>
    </div>
    <div class="grid-balance">
        <div class=" balance-title">In</div>
        <div class=" balance-title">Out</div>
        <div class=" balance-title">Netto</div>
    </div>
    <div class="grid-balance-content">
        <div id="totalIn" class=" balance-val">{{number_format((float)$in, 2, '.', ',')}} EUR</div>
        <div id="totalOut" class=" balance-val">{{number_format((float)$out, 2, '.', ',')}} EUR</div>
        <div id="netto" class=" balance-val">{{number_format((float)($in-$out), 2, '.', ',')}} EUR</div>
    </div>
    <div class="box box-primary" >
        <div class="box-header with-border" style="border-radius:5px 5px 0px 0px; display:flex; align-items:center;">
            <h3 class="box-title" style="display:flex; padding-left:30px; font-size: 24px;">Transaction</h3>
        </div>
    </div>
    <div id="transaction_content">
        @foreach($transactions as $tran)
            <div class="transaction" style=" border:0.1px solid #ddd; width:100%; font-size:20px; !important; justify-content:space-between; background-color:white; display:flex; align-items:center;height:48px;">
                <div style="justify-content:space-between; display:flex; width: 20%;">
                    <div style="display:flex; align-items:center; padding-left: 2.375em;">
                        null
                    </div>
                    <div style="display:flex; align-items:center; padding-left: 2.575em;">
                        <span style="color:{{$tran->type=='add'?'#007acc':'#800'}};">{{($tran->type=='add'?'+':'-').$tran->amount}}</span>
                    </div>
                </div>
                <div style="padding-right: 3.375em;">
                    {{$tran->user->username}}
                </div>
            </div>
    
        @endforeach
    </div>


    
</section>
<!-- Modals -->
@include('backend.terminal.modals.terminal_add')
@stop

@section('scripts')
<script>
    window.getTransaction = function(){
        let dateFrom = $("#dateFrom").val();
        let timeFrom = $("#timeFrom").val();
        let dateTo = $("#dateTo").val();
        let timeTo = $("#timeTo").val();
        dateFrom = getStrDate(dateFrom)+' '+getStrTime(timeFrom)+':00';
        dateTo = getStrDate(dateTo)+' '+getStrTime(timeTo)+':59';
        // console.log(dateFrom, dateTo)
        $.ajax({
            url:'{{route("backend.transaction.get")}}',
            type:'POST',
            data:{
                from:dateFrom,
                to:dateTo
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(res){
            // console.log(res);
            let inAmount = 0;
            let outAmount = 0;
            $("#transaction_content").html('');
            for(let tran of res){
                if(tran.type=='add') inAmount+=tran.amount;
                else outAmount+=tran.amount;

                $("#transaction_content").append(`
                    <div class="transaction" style=" border:0.1px solid #ddd; width:100%; font-size:16px; justify-content:space-between; background-color:white; display:flex; align-items:center;height:48px;">
                        <div style="justify-content:space-between; display:flex;width: 20%;">
                            <div style="display:flex; align-items:center; padding-left: 0.375em;">
                                null
                            </div>
                            <div style="display:flex; align-items:center; padding-left: 0.575em;">
                                <span style="color:${tran.type=='add'?'#007acc':'#800'}">${(tran.type=='add'?'+':'-')+tran.amount}</span>
                            </div>
                        </div>
                        <div style="padding-right: 1.375em;">
                            ${tran.username}
                        </div>
                    </div>  
                `)
            }
            $("#totalIn").text(Number(inAmount).toFixed(2))
            $("#totalOut").text(Number(outAmount).toFixed(2))
            $("#netto").text(Number(inAmount-outAmount).toFixed(2))
        })
    }
    function getStrDate(date){
        date=date.split('/');
        if(date[0]<10)date[0]='0'+date[0];
        if(date[1]<10)date[1]='0'+date[1];
        return date[2]+'-'+date[0]+'-'+date[1];
    }
    function getStrTime(time){
        time=time.split(' ');
        let pm = Boolean(time[1]=='PM')
        time=time[0].split(':');
        if(pm){
            time[0]=12+Number(time[0]);
        }
        if(time[0]==12 || time[0]==24)time[0]-=12;
        return time[0]+":"+time[1];
    }
    $(function () {
        var date = new Date();
        var date_info = JSON.parse(localStorage.getItem("dateInfo"));
        var cur_date = (date.getMonth() + 1) + "/" + date.getDate()+"/"+ date.getFullYear() ;
        // let cur_time = date.getHours();
        // let cur_day = "AM"
        // if(cur_time > 12)
        // {
        //     cur_day = "PM"
        //     cur_time -= 12;
        // }
        // cur_time= `${cur_time}:${date.getMinutes()} ${cur_day}`;
        // $("#dateFrom").val(cur_date);
        // $("#dateTo").val(cur_date);
        var timeFrom = [];
        if(date_info){
            timeFrom.push(date_info.timeFrom.split(":")[0]);
            timeFrom.push(date_info.timeFrom.split(":")[1].split(" ")[0]);
            timeFrom.push(date_info.timeFrom.split(":")[1].split(" ")[1]);
        }
        var timeTo = [];
        if(date_info){
            timeTo.push(date_info.timeTo.split(":")[0]);
            timeTo.push(date_info.timeTo.split(":")[1].split(" ")[0]);
            timeTo.push(date_info.timeTo.split(":")[1].split(" ")[1]);
        }
        let dfrom = false, dto = false;
        $('#timeFrom').val(date_info ? date_info.timeFrom : "12:00 AM");
        $('#timeTo').val(date_info ? date_info.timeTo : "4:00 PM");

        $('#dateFrom').attr("data-minical-initial",new Date(date_info && date_info.dateFrom.length ? date_info.dateFrom : new Date()).getTime());
        $('#dateTo').attr("data-minical-initial",new Date(date_info && date_info.dateTo.length ? date_info.dateTo : new Date()).getTime());
        $("#dateFrom").minical({
            date_changed:function(){
            const date_info = {
                dateFrom : $('#dateFrom').val(),
                dateTo : $('#dateTo').val(),
                timeFrom : $('#timeFrom').val(),
                timeTo : $('#timeTo').val(),
            }
            localStorage.setItem("dateInfo",JSON.stringify(date_info));
                if(dfrom) getTransaction()
                dfrom = true;
            }
        });
        $("#dateTo").minical({
            date_changed:function(){
                if(dto) getTransaction()
                dto = true;
            }
        });
        $("#timeFrom").timepicki({
            reset: true,
            start_time: timeFrom.length ? timeFrom :  [12,00,'AM']
        });
        $("#timeTo").timepicki({
            reset: true,
            start_time: timeTo.length ? timeTo :  [4,00,'AM']

        });
        // $("#dateFrom").on('change', function(){
        //     getTransaction();
        // })
        // $("#dateTo").on('change', function(){
        //     getTransaction();
        // })
       getTransaction();
    })
</script>
@stop