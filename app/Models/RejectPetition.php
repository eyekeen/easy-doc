<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectPetition extends Model
{
    protected $fillable = [
        'petition_id',
        'reason'
    ];
}
