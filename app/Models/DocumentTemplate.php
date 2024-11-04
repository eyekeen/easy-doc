<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    public function document_requireds()
    {
        return $this->hasMany(DocumentRequired::class);
    }
}
