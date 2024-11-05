<?php

namespace App\Http\Controllers;

use App\Models\Petition;
use App\Models\RejectPetition;
use App\Models\Status;
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
            ->leftJoin('reject_petitions as rp', 'rp.petition_id', '=', 'p.id')
            ->select([
                DB::raw('p.id as p_id'),
                DB::raw('u.name as s_name'),
                DB::raw('p.created_at as publish_date'),
                DB::raw('dt.origin_name as origin_name'),
                DB::raw('s.status as status'),
                DB::raw('p.status as status_code'),
                DB::raw('d.path as d_path'),
                DB::raw('rp.petition_id as rpid'),
                DB::raw('rp.reason as reason'),
            ])
            ->where('p.receiver', '=', $user)
            ->get();

        $statuses = Status::where('id', '!=', 1)->get();

        return Inertia::render('Methodologist/Dashboard', [
           'petitions' => $petitions, 
           'statuses' => $statuses
        ]);
    }

    public function update(Request $request, $pid) {
        $new_status = $request->get('new_status');

        $petition = Petition::find($pid);

        $petition->status = $new_status;

        if($petition->save()){
            return response()->json(['petition' => $petition]);
        } else {
            return response()->json(['msg' => 'erro'], 401);
        }
    }

    public function reject(Request $request, $pid) {
        $new_status = $request->get('new_status');
        

        $petition = Petition::find($pid);

        $petition->status = $new_status;

        $reject = new RejectPetition();

        $reject->petition_id = $pid;
        $reject->reason = $request->get('reason');

        if($petition->save() && $reject->save()){
            return response()->json(['petition' => $petition, 'reject' => $reject]);
        } else {
            return response()->json(['msg' => 'erro'], 400);
        }


    }
}
