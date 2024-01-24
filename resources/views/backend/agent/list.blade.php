@extends('backend.layouts.app')

@section('page-title', trans('app.agents'))
@section('page-heading', trans('app.agents'))

@section('content')

	<section class="content-header">
		@include('backend.partials.messages')
	</section>

	<section class="content">

		@if(auth()->user()->hasRole('agent') &&
			$openshift = \VanguardLTE\OpenShift::where(['shop_id' => auth()->user()->shop_id, 'end_date' => NULL])->first())

			@php $summ = \VanguardLTE\User::where(['shop_id' => auth()->user()->shop_id, 'role_id' => 1])->sum('balance'); @endphp

			<div class="row hidden">
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-light-blue">
						<div class="inner">
							@php
								$money = $openshift->users;
                                if($openshift->end_date == NULL){
                                    $money = $summ;
                                }
							@endphp

							<h3>{{ number_format($money, 2, ".", "") }}</h3>
							<p>User @lang('app.balance')</p>
						</div>
						<div class="icon">
							<i class="fa fa-refresh"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3>{{ number_format($openshift->money_in, 2, ".", "") }}</h3>
							<p>@lang('app.in')</p>
						</div>
						<div class="icon">
							<i class="fa fa-level-up"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3>{{ number_format ($openshift->money_out, 2, ".", "") }}</h3>
							<p>@lang('app.out')</p>
						</div>
						<div class="icon">
							<i class="fa fa-level-down"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
							@php
								$total = $openshift->money_in - $openshift->money_out;
							@endphp

							<h3>{{ number_format ($total, 2, ".", "") }}</h3>
							<p>@lang('app.total') Money</p>
						</div>
						<div class="icon">
							<i class="fa fa-line-chart"></i>
						</div>
					</div>
				</div>
				<!-- ./col -->
			</div>
		@endif

			<form action="" method="GET" id="users-form" >
				<div class="box box-danger collapsed-box users_show">
					<div class="box-header with-border">
						<h3 class="box-title">@lang('app.filter')</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-3">
								<label>@lang('app.search')</label>
								<input type="text" class="form-control" name="search" value="{{ Request::get('search') }}" placeholder="@lang('app.search_for_users')">
							</div>
							@if (!Auth::user()->hasRole('agent'))
								<div class="col-md-3">
									<label>@lang('app.role')</label>
									{!! Form::select('role', $roles, Request::get('role'), ['id' => 'role', 'class' => 'form-control']) !!}
								</div>
							@endif
							<div class="col-md-3">
								<label>@lang('app.status')</label>
								{!! Form::select('status', $statuses, Request::get('status'), ['id' => 'status', 'class' => 'form-control']) !!}
							</div>
							<div class="col-md-3">
								<label>@lang('app.parent')</label>
								<input type="text" class="form-control" name="parent" value="{{ Request::get('parent') }}" placeholder="">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">
							@lang('app.filter')
						</button>
					</div>
				</div>
			</form>

			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.agents')</h3>
					<div class="pull-right box-tools">
						<a href="{{ route('backend.agent.create') }}" class="btn btn-block btn-primary btn-sm">@lang('app.add')</a>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>@lang('app.username')</th>
								@if(auth()->user()->hasRole('admin'))
								<th>Log In</th>
							    @endif
								<th>@lang('app.role')</th>
								<th>@lang('app.parent')</th>
								<th>@lang('app.balance')</th>
								<th>@lang('app.fight') (%)</th>
								<th>@lang('app.pay_in')</th>
								<th>@lang('app.pay_out')</th>
							</tr>
							</thead>
							<tbody>
							@if (count($agents))
								@foreach ($agents as $agent)
									@include('backend.agent.partials.row')
								@endforeach
							@else
								<tr><td colspan="6">@lang('app.no_data')</td></tr>
							@endif
							</tbody>
							<thead>
							<tr>
								<th>@lang('app.username')</th>
								@if(auth()->user()->hasRole('admin'))
								<th>Log In</th>
							    @endif
								<th>@lang('app.role')</th>
								<th>@lang('app.parent')</th>
								<th>@lang('app.balance')</th>
								<th>@lang('app.fight') %</th>
								<th>@lang('app.pay_in')</th>
								<th>@lang('app.pay_out')</th>
							</tr>
							</thead>
						</table>
					</div>
					{{ $agents->appends(Request::except('page'))->links() }}
				</div>
			</div>
	</section>

	@include('backend.agent.partials.modals')

@stop

@section('scripts')
	<script>

		$(function() {

			$('.btn-box-tool').click(function(event){
				if( $('.users_show').hasClass('collapsed-box') ){
					$.cookie('users_show', '1');
				} else {
					$.removeCookie('users_show');
				}
			});

			if( $.cookie('users_show') ){
				$('.users_show').removeClass('collapsed-box');
				$('.users_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}

			$("#view").change(function () {
				$("#shops-form").submit();
			});

			$("#filter").detach().appendTo("div.toolbar");

			$("#status").change(function () {
				$("#users-form").submit();
			});

			$("#role").change(function () {
				$("#users-form").submit();
			});

			$('.addPayment').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#AddId').val(id);

			});

			$('.outPayment').click(function(event){
				if( $(event.target).is('.newPayment') ){
					var id = $(event.target).attr('data-id');
				}else{
					var id = $(event.target).parents('.newPayment').attr('data-id');
				}
				$('#OutId').val(id);
				$('#outAll').val('');
			});

			$('#doOutAll').click(function () {
				$('#outAll').val('1');
				$('form#outForm').submit();
			});

			setInterval(function() {
				$.getJSON(' {{ route('backend.agent.balance.get') }} ', function(data) {
					for (var key in data) {
						$('.balance_' + key).html(data[key].balance);
						$('.bonus_' + key).html(data[key].bonus);
						$('.wager_' + key).html(data[key].wager);
					};
				});
			}, 3000);

		});

	</script>
@stop
