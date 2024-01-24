@extends('backend.layouts.app')

@section('content')

<section class="content-header">
    @include('backend.partials.messages')
</section>

<section class="content">

    <div class="box box-default panel-body">
        <div class="box-header with-border">
            <div class="panel-heading"><b>Profile -- </b> {{$user_info->username}}</div>

        </div>

        <div class="box-body table-responsive">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="shop_name">Username</label>
                        <input type="text" class="form-control" name="operator_name" value="{{$user_info->username}}" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="shop_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <!-- <div class="help-block form-text text-muted form-control-feedback">
                        Minimum of 6 characters
                        </div> -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="shop_password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        <!-- <div class="help-block form-text text-muted form-control-feedback">
                        Minimum of 6 characters
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-buttons-w text-right " style="padding-right: 20px;">
                    <button class="btn btn-primary" id="btn-save-password" type="button" disabled> Save New Password</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="shop_password">Timezone</label>
                        <select name="shop_timezone" class="form-control" id="new_timezone">
                            @foreach($timezones as $timezone)
                            <option value="{{$timezone->name}}" {{$timezone->name == $operator_timezone ? 'selected':''}}>{{$timezone->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
            </div>
            <br>
            <div class="row">
                <div class="form-buttons-w text-right " style="padding-right: 20px;">
                    <button class="btn btn-primary" id="btn-save-timezone" type="button"> Save Timezone</button>
                </div>
            </div>
        </div>
        <div class="box-footer"></div>
    </div>
</section>
@stop

@section('scripts')
<script src="https://cdn.rawgit.com/alertifyjs/alertify.js/v1.0.10/dist/js/alertify.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $('#tn-save-password').on('click', function() {
        var new_password = $('#new_password').val()
        console.log(new_password)
        var confirm_password = $('#confirm_password').val()
        if (new_password != confirm_password) {
            alert('Password do not match. Please retype again!')
            return
        }
        $.ajax({
            type: 'POST',
            url: "{{ route('backend.shop.detailpost') }}",
            data: {
                'new_password': new_password
            },
            statusCode: {
                429: function(msg) {
                    alertify.error(ERROR + ' Too Many Requests', 'success', 5); // show alert
                },
                400: function(msg) {
                    alertify.error(ERROR, 'success', 5); // show alert
                },
                200: function(response) {
                    // alertify()->success('USER WAS CREATED.')->delay(6000)->position('bottom right');
                    if (response.status == 'success') {
                        console.log('old_pw_hashed: ', response.old_pw_hashed)
                        console.log('new_pw: ', response.new_pw)
                        console.log('new_pw_hashed: ', response.new_pw_hashed)
                        alert('Successfully Updated!')
                    } else {
                        console.log(response.status)
                        alert('do not match')
                    }
                }
            },
        });

    })

    $('#tn-save-timezone').on('click', function() {
        var new_timezone = $('#new_timezone').val();
        $.ajax({
            type: 'POST',
            url: "{{ route('backend.operator.edit_operator_profile') }}",
            data: {
                'timezone': new_timezone
            },
            statusCode: {
                429: function(msg) {
                    alertify.error(ERROR + ' Too Many Requests', 'success', 5); // show alert
                },
                400: function(msg) {
                    alertify.error(ERROR, 'success', 5); // show alert
                },
                200: function(response) {
                    // alertify()->success('USER WAS CREATED.')->delay(6000)->position('bottom right');
                    alert('Successfully Reset the Timezone');
                    console.log(response.timezone)
                    $('#new_timezone').val(response.timezone);

                }
            },
        });
    })
</script>
@stop