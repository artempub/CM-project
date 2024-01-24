<html lang="en" class="">
<head>
    <meta charset="utf-8">
    <title>Admin | Panel</title>
    <meta name="description" content="Admin || GGinc || BackOffice">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/back/bower_components/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 10vh;width:350px!important;">
        <p class="text-center" style="font-size: 20px;font-weight: bold;">Back Office</p>
        @include('backend.partials.messages')
        <form name="form" method="post" class="form-validation" action="<?= route('backend.auth.login.post') ?>">
            <input type="hidden" value="<?= csrf_token() ?>" name="_token">
            <div class="text-danger wrapper text-center" ng-show="authError">
            </div>
            <div class="list-group list-group-sm">
                <div class="list-group-item">
                    <input type="text" placeholder="Username" name="username" class="form-control no-border"
                        required="">
                </div>
                <div class="list-group-item">
                    <input type="password" placeholder="Password" name="password" class="form-control no-border"
                        required="">
                </div>
            </div>
            <button type="submit" class="btn btn-lg btn-primary btn-block">Log in</button>
            <div class="line line-dashed"></div>
        </form>
   </div>
                      <div class="text-center">
                <p>
                    <span class="text-center"><a href="https://t.me/Goldsvet_Workshop"/>Goldsvet Workshop and Casino customisations! <br><a href="https://t.me/goldsvetworkshop"/>**check for updates**</span>
                </p>
            </div>
</div>
</body>
<script src="/back/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/back/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</html>
@section('scripts')
    {!! JsValidator::formRequest('VanguardLTE\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop
