<?php

namespace VanguardLTE {

    class Cashback extends \Illuminate\Database\Eloquent\Model
    {

        protected $table = 'cashback';

        protected $fillable = [
            'user_id',
            'amount',
            'track',
            'game_log_id',
            'shop_id',
	    'status',
        ];

        public $timestamps = false;

        public function user()
        {
            return $this->hasOne('VanguardLTE\User', 'id', 'user_id');
        }
    }
}
