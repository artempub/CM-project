<?php

namespace VanguardLTE;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApolloGames extends Model
{
    use HasFactory;
    protected $table = 'apollo_games';
    protected $fillable = [
        'gameId',
        'name',
        'img',
        'device',
        'title',
        'categories',
        'flash',
    ];
    public $timestamps = false;
}
