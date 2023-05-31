<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicianController extends Controller
{
    public function index(){
        $tech = DB::select('SELECT * FROM technicians');

        return view('system-management.technician.index',compact('tech'));
    }

    public function create()
    {
        return view('system-management.technician.add');
    }

    public function store(Request $request)
    {
        $tech = $request->validate([
            'fname'=>'required',
            'mname'=>'required',
            'lname'=>'required',
            'section'=>'required'
        ]);

        $tech = new Technician();
        $tech->name = strtoupper($request->fname).' '.strtoupper($request->mname).' '.strtoupper($request->lname);
        $tech->initials = strtoupper(substr($request->fname,0,1).'.'.substr($request->mname,0,1).'.'.substr($request->lname,0,1).'.');
        $tech->section = strtoupper($request->section);
        $tech->save();

        return redirect()->route('technician.index');
    }

    public function edit($id)
    {
        $tech = DB::table('technicians')->where('id', $id)->first();
        return view('system-management.technician.edit',compact('tech'));
    }

    public function update(Request $request, $id)
    {
        $tech = $request->validate([
            'fname'=>'required',
            'mname'=>'required',
            'lname'=>'required',
            'section'=>'required',
            'status'=>'required'
        ]);

        $tech = Technician::find($id);
        $tech->name = strtoupper($request->fname).' '.strtoupper($request->mname).' '.strtoupper($request->lname);
        $tech->initials = strtoupper(substr($request->fname,0,1).'.'.substr($request->mname,0,1).'.'.substr($request->lname,0,1).'.');
        $tech->section = strtoupper($request->section);
        $tech->status = $request->status;
        $tech->update();

        return redirect()->route('technician.index');
    }
}
