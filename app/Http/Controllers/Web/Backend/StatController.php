<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use VanguardLTE\Http\Controllers\Controller;
use Illuminate\Http\Request;
use VanguardLTE\StatGame;
use VanguardLTE\Shop;
use VanguardLTE\JPG;

class StatController extends Controller
{
    public function company_stats(Request $request){
        $start_date=$request->start_date;
        $end_date=$request->end_date;

        $total_bet = StatGame::where(function ($query) {
            $query->where('percent', '>', '0')
                ->orWhere('game', 'like', '% DG');
            });
        

        $total_win=StatGame::where('id', '!=', '-10');

        $jp_out=StatGame::where('game', 'like' ,'%JPG%');

        if($start_date && $end_date){
            $total_bet=$total_bet->where('date_time', '>=', $start_date)
                        ->where('date_time', '<=', $end_date);

            $total_win=$total_win->where('date_time', '>=', $start_date)
                                ->where('date_time', '<=', $end_date);

            $jp_out=$jp_out->where('date_time', '>=', $start_date)
                    ->where('date_time', '<=', $end_date);
        }

        $data['total_bet']=$total_bet->sum('bet');

        $data['jp_out']=$jp_out->sum('win');

        $data['bank_out']=$total_win->sum('win') - $data['jp_out'];


        $shop=Shop::first();
        $company_tax_percent = $shop ? $shop->percent : 0;
        $jpg_percent_sum=JPG::where('shop_id', $shop->id)->sum('percent');
        $data['company_tax']=$data['total_bet'] * (100 - $company_tax_percent - $jpg_percent_sum) / 100;
        $data['game_bank_in']=$data['total_bet'] * $company_tax_percent / 100;

        $data['jp_in']=$data['total_bet'] * $jpg_percent_sum / 100;

        $data['fixed_gae_bank']=$data['company_tax'] + $data['game_bank_in'] - $data['bank_out'];
        
        $data['bank_available']=$data['game_bank_in'] - $data['bank_out'];

            // ->where('date_time', '>=', $start_date)
            // ->where('date_time', '<=', $end_date)
            // ->sum('bet');
        

        return view('backend.stat.company_stat', $data);
    }
}
