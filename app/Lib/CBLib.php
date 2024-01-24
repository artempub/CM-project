<?php

namespace VanguardLTE\Lib;

use VanguardLTE\StatGame;
use VanguardLTE\Cashback;
use VanguardLTE\User;
use Illuminate\Support\Facades\Log;

class CBLib
{
    public static function action($user_id = false, $summ)
    {

        if (!$user_id) {
            $user_id = auth()->user()->id;
        }

        $user = User::find($user_id);

        if (!$user->hasRole('user') || $summ <= 0) {
            return 0;
        }

        if (!$user->shop || $user->shop->cashback <= 0) {
            return 0;
        }

        $cashback = $summ * $user->shop->cashback * 0.01;

        $last_game_log = StatGame::latest('id')->where('user_id', $user_id)->first();

        if ($last_game_log) {
            $last_game_log_id = $last_game_log->id;
        } else {
            $last_game_log_id = -1;
        }

        Cashback::create([
            'user_id' => $user_id,
            'amount' => $cashback,
            'track' => $summ,
            'game_log_id' => $last_game_log_id,
	    'shop_id' => $user->shop->id
        ]);

        // $payeer = User::find($user->parent_id);
        // $data = $user->addBalance('add', $summ * $user->shop->cashback * 0.01, $payeer, false, 'cashback');

        return 0;
    }

    public static function check($user_id = false)
    {
//	Log::info('user_id '.$user_id);
        if (!$user_id) {
            $user_id = auth()->user()->id;
        }

        $user = User::find($user_id);

        if (!$user->hasRole('user')) {
            return 0;
        }

        $cashbacks = Cashback::where('user_id', $user_id)->where('status', 0)->get();

        if (!count($cashbacks)) {
            return 0;
        }

        $stats = StatGame::where('id', '>', $cashbacks[0]->game_log_id)->where('user_id', $user_id)->get();

        if (!count($stats)) {
            return 0;
        }


        $payeer = User::find($user->parent_id);

//	Log::info('stats 0 id '.$stats[0]->id);
        //$last_game_log = end($stats)->id;
        $last_game_log = $stats[count($stats)-1]->id;
	$bet_amount = 0;

        foreach ($stats as $stat) {
            $bet_amount += $stat->bet;
        }

	//Log::info('bet_amount '.$bet_amount);

        $ended = false;

$trigger = 0;
$trigger_value = settings('cashback_trigger_at') ? settings('cashback_trigger_at') : 1;
if($user->balance<=$trigger_value){
$trigger = $trigger_value;
}
        foreach ($cashbacks as $cashback) {

	    
            if (!$ended && $cashback->track+$trigger < $bet_amount) {
		//Log::info('add cashback '.$cashback->amount);
		$cashback->update(['status' => 1]);
                $user->addBalance('add', $cashback->amount, $payeer, false, 'cashback');
                //$cashback->update(['status' => 1]);
                $bet_amount -= $cashback->track;
            } else {
                $ended = true;
		//Log::info('update cashback '.$bet_amount.' '.$cashback->id.' '.$cashback->user_id);
                if ($bet_amount > 0) $cashback->update(['track' => $cashback->track - $bet_amount]);
                $bet_amount = 0;
                $cashback->update(['game_log_id' => $last_game_log]);
		break;
            }
        }

        return 0;
    }
}
