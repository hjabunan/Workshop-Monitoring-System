<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index(){
        $depts = DB::select('select * from departments');
        return view('system-management.department.index',compact('depts'));
    }

    public function create()
    {
        return view('system-management.department.add');
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'deptname'=>'required'
        ]);
        $deptname = new Department;
        $deptname->name = strtoupper($request->deptname);
        $deptname->save();

        return redirect()->route('department.index');
    }


    public function edit($id)
    {
        $depts = DB::table('departments')->where('id', $id)->first();
        return view('system-management.department.edit',compact('depts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'deptname' => 'required',
            'status' => 'required'
        ]);

        $department0 = Department::find($id);
        $department0->name = strtoupper($request->deptname);
        $department0->status = strtoupper($request->status);
        $department0->update();

        return redirect()->route('department.index');
    }

}
