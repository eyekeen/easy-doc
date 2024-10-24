<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $statuses = Status::all(['id', 'status']);
        return Inertia::render('Student/Dashboard', ['statuses' => $statuses]);
    }
}
