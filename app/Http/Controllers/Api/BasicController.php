<?php
namespace VanguardLTE\Http\Controllers\Api
{
    include_once(base_path() . '/app/ShopCore.php');
    include_once(base_path() . '/app/ShopGame.php');
    class BasicController extends ApiController
    {
        public function __construct()
        {
        }
        public function index(\Illuminate\Http\Request $request)
        {
            if( !(isset($request->key) && $request->key == config('demo.key')) )
            {
                return $this->setStatusCode(401)->respondWithArray(['fail' => true]);
            }
            $user = \VanguardLTE\User::where('role_id', 4)->first();
            if( $user )
            {
                \VanguardLTE\QuickShop::create(['data' => json_encode($request->all())]);
                return $this->setStatusCode(200)->respondWithArray(['success' => true]);
            }
        }
        //to interact with Andrea's new game API
        public function interact_game(\Illuminate\Http\Request $request){
            $userId = $request->login;
            $users = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first();
            if (!$users) {
                return response()->json([
                    'status' => 'fail',
                    'error' => 'user_not_found'
                ]);
            }

            if ($request->cmd == 'getBalance') {
                # getBalance request from game server
                if ($users) {
                    return response()->json([
                        'status' => 'success',
                        'error' => '',
                        'login' => $userId,
                        'balance' => number_format((float)$users->balance, 2, '.', ''),
                        'currency' => 'TND',
                    ]);
                }
            }elseif ($request->cmd == 'writeBet') {
                # writeBet request from game server
                $bet = floatval($request->bet);
                $winLose = floatval($request->winLose);
                // $sessionId =  $request->sessionId;/
                $game_slug = $request->gameId;
                $balanceFirst = $users->balance;
                if($balanceFirst > $bet)
                {
                    if ($winLose != 0) {

                        #update user balance regardless of winLose +/-
                        $balanceBeforeWin = $users->balance;
                        $newWinBalance = floatval($balanceBeforeWin + $winLose);

                        \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->update(['balance' => $newWinBalance]);

                    }

                    $finalBalance = \Illuminate\Support\Facades\DB::table('users')->where('id', $userId)->first()->balance;
                    return response()->json([
                        'status' => 'success',
                        'error' => '',
                        'login' => $userId,
                        'balance' =>  number_format((float)$finalBalance, 2, '.', ''),
                        'currency' => 'TND',
                        'operationId' => mt_rand(101, 100000000000)
                    ]);
                } else {
                    return response()->json([
                        'status' => 'fail',
                        'error' => 'fail_balance',
                    ]);
                }
            }else{}
        }
        public function agent(\Illuminate\Http\Request $request)
        {
            $role = \jeremykenedy\LaravelRoles\Models\Role::where('slug', 'agent')->first();
            $token = str_random(60);
            $data = $request->only([
                'username',
                'email',
                'password',
                'password_confirmation'
            ]);
            $request->validate([
                'email' => 'required|unique:users',
                'username' => 'required|unique:users',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6'
            ]);
            $data['role_id'] = $role->id;
            $data['parent_id'] = 1;
            $data['status'] = 'Unconfirmed';
            $data['is_demo_agent'] = 1;
            $data['confirmation_token'] = $token;
            if( isset($data['email']) && ($return = \VanguardLTE\Lib\Filter::domain_filtered($data['email'])) )
            {
                return [
                    'blocked_domain_zone' => [__('app.blocked_domain_zone', ['zone' => $return['domain']])]
                ];
            }
            $user = \VanguardLTE\User::create($data);
            $user->attachRole($role);
            $user->notify(new \VanguardLTE\Notifications\EmailConfirmation($token));
            return ['success' => true];
        }
    }

}
