<?php

namespace VanguardLTE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions_operator extends Model
{
    use HasFactory;
    protected $table = 'permissions_operator';
    protected $fillable = [
        'permission_id',  
        'user_id',  
    ];
    //by 2worldsoft, to show Cash flow
    public function op_per()
    {
        return $this->belongsTo('VanguardLTE\Operator_permissions','permission_id' );
    }
}
