<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UploadController extends Controller
{
    function index() {
        Inertia::render('UploadFile');
    }

    function store(Request $request) {
        
    }
}
