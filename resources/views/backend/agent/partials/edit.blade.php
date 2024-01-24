<div class="box-body box-profile">

    <div class="form-group">
        <label>@lang('app.role')</label>
        {!! Form::select('role_id', Auth::user()->available_roles( true ), $edit ? $agent->role_id : '',
            ['class' => 'form-control', 'id' => 'role_id', 'disabled' => true]) !!}
    </div>

    <div class="form-group">
        <label>@lang('app.shops')</label>
        {!! Form::select('shops[]', $shops, ($edit && $agent->hasRole(['admin', 'distributor'])) ? $agent->shops(true) : Auth::user()->shop_id,
            ['class' => 'form-control', 'id' => 'shops', ($edit) ? 'disabled' : '', ($edit && $agent->hasRole(['agent','distributor'])) ? 'multiple' : '']) !!}
    </div>

    <div class="form-group">
        <label>@lang('app.status')</label>
        {!! Form::select('status', $statuses, $edit ? $agent->status : '' ,
            ['class' => 'form-control', 'id' => 'status', 'disabled' => ($agent->hasRole(['admin']) || $agent->id == auth()->user()->id) ? true: false]) !!}
    </div>

    @if(auth()->user()->hasRole('admin') && $agent->hasRole(['distributor', 'agent']))
    <div class="form-group">
        <label for="device">@lang('app.block')</label>
        {!! Form::select('is_blocked', ['0' => __('app.unblock'), '1' => __('app.block')], $edit ? $agent->is_blocked : old('is_blocked'), ['class' => 'form-control']) !!}
    </div>
    @endif

    <div class="form-group">
        <label>@lang('app.username')</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="(@lang('app.optional'))" value="{{ $edit ? $agent->username : '' }}">
    </div>

    <div class="form-group">
        <label>@lang('app.fight') (%)</label>
        <input type="number" class="form-control" id="fight" name="fight" placeholder="(@lang('app.optional'))" value="{{ $edit ? $agent->fight : 0 }}">
    </div>

    @if( $agent->email != '' )
    <div class="form-group">
        <label>@lang('app.email')</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="(@lang('app.optional'))" value="{{ $edit ? $agent->email : '' }}">
    </div>
    @endif

    <div class="form-group">
        <label>@lang('app.phone')@if($agent->phone) @if($agent->phone_verified) [Verified] @endif @endif</label>
        <div class="input-group">
            <span class="input-group-addon">+</span>
            <input type="text" class="form-control onlynumber" id="phone" name="phone" value="{{ $edit ? $agent->phone : '' }}" @if(!auth()->user()->hasRole('admin') && ($agent->phone_token || $agent->phone)) disabled @endif>
        </div>
    </div>

    @if( $agent->sms_token &&  !$agent->phone_verified)
        @php
            $now = \Carbon\Carbon::now();
            $timer_text = 'Time is up';
            $show_code = false;
            $times = $now->diffInSeconds(\Carbon\Carbon::parse($agent->sms_token_date), false);
            if( $times > 0 ){
                $minutes = floor($times/60);
                $seconds = $times - floor($times/60)*60;
                $show_code = true;
                $timer_text = ($minutes < 10 ? "0" . $minutes : $minutes) . ':' . ($seconds < 10 ? "0" . $seconds : $seconds);
            }
        @endphp
    @if($show_code)
        <div class="form-group" id="timer_code">
            <label>Phone Verification Code

                [<span id="sms_timer" data-seconds="{{ $times }}">{{ $timer_text }}</span>]
            </label>
            <input type="text" class="form-control" id="sms_token" name="sms_token" value="" >
        </div>
        @endif
    @endif

    <div class="form-group">
        <label>@lang('app.lang')</label>
        {!! Form::select('language', $langs, $edit ? $agent->language : '', ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label>{{ $edit ? trans("app.new_password") : trans('app.password') }}</label>
        <input type="password" class="form-control" id="password" name="password" @if ($edit) placeholder="@lang('app.leave_blank_if_you_dont_want_to_change')" @endif>
    </div>

    <div class="form-group">
        <label>{{ $edit ? trans("app.confirm_new_password") : trans('app.confirm_password') }}</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" @if ($edit) placeholder="@lang('app.leave_blank_if_you_dont_want_to_change')" @endif>
    </div>

    @if(auth()->user()->hasRole('admin') && !$agent->hasRole('admin') && $agent->auth_token != '' )
    <div class="form-group">
        <label>Auth</label>
        <input value="{{ route('frontend.user.specauth', ['user' => $agent->id, 'token' => $agent->auth_token]) }}" class="form-control">
	</div>
    @endif

</div>

<div class="box-footer">
    <button type="submit" class="btn btn-primary" id="update-details-btn">
        @lang('app.edit_agent')
    </button>
</div>