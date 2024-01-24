<?php

namespace VanguardLTE {
    class Shop extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'shops';
        protected $fillable = [
            'name',
            'balance',
            'percent',
            'max_win',
            'frontend',
            'password',
            'currency',
            'shop_limit',
            'is_blocked',
            'orderby',
            'user_id',
            'pending',
            'access',
            'country',
            'os',
            'device',
            'rules_terms_and_conditions',
            'rules_privacy_policy',
            'rules_general_bonus_policy',
            'rules_why_bitcoin',
            'rules_responsible_gaming',
            'happyhours_active',
            'progress_active',
            'invite_active',
            'welcome_bonuses_active',
            'cashback_active',
	    'cashback',
            'sms_bonuses_active',
            'wheelfortune_active'
        ];
        public static $values = [
            'currency' => [
                'BTC',
                'ARS',
                'mBTC',
                'EUR',
                'GBP',
                'USD',
                'AUD',
                'CAD',
                'NZD',
                'NOK',
                'SEK',
                'ZAR',
                'INR',
                'RUB',
                'CFA',
                'HRK',
                'HUF',
                'GEL',
                'UAH',
                'RON',
                'BRL',
                'MYR',
                'CNY',
                'JPY',
                'KRW',
                'IDR',
                'VND',
                'THB',
                'TND',
                'SGD'
            ],
            'percent' => [
                90,
                84,
                82,
                74
            ],
            'orderby' => [
                'RTP'
            ],
            'max_win' => [
                50,
                100,
                200,
                300,
                400,
                500,
                1000,
                2000,
                3000,
                4000,
                5000,
                10000,
                50000,
                100000
            ],
            'shop_limit' => [
                100,
                200,
                300,
                400,
                500,
                1000,
                10000,
                100000
            ],
            'percent_labels' => [
                '90' => '90 - 92',
                '84' => '84 - 86',
                '82' => '82 - 84',
                '74' => '74 - 76'
            ]
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
            self::saved(function ($model) {
                Shop::where('id', $model->id)->update(['name' => Lib\Functions::remove_emoji($model->name)]);
                event(new Events\Shop\ShopEdited($model));
            });
            self::deleting(function ($model) {
                StatGame::where('shop_id', $model->id)->delete();
                Category::where('shop_id', $model->id)->delete();
                OpenShift::where('shop_id', $model->id)->delete();
                ShopUser::where('shop_id', $model->id)->delete();
                Statistic::where('shop_id', $model->id)->delete();
                StatisticAdd::where('shop_id', $model->id)->delete();
                Api::where('shop_id', $model->id)->delete();
                ShopCategory::where('shop_id', $model->id)->delete();
                JPG::where('shop_id', $model->id)->delete();
                Pincode::where('shop_id', $model->id)->delete();
                HappyHour::where('shop_id', $model->id)->delete();
                GameBank::where('shop_id', $model->id)->delete();
                FishBank::where('shop_id', $model->id)->delete();
                Invite::where('shop_id', $model->id)->delete();
                WheelFortune::where('shop_id', $model->id)->delete();
                ShopCountry::where('shop_id', $model->id)->delete();
                ShopOS::where('shop_id', $model->id)->delete();
                ShopDevice::where('shop_id', $model->id)->delete();
                Progress::where('shop_id', $model->id)->delete();
                WelcomeBonus::where('shop_id', $model->id)->delete();
                SMSBonus::where('shop_id', $model->id)->delete();
                SMSBonusItem::where('shop_id', $model->id)->delete();
                Reward::where('shop_id', $model->id)->delete();
                Ticket::where('shop_id', $model->id)->delete();
                TicketAnswer::where('shop_id', $model->id)->delete();
                Security::where('shop_id', $model->id)->delete();
                UserActivity::where('shop_id', $model->id)->delete();
            });
        }
        public function get_values($key, $add_empty = false, $add_value = false)
        {
            $_obf_0D080D1D022939321A102A2608313704131B192D1F3E22 = Shop::$values[$key];
            $_obf_0D0E0F25210E3F172C323526131E171E132433121A3811 = $_obf_0D080D1D022939321A102A2608313704131B192D1F3E22;
            if ($add_empty) {
                $_obf_0D16393608061D2713341828211C09042A063E1F072201 = array_combine(array_merge([''], $_obf_0D080D1D022939321A102A2608313704131B192D1F3E22), array_merge(['---'], $_obf_0D0E0F25210E3F172C323526131E171E132433121A3811));
            } else {
                $_obf_0D16393608061D2713341828211C09042A063E1F072201 = array_combine($_obf_0D080D1D022939321A102A2608313704131B192D1F3E22, $_obf_0D0E0F25210E3F172C323526131E171E132433121A3811);
            }
            if ($add_value) {
                return [$add_value => $add_value] + $_obf_0D16393608061D2713341828211C09042A063E1F072201;
            }
            return $_obf_0D16393608061D2713341828211C09042A063E1F072201;
        }
        public function get_percent_label($percent = false)
        {
            if (!$percent) {
                $percent = $this->percent;
            }
            if (isset(Shop::$values['percent_labels'][$percent])) {
                return Shop::$values['percent_labels'][$percent];
            }
            return Shop::$values['percent_labels'][96];
        }
        public function distributors_count()
        {
            $shopUserIds = ShopUser::where('shop_id', $this->id)->pluck('user_id');
            if (count($shopUserIds)) {
                return User::whereIn('id', $shopUserIds)->whereIn('role_id', [
                    3
                ])->count();
            }
            return 0;
        }
        public function getUsersByRole($role)
        {
            $shopUserIds = ShopUser::where('shop_id', $this->id)->groupBy('user_id')->pluck('user_id');
            if ($shopUserIds) {
                $role = Role::where('slug', $role)->first();
                return User::where('role_id', $role->id)->whereIn('id', $shopUserIds)->get();
            }
            return User::where('id', 0)->get();
        }
        public function getUsersByRoleAndParentId($role, $parent_id)
        {

            $shopUserIds = ShopUser::where('shop_id', $this->id)->groupBy('user_id')->pluck('user_id');
            if ($shopUserIds) {
                $role = Role::where('slug', $role)->first();
                return User::where([
                    'role_id' => $role->id,
                    'parent_id' => $parent_id,
                ])->whereIn('id', $shopUserIds)->get();
            }
            return User::where('id', 0)->get();
        }
        public function getRowspan($parent_id = 0)
        {
            $userCnt = 0;
            if ($parent_id) {
                $userCnt = User::where([
                    'parent_id' => $parent_id,
                    'shop_id' => $this->id,
                    'role_id' => 2
                ])->count();
            } else {
                $shopUserIds = ShopUser::where('shop_id', $this->id)->groupBy('user_id')->pluck('user_id');
                if ($shopUserIds) {
                    $role = Role::where('slug', 'distributor')->first();
                    $parentIds = User::where('role_id', $role->id)->whereIn('id', $shopUserIds)->pluck('id');
                    $userCnt = User::where([
                        'shop_id' => $this->id,
                        'role_id' => 2
                    ])->whereIn('parent_id', $parentIds)->count();
                }
            }

            return ($userCnt > 0 ? $userCnt : 1);
        }
        public function categories()
        {
            return $this->hasMany('VanguardLTE\ShopCategory', 'shop_id');
        }
        public function users()
        {
            return $this->hasMany('VanguardLTE\ShopUser');
        }
        public function creator()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'user_id');
        }
        public function countries()
        {
            return $this->hasMany('VanguardLTE\ShopCountry');
        }
        public function oss()
        {
            return $this->hasMany('VanguardLTE\ShopOS');
        }
        public function devices()
        {
            return $this->hasMany('VanguardLTE\ShopDevice');
        }
        public function titles()
        {
            $_obf_0D1601373F294036132A253D022719142B1C2A25113D11 = [];
            if ($this->categories) {
                foreach ($this->categories as $category) {
                    $_obf_0D1601373F294036132A253D022719142B1C2A25113D11[] = $category->category->title;
                }
            }
            return implode(', ', $_obf_0D1601373F294036132A253D022719142B1C2A25113D11);
        }
        public function blocked()
        {
            if (settings('siteisclosed')) {
                return true;
            }
            $parent = User::find($this->creator->id)->first();
            if ($parent->is_blocked) {
                return true;
            }
            if ($this->is_blocked) {
                return true;
            }
            return false;
        }
        public function hasActiveRules()
        {
            $rules = Rule::get();
            if (count($rules)) {
                foreach ($rules as $rule) {
                    if ($this->{'rules_' . $rule->href}) {
                        return true;
                    }
                }
            }
            return false;
        }
        public function getBonusesList()
        {
            $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132 = [];
            if ($this->welcome_bonuses_active) {
                $_obf_0D3B04310C0F3702082F1038252F2C2F1C380B140D0B01 = WelcomeBonus::where(['shop_id' => $this->id])->get();
                if (count($_obf_0D3B04310C0F3702082F1038252F2C2F1C380B140D0B01)) {
                    foreach ($_obf_0D3B04310C0F3702082F1038252F2C2F1C380B140D0B01 as $_obf_0D3B341E2216045C2C0F011E382D280A0A1A2D2F193B32) {
                        $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[] = [
                            'type' => 'welcome_bonus',
                            'is_first' => 0,
                            'data' => $_obf_0D3B341E2216045C2C0F011E382D280A0A1A2D2F193B32
                        ];
                    }
                }
            }
            if ($this->happyhours_active) {
                $happyhour = HappyHour::where([
                    'shop_id' => $this->id,
                    'time' => date('G')
                ])->first();
                if (!$happyhour) {
                    for ($_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201 = date('G'); $_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201 < 24; $_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201++) {
                        $happyhour = HappyHour::where([
                            'shop_id' => $this->id,
                            'time' => $_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201
                        ])->first();
                        if ($happyhour) {
                            break;
                        }
                    }
                }
                if (!$happyhour) {
                    for ($_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201 = 0; $_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201 < date('G'); $_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201++) {
                        $happyhour = HappyHour::where([
                            'shop_id' => $this->id,
                            'time' => $_obf_0D0D152E23100D0A032D292F022E10330B280A5B1E3201
                        ])->first();
                        if ($happyhour) {
                            break;
                        }
                    }
                }
                if ($happyhour) {
                    $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[] = [
                        'type' => 'happyhour',
                        'is_first' => 0,
                        'data' => $happyhour
                    ];
                }
            }
            if ($this->progress_active) {
                $progress = Progress::where([
                    'rating' => isset(auth()->user()->rating) ? auth()->user()->rating + 1 : 1,
                    'shop_id' => $this->id
                ])->first();
                if ($progress) {
                    $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[] = [
                        'type' => 'progress',
                        'is_first' => 0,
                        'data' => $progress
                    ];
                }
            }
            if ($this->invite_active) {
                $invite = Invite::where(['shop_id' => $this->id])->first();
                if ($invite) {
                    $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[] = [
                        'type' => 'invite',
                        'is_first' => 0,
                        'data' => $invite
                    ];
                }
            }
            if ($this->sms_bonuses_active) {
                $_obf_0D2C40261C1F340B5C2C32253B0D232B021D3715023332 = SMSBonus::where(['shop_id' => $this->id])->get();
                if (count($_obf_0D2C40261C1F340B5C2C32253B0D232B021D3715023332)) {
                    $data = [];
                    foreach ($_obf_0D2C40261C1F340B5C2C32253B0D232B021D3715023332 as $smsbonus) {
                        $data[] = $smsbonus;
                    }
                    $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[] = [
                        'type' => 'sms_bonus',
                        'is_first' => 0,
                        'data' => $data
                    ];
                }
            }
            if (isset($_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[0])) {
                $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132[0]['is_first'] = 1;
            }
            return $_obf_0D1F102B5B11305B0C3E37063C02192A0C3C0C33113132;
        }
    }
}
