<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    public function getAll(){
        $statuses = Status::all();

        return $statuses;
    }
}
