<?php

namespace VanguardLTE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timezones extends Model
{
    use HasFactory;
    // use HasFactory;
    protected $table = 'timezones';
    protected $fillable = ['name'];
}
