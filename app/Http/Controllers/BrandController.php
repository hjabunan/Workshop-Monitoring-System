<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function index()
    {
        $brand = DB::select('SELECT * FROM brands');
        return view('system-management.brand.index', compact('brand'));
    }

    public function create()
    {
        return view('system-management.brand.add');
    }

    public function store(Request $request)
    {
        $brand = $request->validate([
            'name'=>'required'
        ]);
        $brand = new Brand();
        $brand->name = strtoupper($request->name);
        $brand->save();
        return redirect()->route('brand.index');
    }

    public function edit($id)
    {
        $brand = DB::table('brands')->where('id', $id)->first();
        return view('system-management.brand.edit',compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $brand = Brand::find($id);
        $brand->name = strtoupper($request->name);
        $brand->status = $request->status;
        $brand->update();
        
        //DB::update('update category set name = ?, status = ?, where id = ?', [$dname,$dstatus,$id]);

        return redirect()->route('brand.index');
    }

}
