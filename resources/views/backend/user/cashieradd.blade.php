@extends('backend.layouts.cashier')

@section('page-title', trans('app.add_user'))
@section('page-heading', trans('app.create_new_user'))

@section('content')
<style>
    section.content-header,
    section.content {
        margin-left: 272px;
    }
    
.box-footer {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    /* border-bottom-right-radius: 3px; */
    /* border-bottom-left-radius: 3px; */
    /* border-top: 1px solid #f4f4f4; */
    padding: 10px;
    background-color: #e6e6e6;
    
}
.box .box-header{
    text-align:unset;
}
.box{
    box-shadow:unset;
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
            @if (count($users))
            @foreach ($users as $cuser)
            <a href="{{ route('backend.user.edit', $cuser->id) }}">
                <div class="side-list-cell">
                    <div class="side-cell-data">{{$cuser->username}}</div>
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

    @if($happyhour)
    <div class="alert alert-success">
        <h4>@lang('app.happyhours')</h4>
        <p> @lang('app.all_player_deposits') {{ $happyhour->multiplier }}</p>
    </div>
    @endif


    {!! Form::open(['route' => 'backend.user.massadd', 'files' => true, 'id' => 'mass-user-form']) !!}
    <div class="box box-default" style="display:none;">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('app.add_user')</h3>
        </div>

        <div class="box-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('app.count')</label>
                        <select name="count" class="form-control">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('app.balance')</label>
                        <input type="text" class="form-control" id="title" name="balance" value="0">
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">
                @lang('app.add_user')
            </button>
        </div>
    </div>
    {!! Form::close() !!}

    {!! Form::open(['route' => 'backend.user.store', 'files' => true, 'id' => 'user-form']) !!}

    <div class="box box-default" style="background-color: #e6e6e6;">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('app.create_home_player')</h3>
        </div>

        <div class="box-body" style="width:50%;">
            <div>

                @include('backend.user.partials.cashiercreate')

            </div>
        </div>

        <div  style="background-color: #e6e6e6;width:50%; gap:3px;display:flex; justify-content:center;">
            <button type="submit" class="btn btn-primary" style="width:50%;">
                @lang('app.create')
            </button>
            <button type="text" class="btn btn-primary" style="width:50%;">
                @lang('app.cancel')
            </button>
        </div>
        
    </div>
    <div>
            <i class="fa fa-asterisk fa-lg" style="color:#38c;"></i>
                -required fields:
        </div>
    {!! Form::close() !!}

</section>
@stop

@section('scripts')
{!! JsValidator::formRequest('VanguardLTE\Http\Requests\User\CreateUserRequest', '#user-form') !!}

<script>
    $("#role_id").change(function (event) {
            var role_id = parseInt($('#role_id').val());
            $("#parent > option").each(function() {
                var id = parseInt($(this).attr('role'));
                if( (id - role_id) != 1 ){
                    $(this).attr('hidden', true);
                } else{
                    $(this).attr('hidden', false);
                }
                $(this).attr('selected', false);
            });
            $('#parent option[value=""]').attr('selected', true);
        });

        $("#role_id").trigger('change');

</script>
@stop