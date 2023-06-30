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

    public function update(Request $request){
        $areaID = $request->areaID;
        $areaTop = $request->areaTop;
        $areaLeft = $request->areaLeft;
        $areaHeight = $request->areaHeight;
        $areaWidth = $request->areaWidth;
        $areaHeightRatio = $request->areaHeightRatio;
        $areaWidthRatio = $request->areaWidthRatio;
        $areaLeftRatio = $request->areaLeftRatio;

        DB::update('UPDATE areas SET areas.top=? , areas.left=? , areas.height=? , areas.width=? , areas.width_ratio=? , areas.height_ratio=? , areas.left_ratio=? WHERE areas.id=?', [$areaTop, $areaLeft, $areaHeight, $areaWidth, $areaWidthRatio, $areaHeightRatio, $areaLeftRatio, $areaID]);

        return redirect()->route('dashboard');
    }
}
