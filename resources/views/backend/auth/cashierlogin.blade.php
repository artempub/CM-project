<html lang="en" class="">

<head>
    <meta charset="utf-8">
    <title>Admin | Panel</title>
    <meta name="description" content="Admin || GGinc || BackOffice">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://netbo.gapi.lol/css/v1/login/animate.css" type="text/css">
    <link rel="stylesheet" href="https://netbo.gapi.lol/css/v1/login/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="https://netbo.gapi.lol/css/v1/login/simple-line-icons.css" type="text/css">
    <link rel="stylesheet" href="https://netbo.gapi.lol/css/v1/login/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="https://netbo.gapi.lol/css/v1/login/font.css" type="text/css">
    <link rel="stylesheet" href="https://netbo.gapi.lol/css/v1/login/app.css" type="text/css">
    <link rel="stylesheet" href="/cashier/custom.css">
    <style>
        body {
            padding-top: 58px !important;
            min-height: 903px;
            display: block;
            overflow: visible;
            overflow-x: hidden;
            top: 0;
            left: 0;
            width: 100%;
            position: absolute;
            border: 0;
            background-color: #e6e6e6;
            color: #000;
        }

        .login-header {
            top: 0;
            padding-top: 0;
            background-color: #3388cc;
            border-color: #ddd;
            color: #fff;
            position: absolute;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 1000;
            border-width: 1px 0;
            border-style: solid;
            font-weight: 700;
            /* height: 58.13px; */
        }

        .header-text {
            font-size: 21px;
            min-height: 17.6px;
            text-align: center;
            display: block;
            margin: 0 30%;
            padding: 14.56px 0;
            text-overflow: ellipsis;
            overflow: hidden;
            font-weight: bold;
            white-space: nowrap;
            outline: 0 !important;
            color: #fff;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
            line-height: 1.3;
            font-family: sans-serif;
        }

        .login-container {
            border-width: 0;
            overflow: visible;
            overflow-x: hidden;
            padding: 16px;
        }

        .login-form {
            width: 450px;
            left: 50%;
            margin-left: -224px;
            margin-top: 192px;
            overflow: visible;
            position: relative;
            padding: 6.4px 16px;
            display: block;
            clear: both;
            border-width: 1px;
            border-style: solid;
            background-color: #fff;
            border-color: #ddd;
            color: #333;
            background-clip: padding-box;
            border-radius: 5px;
        }

        .login-form .alert {
            margin-top: 10px;
        }

        .login-container .logo {
            background-image: url(/cashier/logo.png);
            background-position: 50% 50%;
            background-repeat: no-repeat;
            width: 100%;
            height: 65px;
            position: absolute;
            margin-top: -100px;
            margin-left: -16px;
        }

        .list-group-item {
            padding: 0 !important;
            margin: 16px 0;
            border-width: 0;
            display: block;
            position: relative;
            overflow: visible;
            clear: both;
            border-bottom-color: rgba(0, 0, 0, .15);
            border-bottom-style: solid;
            color: #333;
        }

        .list-group-item:before,
        .list-group-item:after {
            content: "";
            display: table;
            clear: both;
        }

        .list-group-item label {
            float: left;
            width: 20%;
            margin: 8px 2% 0 0;
            display: block;
            font-weight: 400;
            font-size: 16px;
            color: #333;
            line-height: 1.3;
            font-family: sans-serif;
        }

        .list-group-item input {
            margin: 0;
            min-height: 37.2px;
            text-align: left;
            border: 0;
            background: transparent none;
            padding: 6.4px;
            line-height: 22.4px;
            display: block;
            width: 100%;
            box-sizing: border-box;
            outline: 0;
            float: left;
            width: 78%;
            border: 1px solid #ddd;
        }

        .login-button {
            background-color: #38c;
            border-color: #38c;
            color: #fff;
            border-radius: 5px;
            font-weight: 700;
            font-size: 16px;
            margin: 8px 0;
            padding: 10.2px 16px;
            display: block;
            position: relative;
            text-align: center;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            box-shadow: 0 1px 3px rgb(0 0 0 / 15%);
            border-width: 1px;
            border-style: solid;
            line-height: 1.3;
            font-family: sans-serif;
        }
    </style>
</head>

<body>
    <div class="login-header">
        <h1 class="header-text">Crediting Tool</h1>
    </div>
    <div class="login-container">
        <div class="login-form">
            <div class="logo"></div>
            @include('backend.partials.messages')
            <form name="form" method="post" class="form-validation" action="<?= route('cashier.auth.login.post') ?>">
                <input type="hidden" value="<?= csrf_token() ?>" name="_token">
                {{-- <div class="text-danger wrapper text-center" ng-show="authError">
                </div> --}}
                <div class="list-group list-group-sm">
                    <div class="list-group-item">
                        <label for="username">User name:</label>
                        <input type="text" placeholder="" name="username" class="form-control no-border" required="">
                    </div>
                    <div class="list-group-item">
                        <label for="password">Password:</label>
                        <input type="password" placeholder="*******" name="password" class="form-control no-border" required="">
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-primary btn-block login-button">Sign Up</button>
            </form>
        </div>
    </div>
</body>

</html>

@section('scripts')
{!! JsValidator::formRequest('VanguardLTE\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop