@php



    if(Auth::check()){

            $refund = false;
            $daily_entry = false;
            if( auth()->user()->shop && auth()->user()->shop->progress_active ){
                $refund = \VanguardLTE\Progress::where(['shop_id' => auth()->user()->shop_id, 'rating' => auth()->user()->rating, 'status' => 1])->first();
                $daily_entry = \VanguardLTE\Progress::where([
                        'shop_id' => auth()->user()->shop_id,
                        'day' => date('l'),
                        'rating' => auth()->user()->rating,
                        ])->first();
            }

            if(  auth()->user()->shop && auth()->user()->shop->invite_active ){
                $invite = \VanguardLTE\Invite::where(['shop_id' => auth()->user()->shop_id])->first();
            } else {
                $invite = false;
            }
            $sms_bonus = \VanguardLTE\SMSBonusItem::where(['user_id' => auth()->user()->id, 'status' => 0])->orderBy('id', 'DESC')->first();

            $currency = auth()->user()->present()->shop ? auth()->user()->present()->shop->currency : '';
    } else{
            $daily_entry = false;
            $refund = false;
            $invite = false;
            $sms_bonus = false;
            $currency = '';
    }

    $rules = \VanguardLTE\Rule::get();

@endphp

<footer class="footer">
    <div class="container">
        <div class="footer__block">
            <div class="footer__item footer__item--left">
                <div class="footer__item-acc">
                    <div class="footer__item-acc-img">
                        @if( auth()->user()->shop && auth()->user()->shop->progress_active )
                            <a href="{{ route('frontend.progress') }}">
                                <img src="/frontend/Default/img/badges64x64/badge-{{ auth()->user()->badge() }}.png" class="rating" >
                            </a>
                        @else
                            <img src="/frontend/Default/img/badges64x64/badge-{{ auth()->user()->badge() }}.png" class="rating" >
                        @endif
                        <div class="footer__item-acc-rating">{{ auth()->user()->username }}</div>
                    </div>
                    <ul class="footer__item-acc-info">
                        <li class="tooltip-btn balanceMenu">
                            <span class="info-icon @if(auth()->user()->balance == 0) _disabled @endif"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 19.67"><path d="M13.45.08a2,2,0,0,1,2.47,1.37A2,2,0,0,1,16,2V3.67h2a2,2,0,0,1,2,2v12a2,2,0,0,1-2,2H2a2,2,0,0,1-2-2H0a1.66,1.66,0,0,1,0-.32V5.43A2,2,0,0,1,1.45,3.51ZM8.14,17.67H18v-8H16v4.25a2,2,0,0,1-1.45,1.92ZM18,5.67v2H16v-2ZM2,5.43V17.35l12-3.43V2ZM12,7.67a1,1,0,1,1-1-1A1,1,0,0,1,12,7.67Z"/></svg></span>
                            <span class="info-value balance">{{ number_format(auth()->user()->balance, 2,".","") }} {{ $currency }}</span>
                        </li>
                        <li class="tooltip-btn bonusMenu">
                            @if(
                                auth() ->user()->tournaments > 0 || auth() ->user()->happyhours > 0 || auth() ->user()->refunds > 0 ||
                                auth() ->user()->progress > 0 || auth() ->user()->daily_entries > 0 || auth() ->user()->invite > 0 ||
                                auth() ->user()->welcomebonus > 0 || auth() ->user()->smsbonus > 0 || auth() ->user()->wheelfortune > 0
                            )
                                <span class="tooltip-item">
                                    @if(auth() ->user()->tournaments > 0)<p>Tournaments = {{ number_format(auth() ->user()->tournaments, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->happyhours > 0)<p>Happy Hours = {{ number_format(auth() ->user()->happyhours, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->refunds > 0)<p>Refund = {{ number_format(auth() ->user()->refunds, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->progress > 0)<p>Progress Bonus = {{ number_format(auth() ->user()->progress, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->daily_entries > 0)<p>Daily Entries = {{ number_format(auth() ->user()->daily_entries, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->invite > 0)<p>Invite Bonus = {{ number_format(auth() ->user()->invite, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->welcomebonus > 0)<p>Welcome Bonus = {{ number_format(auth() ->user()->welcomebonus, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->smsbonus > 0)<p>SMS Bonus = {{ number_format(auth() ->user()->smsbonus, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->wheelfortune > 0)<p>Wheel Fortune = {{ number_format(auth() ->user()->wheelfortune, 2,".","") }}</p>@endif
                                </span>
                                <span class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M3.5,5A3.75,3.75,0,0,1,3,3,3,3,0,0,1,6,0a4.36,4.36,0,0,1,4,3.11A4.36,4.36,0,0,1,14,0a3,3,0,0,1,3,3,3.75,3.75,0,0,1-.5,2H18a2,2,0,0,1,2,2V9a2,2,0,0,1-2,2v7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V11A2,2,0,0,1,0,9V7A2,2,0,0,1,2,5ZM9,7H2V9H9Zm9,2H11V7h7ZM9,18V11H4v7Zm7,0H11V11h5ZM6,2A1,1,0,0,0,5,3C5,4.25,6,4.85,8.43,5,8.16,3.11,7.16,2,6,2Zm5.5,3c.27-1.86,1.27-3,2.43-3a1,1,0,0,1,1,1C14.93,4.25,13.91,4.85,11.5,5Z"/></svg></span>
                            @else
                                <span class="tooltip-item" style="display: none;"></span>
                                <span class="info-icon _disabled"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M3.5,5A3.75,3.75,0,0,1,3,3,3,3,0,0,1,6,0a4.36,4.36,0,0,1,4,3.11A4.36,4.36,0,0,1,14,0a3,3,0,0,1,3,3,3.75,3.75,0,0,1-.5,2H18a2,2,0,0,1,2,2V9a2,2,0,0,1-2,2v7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V11A2,2,0,0,1,0,9V7A2,2,0,0,1,2,5ZM9,7H2V9H9Zm9,2H11V7h7ZM9,18V11H4v7Zm7,0H11V11h5ZM6,2A1,1,0,0,0,5,3C5,4.25,6,4.85,8.43,5,8.16,3.11,7.16,2,6,2Zm5.5,3c.27-1.86,1.27-3,2.43-3a1,1,0,0,1,1,1C14.93,4.25,13.91,4.85,11.5,5Z"/></svg></span>
                            @endif
                            <span class="info-value">{{ number_format( (auth() ->user()->tournaments + auth() ->user()->happyhours + auth()->user()->refunds + auth() ->user()->progress + auth() ->user()->daily_entries + auth() ->user()->invite + auth() ->user()->welcomebonus + auth() ->user()->smsbonus + auth() ->user()->wheelfortune), 2,".","") }} {{ $currency }}</span>
                        </li>
                        <li class="tooltip-btn wagerMenu">
                            @if(
                                auth() ->user()->count_tournaments > 0 || auth() ->user()->count_happyhours > 0 || auth() ->user()->count_refunds > 0 ||
                                auth() ->user()->count_progress > 0 || auth() ->user()->count_daily_entries > 0 || auth() ->user()->count_invite > 0 ||
                                auth() ->user()->count_welcomebonus > 0 || auth() ->user()->count_smsbonus > 0 || auth() ->user()->count_wheelfortune > 0
                            )
                                <span class="tooltip-item">
                                    @if(auth() ->user()->count_tournaments > 0)<p>Tournaments = {{ number_format(auth() ->user()->count_tournaments, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_happyhours > 0)<p>Happy Hours = {{ number_format(auth() ->user()->count_happyhours, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_refunds > 0)<p>Refund = {{ number_format(auth() ->user()->count_refunds, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_progress > 0)<p>Progress Bonus = {{ number_format(auth() ->user()->count_progress, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_daily_entries > 0)<p>Daily Entries = {{ number_format(auth() ->user()->count_daily_entries, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_invite > 0)<p>Invite Bonus = {{ number_format(auth() ->user()->count_invite, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_welcomebonus > 0)<p>Welcome Bonus = {{ number_format(auth() ->user()->count_welcomebonus, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_smsbonus > 0)<p>SMS Bonus = {{ number_format(auth() ->user()->count_smsbonus, 2,".","") }}</p>@endif
                                    @if(auth() ->user()->count_wheelfortune > 0)<p>Wheel Fortune = {{ number_format(auth() ->user()->count_wheelfortune, 2,".","") }}</p>@endif
                                </span>
                                <span class="info-icon ">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22"><path d="M0,11A11,11,0,1,0,11,0,11,11,0,0,0,0,11Zm20,0a9,9,0,1,1-9-9A9,9,0,0,1,20,11ZM6.58,16.94,7.43,12,3.85,8.53l4.94-.71L11,3.34l2.21,4.48,4.94.71L14.57,12l.85,4.92L11,14.62Zm5.85-5.62.33,2L11,12.36l-1.76.92.33-2L8.15,9.93l2-.29L11,7.86l.88,1.78,2,.29Z"/></svg>
                                </span>
                            @else
                                <span class="tooltip-item" style="display: none;"></span>
                                <span class="info-icon _disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22"><path d="M0,11A11,11,0,1,0,11,0,11,11,0,0,0,0,11Zm20,0a9,9,0,1,1-9-9A9,9,0,0,1,20,11ZM6.58,16.94,7.43,12,3.85,8.53l4.94-.71L11,3.34l2.21,4.48,4.94.71L14.57,12l.85,4.92L11,14.62Zm5.85-5.62.33,2L11,12.36l-1.76.92.33-2L8.15,9.93l2-.29L11,7.86l.88,1.78,2,.29Z"/></svg>
                                </span>
                            @endif
                            <span class="info-value">{{ number_format( (auth() ->user()->count_tournaments + auth() ->user()->count_happyhours + auth()->user()->count_refunds + auth() ->user()->count_progress + auth() ->user()->count_daily_entries + auth() ->user()->count_invite  + auth() ->user()->count_welcomebonus + auth() ->user()->count_smsbonus + auth() ->user()->count_wheelfortune), 2,".","") }} {{ $currency }}</span>
                        </li>

                        <li class="tooltip-btn refunds-icon">
                            @if ( $refund && auth()->user()->present()->refunds > 0 && auth()->user()->present()->balance <= $refund->min_balance )
                                <span class="tooltip-item"><p>Refund</p></span>
                                <span class="info-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10,2a7,7,0,0,1,5.81,3H12V7h7V0H17V3.27A8.92,8.92,0,0,0,10,0,10,10,0,0,0,0,10H2A8,8,0,0,1,10,2Zm0,16a7,7,0,0,1-5.81-3H8V13H1v7H3V16.73A8.92,8.92,0,0,0,10,20,10,10,0,0,0,20,10H18A8,8,0,0,1,10,18Z"/></svg>
                                </span>
                                <span class="info-value refunds" id="refunds">{{ number_format(auth()->user()->refunds, 2,".","") }} {{ $currency }}</span>
                            @else
                                <span class="info-icon _disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10,2a7,7,0,0,1,5.81,3H12V7h7V0H17V3.27A8.92,8.92,0,0,0,10,0,10,10,0,0,0,0,10H2A8,8,0,0,1,10,2Zm0,16a7,7,0,0,1-5.81-3H8V13H1v7H3V16.73A8.92,8.92,0,0,0,10,20,10,10,0,0,0,20,10H18A8,8,0,0,1,10,18Z"/></svg>
                                </span>
                                <span class="info-value refunds">{{ number_format(auth()->user()->refunds, 2,".","") }} {{ $currency }}</span>
                            @endif
                        </li>

                    </ul>
                </div>
            </div>
            <div class="footer__item">
                <div class="footer__item-tabs">

                    <a href="#" data-name="modal-kassa" class="footer__item-tab paymentsMenu
                            @if(
                                settings('payment_interkassa') && \VanguardLTE\Lib\Setting::is_available('interkassa', auth()->user()->shop_id) ||
                                settings('payment_coinbase') && \VanguardLTE\Lib\Setting::is_available('coinbase', auth()->user()->shop_id) ||
                                settings('payment_btcpayserver') && \VanguardLTE\Lib\Setting::is_available('btcpayserver', auth()->user()->shop_id) ||
                                settings('payment_pin')
                            ) modal-btn _active @endif
                        ">
                        <div class="footer__item-tab-img"><img src="/frontend/Default/img/svg/bit-icon.svg" alt=""></div>
                    </a>

                    @php
                        $rewards = \VanguardLTE\Reward::where(['user_id' => auth()->user()->id, 'user_received' => 0, 'activated' => 1])->get();
                    @endphp

                    <a href="javascript:;"
                       @if( auth()->user()->phone_verified )
                           @if( auth()->user()->shop && auth()->user()->shop->invite_active )
                               @if(auth()->user()->agreed)
                               data-name="modal-invite-1"
                               @else(!auth()->user()->agreed)
                               data-name="modal-invite"
                               @endif
                           @endif
                       @else
                           data-name="modal-invite-3"
                       @endif
                       class="footer__item-tab inviteMenu @if( auth()->user()->shop && auth()->user()->shop->invite_active ) _active  modal-btn @if( count($rewards) ) tooltip-btn @endif @endif">
                        <div class="footer__item-tab-img"><img src="/frontend/Default/img/svg/message-icon.svg" alt=""></div>

                        @if( count($rewards) )
                            <span class="tooltip-item" style="display: block !important;">
                                <p>
                                    @foreach($rewards AS $reward)
                                        Bonus: {{ number_format($reward->sum, 2,".","") }}<br>
                                    @endforeach
                                </p>
                            </span>
                        @endif
                    </a>

                    <a href="javascript:;" class="footer__item-tab dailyEntryMenu @if(($daily_entry && \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(auth()->user()->last_daily_entry), false)) || $sms_bonus)_active @endif " id="daily_entry">
                        <div class="footer__item-tab-img"><img src="/frontend/Default/img/svg/gift-icon2.svg" alt=""></div>
                    </a>

                    <a href="#" class="footer__item-tab" >
                        <div class="footer__item-tab-img">
                            <img src="/frontend/Default/img/svg/fortuna.svg" alt="">
                        </div>
                       <!-- <span class="tooltip-item"><p>text 1</p></span> -->
                    </a>


                    <a href="#" data-name="#" class="footer__item-tab">
                        <div class="footer__item-tab-img"><img src="/frontend/Default/img/svg/diamond-icon.svg" alt=""></div>
                       <!-- <span class="tooltip-item"><p>text 2</p></span> -->
                    </a>

                </div>
            </div>

            <div class="footer__item footer__item--right">
                <div class="footer__item-search">
                    <span class="search-wrap"><input type="text" placeholder="Search" class="search"></span>
                </div>

                @if(
                    settings('contact_form_active') ||
                    \VanguardLTE\Faq::count() > 0 ||
                    auth()->user()->shop->getBonusesList() ||
                    count($rules) && auth()->user()->shop->hasActiveRules()
                )
                <div class="footer__item-burger">
                    <button class="footer-menu">
                        <span></span>
                    </button>
                    <ul class="footer-menu__list tooltip-item">
                        @if( settings('contact_form_active') )
                        <li class="footer-menu__list-item">
                            <a href="#" class="footer-menu__list-link modal-btn" data-name="modal-contact">Contact Form</a>
                        </li>
                        @endif
                        @if( \VanguardLTE\Faq::count() > 0 )
                        <li class="footer-menu__list-item">
                            <a href="{{ route('frontend.faq') }}" class="footer-menu__list-link ">FAQ</a>
                        </li>
                        @endif
                        @if( auth()->user()->shop->getBonusesList() )
                            <li class="footer-menu__list-item">
                                <a href="{{ route('frontend.bonuses') }}" class="footer-menu__list-link ">Bonuses</a>
                            </li>
                        @endif
                        @if( count($rules) && auth()->user()->shop->hasActiveRules())
                            @foreach($rules AS $rule)
                                @if(auth()->user()->shop->{'rules_'.$rule->href})
                                    <li class="footer-menu__list-item">
                                        <a href="#" class="footer-menu__list-link modal-btn" data-name="modal-{{ $rule->href }}">{{ $rule->title }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
                @endif

                <a href="{{ route('frontend.auth.logout') }}" class="btn btn--logout">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22">
                        <path d="M8,21H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1H8"/>
                        <polyline points="15 15 19 11 15 7"/><line x1="19" y1="11" x2="7" y2="11"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<div class="notification">
    <div class="notification__message notification__message_failed">
        <img src="/frontend/Default/img/svg/!.svg" alt="">
        <p class="notification__title">Error</p>
        <p class="notification__text">Something went worng!</p>
        <button class="notification__close">&times;</button>
    </div>
    <div class="notification__message notification__message_success">
        <img src="/frontend/Default/img/svg/check.svg" alt="">
        <p class="notification__text">I am done successfully!</p>
        <button class="notification__close">&times;</button>
    </div>
</div>

<div class="overlay"></div>

<!-- MODAL-CONTACT -->
<div class="modal modal-contact">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">Contact Form</h3>
            <p class="modal__text">If you have any questions or comments, you can send us a letter at any time convenient for you.</p>
            <form class="form form-text">
                <div class="modal__textarea">
                    <textarea id="messageContactForm"></textarea>
                </div>
                <div class="modal__btn">
                    <a href="#" class="btn" id="sendContactForm">Send</a>
                </div>
            </form>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-CONTACT END-->

<!-- MODAL-BONUS -->
<div class="modal modal-bonus modal-pin">
    <div class="modal__body">
        <div class="modal__content">
            <div class="modal__invite">
                <h3 class="modal__title">Heading Heading Heading</h3>
                <p class="modal__text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis. </p>
                <button class="btn modal-close">Close</button>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-BONUS END -->

<!-- MODAL-LOOT -->
<div class="modal modal-loot">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">My Lootboxes</h3>
            <div class="modal-slider-loot">
                <div class="modal__slider">
                    <div class="modal__slider-slide">
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal__slider">
                    <div class="modal__slider-slide">
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                        <div class="modal__slider-item">
                            <div class="modal__slider-row">
                                <div class="modal__slider-img">
                                    <img src="/frontend/Default/img/badges64x64/badge-01.png" alt="">
                                    <span class="modal__slider-text">+1 Box Cammon</span>
                                </div>
                                <a href="javascript:;" class="btn">OK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-LOOT END -->

<!-- MODAL-KASSA -->
<div class="modal modal-kassa">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">CashBox</h3>
            <div class="modal__kassa">

                <div class="modal__kassa-row">

                    @if( settings('payment_interkassa') && \VanguardLTE\Lib\Setting::is_available('interkassa', auth()->user()->shop_id) )

                        @php $interkassa = \VanguardLTE\Lib\Interkassa::get_systems(auth()->user()->id, auth()->user()->shop_id); @endphp

                        @if( isset($interkassa['success']) && count($interkassa['systems']) )
                            @foreach($interkassa['systems'] AS $system)

                                {!! Form::open(['route' => 'frontend.balance.post', 'method' => 'POST']) !!}
                                <div class="modal__kassa-row-item">
                                    <div class="modal__kassa-row-img">
                                        <img src="/frontend/Default/img/_src/logo-{{ $system['ps'] }}.png" alt="">
                                    </div>
                                    <div class="modal__kassa-row-input">
                                        <input type="text" placeholder="Enter the amount" name="summ">
                                    </div>
                                    <div class="modal__kassa-row-btn">
                                        <input type="hidden" name="system" value="interkassa_{{ $system['als'] }}">
                                        <button class="btn">PAY</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}

                            @endforeach
                        @endif

                    @endif

                    @if( settings('payment_coinbase') && \VanguardLTE\Lib\Setting::is_available('coinbase', auth()->user()->shop_id) )
                        {!! Form::open(['route' => 'frontend.balance.post', 'method' => 'POST']) !!}
                        <div class="modal__kassa-row-item">
                            <div class="modal__kassa-row-img">
                                <img src="/frontend/Default/img/_src/logo-kassa-2.png" alt="">
                            </div>
                            <div class="modal__kassa-row-input">
                                <input type="text" placeholder="Enter the amount" name="summ">
                            </div>
                            <div class="modal__kassa-row-btn">
                                <input type="hidden" name="system" value="coinbase">
                                <button class="btn">PAY</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @endif
                    @if( settings('payment_btcpayserver') && \VanguardLTE\Lib\Setting::is_available('btcpayserver', auth()->user()->shop_id) )
                        {!! Form::open(['route' => 'frontend.balance.post', 'method' => 'POST']) !!}
                        <div class="modal__kassa-row-item">
                            <div class="modal__kassa-row-img">
                                <img src="/frontend/Default/img/_src/logo-kassa-3.png" alt="">
                            </div>
                            <div class="modal__kassa-row-input">
                                <input type="text" placeholder="Enter the amount" name="summ">
                            </div>
                            <div class="modal__kassa-row-btn">
                                <input type="hidden" name="system" value="btcpayserver">
                                <button class="btn">PAY</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @endif
                    @if( settings('payment_pin') )
                        <div class="modal__kassa-row-item">
                            <div class="modal__kassa-row-img">
                                <img src="/frontend/Default/img/_src/logo-kassa-4.png" alt="">
                            </div>
                            <div class="modal__kassa-row-input">
                                <input type="text" placeholder="Enter the amount" id="inputPin">
                            </div>
                            <div class="modal__kassa-row-btn">
                                <a href="javascript:;" class="btn" id="send">PAY</a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-KASSA END -->

<!-- MODAL-INVITE -->
<div class="modal modal-invite modal-pin">
    <div class="modal__body">
        <div class="modal__content">
            <div class="modal__invite">
                <h3 class="modal__title">Invite Friends</h3>
                <span class="modal__subtitle">You paraticipate in a referal program</span>
                <p class="modal__text">
                    Welcome to the all-new {{ config('app.name') }} Referral Reward program!
                    <br>
                    <br>
                    Join the {{ config('app.name') }} community of over 10.000 players who share a passion for fun games,
                    spectacular HD graphics and promotional offers!
                    <br>
                    <br>
                    It’s easy: Invite your Friends and earn UNLIMITED Bonus Play! The {{ config('app.name') }} Referral Rewards
                    program wars designed with YOU in mind. Earn as much as you can for free by welcoming new players to the {{ config('app.name') }} community. Every referral earns you ${{ $invite ? number_format($invite->sum, 2,".","") : 0 }} in Bonus Rewards!
                    Plus, every person you invite gets a free ${{ $invite ? number_format($invite->sum_ref, 2,".","") : 0  }} Bonus when they use purchase code for first-time deposit of ${{ $invite ? number_format($invite->min_amount, 2,".","") : 0 }}!
                    <br>
                    <br>
                    We kept things simple for the best possible experience for you and those you refer.
                    You'll love the ability to privately track your Bonuses in your account directly from
                    your phone! Let it bulld up or play whenever you like!
                    <br>
                    <br>
                    {{ config('app.name') }} REFERRAL REWARDS - The Gateway to Unlimited Bonus Play!
                    It's that simple Play. Invite. Earn. Repeat!
                </p>
                <a href="{{ route('frontend.profile.agree') }}" class="btn modal-close">Ok</a>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-INVITE END -->

<!-- MODAL-INFO -->
<div class="modal modal-info">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">My Progress</h3>
            <div class="modal__table">
                <div class="modal__table-wrap avoid-scroll custom-scroll" data-simplebar>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td>Sum</td>
                            <td>Type</td>
                            <td>Spins</td>
                            <td>Bet</td>
                        </tr>
                        </thead>
                        <tbody>

                        @if( auth()->user()->shop && auth()->user()->shop->progress_active )
                            @php $progress = \VanguardLTE\Progress::where(['shop_id' => auth()->user()->shop_id])->orderBy('rating')->get() @endphp
                            @if($progress)
                                @foreach($progress AS $item)
                                    <tr @if($item->rating == auth()->user()->rating) class="selected" @endif>
                                        <td class="big-td"><img src="/frontend/Default/img/badges64x64/badge-{{ $item->badge() }}.png" alt="">Range #{{ $item->rating }}</td>
                                        <td>{{ $item->sum }}</td>
                                        <td>@if( $item->type == 'sum_pay' ) PAY SUM @else ONE PAY @endif</td>
                                        <td>{{ $item->spins }}</td>
                                        <td>{{ $item->bet }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endif


                        </tbody>
                    </table>
                </div>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-INFO END-->

<!-- MODAL-INVITE-1 -->
<div class="modal modal-invite-1 modal-pin">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">Invite Friends</h3>
            <div class="modal__invite-block">
                <div class="modal__invite-item">

                    @if( count(auth()->user()->rewards()))

                        <div class="modal__invite-title">
                            Invited friends
                        </div>
                        <div class="modal__invite-phones">
                            @foreach(auth()->user()->rewards() AS $reward)
                                @if($reward->referral)
                                <div class="modal__invite-row" id="reward{{ $reward->id }}">
                                    <div class="modal__invite-info">
                                        <div class="modal__invite-date">{{ \Carbon\Carbon::parse($reward->created_at)->format(config('app.date_format')) }}</div>
                                        <span class="modal__invite-valid">Until {{ \Carbon\Carbon::parse($reward->until)->format(config('app.date_format')) }}</span>
                                        <div class="modal__invite-phones-value">
                                            @if( auth()->user()->id == $reward->user_id )
                                                +{{ $reward->referral->phone }}
                                            @else
                                                My Bonus
                                            @endif
                                        </div>
                                    </div>
                                    @if( $reward->activated )
                                    <div class="modal__invite-phones-btn">
                                        <a href="javascript:;" class="btn take_reward" data-id="{{ $reward->id }}">Take bonus</a>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else



                        <div class="modal__invite-title">
                            You have not invitees yet
                        </div>
                        <div class="modal__invite-subtitle">
                            Enter the phone and  invite a friends
                        </div>
                        <div class="modal__invite-place" >
                        </div>

                    @endif
                </div>
                <div class="modal__invite-item">
                    <div class="modal__invite-label">
                        Enter phone number
                    </div>
                    <div class="modal__invite-input">
                        <input type="text" class="loginInput " autocomplete='off' id="inputPhone" value="+">
                    </div>
                    <div class="modal__invite-pin">
                        <form name='PINform' class="form-pin">
                            <input type='button' class='PINbutton' name='1' value='1'/>
                            <input type='button' class='PINbutton' name='2' value='2'/>
                            <input type='button' class='PINbutton' name='3' value='3'/>
                            <input type='button' class='PINbutton' name='4' value='4'/>
                            <input type='button' class='PINbutton' name='5' value='5'/>
                            <input type='button' class='PINbutton' name='6' value='6'/>
                            <input type='button' class='PINbutton' name='7' value='7'/>
                            <input type='button' class='PINbutton' name='8' value='8'/>
                            <input type='button' class='PINbutton' name='9' value='9'/>
                            <input type='button' class='buttonClear clear' value='C'  onClick=clearForm(this); />
                            <input type='button' class='PINbutton' name='0' value='0' />
                            <input type='button' class='buttonClear backspace' value='X' onClick=backspace(this); />
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal__invite-btn">
                <a href="javascript:;" class="btn" id="sendPhone">Invite</a>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-INVITE-1 END -->


<!-- MODAL-INVITE-3 -->
<div class="modal modal-invite-3 modal-pin">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">Invite Friends</h3>
            <div class="modal__invite-block">
                <div class="modal__invite-item">
                    <div class="modal__invite-title">
                        Bonus Conditions
                    </div>
                    <p class="modal__invite-text">
                        Let's have some fun!
                        <br>
                        <br>
                        Here are a few helpful tips:
                        <br>
                        <br>
                        After confirming your own valid mobile phone number, proceed on to the next easy steps
                        <br>
                        <br>
                        * Invite Friends you know and trust. They must be 18 years or older to participate.
                        * Monitor your Referral Reward Bonuses on your device to see when those you refer complete the easy steps in their test message invite.
                        <br>
                        <br>
                        It's that simple Play. Invite. Earn. Repeat!

                    </p>
                </div>
                <div class="modal__invite-item">
                    <div id="show_phone" @if( (auth()->user()->sms_token && !auth()->user()->phone_verified)) style="display: none; " @endif>
                        <div class="modal__invite-label">Enter phone number</div>
                        <div class="modal__invite-input">
                            <input type="text" class="loginInput " autocomplete='off' id="myPhone" value="+{{ auth()->user()->formatted_phone() }}">
                        </div>
                    </div>

                    <div id="show_sms_timer" @if( !(auth()->user()->sms_token && !auth()->user()->phone_verified)) style="display: none; " @endif>
                        <div class="modal__invite-label">Enter verification code on SMS</div>
                        <div class="modal__invite-input">
                            <input type="text" class="loginInput bonus-input" placeholder="X  X  X  X"  autocomplete='off' id="myCode">
                            <span id="show_sms_timer_item" @if( !(auth()->user()->sms_token &&  !auth()->user()->phone_verified)) style="display: none" @endif>
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $timer_text = 'Time is up';
                                    $show_resend = true;
                                    $times = $now->diffInSeconds(\Carbon\Carbon::parse(auth()->user()->sms_token_date), false);
                                    if( $times > 0 ){
                                        $show_resend = false;
                                        $minutes = floor($times/60);
                                        $seconds = $times - floor($times/60)*60;
                                        $timer_text = ($minutes < 10 ? "0" . $minutes : $minutes) . ':' . ($seconds < 10 ? "0" . $seconds : $seconds);
                                    }
                                @endphp
                                <span id="sms_timer" class="sms-code accent" data-seconds="{{ $times }}">
                                    {{ $timer_text }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="modal__invite-pin">
                        <form name='PINform' class="form-pin">
                            <input type='button' class='PINbutton' name='1' value='1'/>
                            <input type='button' class='PINbutton' name='2' value='2'/>
                            <input type='button' class='PINbutton' name='3' value='3'/>
                            <input type='button' class='PINbutton' name='4' value='4'/>
                            <input type='button' class='PINbutton' name='5' value='5'/>
                            <input type='button' class='PINbutton' name='6' value='6'/>
                            <input type='button' class='PINbutton' name='7' value='7'/>
                            <input type='button' class='PINbutton' name='8' value='8'/>
                            <input type='button' class='PINbutton' name='9' value='9'/>
                            <input type='button' class='buttonClear clear' value='C'  onClick=clearForm(this); />
                            <input type='button' class='PINbutton' name='0' value='0' />
                            <input type='button' class='buttonClear backspace' value='X' onClick=backspace(this); />
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal__invite-btn">

                <a href="javascript:;" class="btn verifyMyPhone" id="verifyMyPhone" @if( auth()->user()->sms_token && !auth()->user()->phone_verified) style="display: none" @endif>Send code</a>

                <a href="javascript:;" class="btn" id="ckeckCode" @if( !(auth()->user()->sms_token && !auth()->user()->phone_verified))style="display: none" @endif>Check code</a>


            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>
<!-- MODAL-INVITE-3 END-->

<!-- POPUPS -->

<div class="popup popup-1">
    <div class="popup__body">
        <div class="popup__content" style="background-image: url('/frontend/Default/img/_src/popup-bg-2.png')">
            <div class="popup__value" data-attr="$8">$8</div>
            <div class="popup__info">
                <div class="popup__title">
                    Congratulations!
                    <span>Added to yor balance</span>
                </div>
                <a href="javascript:;" class="btn popup-btn">Ok</a>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>

<div class="popup popup-2">
    <div class="popup__body">
        <div class="popup__content">
            <div class="popup__bg" style="background-image: url('/frontend/Default/img/_src/popup-bg-2.png')"></div>
            <div class="popup__value">
                <img src="/frontend/Default/img/badges320x320/badge-15.png" alt="">
            </div>
            <div class="popup__info">
                <div class="popup__title">
                    Congratulations!
                    <span>You hotel was awarded a news star</span>
                </div>
                <div class="popup__prize">
                    You prize: <span>LootBox Bare</span>
                </div>
                <a href="javascript:;" class="btn popup-btn">Got it</a>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>


<div class="popup popup-3">
    <div class="popup__body">
        <div class="popup__content" style="background-image: url('/frontend/Default/img/_src/stars.png')">
            <div class="popup__value" data-attr="1000">1000</div>
            <div class="popup__info">
                <div class="popup__title">
                    Congratulations!
                    <span><span class="popup__type" style="display: initial;"></span> added to yor balance</span>
                </div>
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>


<!-- /.MAIN -->

@if(auth()->user()->shop)
    @foreach(['privacy_policy', 'general_bonus_policy', 'why_bitcoin', 'responsible_gaming', 'terms_and_conditions'] AS $modal)
        @if(auth()->user()->shop->{'rules_' . $modal})
            @php $rule = \VanguardLTE\Rule::where('href', $modal)->first(); @endphp
            @if($rule)
                <div class="modal modal-{{ $modal }}">
                    <div class="modal__body">
                        <div class="modal__content">
                            <h3 class="modal__title">{{ $rule->title }}</h3>
                            <div class="modal__{{ $modal }} modal__custom_scroll custom-scroll" data-simplebar>{!! $rule->text !!}</div>
                            <span class="close-btn"><img src="/frontend/Default/img/_src/close.svg" alt=""></span>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endforeach
@endif

@if(Auth::check() &&  auth()->user()->shop && auth()->user()->shop->rules_terms_and_conditions && !auth()->user()->agreed)
    @php $rule = \VanguardLTE\Rule::where('href', 'terms_and_conditions')->first(); @endphp
    @if($rule)
        <div class="modal modal-terms_and_conditions2">
            <div class="modal__body">
                <div class="modal__content">
                    <h3 class="modal__title">{{ $rule->title }}</h3>
                    <div class="modal__terms_and_conditions2 modal__custom_scroll custom-scroll" data-simplebar>
                        {!! $rule->text !!}
                    </div>
                    <a href="{{ route('frontend.profile.agree') }}" class="btn">Agree</a>
                </div>
            </div>
        </div>
    @endif
@endif

<div class="modal modal-notifications">
    <div class="modal__body">
        <div class="modal__content">
            <h3 class="modal__title">Notifications</h3>
            <div class="modal__notifications-block">
                @if(isset ($errors) && count($errors) > 0)
                    <div class="alert alert-danger">
                        <h4>@lang('app.error')</h4>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if(Session::get('success', false))
                    <?php $data = Session::get('success'); ?>
                    @if (is_array($data))
                        @foreach ($data as $msg)
                            <div class="alert alert-success">
                                <h4>@lang('app.success')</h4>
                                <p>{{ $msg }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-success">
                            <h4>@lang('app.success')</h4>
                            <p>{{ $data }}</p>
                        </div>
                    @endif
                @endif
            </div>
            <span class="close-btn">
					<img src="/frontend/Default/img/_src/close.svg" alt="">
				</span>
        </div>
    </div>
</div>




<!-- /.MAIN -->
