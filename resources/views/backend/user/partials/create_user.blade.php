<div class="form-group col-md-10">
    <label class="col-md-1 control-label">@lang('app.username')</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-user"></i>
            </span>
            <input type="text" class="form-control" id="username" name="username" value="">
            <input type="hidden" class="form-control" id="user_role" name="user_role"  value="user">
        </div>
    </div>

</div>

<div class="form-group col-md-10">

    <label class="col-md-1 control-label">@lang('app.password')</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-lock"></i>
            </span>
            <input type="password" class="form-control" id="password" name="password" value="123456">
        </div>
    </div>

</div>
<div class="form-group col-md-10">

    <label class="col-md-1 control-label">{{ trans('app.confirm_password') }}</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-lock"></i>
            </span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="123456">
        </div>
    </div>

</div>
