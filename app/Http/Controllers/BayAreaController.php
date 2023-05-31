<?php

namespace App\Http\Controllers;

use App\Models\BayArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BayAreaController extends Controller
{
    public function index(){
        $area = DB::select('SELECT bay_areas.id, bay_areas.area_name, bay_areas.section, sections.name, bay_areas.category, categories.cat_name, bay_areas.status
                            FROM bay_areas
                            INNER JOIN sections ON bay_areas.section = sections.id
                            INNER JOIN categories
                            ON bay_areas.category = categories.id
                            ORDER BY sections.name,  bay_areas.id, bay_areas.area_name
                        ');
        
                    // TABLE('bay_areas')
                    //     ->join('sections','bay_areas.section','=','sections.id')
                    //     ->join('categories','bay_areas.category','=','categories.id')
                    //     ->select('bay_areas.*', 'sections.name', 'categories.cat_name')
                    //     ->groupBy('sections.name');
        return view('system-management.area.index',compact('area'));
    }

    public function create()
    {
        $cat = DB::select('select id, cat_name from categories where status=1');
        $sec = DB::SELECT('SELECT id, name FROM sections WHERE status=1');
        return view('system-management.area.add', compact('cat', 'sec'));
    }

    public function store(Request $request)
    {
        $area = $request->validate([
            'aname'=>'required',
            'section'=>'required',
            'category'=>'required',
        ]);
        $area = new BayArea();
        $area->area_name = strtoupper($request->aname);
        $area->section = $request->section;
        $area->category = $request->category;
        $area->save();
        return redirect()->route('area.index');
    }


    public function edit($id)
    {
        $area = DB::table('bay_areas')->where('id', $id)->first();
        $cat = DB::select('SELECT * FROM categories');
        $sec = DB::SELECT('SELECT id, name FROM sections WHERE status=1');
        return view('system-management.area.edit',compact('area','cat','sec'));
    }

    public function update(Request $request, $id)
    {
        $area = $request->validate([
            'aname' => 'required',
            'section' => 'required',
            'categ' => 'required',
            'status' => 'required',
        ]);

        $area = BayArea::find($id);
        $area->area_name = strtoupper($request->aname);
        $area->section = strtoupper($request->section);
        $area->category = strtoupper($request->categ);
        $area->status = $request->status;
        $area->update();
        
        // //DB::update('update departments set name = ?, status = ?, where id = ?', [$dname,$dstatus,$id]);

        return redirect()->route('area.index');
    }

}