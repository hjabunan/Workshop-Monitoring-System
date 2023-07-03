<?php

namespace App\Http\Controllers;

use App\Models\AreaTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public function add(Request $request){
        $name = $request->area;

        $request->validate([
            'area' => 'required',
        ]);

        $newArea = new AreaTable();
        $newArea->name = $name;
        $newArea->top = 4;
        $newArea->left = 888;
        $newArea->height = 90;
        $newArea->width = 90;
        $newArea->width_ratio = 0.1;
        $newArea->height_ratio = 0.1;
        $newArea->left_ratio = 1.15;
        $newArea->save();

        return redirect()->route('dashboard');
    }

    public function edit($id){
        $areas = DB::table('area_tables')->get();
        $thisArea = DB::table('area_tables')->where('id', $id)->first();
        return view('edit-area', compact('areas', 'id', 'thisArea'));
    }

    public function delete($id){
        DB::TABLE('area_tables')->WHERE('id','=',$id)->DELETE();

        $areas = DB::table('area_tables')->get();
        $thisArea = DB::table('area_tables')->where('id', $id)->first();
        return view('edit-area', compact('areas', 'id', 'thisArea'));
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

        // $AT = AreaTable::find($areaID);
        // $AT->top = $areaTop;
        // $AT->left = $areaLeft;
        // $AT->height = $areaHeight;
        // $AT->width = $areaWidth;
        // $AT->width_ratio = $areaWidthRatio;
        // $AT->height_ratio = $areaHeightRatio;
        // $AT->left_ratio = $areaLeftRatio;
        // $AT->update();
        DB::update('UPDATE area_tables 
                    SET area_tables.top=? , area_tables.left=? , area_tables.height=? , area_tables.width=? , area_tables.width_ratio=? , area_tables.height_ratio=? , area_tables.left_ratio=? 
                    WHERE area_tables.id=?', [$areaTop, $areaLeft, $areaHeight, $areaWidth, $areaWidthRatio, $areaHeightRatio, $areaLeftRatio, $areaID]);

        return redirect()->route('dashboard');
    }
}
