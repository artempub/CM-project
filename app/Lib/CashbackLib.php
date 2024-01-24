<?php

namespace VanguardLTE\Lib;

use VanguardLTE\Message;
use VanguardLTE\Statistic;
use VanguardLTE\User;
use VanguardLTE\Cashback;

class CashbackLib
{

    public static function action($user_id = false)
    {

        if (!$user_id) {
            $user_id = auth()->user()->id;
        }

        $user = User::find($user_id);

        if (!$user->hasRole('user')) {
            return 0;
        }

        if (!($user->shop && $user->shop->cashback_active)) {
            return 0;
        }

        $count = Cashback::where(['shop_id' =>  $user->shop_id])->count();

        if (!$count) {
            return 0;
        }

        $statistics = Statistic::where('user_id', $user_id)
            ->whereIn('system', Cashback::$values['systems'])
            ->orderBy('id', 'ASC')
            ->take($count)
            ->get();
        // $stattt = '';
        if ($statistics) {
            // $stattt .= 'statistic ';
            foreach ($statistics as $index => $statistic) {
                $cashback = Cashback::where(['shop_id' => $user->shop_id, 'pay' => $index + 1])->first();
                $getBonus = Statistic::where(['user_id' => $user_id, 'system' => 'cashback', 'title' => 'CB ' . ($index + 1)])->first();
                // $stattt .= $index . ' ';
                if (!$getBonus && $cashback) {
                    // $stattt .= '1if ';
                    if ($statistic->sum >= $cashback->sum) {
                        // $stattt .= '2if ';
                        $bonus = $statistic->sum * $cashback->bonus / 100;
                        $payeer = User::where('id', $user->parent_id)->first();
                        $data = $user->addBalance('add', $bonus, $payeer, false, 'cashback', false, $cashback);
                        // $stattt.= $data.'\n';
                        Message::create(['user_id' => $user->id, 'type' => 'cashback', 'value' => $bonus, 'shop_id' => $user->shop_id]);
                    }
                }
            }
        }

        return 0;
    }
}
