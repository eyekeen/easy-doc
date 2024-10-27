<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MethodologistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->id;

        $petitions = DB::table('petitions AS p')
            ->join('document_templates AS dt', 'dt.id', '=', 'p.template_id')
            ->join('users AS u', 'u.id', '=', 'p.sender')
            ->join('documents AS d', 'd.id', '=', 'p.document_id')
            ->join('statuses as s', 's.id', '=', 'p.status')
            ->select([
                DB::raw('p.id as p_id'),
                DB::raw('u.name as s_name'),
                DB::raw('p.created_at as publish_date'),
                DB::raw('dt.origin_name as origin_name'),
                DB::raw('s.status as status'),
            ])
            ->where('p.receiver', '=', $user)
            ->get();


        return Inertia::render('Methodologist/Dashboard', [
           'petitions' => $petitions
        ]);
    }
}
