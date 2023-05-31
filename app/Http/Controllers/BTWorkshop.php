<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BTWorkshop extends Controller
{
    public function index(){
        
        return view('workshop-ms.bt-workshop.index');
    }
}
