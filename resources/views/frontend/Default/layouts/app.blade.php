<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('page-title') - {{ settings('app_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('page-title') - {{ settings('app_name') }}">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" href="/frontend/Default/img/favicon.png">
    <meta property="og:image" content="/frontend/Default/img/vladA.png">
    <link rel="stylesheet" href="/frontend/Default/css/slick.css">
    <link rel="stylesheet" href="/frontend/Default/css/simplebar.css">
    <link rel="stylesheet" href="/frontend/Default/css/styles.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/horizontal/style.css">

</head>

<body class="@yield('add-body-class')">
    <script type="text/javascript">
        var is_games_page =
            @if (isset($is_game_page))
                true
            @else
                false
            @endif ;
        var terms_and_conditions =
            @if (Auth::check() &&
                    auth()->user()->shop &&
                    auth()->user()->shop->rules_terms_and_conditions &&
                    !auth()->user()->agreed)
                true
            @else
                false
            @endif ;
    </script>

    <!-- MAIN -->
    <main class="main @yield('add-main-class')">
        @yield('content')
    </main>
    <!-- /.MAIN -->

    @yield('footer')

    <!-- SCRIPTS -->
    @if (Auth::check())
        <div class="preloader">
            <div style="transform: scale(0.75);width: 100%;height: 100%;">
                <img class="preloader_image" src="/frontend/Default/img/_src/logo.jpg" alt="preloader image">
                <div class="container progress-container" style="width:60%">
                    <h4 class="progress-bar-percent">LOADING&nbsp;&nbsp;...&nbsp;<span
                            class="progress-bar-percent-count">0%</span></h4>
                    <div class="progress" style="padding:0 !important;height:15px;">
                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"
                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0">
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div id="preloader_progress" class="progress preloader_status">
		<div style="display: flex;margin-left:40%;">Loading ... <span id="progress_num">10%</span></div>
		<div id="bar_txt" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
		</div>
	  </div> --}}
        </div>
    @endif
    <script src="/frontend/Default/js/jquery-3.4.1.min.js"></script>
    <script src="/frontend/Default/js/jquery.inputmask.bundle.min.js"></script>
    <script src="/frontend/Default/js/simplebar.min.js"></script>
    <script src="/frontend/Default/js/slick.min.js"></script>
    <script src="/back/bower_components/moment/min/moment.min.js"></script>
    <script src="/back/bower_components/moment/min/moment-timezone-with-data-1970-2030.min.js"></script>
    <script src="/frontend/Default/js/countdown.min.js"></script>
    <script src="/frontend/Default/js/moment-countdown.min.js"></script>
    <script src="/frontend/Default/js/lazyload.min.js"></script>

    <script type="text/javascript">
        $(function() {
            moment.tz.setDefault("{{ config('app.timezone') }}");
        });
    </script>

    <script type="text/javascript">
        $(function() {
            setInterval(function() {
                $.get('/refresh-csrf').done(function(data) {
                    $('[name="csrf-token"]').attr('content', data);
                    $('[name="_token"]').val(data);
                });
            }, 5000);

        });
        var lazyLoadInstance = new LazyLoad({
            // Your custom settings go here
        });
    </script>

    <script src="/frontend/Default/js/custom.js"></script>

    @yield('scripts')

</body>

</html>
