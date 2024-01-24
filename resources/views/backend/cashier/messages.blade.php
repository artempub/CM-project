@if(!(isset ($errors) && count($errors) > 0) && !Session::get('success', false) && Auth::check() && auth()->user()->shop_id > 0)
    @php
        $infos = [];
        $allInfos = \VanguardLTE\Info::get();
        if( count($allInfos) ){
            foreach($allInfos AS $infoItem){
                $toAdd = false;
                if($infoItem->user){
                    if($infoItem->user->hasRole('admin')){
                        $toAdd = true;
                    }
                    if($infoItem->user->hasRole('agent')){
                        if( in_array(auth()->user()->id, $infoItem->user->availableUsers()) ){
                            $toAdd = true;
                        }
                    }
                }
                if($toAdd){
                    if($infoItem->roles == '' || auth()->user()->hasRole(strtolower($infoItem->roles))){
                        $infos[] = $infoItem;
                    }
                }
            }
        }
        if( count($infos) > 1 ){
            $infos = [$infos[rand(1, count($infos))-1]];
        }
    @endphp
    @if($infos)
        @foreach($infos as $info)
            <div class="alert alert-warning">
                <h4>{{ $info->title  }}</h4>
                <p>{!! $info->text !!}</p>
            </div>
        @endforeach
    @endif
@endif

@php

    $messages = [];

    if( Auth::check() ){
        $infoShop = \VanguardLTE\Shop::find(auth()->user()->shop_id);
        $infoGames = \VanguardLTE\JPG::select(\DB::raw('SUM(percent) AS percent'))->where(['shop_id' => auth()->user()->shop_id])->first();

        if( $infoShop && ($infoGames->percent+$infoShop->percent) >= 100 ){
            $text = '<p>JPG = <b>' .$infoGames->percent. '%</b></p>';
            $text .= '<p>'.$infoShop->name.' = <b>' .$infoShop->percent. '%</b></p>';
            $text .= '<p>' . __('app.total_percentage', ['name' => $infoShop->name, 'percent' => $infoGames->percent+$infoShop->percent]).'</p>';
            $messages[] = $text;
        }
    }

    if( file_exists( resource_path() . '/views/system/pages/new_license.blade.php' ) ){
        $messages[] = __('app.new_license');
    }

@endphp

@if (session('blockError'))
    <div class="alert alert-danger">
        Errors in block {{ strtoupper(session('blockError')) }}
    </div>
@endif

@if(!isset($hide_block))
    @if(isset ($messages) && count($messages) > 0)
        <div class="alert alert-danger">
            <h4>@lang('app.error')</h4>
            <p>{!!  $messages[array_rand($messages)];  !!}</p>
        </div>
    @endif
@endif

@if(isset ($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        <h4>@lang('app.error')</h4>
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif



@if(settings('siteisclosed'))
	<div class="alert alert-danger">
         <h4>@lang('app.turned_off')</h4>
         <p>@lang('app.site_is_turned_off')</p>
    </div>
@endif

<style>
.btn-close:hover {
  color: #919191;
}
.modale:before {
  content: "";
  display: none;
  background: rgba(0, 0, 0, 0.6);
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 10;
}
.opened:before {
  display: block;
}
.opened .modal-dialog {
  -webkit-transform: translate(0, 0);
  -ms-transform: translate(0, 0);
  transform: translate(0, 0);
  top: 20%;
}
.modal-dialog {
  background: #fefefe;
  /* border: #333333 solid 0px; */
  border-radius: 5px;
  margin-left: -100px;
  text-align:center;
  position: fixed;
  left: 50%;
  top: -100%;
  z-index: 11;
  width: 340px;
  padding-top:20px;
  padding-bottom:10px;
  box-shadow:0 5px 10px rgba(0,0,0,0.3);
  -webkit-transform: translate(0, -500%);
  -ms-transform: translate(0, -500%);
  transform: translate(0, -500%);
  -webkit-transition: -webkit-transform 2s ease-out;
  -moz-transition: -moz-transform 2s ease-out;
  -o-transition: -o-transform 2s ease-out;
  transition: transform 1s ease-out;
}
h2 {
    display:flex;
    font-size:22px;
    padding-left: 30px;

}
.modal-body {
  padding: 20px;
}
</style>

@if(Session::get('success', false))
    <?php $data = Session::get('success'); ?>
    @if (is_array($data) && $data[0]=='cash')
        <!-- Modal -->
        <div class="modale" aria-hidden="true">
            <div class="modal-dialog">
                <div style="display:left; font-size:12px; margin-bottom:20px;">
                <h2>{{$data[1]=='in'?'Added':'Taken'}}: {{number_format($data[2], 2, '.', '')}} EUR</h2>
                <h2>New balance value: {{number_format($data[3], 2, '.', '')}} EUR</h2>
                </div>
                <!-- <a href="#" class="btn-close closemodale" aria-hidden="true">&times;</a> -->
                <div class="btn-close closemodale" style="display:flex; justify-content:center; width:100%; margin-bottom:20px;">
                <button style="display: flex;
                justify-content: center;
                font-size: 19px;
                border:none;
                box-shadow:2px #ddd;
                border-radius: 5px;
                width: 95%;
                padding: 5px;">Close</button>
                </div>
            </div>
        </div>
        <script>
            $('.closemodale').click(function (e) {
                e.preventDefault();
                $('.modale').removeClass('opened');
            });
            setTimeout(() => {
                $('.modale').addClass('opened');
            }, 1000*0.5);
        </script>
    @endif

<!-- /Modal -->

  <!-- <h1>Dead Simple Modal</h1>
  <p><a href="#" class="btn btn-big openmodale">Open Login Box</a></p> -->
    <!-- @if (is_array($data))
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
    @endif -->
@endif
