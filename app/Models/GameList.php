<?php 
namespace VanguardLTE
{
    class GameList extends \Illuminate\Database\Eloquent\Model
    {
        protected $table = 'gamelist';
        protected $fillable = [
            'game_slug',
            'game_name', 
            'game_provider',
        ];
        public $timestamps = false;
        public static function boot()
        {
            parent::boot();
        }
        public function game_user()
        {
            return $this->belongsTo('VanguardLTE\User','user_id');
        }
        public function game_title()
        {
            return $this->belongsTo('VanguardLTE\Game','game_id');
        }
    }

}
