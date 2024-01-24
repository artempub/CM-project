<?php

namespace VanguardLTE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

class Operator_permissions extends Model
{
    use HasFactory;
    protected $table = 'operator_permissions';
    protected $fillable = [
        'title',  
    ];
    //by 2worldsoft, to show Cash flow
    public function per_op()
    {
        return $this->hasMany('VanguardLTE\Permissions_operator','permission_id', 'id')->where('user_id',Session::get('sel_operator_id'));
        // return $this->hasMany('VanguardLTE\Permissions_operator','permission_id', 'id');
    }

    public function hasPermission($operator_id)
    {
        return $this->per_op()->where(['user_id' => $operator_id])->first();
    }

}
