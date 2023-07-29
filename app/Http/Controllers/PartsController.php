<?php

namespace App\Http\Controllers;

use App\Models\Parts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartsController extends Controller
{
    public function index(){
        $part = DB::table('parts')->orderBy('id','asc')->get();

        return view('system-management.parts.index',compact('part'));
    }

    public function create(){
        return view('system-management.parts.add');
    }

    public function store(Request $request){
        $part = $request->validate([
            'partnum'=>'required',
            'partbrand'=>'required',
            'partprice'=>'required',
            'status'=>'required',
        ]);
        $part = new Parts();
        $part->partno = strtoupper($request->partnum);
        $part->itemno = strtoupper($request->partitemno);
        $part->partname = strtoupper($request->partname);
        $part->brand = strtoupper($request->partbrand);
        $part->price = strtoupper($request->partprice);
        $part->status = strtoupper($request->status);
        $part->save();

        return redirect()->route('parts.index');
    }

    public function edit($id){
        $parts = DB::table('parts')->where('id', $id)->first();
        return view('system-management.parts.edit',compact('parts'));
    }

    public function update(Request $request, $id){
        $part = $request->validate([
            'partnum'=>'required',
            'partbrand'=>'required',
            'partprice'=>'required',
            'status'=>'required',
        ]);

        $part = Parts::find($id);
        $part->partno = strtoupper($request->partnum);
        $part->itemno = strtoupper($request->partitemno);
        $part->partname = strtoupper($request->partname);
        $part->brand = strtoupper($request->partbrand);
        $part->price = strtoupper($request->partprice);
        $part->status = strtoupper($request->status);
        $part->update();

        return redirect()->route('parts.index');
    }
}
