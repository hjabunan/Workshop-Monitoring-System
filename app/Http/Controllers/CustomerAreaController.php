<?php

namespace App\Http\Controllers;

use App\Models\CustomerArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAreaController extends Controller
{
    public function index(){
        $custarea = DB::select('SELECT * FROM customer_areas');
        return view('system-management.cust_area.index', compact('custarea'));
    }
    
    public function create()
    {
        return view('system-management.cust_area.add');
    }

    public function store(Request $request)
    {
        $custarea = $request->validate([
            'name'=>'required'
        ]);
        $custarea = new CustomerArea();
        $custarea->custarea = strtoupper($request->name);
        $custarea->save();
        return redirect()->route('cust_area.index');
    }

    public function edit($id)
    {
        $custarea = DB::table('customer_areas')->where('id', $id)->first();
        return view('system-management.cust_area.edit',compact('custarea'));
    }

    public function update(Request $request, $id)
    {
        $custarea = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $custarea = CustomerArea::find($id);
        $custarea->custarea = strtoupper($request->name);
        $custarea->status = $request->status;
        $custarea->update();
        
        //DB::update('update category set name = ?, status = ?, where id = ?', [$dname,$dstatus,$id]);

        return redirect()->route('cust_area.index');
    }
}
