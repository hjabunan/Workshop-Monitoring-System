<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminMonitor extends Controller
{
    public function index(){
        return view('workshop-ms.admin_monitoring.index');
    }
}
