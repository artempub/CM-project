@extends('frontend.Default.layouts.app')

@section('page-title', trans('app.login'))
@section('add-main-class', 'main-login')

@section('content')

<style>

.usr-txt {
	position: absolute;
	margin-left:5px;
	margin-top:-116px!important;
	font-size:22px;
	color:white;
	
	
}
.pwd-txt {
	position: absolute;
	margin-left:5px;
	margin-top:-113px !important;
	font-size:22px;
	color:white;
	
	
}
</style>

    <!-- MAIN -->

        <!-- LOGIN BEGIN -->
        <div class="login">
		
            <div class="login__block">
                <div class="login__left">
                    <form action="<?= route('frontend.auth.login.post') ?>" class="login-form" method="POST">
                        @csrf
						
                        <div class="input__group">
						<span class= "usr-txt">Username:</span>
                            <input type="text" id="inputUser" placeholder="@lang('app.email_or_username')" class="info-input" name="username">
                        </div>
                        <div class="input__group">
							<span class= "pwd-txt">Password:</span>
                            <input type="password" id="inputPass" placeholder="@lang('app.password')" class="info-input" name="password">
                        </div>
					
                        <div class="input__group" style="width: 150px">
                            <button type="submit" id="submit" class="button-f btn" style="margin-top: -100px">Enter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		
        <!-- LOGIN END -->
    <!-- /.MAIN -->

    @if(isset ($errors) && count($errors) > 0)
        <div class="notification">
            <div class="notification__message notification__message_failed _active">
                <img src="/frontend/Default/img/svg/!.svg" alt="">
                <p class="notification__title">Error</p>
                <p class="notification__text">
                    @foreach($errors->all() as $error)
                        {!!  $error  !!}<br>
                    @endforeach
                </p>
                <button class="notification__close">&times;</button>
            </div>
        </div>
    @endif

@stop

@section('scripts')
    <script type="text/javascript">
        setTimeout(function () {
            $('.notification__message_failed').removeClass('_active');
        }, 3000);
    </script>
  {!! JsValidator::formRequest('VanguardLTE\Http\Requests\Auth\LoginRequest', '#login-form') !!}
@stop
