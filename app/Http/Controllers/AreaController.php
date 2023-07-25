<?php

namespace App\Http\Controllers;

use App\Models\AreaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function add(Request $request){
        $name = $request->area;
        $id = $request->input('selectedDataId');
        $areacolor = $request->colorpicker;

        $request->validate([
            'area' => 'required',
            'colorpicker' => 'required',
        ]);

        $newArea = new AreaTable();
        $newArea->name = $name;
        $newArea->areaid = $id;
        $newArea->top = 4;
        $newArea->left = 888;
        $newArea->height = 90;
        $newArea->width = 90;
        $newArea->width_ratio = 0.1;
        $newArea->height_ratio = 0.1;
        $newArea->left_ratio = 1.15;
        $newArea->left_ratio = 1.15;
        $newArea->hexcolor = $areacolor;
        $newArea->save();

        return redirect()->route('dashboard');
    }

    public function edit($id){
        $areas = DB::table('area_tables')->get();
        $thisArea = DB::table('area_tables')->where('id', $id)->first();
        return view('edit-area', compact('areas', 'id', 'thisArea'));
    }

    public function delete(Request $request){
        DB::TABLE('area_tables')->WHERE('id','=',$request->areaID)->DELETE();

        // DB::UPDATE('UPDATE sections 
        //             SET sections.isset = 0
        //             WHERE sections.name=?', [$request->areaName]);

        $areas = DB::table('area_tables')->get();
        return response()->json(['success' => true, 'areas' => $areas]);
    }

    public function updateC(Request $request){
        $areaID = $request->id;
        $areaColor = $request->colorA;

        DB::table('area_tables')
                ->where('id', $areaID)
                ->update(['hexcolor' => $areaColor]);
        
        $areas = DB::table('area_tables')->get();

        return response()->json(['success' => true, 'areas' => $areas]);

    }

    public function update(Request $request){
        $areaID = $request->areaID;
        $areaTop = $request->areaTop;
        $areaLeft = $request->areaLeft;
        $areaHeight = $request->areaHeight;
        $areaWidth = $request->areaWidth;
        $areaWidthRatio = $request->areaWidthRatio;
        $areaHeightRatio = $request->areaHeightRatio;
        $areaLeftRatio = $request->areaLeftRatio;

        DB::update('UPDATE area_tables 
                    SET area_tables.top=? , area_tables.left=? , area_tables.height=? , area_tables.width=? , area_tables.width_ratio=? , area_tables.height_ratio=? , area_tables.left_ratio=? 
                    WHERE area_tables.id=?', [$areaTop, $areaLeft, $areaHeight, $areaWidth, $areaWidthRatio, $areaHeightRatio, $areaLeftRatio, $areaID]);

        return redirect()->route('dashboard');
    }
}
