<tr>
	<td>
		@if(!$agent->is_online())
		<small><i class="fa fa-circle text-red"></i></small>
		@else
		<small><i class="fa fa-circle text-green"></i></small>
		@endif

		@if( auth()->user()->hasPermission('agents.edit') )
		<a href="{{ route('backend.agent.edit', $agent->id) }}">
			{{ $agent->username ?: trans('app.n_a') }}
		</a>
		@else
		{{ $agent->username ?: trans('app.n_a') }}
		@endif
		&nbsp;
	</td>
	@if(auth()->user()->hasRole('admin') && !$agent->hasRole('admin'))
	<td>
		<a href="{{ route('backend.agent.specauth', ['agent' => $agent->id, 'token' => $agent->auth_token]) }}" class="btn btn-xs btn-default">Log In</a>
	</td>
	@endif
	<td class="role_{{ $agent->id }}">{{ $agent->role->name }}</td>
	<td class="parent_{{ $agent->id }}">{{ $agent->parent_username }}</td>
	<td class="balance_{{ $agent->id }}">{{ number_format(floatval($agent->balance), 2, '.', '') }}</td>
	<td class="fight_{{ $agent->id }}">{{ $agent->fight }}</td>
	<td>
		@if(
		(
		(Auth::user()->hasRole('admin') && $agent->parent_id==Auth::user()->id) ||
		(Auth::user()->hasRole('distributor') && str_contains($agent->ancestry, Auth::user()->ancestry)) ||
		(Auth::user()->hasRole('agent') && $agent->parent_id == Auth::user()->id)
		)
		&& $agent->status !== \VanguardLTE\Support\Enum\UserStatus::BANNED
		)
		<a class="newPayment addPayment" href="#" data-toggle="modal" data-target="#openAddModal" data-id="{{ $agent->id }}">
			<button type="button" class="btn btn-block btn-success btn-xs">@lang('app.add')</button>
		</a>
		@else
		<button type="button" class="btn btn-block btn-success hidden btn-xs">@lang('app.add')</button>
		@endif
	</td>
	<td>
		@if(
		(
		(Auth::user()->hasRole('admin') && $agent->parent_id==Auth::user()->id) ||
		(Auth::user()->hasRole('distributor') && str_contains($agent->ancestry, Auth::user()->ancestry)) ||
		(Auth::user()->hasRole('agent') && $agent->parent_id == Auth::user()->id)
		)
		&& $agent->status !== \VanguardLTE\Support\Enum\UserStatus::BANNED
		)
		<a class="newPayment outPayment" href="#" data-toggle="modal" data-target="#openOutModal" data-id="{{ $agent->id }}">
			<button type="button" class="btn btn-block btn-danger btn-xs">@lang('app.out')</button>
		</a>
		@else
		<button type="button" class="btn btn-block btn-danger hidden btn-xs">@lang('app.out')</button>
		@endif
	</td>

	@if(isset($show_shop) && $show_shop)
	@if($user->shop)
	<td><a href="{{ route('backend.shop.edit', $user->shop->id) }}">{{ $user->shop->name }}</a></td>
	@else
	<td></td>
	@endif
	@endif
</tr>