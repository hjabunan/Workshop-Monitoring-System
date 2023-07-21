<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartsController extends Controller
{
    public function index(){
        $part = DB::table('parts')->orderBy('id','asc')->get();

        return view('system-management.parts.index',compact('part'));
    }

    public function edit($id)
    {
        $parts = DB::table('parts')->where('id', $id)->first();
        return view('system-management.parts.edit',compact('parts'));
    }
}
