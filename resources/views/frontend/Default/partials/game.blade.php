<div class="grid-item grid-item--width2" style="{{$key == 42 ? 'margin-right:1px':''}}">
    <a  data-href="{{ route('frontend.game.go', $game->name) }}?api_exit=/" ontouchend="changeEndURL(this)" ontouchmove="changeMoveURL(this)" href="{{ route('frontend.game.go', $game->name) }}?api_exit=/" style="color:white; width:100%; height:100%;">
        <div class="grid__content games"  style="height: 100%;padding: 5%;">
            <div class="games__item" style="height: 100%; padding: 0px;">
                <div class="games__content" style="height: 100%;">
                    <img style="height: 100%;" class="lazy" src="/frontend/Default/img/_src/game_loader.png" data-src="{{ $game->name ? '/frontend/Default/ico/' . $game->name . '.jpg' : '' }}" alt="{{ $game->title }}">
                    @if($game->jackpot)
                        <span class="label label-d label--left">
                        {{ number_format($game->jackpot->balance, 2,".","") }} {{ $currency }}
                        </span>
                    @endif
                    @if(
                        $game->tournaments->filter(function ($tournament){
                            return(
                                \Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::parse($tournament->tournament->start), false) <= 0
                                &&
                                \Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::parse($tournament->tournament->end), false) >= 0
                            );
                        })->count()
                    )
                        <span class="label-cup">
                            <span class="cup-img"><img src="/frontend/Default/img/svg/game-cup.svg" alt=""></span>
                            @if($game->is_new())
                                <span class="label"></span>
                                <span class="label label--right label-b ">NEW</span>
                            @elseif($game->is_hot())
                                <span class="label"></span>
                                <span class="label label--right label-g ">HOT</span>
                            @else
                                @if($game->label == 'Exclusive')
                                    <span class="label"></span>
                                    <span class="label label--right label-d">{{ mb_strtoupper($game->label) }}</span>
                                @endif
                            @endif
                        </span>
                    @else

                        @if($game->is_new())
                            <span class="label"></span>
                            <span class="label label--right label-b ">NEW</span>
                        @elseif($game->is_hot())
                            <span class="label"></span>
                            <span class="label label--right label-g ">HOT</span>
                        @else
                            @if($game->label == 'Exclusive')
                                <span class="label"></span>
                                <span class="label label--right label-d">{{ mb_strtoupper($game->label) }}</span>
                            @endif
                        @endif

                    @endif
                    <!-- <a href="{{ route('frontend.game.go', $game->name) }}?api_exit=/" class="play-btn checkAgreed btn">Play</a> -->
                    <span class="game-name">{{ $game->title }}</span>
                </div>
            </div>
        </div>
    </a>
</div>


<script>
    var moveState = false;
    var moveCount = 0;
    function changeMoveURL(){
        moveState = true;
        moveCount++;
    }
    function changeEndURL(e){
        if(!moveState || moveCount < 5){
            location.href=$(e)[0].attributes[0].value;
        }
        moveState = false;
    }
</script>
