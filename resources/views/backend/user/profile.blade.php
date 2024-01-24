@extends('backend.layouts.app')

@section('content')

<section class="content-header">
    @include('backend.partials.messages')
</section>

<section class="content">

    <div class="box box-default panel-body">
        <div class="box-header with-border">
            <div class="panel-heading"><b>User "<b class="text-danger"> {{$user_info->username}}</b>" Details</b>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" id="logs_tab" class="active">
                                <a href="#op_profile" class="nav-link" aria-controls="op_profile" role="tab" data-toggle="tab">Profile
                                </a>
                            </li>
                            <!-- <li role="presentation" id="ops_tab" class="">
                                <a href="#op_atm" class="nav-link" aria-controls="op_atm" role="tab" data-toggle="tab">Transactions
                                </a>
                            </li> -->
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="op_profile">
                                <br>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Credits
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Credits">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <input type="text" disabled="" value="{{$user_info->balance}}" class="text-center form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    {!! Form::open(['route' => 'backend.user.change_pw', 'files' => true, 'id' => 'change-pw-form']) !!}
                                    <input type="hidden" name="user_id"  id="user_id" value="{{$user_info->id}}">
                                    <input type="hidden" name="username"  id="username" value="{{$user_info->username}}">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Password
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Password">
                                            </span>
                                        </label>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" class="form-control" placeholder="password">
                                                <span class="input-group-btn">
                                                    <button id="btn_changePw" class="btn btn-primary" type="submit" >
                                                        Save
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                                {!! Form::open(['route' => 'backend.user.edit_profile', 'files' => true, 'id' => 'user-profile-form']) !!}
                                <input type="hidden" name="edit_user_profile" value="req_true">
                                <input type="hidden" name="user_id" value="{{$user_info->id}}">

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Username
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Name from player - terminal. The name appears in the cashier page"></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="username" class="none"></label>
                                            <input type="text" name="username" id="username" value="{{$user_info->username}}" class="form-control name" style="margin-top:-20px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Pin
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Pin is used on VLT software, if you want to enter the games with a pin on a VLT insert PIN here. Only numbers are allowed., To disable PIN leave this field blank."></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="pin" class="none"></label>
                                            <input type="text" name="pin_value" id="pin_value" value="{{$user_info->pin_value}}" class="form-control wpin" style="margin-top:-20px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label text-right">
                                            Pin
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Pin is used on VLT software, if you want to enter the games with a pin on a VLT insert PIN here. Only numbers are allowed., To disable PIN leave this field blank."></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label class="switch"><input type="checkbox" name="pin_enabled" value="1" {{ $user_info->pin_enabled == 1 ? 'checked' : '' }}><span class="slider round"></span></label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label text-right">
                                            Reset VLT
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="This option is for VLT software.If you are getting lisence problem error message or if you are going to move an active account to a new PC you must reset VLT for security reasons"></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label class="switch"><input type="checkbox" name="reset_vlt" value="1" {{ $user_info->reset_vlt == 1 ? 'checked' : '' }} ><span class="slider round"></span></label>

                                        </div>
                                    </div>
                                </div>


                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label text-right">
                                            IP
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="IP"></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="ip" class="none"></label>
                                            <input type="text" name="ip_address" id="ip_address" disabled value="{{$user_info->ip_address}}" class="form-control" style="margin-top:-20px;">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label text-right">
                                            URL
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="URL"></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="url" class="none"></label>
                                            <input type="text" id="url" name="url" disabled value="{{$user_info->url}}" class="form-control" style="margin-top:-20px;">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label text-right">
                                            Api Userhash
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title="Api Userhash"></span>
                                        </label>
                                        <div class="col-md-3">
                                            <label for="userhash" class="none"></label>
                                            <input type="text" id="api_token" name="api_token" disabled value="{{$user_info->api_token}}" class="form-control" style="margin-top:-20px;">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">
                                            Family
                                            <span style="color: #3c8dbc;" class="fa fa-question-circle red-tooltip" data-toggle="tooltip" data-original-title=" Family">
                                            </span>
                                        </label>
                                        <div class="col-md-10">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                    Administrator
                                                </li>
                                                @if(isset($parent_username_array))
                                                @foreach($parent_username_array as $parent)
                                                <li class="breadcrumb-item">
                                                    {{$parent}}
                                                </li>
                                                <!-- <li class="breadcrumb-item">
                                                    <a href="https://netxo.gapi.lol/bo/shop/profile/814302" class="text-danger">sa7rawi</a>
                                                </li> -->
                                                @endforeach
                                                @endif
                                            </ol>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group" style="padding-bottom: 15px;">
                                    <div class="col-lg-offset-2 col-lg-10 text-right">
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                </br>
                                {!! Form::open(['route' => 'backend.user.remove', 'files' => true, 'id' => 'user-remove-form']) !!}

                                <div class="line line-lg b-b b-light"></div>
                                <div class="col-md-12 text-right" style="padding-top: 20px;">
                                    <input type="hidden" name="remove_user_id" value="{{$user_info->id}}">
                                    <input type="hidden" name="remove_user_role" value="user">
                                    <button type="button" id="btn_submit_del_user" class="btn-atag">Delete this User</button>
                                </div>
                                {!! Form::close() !!}

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="box-footer"></div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="del_confirm_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="exampleModalLabel">Warning!</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure to delete the account?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="del_sure">Sure</button>
            </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    function permissionOnOff(permission_id, state) {
        myData.sel_permission = permission_id;
        myData.sel_status = state;

        permission_table.ajax.reload(null, false);
    }

    $.fn.dataTable.ext.errMode = "none";
    var myData = {};

    $('#btn_submit_del_user').on('click', function(e){
        e.preventDefault()
        $('#del_confirm_Modal').modal('show')
    })
    $('#del_sure').on('click', function(e){
        $('form#user-remove-form').submit();
        $('#del_confirm_Modal').modal('hide')

    }) 

</script>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

@stop