<?php

namespace App\Http\Controllers;

use App\Models\XModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XModelController extends Controller
{
    public function index(){
        $model = DB::select('SELECT * FROM x_models');

        return view('system-management.model.index', compact('model'));
    }

    public function create()
    {
        return view('system-management.model.add');
    }

    public function store(Request $request)
    {
        $model = $request->validate([
            'name'=>'required',
        ]);
        $model = new XModel();
        $model->name = strtoupper($request->name);
        $model->save();
        return redirect()->route('model.index');
    }

    public function edit($id)
    {
        $model = DB::table('x_models')->where('id', $id)->first();
        return view('system-management.model.edit',compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $model = XModel::find($id);
        $model->name = strtoupper($request->name);
        $model->status = $request->status;
        $model->update();

        return redirect()->route('model.index');
    }
}
