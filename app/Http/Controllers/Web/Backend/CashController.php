<?php

namespace VanguardLTE\Http\Controllers\Web\Backend;

use Session;
use DataTables;

include_once(base_path() . '/app/ShopCore.php');
include_once(base_path() . '/app/ShopGame.php');

class CashController extends \VanguardLTE\Http\Controllers\Controller
{
    private $users = null;
    private $child_shop_arrays = array();

    private $child_users_array = array();
    private $users_count = 0;
    private $shops_count = 0;
    private $operators_count = 0;
    private $user_roleId = 0;
    private $shop_roleId = 0;

    public function __construct(\VanguardLTE\Repositories\User\UserRepository $users)
    {
        $this->middleware([
            'auth',
            '2fa'
        ]);
        $this->middleware('permission:access.admin.panel');
        $this->users = $users;
    }

    public function get_hierarchy_childs_ids($child_users, $role_id = 0)
    {
        foreach ($child_users as $key => $child_user) {
            if ($role_id == 0) {
                array_push($this->child_users_array, $child_user->id);
            } else {
                if ($child_user->role_id == $role_id) {
                    array_push($this->child_users_array, $child_user->id);
                }
            }

            if ($child_user->role_id == $this->user_roleId) {
                $this->users_count++;
            } elseif ($child_user->role_id == $this->shop_roleId) {
                $this->shops_count++;
            } else {
                $this->operators_count++;
            }
            if (count($child_user->childs)) {
                $this->get_hierarchy_childs_ids($child_user->childs, $role_id);
            }
        }
    }

    public function index_cash(\Illuminate\Http\Request $request)
    {
        //Store the role IDs to Global vars
        $this->user_roleId =  \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'user')->first()->id;
        $operator_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'operator')->first()->id;
        $shop_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'shop')->first()->id;

        $operators = \VanguardLTE\User::where(['role_id' => $operator_role_id, 'parent_id' => auth()->user()->id])->get();
        // $game_grouped = \VanguardLTE\GameCategory::join('games', 'game_categories.game_id', '=', 'games.id')->groupBy('game_categories.category_id')->get();
        // $game_grouped = \VanguardLTE\GameCategory::groupBy('category_id')->selectRaw('*')->get();
        // dd($game_grouped[0]);
        $categories = \VanguardLTE\Category::all();
        // $currencies = \VanguardLTE\Currency::all();
        // $currencies = array_merge(\VanguardLTE\Shop::$values['currency'], ['ALL']);
        $currencies = \VanguardLTE\Shop::$values['currency'];

        //08/04/22, to get total in/out/sum over all rows at first loading
        // $gamehistory

        if ($request->ajax()) {

            Session::put('currency', 'ALL');

            if (isset($request->startdate)) {
                //change date range
                $start_date = date('Y-m-d H:i:s', strtotime($request->startdate));
                $end_date = date('Y-m-d H:i:s', strtotime($request->enddate));
                Session::put('startdate', $start_date);
                Session::put('enddate', $end_date);
            } else {
                if (Session::get('startdate') == null) {
                    $_start_date = date('Y-m-d H:i:s', strtotime(now()));
                    $_end_date = date('Y-m-d H:i:s', strtotime(now()));
                    Session::put('startdate', $_start_date);
                    Session::put('enddate', $_end_date);
                }
            }

            if (isset($request->operator_id)) {
                Session::put('operator_id', $request->operator_id);
            } else {
                if (Session::get('operator_id') == null) {
                    Session::put('operator_id', auth()->user()->id);
                }
            }

            if (isset($request->game_provider)) {
                Session::put('game_provider', $request->game_provider);
            } else {
                if (Session::get('game_provider') == null) {
                    Session::put('game_provider', 'all');
                }
            }

            $child_users = \VanguardLTE\User::find(Session::get('operator_id'))->childs;
            $this->child_users_array = array();
            $this->get_hierarchy_childs_ids($child_users, $this->user_roleId);

            // if (Session::get('startdate') == Session::get('enddate')) { //now()
            # get today's trans only
            // if (Session::get('currency') == 'ALL') {
            //     if (Session::get('game_provider') == 'all') {
            //         # get games over all providers
            //         $players = \VanguardLTE\GamesHistory::whereIn('user_id', $this->child_users_array)
            //         ->whereYear('games_history.created_at','>=', now()->year)
            //         ->whereMonth('games_history.created_at','>=', now()->month)
            //         ->whereDay('games_history.created_at','>=', now()->day)
            //         ->leftJoin('users as user_table', 'user_table.id','=','games_history.user_id')
            //         ->leftJoin('gamelist as gamelist_table', 'gamelist_table.game_slug','=','games_history.game_id')
            //         ->select('user_table.username as username','gamelist_table.game_name as game_name', 'gamelist_table.game_provider as game_provider', 'games_history.in_amount as in_amount','games_history.out_amount as out_amount','games_history.created_at as created_at')
            //         ->get();
            //     }else{
            //         # get games for the request provider
            //         $players = \VanguardLTE\GamesHistory::whereIn('user_id', $this->child_users_array)
            //         ->whereYear('games_history.created_at','>=', now()->year)
            //         ->whereMonth('games_history.created_at','>=', now()->month)
            //         ->whereDay('games_history.created_at','>=', now()->day)
            //         ->leftJoin('users as user_table', 'user_table.id','=','games_history.user_id')
            //         ->leftJoin('gamelist as gamelist_table', 'gamelist_table.game_slug','=','games_history.game_id')
            //         // ->leftJoin('gamelist as gamelist_table', function($join) {
            //         //     $join->on('gamelist_table.game_slug', '=', 'games_history.game_id');
            //         //     $join->where('gamelist_table.game_provider', Session::get('game_provider'));
            //         // })
            //         ->select('user_table.username as username','gamelist_table.game_name as game_name', 'gamelist_table.game_provider as game_provider', 'games_history.in_amount as in_amount','games_history.out_amount as out_amount','games_history.created_at as created_at')
            //         // ->where('gamelist_table.game_provider', Session::get('game_provider'))
            //         ->get();
            //     }
            // } else {
            // }
            // } else { // here is the real action as there is no case of startDate=endDate
            # get trans for the date range
            // if (Session::get('currency') == 'ALL') {
            // if (Session::get('game_provider') == 'all') {
            //check if there is get_total_values request after datatable load
            if ($request->action && $request->action == 'get_total_values') {
                # get total in / out / total over all page regarding the latest filter params stored in Session
                $gamehistory_total = 0;
            }
            $players = \VanguardLTE\StatGame::whereIn('user_id', $this->child_users_array)
                ->whereBetween('stat_game.date_time', [Session::get('startdate'), Session::get('enddate')])
                ->leftJoin('users as user_table', 'user_table.id', '=', 'stat_game.user_id')
                ->leftJoin('games as games_table', 'games_table.name', '=', 'stat_game.game')
                ->select('user_table.username as username', 'games_table.title as game_name', 'stat_game.bet as in_amount', 'stat_game.win as out_amount', 'stat_game.date_time as created_at')
                ->get();
            // } else {
            //     $players = \VanguardLTE\StatGame::whereIn('user_id', $this->child_users_array)
            //         ->whereBetween('stat_game.date_time', [Session::get('startdate'), Session::get('enddate')])
            //         ->leftJoin('users as user_table', 'user_table.id', '=', 'stat_game.user_id')
            //         ->leftJoin('games as games_table', 'games_table.name', '=', 'stat_game.game')
            //         // ->leftJoin('games as games_table', function($join) {
            //         //     $join->on('games_table.name', '=', 'stat_game.game');
            //         //     $join->where('games_table.game_provider', Session::get('game_provider'));
            //         // })
            //         ->where('games_table.game_provider', Session::get('game_provider'))
            //         ->select('user_table.username as username', 'games_table.title as game_name', 'stat_game.bet as in_amount', 'stat_game.win as out_amount', 'stat_game.date_time as created_at')
            //         ->get();
            // }
            //check if there is get_total_values request after datatable load
            if ($request->action && $request->action == 'get_total_values') {
                # get total in / out / total over all page regarding the latest filter params stored in Session
                $inTotal_all = 0;
                $outTotal_all = 0;
                $sumTotal_all = 0;
                foreach ($players as $key => $player) {
                    # code...
                    $inTotal_all += $player->in_amount;
                    $outTotal_all += $player->out_amount;
                }
                $sumTotal_all = $inTotal_all - $outTotal_all;
                return response()->json([
                    'inTotal_all' => $inTotal_all,
                    'outTotal_all' => $outTotal_all,
                    'sumTotal_all' => $sumTotal_all
                ]);
            }
            // } else {
            // }
            // }

            return Datatables::of($players)
                ->make(true);
        }
        $recent_operator_id = Session::get('operator_id') ? Session::get('operator_id') : auth()->user()->id;
        $recent_provider = Session::get('game_provider') ? Session::get('game_provider') : 'all';
        return view('backend.cash.index', compact('categories', 'recent_provider', 'operators', 'recent_operator_id'));
    }
    public function index(\Illuminate\Http\Request $request)
    {

        $operator_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'operator')->first()->id;
        $shop_role_id = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'shop')->first()->id;

        $operators = \VanguardLTE\User::where(['role_id' => $operator_role_id, 'parent_id' => auth()->user()->id])->get();
        // $currencies = \VanguardLTE\Currency::all();
        // $currencies = array_merge(\VanguardLTE\Shop::$values['currency'], ['ALL']);
        $currencies = \VanguardLTE\Shop::$values['currency'];

        // $query_shops = "Select temp.*, u1.username From (SELECT wt.*, wu.parent_id, wu.username as child_name FROM `w_transaction` as wt LEFT JOIN w_users as wu on wt.from_userId = wu.id GROUP BY wt.from_userId) as temp LEFT JOIN w_users as u1 on temp.parent_id = u1.id";
        //set the default options
        $_start_date = date('Y-m-d H:i:s', strtotime(now()));
        $_end_date = date('Y-m-d H:i:s', strtotime(now()));
        Session::put('startdate', $_start_date);
        Session::put('enddate', $_end_date);
        Session::put('operator_id', auth()->user()->id);
        Session::put('currency', 'ALL');

        if (isset($request->startdate)) {
            //change date range
            $start_date = date('Y-m-d H:i:s', strtotime($request->startdate));
            $end_date = date('Y-m-d H:i:s', strtotime($request->enddate));
            Session::put('startdate', $start_date);
            Session::put('enddate', $end_date);
        }
        if (isset($request->operator_id)) {
            Session::put('operator_id', $request->operator_id);
        }
        if (isset($request->currency)) {
            Session::put('currency', $request->currency);
        }

        //get all possible Shops ids to show corresponding Shops per Operator network
        $child_users = \VanguardLTE\User::find(Session::get('operator_id'))->childs;
        $this->get_hierarchy_parents_ids($child_users, $shop_role_id);

        if (Session::get('startdate') == Session::get('enddate')) { //now()
            # get today's trans only
            if (Session::get('currency') == 'ALL') {
                # get all currency trans
                $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->whereYear('created_at', '>=', now()->year)
                    ->whereMonth('created_at', '>=', now()->month)
                    ->whereDay('created_at', '>=', now()->day)
                    ->groupBy('currency')
                    ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                    ->get();

                $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->groupBy('currency')
                    ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                    ->get();
            } else {
                # get specific currency only
                $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->whereYear('created_at', '>=', now()->year)
                    ->whereMonth('created_at', '>=', now()->month)
                    ->whereDay('created_at', '>=', now()->day)
                    ->where('currency', Session::get('currency'))
                    ->groupBy('from_userId')
                    ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                    ->get();

                $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->where('currency', Session::get('currency'))
                    ->groupBy('from_userId')
                    ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                    ->get();
            }
        } else {
            # get trans for the date range
            // var_dump(Session::get('startdate'),Session::get('enddate'), Session::get('currency'), $this->child_shop_arrays);
            if (Session::get('currency') == 'ALL') {
                $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->whereBetween('created_at', [Session::get('startdate'), Session::get('enddate')])
                    ->groupBy('currency')
                    ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                    ->get();

                $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->groupBy('currency')
                    ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                    ->get();
            } else {
                $shops_all = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->whereBetween('created_at', [Session::get('startdate'), Session::get('enddate')])
                    ->where('currency', Session::get('currency'))
                    ->groupBy('from_userId')
                    ->selectRaw('*, SUM(in_amount) as sum_in, SUM(out_amount) as sum_out, SUM(payout) as sum_payout')
                    ->get();

                $shops_last_reset = \VanguardLTE\Transaction::whereIn('from_userId', $this->child_shop_arrays)
                    ->where('currency', Session::get('currency'))
                    ->groupBy('from_userId')
                    ->selectRaw('SUM(in_amount) as sum_in, SUM(out_amount) as sum_out')
                    ->get();
            }
        }
        $recent_operator_id = Session::get('operator_id');
        $recent_currency = Session::get('currency');

        return view('backend.cash.index', compact('operators', 'currencies', 'shops_all', 'shops_last_reset', 'recent_operator_id', 'recent_currency'));
    }

    public function get_hierarchy_parents_ids($child_users, $role_id)
    {
        foreach ($child_users as $key => $child_user) {
            if ($child_user->role_id == $role_id) {
                # add user ID
                array_push($this->child_shop_arrays, $child_user->id);
            }
            if (count($child_user->childs)) {
                $this->get_hierarchy_parents_ids($child_user->childs, $role_id);
            }
        }
    }
}
