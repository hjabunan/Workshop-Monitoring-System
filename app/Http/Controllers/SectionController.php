<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index(){
        $search = '';
        $section = DB::select('SELECT * FROM wms_sections');

        return view('system-management.section.index', compact('search','section'));
    }

    public function search($search){
        $section = DB::table('wms_sections')
                    ->whereRaw("CONCAT_WS(' ', name, status) LIKE '%{$search}%'")->get();
                    
        return view('system-management.section.index', compact('search','section'));
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

        $user = DB::TABLE('wms_users')->where('role',1)->first();
        
        if($user){
            User::WHERE('role', 1)
            ->UPDATE([
                'area' => $user->area . ',' . $section->id,
            ]);
        }

        return redirect()->route('section.index');
    }

    public function edit($id)
    {
        $section = DB::table('wms_sections')->where('id', $id)->first();
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
