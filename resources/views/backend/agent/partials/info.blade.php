<div class="col-md-3">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle" src="/back/img/{{ $agent->present()->role_id }}.png"
                alt="{{ $agent->present()->username }}">
            <h4 class="profile-username text-center">{{ $agent->present()->username }}</h4>
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>@lang('app.balance')</b> <a
                        class="pull-right">{{ number_format($agent->present()->balance, 2, '.', '') }}</a>
                </li>
            </ul>

            @if ($agent->id != Auth::id())
                @permission('agents.delete')
                    @if (auth()->user()->hasRole(['admin', 'distributor']))
                        <a href="{{ route('backend.agent.delete', $agent->id) }}" class="btn btn-danger btn-block"
                            data-method="DELETE" data-confirm-title="@lang('app.please_confirm')"
                            data-confirm-text="@lang('app.are_you_sure_delete_agent')" data-confirm-delete="@lang('app.yes_delete_him')">
                            <b>@lang('app.delete')</b></a>
                        <a href="{{ route('backend.agent.hard_delete', $agent->id) }}" class="btn btn-danger btn-block"
                            data-method="DELETE" data-confirm-title="@lang('app.please_confirm')"
                            data-confirm-text="@lang('app.are_you_sure_delete_agent')" data-confirm-delete="@lang('app.yes_delete_him')">
                            <b>@lang('app.hard_delete') {{ $agent->role->name }}</b></a>
                    @endif
                @endpermission
            @endif
        </div>
    </div>

    @if (!$agent->hasRole('admin'))
        <div>
            @if (
                (Auth::user()->hasRole('admin') && $agent->hasRole(['distributor'])) ||
                    (Auth::user()->hasRole('agent') && $agent->parent_id == Auth::user()->id))
                <button type="button" class="btn btn-block btn-success btn-xs newPayment addPayment"
                    data-toggle="modal" data-target="#openAddModal"
                    data-id="{{ $agent->id }}">@lang('app.add')</button>
            @else
                <button type="button" class="btn btn-block btn-success disabled btn-xs">@lang('app.add')</button>
            @endif

            @if (
                (Auth::user()->hasRole('admin') && $agent->hasRole(['distributor'])) ||
                    (Auth::user()->hasRole('agent') && $agent->parent_id == Auth::user()->id))
                <button type="button" class="btn btn-block btn-danger btn-xs newPayment outPayment" data-toggle="modal"
                    data-target="#openOutModal" data-id="{{ $agent->id }}">@lang('app.out')</button>
            @else
                <button type="button" class="btn btn-block btn-danger disabled btn-xs">@lang('app.out')</button>
            @endif
        </div><br />
    @endif

    @if ($agent->hasRole('admin'))
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.google_2fa')</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label>@lang('app.enable')</label>
                    {!! Form::select(
                        'google2fa_enable',
                        [0 => __('app.disabled'), 1 => __('app.active')],
                        $agent->google2fa_enable,
                        ['class' => 'form-control'],
                    ) !!}
                    <input value="{{ $secret }}" type="hidden" name="secret_key">
                </div>
                @if (
                    ($agent->google2fa_secret == null && $agent->google2fa_enable) ||
                        ($agent->google2fa_secret != null && !$agent->google2fa_enable))
                    <div class="form-group">
                        <label>@lang('app.code')</label>
                        <input type="text" name="google_2fa_code" value="" class="form-control">
                    </div>
                @endif

                @if ($QR_Image)
                    @if ($agent->google2fa_secret == '')
                        <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can
                            use the code {{ $secret }}</p>
                        <div>
                            <img src="{{ $QR_Image }}">
                        </div>
                        <p>You must set up your Google Authenticator app before continuing. You will be unable to login
                            otherwise</p>
                    @endif
                @endif
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="update-details-btn">
                    @lang('app.edit_agent')
                </button>
            </div>
        </div>
    @endif
</div>
