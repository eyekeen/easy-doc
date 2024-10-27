<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    protected $fillable = [
        'sender',
        'receiver',
        'template_id',
        'document_id',
        'status'
    ];
}
