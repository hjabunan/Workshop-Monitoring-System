<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index(){
        $section = DB::select('SELECT * FROM sections');

        return view('system-management.section.index', compact('section'));
    }

    public function create()
    {
        return view('system-management.section.add');
    }

    public function store(Request $request)
    {
        $section = $request->validate([
            'name'=>'required',
        ]);
        $section = new Section();
        $section->name = strtoupper($request->name);
        $section->save();
        return redirect()->route('section.index');
    }

    public function edit($id)
    {
        $section = DB::table('sections')->where('id', $id)->first();
        return view('system-management.section.edit',compact('section'));
    }

    public function update(Request $request, $id)
    {
        $section = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $section = Section::find($id);
        $section->name = strtoupper($request->name);
        $section->status = $request->status;
        $section->update();

        return redirect()->route('section.index');
    }
}
