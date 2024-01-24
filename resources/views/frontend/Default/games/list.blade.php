@extends('frontend.Default.layouts.app')
@section('page-title', $title)
@section('add-body-class', 'locked')
@section('add-header-class', 'header--redirected')

@section('content')


    @php
        if (Auth::check()) {
            $currency = auth()
                ->user()
                ->present()->shop
                ? auth()
                    ->user()
                    ->present()->shop->currency
                : '';
        } else {
            $currency = '';
        }
        $limit = 12;
        $minW = 25;
        $detect = new \Detection\MobileDetect();
        if ($detect->isMobile()) {
            $limit = 12;
            $minW = 40;
        } elseif ($detect->isTablet()) {
            $limit = 12;
        }
        // echo $games;
    @endphp

    <div class="hideMe">
        @if (Auth::check())
            <!--<a href="{{ route('frontend.auth.logout') }}" class="btn btn--logout" style="float: right;position: relative;right: 20px;top: 20px;z-index:9999">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22">
                                                        <path d="M8,21H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1H8"/>
                                                        <polyline points="15 15 19 11 15 7"/><line x1="19" y1="11" x2="7" y2="11"/>
                                                    </svg>
                                                    Exit
                                                </a>-->
        @endif
        <svg id="leftArrow" class="arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <g stroke-linejoin="round" stroke-linecap="round">
                <circle r="46" cx="50" cy="50" />
                <polyline points="60 25, 30 50, 60 75"></polyline>
            </g>
        </svg>
        <svg id="rightArrow" class="arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <g stroke-linejoin="round" stroke-linecap="round">
                <circle r="46" cx="50" cy="50" />
                <polyline points="40 25, 70 50, 40 75"></polyline>
            </g>
        </svg>
        <div id="masterWrap">
            <div id="panelWrap">
                @if ($games && count($games))
                    @foreach ($games as $key => $game)
                        @if ($key % $limit == 0)
                            @if ($key > 0)
                                </section>
                            @endif
                            <section>
                        @endif
                        @include('frontend.Default.partials.game')
                    @endforeach
                    @if (count($games) % $limit != 0)
                        </section>
                    @endif
                @endif
            </div>
        </div>

        {{-- <div class="balancewrap">
        <span id="credit_text" >(1 credit = 1 euro cent)</span>
        @if (isset(auth()->user()->balance) && auth()->user()->balance)
	<span>
        <span id="money_text" class="label label-d label--left" style="font-size: 2.5em;">
            {{ number_format(auth()->user()->balance*100, 0,".","") }}
        </span>
        @else
        <span id="money_text" class="label label-d label--left" style="font-size: 2.5em;">
            0 {{ $currency }}
        </span>
        @endif
    </div> --}}

        <div class="balancewrap">
            <input type="hidden" name="userId" id="userId" value="{{ auth()->user()->id }}" />
            @if (isset(auth()->user()->balance) && auth()->user()->balance)
                @if (auth()->user()->currency == 'EUR')
                    <span style="font-size:1.8vh">1 credit = 1 euro cent</span>
                @else
                    <span style="font-size:1.8vh">100 credits = 1 {{ auth()->user()->currency }}</span>
                @endif
                <span class="label label-d label--left credit_number" style="font-size: 4vh;">
                    <b style="color: yellow" class="user_balance">
                        {{ number_format(auth()->user()->balance * 100, 0, '.', '') }}
                    </b>
                </span>
            @else
                <span class="label label-d label--left credit_number" style="font-size: 3vh;">
                    0 <b style="color: yellow">{{ $currency }}</b>
                </span>
            @endif
        </div>

        <div class="dots">
        </div>
        <div class="flagWrap">
            <img src="/horizontal/img/en.png" class="btn" data-flag="0">
        </div>
    </div>

@endsection

@section('scripts')
    @include('frontend.Default.partials.scripts')
@endsection
