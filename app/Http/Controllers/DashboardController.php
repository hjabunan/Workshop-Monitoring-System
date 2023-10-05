<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getSName(Request $request)
    {
        $areaName = $request->areaName;

        $SNames = DB::table('wms_sections')->where('name', $areaName)->get();

        $result = "";
        foreach ($SNames as $SName) {
            if ($areaName == $SName->name) {
                $result = $SName->route;
            } else  {
                $result = '/dashboard';
            }
        }

        echo $result;
    }
}
