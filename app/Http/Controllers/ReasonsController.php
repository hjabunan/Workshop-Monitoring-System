<?php

namespace App\Http\Controllers;

use App\Models\Reasons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReasonsController extends Controller
{
    public function index(){
        $reason = DB::table('ws_reasons')
                    ->get();

        $search = '';
        return view('system-management.reason.index', compact('reason','search'));
    }

    public function create()
    {
        return view('system-management.reason.add');
    }

    public function store(Request $request)
    {
        $reason = $request->validate([
            'name'=>'required',
        ]);
        $reason = new Reasons();
        $reason->reason_name = strtoupper($request->name);
        $reason->save();

        return redirect()->route('reason.index');
    }

    public function edit($id)
    {
        $reason = DB::table('ws_reasons')->where('id', $id)->first();
        return view('system-management.reason.edit',compact('reason'));
    }

    public function update(Request $request, $id)
    {
        $reason = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $reason = Reasons::find($id);
        $reason->reason_name = strtoupper($request->name);
        $reason->reason_status = $request->status;
        $reason->update();

        return redirect()->route('reason.index');
    }

    public function search($search){
        $reason = DB::table('ws_reasons')
                    ->whereRaw("CONCAT_WS(' ', reason_name, reason_status) LIKE '%{$search}%'")->get();
                    
        return view('system-management.reason.index', compact('search','reason'));
    }
}
