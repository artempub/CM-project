@extends('backend.layouts.user')

@section('content')
<div class="row wow fadeIn">

    <!--Grid column-->
    <div class="col-md-9 ">
        <section class="content-header">
            <div class="panel-heading"><b>Reset</b> </div>
        </section>

        <section class="content">

            <div class="content-box">
                <div class="element-wrapper">
                    <div class="element-box-tp">

                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-sm table-hover dataTable no-footer" style="width:100%" id="user-datatable">
                                        <thead>
                                            <tr>
                                                <th class=" text-center">Shop</th>
                                                <th class="text-success text-center">In</th>
                                                <th class="text-danger text-center">Out</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class=" text-center">{{$shop_name}}</td>
                                                <td class="text-success text-center">{{$shop_total_in}}</td>
                                                <td class="text-danger text-center">{{$shop_total_out}}</td>
                                                <td class=" text-center">{{$shop_total_in - $shop_total_out}}</td>
                                                <td class=" text-center"><button type="button" id="btn-reset" class="btn btn-success " data-id="{{$shop_id}}" onclick="user_reset()">RESET</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

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