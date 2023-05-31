<?php

namespace App\Http\Controllers;

use App\Models\Mast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MastController extends Controller
{
    public function index(){
        $mast = DB::select('SELECT * FROM masts');

        return view('system-management.mast.index', compact('mast'));
    }

    public function create()
    {
        return view('system-management.mast.add');
    }

    public function store(Request $request)
    {
        $mast = $request->validate([
            'name'=>'required',
        ]);
        $mast = new Mast();
        $mast->name = strtoupper($request->name);
        $mast->save();
        return redirect()->route('mast.index');
    }

    public function edit($id)
    {
        $mast = DB::table('masts')->where('id', $id)->first();
        return view('system-management.mast.edit',compact('mast'));
    }

    public function update(Request $request, $id)
    {
        $mast = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $mast = Mast::find($id);
        $mast->name = strtoupper($request->name);
        $mast->status = $request->status;
        $mast->update();

        return redirect()->route('mast.index');
    }
}
