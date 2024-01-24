<div class="form-group col-md-10">
    <label class="col-md-2 control-label">@lang('app.username')</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-user"></i>
            </span>
            <input type="text" class="form-control" id="username" name="username" placeholder="" value=""
                required>
            <input type="hidden" class="form-control" id="user_role" name="user_role" value="shop">
        </div>
    </div>
</div>

<div class="form-group col-md-10">

    <label class="col-md-2 control-label">Assign to Operator</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-group"></i>
            </span>
            <select class="form-control" name="parent_operator" id="parent_operator" data-live-search="true">
                @foreach ($child_operators as $child_operator)
                    <option value="{{ $child_operator->id }}"
                        {{ $child_operator->id == $recent_operator_id ? 'selected' : '' }}>{{ $child_operator->username }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

</div>

<div class="form-group col-md-10">

    <label class="col-md-2 control-label">@lang('app.password')</label>
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

    <label class="col-md-2 control-label">{{ trans('app.confirm_password') }}</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-lock"></i>
            </span>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                value="123456">
        </div>
    </div>

</div>
<div class="form-group col-md-10">
    <label class="col-md-2 control-label">@lang('app.lobby')</label>
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-search"></i>
            </span>
            <select class="form-control" name="lobby" id="lobby">
                @foreach ($lobbies as $lobby)
                    <option value="{{ $lobby }}">{{ $lobby }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
