<?php

namespace VanguardLTE\Http\Controllers\Web\Frontend {

    use Illuminate\Support\Facades\Storage;
    use \VanguardLTE\User;
    use \VanguardLTE\ApolloGames;
    use \VanguardLTE\ApolloTransaction;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Database\Eloquent\Builder;

    class ApolloController extends \VanguardLTE\Http\Controllers\Controller
    {

        // private $blocked = array("55433839mm");
        private $blocked = array();
        private static $endpoint = 'http://tbs2api.dark-a.com/API/';
        // private static $serverIp = '138.68.104.0';
        private static $hallID = '3200756';
        private static $hallKey = '123456';

        public function __construct()
        {
        }

        public function callback(\Illuminate\Http\Request $request)
        {
            $message = $request->getContent();
            Storage::disk('local')->put('apollo.txt', $message);
            $message = json_decode($message, true);

            if ($message['hall'] !== self::$hallID || (!isset($message['key']) && !isset($message['sign']))) {
                throw new \Exception('Invalid Hall');
            }
            if (isset($message['key']) && $message['key'] !== self::$hallKey) {
                throw new \Exception('Invalid Key');
            } else if (isset($message['sign']) && $message['sign'] == $this->sign($message)) {
                throw new \Exception('Invalid Sign');
            }

            switch ($message['cmd']) {
                case "getBalance":
                    return $this->getBalance($message);
                case "writeBet":
                    return $this->writeBet($message);
                default:
                    break;
            }
        }

        private function sign($data)
        {
            unset($data['sign']);
            ksort($data, SORT_STRING);
            array_push($data, self::$hallKey);
            $data = implode(':', $data);
            $sign = hash('sha256', $data);
            return $sign;
        }

        private function currency($user)
        {
            return $user->currency ? $user->currency : ($user->shop->currency ? $user->shop->currency : "USD");
        }

        private function operationId()
        {
            return rand(100, 9999999999);
        }

        public function getBalance($req)
        {

            Storage::disk('local')->put('apollo_getBalance.txt', json_encode($req));

            $user = User::find($req['login']);

            if (!$user) {
                return [
                    "status" => 'fail',
                    'error' => 'user_not_found'
                ];
            };

            return [
                "status" => 'success',
                'error' => '',
                'login' => $user->id,
                'balance' => number_format($user->balance, 2, '.', ''),
                'currency' => $this->currency($user),
                'operationId' => $this->operationId()
            ];
        }

        public function writeBet($req)
        {
            Storage::disk('local')->put('apollo_writeBet.txt', json_encode($req));


            $user = User::find($req['login']);

            if (!$user) {
                return [
                    "status" => 'fail',
                    'error' => 'user_not_found'
                ];
            };

            if ($user->balance < $req['bet']) {
                return [
                    'status' => 'fail',
                    'error' => 'fail_balance'
                ];
            }

            $rdata = [
                "status" => 'success',
                'error' => '',
                'login' => $user->id,
                'operationId' => $this->operationId()
            ];

            $trans = new ApolloTransaction;
            $trans->userId = $req['login'];
            $trans->bet = $req['bet'];
            $trans->win = $req['win'];
            $trans->tradeId = $req['tradeId'];
            $trans->betInfo = $req['betInfo'];
            $trans->gameId = $req['gameId'];
            if (isset($req['matrix'])) $trans->matrix = $req['matrix'];
            if (isset($req['date'])) $trans->date = $req['date'];
            if (isset($req['WinLines'])) $trans->WinLines = $req['WinLines'];
            $trans->sessionId = $req['sessionId'];

            $trans->balance_before = $user->balance;
            $trans->balance_after = $user->balance - $req['bet'] + $req['win'];

            try {
                $saved = $trans->save();
            } catch (\Exception $e) {
            }

            // if (!$saved) {

            // }

            $balance = $this->changeBalance($req['login'], (float)$req['bet'], (float)$req['win']);
            $rdata['balance'] = number_format($balance, 2, '.', '');
            $rdata['currency'] = $this->currency($user);;
            return $rdata;
        }

        public function changeBalance($userid, $bet, $win)
        {
            $user = User::find($userid);
            $user->increment('balance', $win - $bet);
            return $user->balance;
        }


        //=============================================================|| apollo ||===================================================================//

        public function initGames()
        {
            $reqParams = [
                'cmd' => 'gamesList',
                'hall' => self::$hallID,
                'key' => self::$hallKey,
                'cdnUrl' => '',
            ];

            $res = Http::post(self::$endpoint, $reqParams)->json($key = null);

            if ($res['status'] == 'success') {

                \VanguardLTE\ApolloGames::truncate();

                foreach ($res['content']['gameList'] as $game) {
                    \VanguardLTE\ApolloGames::create([
                        'gameId' => $game['id'],
                        'name' => $game['name'],
                        'img' => $game['img'],
                        'device' => $game['device'],
                        'title' => $game['title'],
                        'categories' => $game['categories'],
                        'flash' => $game['flash'],
                    ]);
                }
                return 'true';
            }
            return 'false';
        }

        public function getGame(\Illuminate\Http\Request $request)
        {
            $reqParams = [
                "cmd" => "openGame",
                "hall" => self::$hallID,
                "domain" => $request->headers->get('origin'),
                "exitUrl" => redirect()->back()->getTargetUrl(),
                "language" => "en",
                "key" => self::$hallKey,
                "login" => auth()->user()->id,
                "gameId" => $request->gameId,
                "cdnUrl" => "",
                "demo" => "0"
            ];

            // return $reqParams;

            $res = Http::post(self::$endpoint . 'openGame/', $reqParams)->json($key = null);

            if ($res['status'] == 'success') {
                return $res['content']['game']['url'];
            }
        }
    }
}
