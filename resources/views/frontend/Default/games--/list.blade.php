@extends('frontend.Default.layouts.app')
@section('page-title', $title)
@section('add-body-class', 'locked')
@section('add-header-class', 'header--redirected')

@section('content')


	@php
        if(Auth::check()){
            $currency = auth()->user()->present()->shop ? auth()->user()->present()->shop->currency : '';
        } else{
            $currency = '';
        }
	@endphp

    @include('frontend.Default.partials.header')

    <div class="container">
        <!-- SLIDER - BEGIN -->
        <div class="grid">
            <div class="grid-item grid-item--width3 @if($tournament) grid-item--height5 @endif">
                <div class="grid__content">
                    <!-- JACKPOT - BEGIN -->
                    <div class="jackpot jackpot--value jackpot--action">
							<span class="jackpot__label">
								Jackpot
							</span>
                        <div class="jackpot--title">
                            <h2 class="jackpot__value jackpot__value--title jackpotSum">
                                {{ number_format($jpgSum, 2,".","") }}
                            </h2>
                        </div>
                    </div>

                    <div class="jackpot-prizes">
                        @if( count($jpgs) )
                            @foreach($jpgs AS $index=>$jpg)
                                <div class="jackpot-prizes__item">
                                    <p class="jackpot__value jackpot{{ $index }}">{{ number_format($jpg->balance, 2,".","") }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <!-- JACKPOT - BEGIN -->

                    @if($tournament)
                    <div class="tournament">
                        <div class="tournament__cup">
                            <img src="" alt="">
                        </div>
                        <span class="tournament__status
						@if( $tournament->is_waiting() ) _soon @elseif( $tournament->is_completed() ) _completed @else _active @endif
                            "></span>
                        <p class="tournament__title">
                            <img src="/frontend/Default/img/svg/tournament-cup.svg" alt="">
                            Current tournament
                        </p>
                        <div class="tournament__img">
                            <img class="lazy" data-src="{{ '/storage/tournaments/' . $tournament->image }}" style="width: 100%;">
                            <div class="tournament__info">
                                <div class="tournament__info-name">{{ $tournament->name }}</div>
                                <a href="{{ route('frontend.tournaments') }}" class="tournament__info-btn btn"></a>
                            </div>
                        </div>
                        <div class="tournament__time">
                            <div class="tournament__time-item">
                                @if( $tournament->is_waiting() )
                                    <span class="tournament__time-top">Time to start:</span>
                                    <span class="tournament__time-val _time countdown" data-date="{{ $tournament->start }}"></span>
                                @elseif( $tournament->is_completed() )
                                    <span class="tournament__time-top">End:</span>
                                    <span class="tournament__time-val _time">00:00</span>
                                @else
                                    <span class="tournament__time-top">Time left:</span>
                                    <span class="tournament__time-val _time countdown" data-date="{{ $tournament->end }}"></span>
                                @endif
                            </div>
                            <div class="tournament__time-item">
								<span class="tournament__time-top">Prize fund:</span>
                                <span class="tournament__time-val">{{ number_format($tournament->sum_prizes, 2,".","") }} {{ $currency }}</span>
                            </div>
                        </div>
                        <div class="tournament__table">
                            <div class="tournament__table-head">
                                <span class="tournament__table-head-item">№</span>
                                <span class="tournament__table-head-item">Login</span>
                                <span class="tournament__table-head-item">Point</span>
                            </div>
                            <div class="tournament__table-body" data-simplebar>
                                @if( count($tournament->stats) )
                                    @php $index=1; @endphp
                                    @foreach($tournament->get_stats(0, 10, true) AS $stat)
                                        <div class="tournament__table-row">
                                            <span class="tournament__table-item">{{ $index }}</span>
                                            <span class="tournament__table-item">{{ $stat['username'] }}</span>
                                            <span class="tournament__table-item">{{ $stat['points'] }}</span>
                                        </div>
                                        @php $index++; @endphp
                                    @endforeach
                                @else
                                    <div class="tournament__table-row">
                                        <span class="tournament__table-item">@lang('app.no_data')</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- GAMES - BEGIN -->


            <div id="games">
                @if ($games && count($games))
                    @foreach ($games as $key=>$game)
                        @include('frontend.Default.partials.game')
                    @endforeach
                @endif
            </div>



        </div>
    </div>



@endsection

@section('footer')
	@include('frontend.Default.partials.footer')
@endsection

@section('scripts')
	@include('frontend.Default.partials.scripts')
@endsection
