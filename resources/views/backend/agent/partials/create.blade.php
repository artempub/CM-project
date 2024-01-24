<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.username')</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="(@lang('app.optional'))" value="{{ old('username') }}">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.shops')</label>
        @if( auth()->user()->hasRole('admin') )
            {!! Form::select('shop_id', ['0' => trans('app.no_shop')] + $shops, old('shop_id')? : '0', ['class' => 'form-control', 'id' => 'shops']) !!}
        @else
            {!! Form::select('shop_id', $shops, old('shop_id')? : '0', ['class' => 'form-control', 'id' => 'shops']) !!}
        @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.role')</label>
        {!! Form::select('role_id', Auth::user()->available_roles(), old('role_id'),
            ['class' => 'form-control', 'id' => 'role_id', '']) !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.status')</label>
        {!! Form::select('status', $statuses, old('status'),
            ['class' => 'form-control', 'id' => 'status', '']) !!}
    </div>
</div>

@if( auth()->user()->hasRole('agent') )
    <input type="hidden" name="shop_id" value="{{ auth()->user()->shop_id }}">
@endif

<div class="col-md-6">
    <div class="form-group">
        <label>{{ trans('app.balance') }}</label>
        <input type="number" class="form-control" id="balance" name="balance" value="{{ old('balance')? : 0 }}">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.fight') (%)</label>
        <input type="number" class="form-control" id="fight" name="fight" value="{{ old('fight')? : 0 }}">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>{{ trans('app.password') }}</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>{{ trans('app.confirm_password') }}</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>
</div>