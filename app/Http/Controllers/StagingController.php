<?php

namespace App\Http\Controllers;

use App\Models\Staging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StagingController extends Controller
{
    public function index(){
        $stg = DB::table('stagings')
                    ->get();
        return view('system-management.scl.index', compact('stg'));
    }

    public function create(){
        return view('system-management.scl.add');
    }

    public function store(Request $request)
    {
        $scl = $request->validate([
            'stg_name'=>'required',
            'din'=>'required',
            'dout'=>'required',
            'stg_color'=>'required',
        ]);
        $scl = new Staging();
        $scl->stg_name = strtoupper($request->stg_name);
        $scl->stg_dayin = $request->din;
        $scl->stg_dayout = $request->dout;
        $scl->stg_color = $request->stg_color;
        $scl->save();
        return redirect()->route('scl.index');
    }

    public function edit($id){
        $scl = DB::table('stagings')->where('id', $id)->first();
        return view('system-management.scl.edit',compact('scl'));
    }

    public function update(Request $request, $id){
        $scl = $request->validate([
            'stg_name'=>'required',
            'din'=>'required',
            'dout'=>'required',
            'stg_color'=>'required',
        ]);

        $scl = Staging::find($id);
        $scl->stg_name = strtoupper($request->stg_name);
        $scl->stg_dayin = $request->din;
        $scl->stg_dayout = $request->dout;
        $scl->stg_color = $request->stg_color;
        $scl->update();

        return redirect()->route('scl.index');
    }
}
