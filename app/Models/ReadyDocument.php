<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadyDocument extends Model
{
    protected $fillable = [
        'petition_id',
        'name',
        'path',
        'note',
        'electronKey'
    ];
}
