<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MCIController extends Controller
{
    // -------------------------------------------------------------MONITORING---------------------------------------------------------------//
    public function index(){
        $bays = DB::TABLE('bay_areas')
                ->WHERE('section','20')
                ->WHERE('status','1')
                ->orderBy('id','asc')->get();

        $baysT = DB::TABLE('bay_areas')
                ->WHERE('status','1')
                ->orderBy('area_name','asc')->get();

                
        $sectionT = DB::SELECT('SELECT * FROM sections WHERE status="1"');
        
        $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification,
                                unit_pull_outs.POUMastHeight,
                                unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_workshops.WSDelTransfer = 0 AND unit_workshops.WSStatus <= 4
                            ');


        $CUnitTICJ = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',1)->WHERE('WSStatus','<=',4)->count());
        $CUnitTEJ = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',2)->WHERE('WSStatus','<=',4)->count());
        $CUnitTICC = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',3)->WHERE('WSStatus','<=',4)->count());
        $CUnitTEC = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',4)->WHERE('WSStatus','<=',4)->count());
        $CUnitTRT = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',5)->WHERE('WSStatus','<=',4)->count());
        $CUnitBTRT = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',6)->WHERE('WSStatus','<=',4)->count());
        $CUnitBTS = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',7)->WHERE('WSStatus','<=',4)->count());
        $CUnitRTR = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',8)->WHERE('WSStatus','<=',4)->count());
        $CUnitRS = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',9)->WHERE('WSStatus','<=',4)->count());
        $CUnitST = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',10)->WHERE('WSStatus','<=',4)->count());
        $CUnitPPT = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',11)->WHERE('WSStatus','<=',4)->count());
        $CUnitOPC = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',12)->WHERE('WSStatus','<=',4)->count());
        $CUnitHPT = (DB::TABLE('unit_workshops')->WHERE('WSUnitType',13)->WHERE('WSStatus','<=',4)->count());
        $CUnitTotal = (DB::TABLE('unit_workshops')->WHERE('WSUnitType','!=',"")->WHERE('WSStatus','<=',4)->count());
        
        return view('workshop-ms.mci-monitoring.index',compact('bays', 'baysT', 'sectionT', 'workshop','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
    }
}
