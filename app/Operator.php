<?php

namespace VanguardLTE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;
    protected $table = 'operators';
    protected $fillable = [
        'user_id',
        'api_hash',
        'start_credit',
        'credits',
        'account_limit',
        'timezone',
        'currency', 
        'ip_address', 
        'url', 
        'percentage', 
    ];
    public function operator_user()
    {
        return $this->belongsTo('VanguardLTE\User', 'user_id');
    }
}
