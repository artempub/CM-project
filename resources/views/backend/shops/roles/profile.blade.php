@extends('backend.layouts.user')

@section('content')
<div class="row wow fadeIn">

    <!--Grid column-->
    <div class="col-md-9 ">
        <section class="content-header">
            <div class="panel-heading"><b>Profile</b> </div>
        </section>

        <section class="content">

            <div class="content-box">
                <div class="element-wrapper">
                    <div class="element-box-tp">

                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="shop_name">Username</label>
                                        <input type="text" class="form-control" id="shop_name" name="shop_name" value="{{$shop_name}}">
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="shop_password">Current Password</label>
                        <input type="password" class="form-control" name="shop_password" placeholder="current-password" disabled>
                    </div>
                </div> -->
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
                            <!-- <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="shop_password">Timezone</label>
                                        <select name="shop_timezone" class="form-control" id="shop_timezone">
                                            <option value="Africa/Abidjan">Africa/Abidjan</option>
                                            <option value="Africa/Accra">Africa/Accra</option>
                                            <option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox">
                                    <label style="font-size: 1.4em">
                                        &nbsp; After credits IN print receipt?
                                    </label>
                                </div>
                            </div> -->
                            <br>
                            <div class="">
                                <div class="form-buttons-w text-right " style="padding-right: 20px;">
                                    <button class="btn btn-primary" id="btn-save-new-password" type="button"> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <!--Grid column-->

</div>

@stop

@section('scripts')
<script src="https://cdn.rawgit.com/alertifyjs/alertify.js/v1.0.10/dist/js/alertify.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    $('#btn-save-new-password').on('click', function() {
        var new_username = $('#shop_name').val()
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
                'username': new_username,
                'password': new_password
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
                        alert('Successfully Updated!')
                    } else {
                        console.log(response.status)
                        alert('do not match')
                    }
                }
            },
        });

    })

    function user_reset() {
        var shop_id = $('#btm-reset').data('id');
        $.ajax({
            type: 'POST',
            url: "{{ route('backend.shop.resetpost') }}",
            data: {
                'shop_id': shop_id
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
                    alert('Successfully Reset!');
                }
            },
        });
    }
</script>
@stop