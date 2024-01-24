@extends('backend.layouts.app')

@section('page-title', trans('app.game_stats'))
@section('page-heading', trans('app.game_stats'))

@section('content')

	<section class="content-header">
		@include('backend.partials.messages')
	</section>

	<section class="content">
		<form action="" method="GET">
			<div class="box box-danger collapsed-box game_stat_show">
				<div class="box-header with-border">
					<h3 class="box-title">@lang('app.filter')</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.user')</label>
								<input type="text" class="form-control" name="user" value="{{ Request::get('user') }}">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>@lang('app.game')</label>
								<input type="text" class="form-control" name="game" value="{{ Request::get('game') }}">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label> @lang('app.date_start')</label>
								<div class="input-group">
									<button type="button" class="btn btn-default pull-right" id="daterange-btn">
										<span><i class="fa fa-calendar"></i> {{ Request::get('dates_view') ?: __('app.date_start_picker') }}</span>
										<i class="fa fa-caret-down"></i>
									</button>
								</div>
								<input type="hidden" id="dates_view" name="dates_view" value="{{ Request::get('dates_view') }}">
								<input type="hidden" id="dates" name="dates" value="{{ Request::get('dates') }}">
							</div>
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

		@if( !$agent->hasRole('admin') )
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.distributor') @lang('app.stats_menu') - @lang('app.my') @lang('app.distributor')</h3>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>@lang('app.user')</th>
								<th>@lang('app.fight') (%)</th>
								<th>@lang('app.my') @lang('app.win_loss')</th>
								<th>@lang('app.agent') @lang('app.pay_upline')</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<a href="{{ route('backend.agent_stat', $agent->id) }}">
										{{ $agent->username }}
									</a>
								</td>
								<td>
									{{ $agent->fight }}
								</td>
								<td>
									{{ number_format(($agent->total_bet - $agent->total_win) * $agent->fight / 100, 2, '.', '') }}
								</td>
								<td>
									{{ number_format(($agent->total_bet - $agent->total_win) * (100 - $agent->fight) / 100, 2, '.', '') }}
								</td>
							</tr>
						</tbody>
                	</table>
            	</div>
        	</div>
		</div>
		@endif

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.agent') @lang('app.stats_menu') - @lang('app.my') @lang('app.agents')</h3>
			</div>
			<div class="box-body">
				<span class="badge bg-yellow text-badge">@lang('app.total_win'): {{ $child_agent_total_win }}</span>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>@lang('app.user')</th>
								<th>@lang('app.fight') (%)</th>
								<th>@lang('app.agent') @lang('app.win_loss')</th>
								<th>@lang('app.agent') @lang('app.pay_company')</th>
							</tr>
						</thead>
						<tbody>
						@if (count($agents))
							@foreach ($agents as $child_agent)
							<tr>
								<td>
									<a href="{{ route('backend.agent_stat', $child_agent->id) }}">
										{{ $child_agent->username }}
									</a>
								</td>
								<td>
									{{ $child_agent->fight }}
								</td>
								<td>
									{{ number_format(($child_agent->total_bet - $child_agent->total_win) * $child_agent->fight / 100, 2, '.', '') }}
								</td>
								<td>
									{{ number_format(($child_agent->total_bet - $child_agent->total_win) * (100 - $child_agent->fight) / 100, 2, '.', '') }}
								</td>
							</tr>
							@endforeach
						@else
							<tr><td colspan="3">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
							<tr>
								<th>@lang('app.user')</th>
								<th>@lang('app.fight') (%)</th>
								<th>@lang('app.agent') @lang('app.win_loss')</th>
								<th>@lang('app.agent') @lang('app.pay_company')</th>
							</tr>
						</thead>
                	</table>
            	</div>
        	</div>
		</div>

		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">@lang('app.agent') @lang('app.stats_menu') - @lang('app.my') @lang('app.users')</h3>
			</div>
			<div class="box-body">
				<span class="badge bg-yellow text-badge">@lang('app.total_win'): {{ $child_user_total_win }}</span>
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>@lang('app.user')</th>
								<th>@lang('app.bet')</th>
								<th>@lang('app.win')</th>
								<th>@lang('app.win_loss')</th>
							</tr>
						</thead>
						<tbody>
						@if (count($users))
							@foreach ($users as $child_user)
							<tr>
								<td>
									<a href="{{ route('backend.user.edit', $child_user->id) }}">
										{{ $child_user->username }}
									</a>
								</td>
								<td>
									{{ number_format($child_user->total_bet ?? 0, 2, '.', '')}}
								</td>
								<td>
									{{ number_format($child_user->total_win ?? 0, 2, '.', '') }}
								</td>
								<td>
									{{ number_format($child_user->total_win - $child_user->total_bet, 2, '.', '') }}
								</td>
							</tr>
							@endforeach
						@else
							<tr><td colspan="3">@lang('app.no_data')</td></tr>
						@endif
						</tbody>
						<thead>
							<tr>
								<th>@lang('app.user')</th>
								<th>@lang('app.bet')</th>
								<th>@lang('app.win')</th>
								<th>@lang('app.win_loss')</th>
							</tr>
						</thead>
                	</table>
            	</div>
        	</div>
		</div>
	</section>

@stop

@section('scripts')
	<script>
		$('#stats-table').dataTable();
		$(function() {
			$('input[name="dates"]').daterangepicker({
				timePicker: true,
				timePicker24Hour: true,
				startDate: moment().subtract(30, 'day'),
				endDate: moment().add(7, 'day'),

				locale: {
					format: 'YYYY-MM-DD HH:mm'
				}
			});
			$('.btn-box-tool').click(function(event){
				if( $('.game_stat_show').hasClass('collapsed-box') ){
					$.cookie('game_stat_show', '1');
				} else {
					$.removeCookie('game_stat_show');
				}
			});

			if( $.cookie('game_stat_show') ){
				$('.game_stat_show').removeClass('collapsed-box');
				$('.game_stat_show .btn-box-tool i').removeClass('fa-plus').addClass('fa-minus');
			}
		});
	</script>
@stop
