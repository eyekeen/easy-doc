<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const UNDER_REVIEW = 1;
    const IN_PROGRESS = 2;
    const READY_FOR_ISSUE = 3;
    const NEEDS_REVISION = 4;
    const REJECTED = 5;

    protected $fillable = [
        'status',
    ];

}
