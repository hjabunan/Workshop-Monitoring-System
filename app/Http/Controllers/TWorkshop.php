<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TWorkshop extends Controller
{
    public function index(){
        $customer = DB::SELECT('SELECT * FROM companies');
        
        return view('workshop-ms.t-workshop.index',compact('customer'));
    }
}
