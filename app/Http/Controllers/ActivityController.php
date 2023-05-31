<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(){
        $act = DB::select('SELECT * FROM activities');

        return view('system-management.activity.index', compact('act'));
    }

    public function create()
    {
        return view('system-management.activity.add');
    }

    public function store(Request $request)
    {
        $act = $request->validate([
            'name'=>'required',
        ]);
        $act = new Activity();
        $act->name = strtoupper($request->name);
        $act->save();
        return redirect()->route('activity.index');
    }

    public function edit($id)
    {
        $act = DB::table('activities')->where('id', $id)->first();
        return view('system-management.activity.edit',compact('act'));
    }

    public function update(Request $request, $id)
    {
        $act = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $act = Activity::find($id);
        $act->name = strtoupper($request->name);
        $act->status = $request->status;
        $act->update();

        return redirect()->route('activity.index');
    }
}
