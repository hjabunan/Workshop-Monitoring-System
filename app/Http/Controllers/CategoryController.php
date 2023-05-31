<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(){
        $category = DB::select('SELECT * FROM categories');

        //$dept = DB::select('select * from departments where id=?,' [1]);
        //$depts =DB::table('departments')->select('id','name')->get();
        return view('system-management.category.index', compact('category'));
    }

    public function create()
    {
        return view('system-management.category.add');
    }

    public function store(Request $request)
    {
        $cat = $request->validate([
            'cname'=>'required'
        ]);
        $cat = new Category();
        $cat->cat_name = strtoupper($request->cname);
        $cat->save();
        return redirect()->route('category.index');
    }

    public function edit($id)
    {
        $cat = DB::table('categories')->where('id', $id)->first();
        return view('system-management.category.edit',compact('cat'));
    }

    public function update(Request $request, $id)
    {
        $cat = $request->validate([
            'cname' => 'required',
            'status' => 'required'
        ]);

        $cat = Category::find($id);
        $cat->cat_name = strtoupper($request->cname);
        $cat->status = $request->status;
        $cat->update();
        
        //DB::update('update category set name = ?, status = ?, where id = ?', [$dname,$dstatus,$id]);

        return redirect()->route('category.index');
    }
}
