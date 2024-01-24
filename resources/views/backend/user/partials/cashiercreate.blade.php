<div   >
    <div class="form-group">
        <label>@lang('app.home_player_login')  <i class="fa fa-asterisk fa-lg" style="color:#38c;"></i></label>
        <input type="text" class="form-control" id="username" name="username" placeholder="" value="">
    </div>
</div>

<div  style="display:none;">
    <div class="form-group">
        <label>@lang('app.role')</label>
        {!! Form::select('role_id', Auth::user()->available_roles(), '',
            ['class' => 'form-control', 'id' => 'role_id', '']) !!}
    </div>
</div>

    <input type="hidden" name="shop_id" value="{{ auth()->user()->shop_id }}">


    <div >
        <div class="form-group">
            <label>{{ trans('app.home_player_email') }}</label>
            <input type="text" class="form-control" id="email" name="email" value="">
        </div>
    </div>


    <div style="display:none">
        <div class="form-group">
            <label>{{ trans('app.balance') }}</label>
            <input type="text" class="form-control" id="balance" name="balance" value="0">
        </div>
    </div>

<div >
    <div class="form-group">
        <label>{{ trans('app.password') }}:  <i class="fa fa-asterisk fa-lg" style="color:#38c;"></i></label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
</div>
<div >
    <div class="form-group">
        <label>{{ trans('app.confirm_password') }}:  <i class="fa fa-asterisk fa-lg" style="color:#38c;"></i></label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
    </div>
</div>
