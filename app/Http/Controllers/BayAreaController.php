<?php

namespace App\Http\Controllers;

use App\Models\BayArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BayAreaController extends Controller
{
    public function index(){
        $search = '';

        $area = DB::select('SELECT wms_bay_areas.id, wms_bay_areas.area_name, wms_bay_areas.section, wms_sections.name, wms_bay_areas.category, wms_categories.cat_name, wms_bay_areas.status
                            FROM wms_bay_areas
                            INNER JOIN wms_sections ON wms_bay_areas.section = wms_sections.id
                            INNER JOIN wms_categories
                            ON wms_bay_areas.category = wms_categories.id
                            ORDER BY wms_sections.name,  wms_bay_areas.id, wms_bay_areas.area_name
                        ');
        return view('system-management.area.index',compact('search','area'));
    }

    public function search($search){
        $area = DB::table('wms_bay_areas')
                    ->select('wms_bay_areas.id', 'wms_bay_areas.area_name', 'wms_bay_areas.section', 'wms_sections.name', 'wms_bay_areas.category', 'wms_categories.cat_name', 'wms_bay_areas.status')
                    ->leftJoin('wms_sections','wms_bay_areas.section','=','wms_sections.id')
                    ->leftJoin('wms_categories','wms_bay_areas.category','=','wms_categories.id')
                    ->whereRaw("CONCAT_WS(' ', wms_bay_areas.id, area_name, name, cat_name, section, category) LIKE '%{$search}%'")->get();
                    
        return view('system-management.area.index', compact('search','area'));
    }

    public function create()
    {
        $cat = DB::select('select id, cat_name from wms_categories where status=1');
        $sec = DB::SELECT('SELECT id, name FROM wms_sections WHERE status=1');
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
        $area = DB::table('wms_bay_areas')->where('id', $id)->first();
        $cat = DB::select('SELECT * FROM wms_categories');
        $sec = DB::SELECT('SELECT id, name FROM wms_sections WHERE status=1');
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

        return redirect()->route('area.index');
    }

}