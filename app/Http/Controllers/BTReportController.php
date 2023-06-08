<?php

namespace App\Http\Controllers;

use App\Models\BayArea;
use App\Models\TechnicianSchedule;
use App\Models\UnitBrandNew;
use App\Models\UnitBrandNewBats;
use App\Models\UnitConfirm;
use App\Models\UnitDowntime;
use App\Models\UnitParts;
use App\Models\UnitPullOut;
use App\Models\UnitPullOutBat;
use App\Models\UnitWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BTReportController extends Controller
{
    // -------------------------------------------------------------MONITORING---------------------------------------------------------------//
    public function index(){
        $bays = DB::TABLE('bay_areas')
                ->WHERE('section','2')
                ->WHERE('status','1')
                ->orderBy('id','asc')->get();

        $baysT = DB::TABLE('bay_areas')
                ->WHERE('status','1')
                ->orderBy('area_name','asc')->get();

                
        $sectionT = DB::SELECT('SELECT * FROM sections WHERE status="1"');
        
        // $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
            //                         unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
            //                         unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE,
            //                         bay_areas.area_name, brands.name,
            //                         unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials,
            //                         unit_downtimes.id as DTID, unit_downtimes.DTJONum, unit_downtimes.DTSDate, unit_downtimes.DTEDate, unit_downtimes.DTReason, unit_downtimes.DTRemarks, unit_downtimes.DTTDays
            //                         FROM unit_workshops
            //                         INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
            //                         INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
            //                         INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
            //                         INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
            //                         INNER JOIN unit_downtimes on unit_workshops.id = unit_downtimes.DTJONum
            //                     ');
        
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
        
        return view('workshop-ms.bt-workshop.index',compact('bays', 'baysT', 'sectionT', 'workshop','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
    }
    
    public function getEvents(Request $request){
        $events = DB::table('technician_schedules')
                    ->select('activity', 'scheddate','status')
                    // ->where('status', '=', 1)
                    ->where('baynum', '=', $request->bay)
                    ->get();
        
        $formattedEvents = [];

        foreach ($events as $event) {
            if($event->status == 1){
                $ecolor = "#FF0000";
            }else if($event->status == 2){
                $ecolor = "#0000FF";
            }else{
                $ecolor = "#008000";
            }

            $formattedEvents[] = [
                'title' => $event->activity,
                'start' => \Carbon\Carbon::parse($event->scheddate)->toIso8601String(),
                'end' => \Carbon\Carbon::parse($event->scheddate)->toIso8601String(),
                'color' => $ecolor,
                // 'description' => $event->activity,
                // add other event properties as needed
            ];
        }
    
        return response()->json($formattedEvents);
    }

    public function getBayData(Request $request){
        $bay = $request->bay;
        $date = $request->output;

        $DT = 0;
        $WSf = (DB::TABLE('unit_workshops')->WHERE('WSBayNum',$bay)->first());
        if($WSf != null){
            $WSIDf =$WSf->id;
            $DT = (DB::TABLE('unit_downtimes')->WHERE('DTJONum',$WSIDf)->get())->count();
        }

        
        $result = '';

        if ($DT>0){
            $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
                                    unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
                                    unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE, unit_workshops.WSRemarks,
                                    bay_areas.area_name, brands.name,
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, 
                                    unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, 
                                    unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials,
                                    unit_downtimes.id as DTID, unit_downtimes.DTJONum, unit_downtimes.DTSDate, unit_downtimes.DTEDate, unit_downtimes.DTReason, unit_downtimes.DTRemarks, unit_downtimes.DTTDays
                                    FROM unit_workshops
                                    INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                    INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                    INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                    INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                    INNER JOIN unit_downtimes on unit_workshops.id = unit_downtimes.DTJONum
                                    WHERE WSStatus<=4 AND WSBayNum = ?',[$bay]
                                );
        
            $DTtable = '';
            $totalDTTDays = 0;

            $dtReasons = array(
                'LACK OF SPACE' => 0,
                'LACK OF TECHNICIAN' => 0,
                'NO WORK' => 0,
                'WAITING FOR MACHINING' => 0,
                'WAITING FOR PARTS' => 0,
                'WAITING FOR PO' => 0
            );

            if(count($workshop)>0){
                foreach($workshop as $WS){
                    if($WS->DTReason == 1){
                        $DTReason = 'LACK OF SPACE';
                    }else if($WS->DTReason == 2){
                        $DTReason = 'LACK OF TECHNICIAN';
                    }else if($WS->DTReason == 3){
                        $DTReason = 'NO WORK';
                    }else if($WS->DTReason == 4){
                        $DTReason = 'WAITING FOR MACHINING';
                    }else if($WS->DTReason == 5){
                        $DTReason = 'WAITING FOR PARTS';
                    }else{
                        $DTReason = 'WAITING FOR PO';
                    }

                    $DTtable .= '<tr class="bg-white border-b hover:bg-gray-200">
                                    <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                        <span data-id="'.$WS->DTID.'">
                                        '.$WS->DTSDate.'
                                        </span>
                                    </td>
                                    <td class="px-1 py-0.5 text-center text-xs">
                                        '.$WS->DTEDate.'
                                    </td>
                                    <td class="px-1 py-0.5 text-center text-xs">
                                        '.$DTReason.'
                                    </td>
                                    <td class="px-1 py-0.5 text-center text-xs">
                                        '.$WS->DTRemarks.'
                                    </td>
                                    <td class="px-1 py-0.5 text-center text-xs">
                                        '.$WS->DTTDays.'
                                    </td>
                                    <td class="hidden">
                                        '.$WS->DTID.'
                                    </td>
                                </tr>
                    ';

                    $totalDTTDays += $WS->DTTDays;
                    $dtReason = '';
                    switch ($WS->DTReason) {
                        case 1:
                            $dtReason = 'LACK OF SPACE';
                            break;
                        case 2:
                            $dtReason = 'LACK OF TECHNICIAN';
                            break;
                        case 3:
                            $dtReason = 'NO WORK';
                            break;
                        case 4:
                            $dtReason = 'WAITING FOR MACHINING';
                            break;
                        case 5:
                            $dtReason = 'WAITING FOR PARTS';
                            break;
                        case 6:
                            $dtReason = 'WAITING FOR PO';
                            break;
                    }
                    $dtReasons[$dtReason] += $WS->DTTDays;

                    $technicians = '';
                    $TechICharge = DB::SELECT('SELECT DISTINCT(techid), technicians.initials as TInitials  
                                                FROM technician_schedules 
                                                INNER JOIN technicians on techid=technicians.id 
                                                WHERE baynum=?',[$request->bay]);

                        if(count($TechICharge)>0){
                            foreach ($TechICharge as $TIC) {
                                $technicians .='
                                                <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg" value="'.$TIC->techid.'">'.$TIC->TInitials.'</li>
                                ';
                            }
                        }else{
                            $technicians .='
                                            <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg" value=""></li>
                            ';
                        }
                    
                    $TechSchedule = DB::TABLE('technician_schedules')->WHERE('baynum','=',$request->bay)
                                                                    ->WHERE('scheddate','=',$date)
                                                                    ->WHERE('status','!=',3)
                                                                    ->first();
                    
                        if($TechSchedule != null){
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=','']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=','']])->count();

                            $result = array(
                                            'WSPOUID' => $WS->WSPOUID,
                                            'WSID' => $WS->WSID,
                                            'WSToA' => $WS->WSToA,
                                            'WSStatus' => $WS->WSStatus,
                                            'WSBayNum' => $WS->WSBayNum,
                                            'POUCode' => $WS->POUCode,
                                            'POUCustomer' => $WS->POUCustomer,
                                            'POUCustAddress' => $WS->POUCustAddress,
                                            'POUSalesman' => $WS->POUSalesman,
                                            'POUBrand' => $WS->name,
                                            'POUSerialNum' => $WS->POUSerialNum,
                                            'POUModel' => $WS->POUModel,
                                            'POUMastType' => $WS->POUMastType,
                                            'WSUnitType' => $WS->WSUnitType,
                                            'WSATIDS' => $WS->WSATIDS,
                                            'WSATIDE' => $WS->WSATIDE,
                                            'WSATRDS' => $WS->WSATRDS,
                                            'WSATRDE' => $WS->WSATRDE,
                                            'WSAAIDS' => $WS->WSAAIDS,
                                            'WSAAIDE' => $WS->WSAAIDE,
                                            'WSAARDS' => $WS->WSAARDS,
                                            'WSAARDE' => $WS->WSAARDE,
                                            'DTTable' => $DTtable,
                                            'Total_DTTDays' => $totalDTTDays,
                                            'DTTotalsLOS' => $dtReasons['LACK OF SPACE'],
                                            'DTTotalsLOT' => $dtReasons['LACK OF TECHNICIAN'],
                                            'DTTotalsNW' => $dtReasons['NO WORK'],
                                            'DTTotalsWFM' => $dtReasons['WAITING FOR MACHINING'],
                                            'DTTotalsWFP' => $dtReasons['WAITING FOR PARTS'],
                                            'DTTotalsWFPO' => $dtReasons['WAITING FOR PO'],
                                            'count1' => $partcount1,
                                            'count2' => $partcount2,
                                            'TechIC' => $technicians,
                                            'UnitTSID' => $TechSchedule->id,
                                            'Activity' => $TechSchedule->activity,
                                            'UnitActivityStatus' => $TechSchedule->status,
                                            'UnitInfoRemarks' => $TechSchedule->remarks,
                                            'MonSoW' => $TechSchedule->scopeofwork,
                                            'WSRemarks' => $WS->WSRemarks,
                                            // 'WSStatus' => $WS->WSStatus,
                                
                            );
                        }else{
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=','']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=','']])->count();

                            $result = array(
                                            'WSPOUID' => $WS->WSPOUID,
                                            'WSID' => $WS->WSID,
                                            'WSToA' => $WS->WSToA,
                                            'WSStatus' => $WS->WSStatus,
                                            'WSBayNum' => $WS->WSBayNum,
                                            'POUCode' => $WS->POUCode,
                                            'POUCustomer' => $WS->POUCustomer,
                                            'POUCustAddress' => $WS->POUCustAddress,
                                            'POUSalesman' => $WS->POUSalesman,
                                            'POUBrand' => $WS->name,
                                            'POUSerialNum' => $WS->POUSerialNum,
                                            'POUModel' => $WS->POUModel,
                                            'POUMastType' => $WS->POUMastType,
                                            'WSUnitType' => $WS->WSUnitType,
                                            'WSATIDS' => $WS->WSATIDS,
                                            'WSATIDE' => $WS->WSATIDE,
                                            'WSATRDS' => $WS->WSATRDS,
                                            'WSATRDE' => $WS->WSATRDE,
                                            'WSAAIDS' => $WS->WSAAIDS,
                                            'WSAAIDE' => $WS->WSAAIDE,
                                            'WSAARDS' => $WS->WSAARDS,
                                            'WSAARDE' => $WS->WSAARDE,
                                            'DTTable' => $DTtable,
                                            'Total_DTTDays' => $totalDTTDays,
                                            'DTTotalsLOS' => $dtReasons['LACK OF SPACE'],
                                            'DTTotalsLOT' => $dtReasons['LACK OF TECHNICIAN'],
                                            'DTTotalsNW' => $dtReasons['NO WORK'],
                                            'DTTotalsWFM' => $dtReasons['WAITING FOR MACHINING'],
                                            'DTTotalsWFP' => $dtReasons['WAITING FOR PARTS'],
                                            'DTTotalsWFPO' => $dtReasons['WAITING FOR PO'],
                                            'count1' => $partcount1,
                                            'count2' => $partcount2,
                                            'TechIC' => $technicians,
                                            'WSRemarks' => $WS->WSRemarks,
                                            // 'WSStatus' => $WS->WSStatus,
                            );
                        }
                }
            }else{
                $result = " ";
            }
        }else{
            $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
                                    unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
                                    unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE, unit_workshops.WSRemarks,
                                    bay_areas.area_name, brands.name,
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, 
                                    unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, 
                                    unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials
                                    FROM unit_workshops
                                    INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                    INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                    INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                    INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                    WHERE WSStatus<=4 AND WSBayNum = ?',[$bay]
                                );

            if(count($workshop)>0){
                foreach($workshop as $WS){
                    $TechICharge = DB::SELECT('SELECT DISTINCT(techid), technicians.initials as TInitials  
                                                FROM technician_schedules 
                                                INNER JOIN technicians on techid=technicians.id 
                                                WHERE baynum=?',[$request->bay]);

                    $technicians = '';
                        if(count($TechICharge)>0){
                            foreach ($TechICharge as $TIC) {
                                $technicians .='
                                                <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg" value="'.$TIC->techid.'">'.$TIC->TInitials.'</li>
                                ';
                            }
                        }else{
                            $technicians .='
                                            <li class="w-full px-4 py-2 border-b border-gray-200 rounded-t-lg" value=""></li>
                            ';
                        }
                    
                    $TechSchedule = DB::TABLE('technician_schedules')->WHERE('baynum','=',$bay)
                                                                    ->WHERE('scheddate','=',$date)
                                                                    ->WHERE('status','!=',3)
                                                                    ->first();
                    
                        if($TechSchedule != null){
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=','']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=','']])->count();

                            $result = array(
                                            'WSPOUID' => $WS->WSPOUID,
                                            'WSID' => $WS->WSID,
                                            'WSToA' => $WS->WSToA,
                                            'WSStatus' => $WS->WSStatus,
                                            'WSBayNum' => $WS->WSBayNum,
                                            'POUCode' => $WS->POUCode,
                                            'POUCustomer' => $WS->POUCustomer,
                                            'POUCustAddress' => $WS->POUCustAddress,
                                            'POUSalesman' => $WS->POUSalesman,
                                            'POUBrand' => $WS->name,
                                            'POUSerialNum' => $WS->POUSerialNum,
                                            'POUModel' => $WS->POUModel,
                                            'POUMastType' => $WS->POUMastType,
                                            'WSUnitType' => $WS->WSUnitType,
                                            'WSATIDS' => $WS->WSATIDS,
                                            'WSATIDE' => $WS->WSATIDE,
                                            'WSATRDS' => $WS->WSATRDS,
                                            'WSATRDE' => $WS->WSATRDE,
                                            'WSAAIDS' => $WS->WSAAIDS,
                                            'WSAAIDE' => $WS->WSAAIDE,
                                            'WSAARDS' => $WS->WSAARDS,
                                            'WSAARDE' => $WS->WSAARDE,
                                            'Total_DTTDays' => 0,
                                            'count1' => $partcount1,
                                            'count2' => $partcount2,
                                            'TechIC' => $technicians,
                                            'UnitTSID' => $TechSchedule->id,
                                            'Activity' => $TechSchedule->activity,
                                            'UnitActivityStatus' => $TechSchedule->status,
                                            'UnitInfoRemarks' => $TechSchedule->remarks,
                                            'MonSoW' => $TechSchedule->scopeofwork,
                                            'WSRemarks' => $WS->WSRemarks,
                                
                            );
                        }else{
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=','']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=','']])->count();

                            $result = array(
                                            'WSPOUID' => $WS->WSPOUID,
                                            'WSID' => $WS->WSID,
                                            'WSToA' => $WS->WSToA,
                                            'WSStatus' => $WS->WSStatus,
                                            'WSBayNum' => $WS->WSBayNum,
                                            'POUCode' => $WS->POUCode,
                                            'POUCustomer' => $WS->POUCustomer,
                                            'POUCustAddress' => $WS->POUCustAddress,
                                            'POUSalesman' => $WS->POUSalesman,
                                            'POUBrand' => $WS->name,
                                            'POUSerialNum' => $WS->POUSerialNum,
                                            'POUModel' => $WS->POUModel,
                                            'POUMastType' => $WS->POUMastType,
                                            'WSUnitType' => $WS->WSUnitType,
                                            'WSATIDS' => $WS->WSATIDS,
                                            'WSATIDE' => $WS->WSATIDE,
                                            'WSATRDS' => $WS->WSATRDS,
                                            'WSATRDE' => $WS->WSATRDE,
                                            'WSAAIDS' => $WS->WSAAIDS,
                                            'WSAAIDE' => $WS->WSAAIDE,
                                            'WSAARDS' => $WS->WSAARDS,
                                            'WSAARDE' => $WS->WSAARDE,
                                            'Total_DTTDays' => 0,
                                            'count1' => $partcount1,
                                            'count2' => $partcount2,
                                            'TechIC' => $technicians,
                                            'WSRemarks' => $WS->WSRemarks,
                                            // 'WSStatus' => $WS->WSStatus,
                            );
                        }
                }
            }else{
                $result = " ";
            }
        }
        return response()->json($result);
    }

    public function saveBayData(Request $request){
        UnitWorkshop::where('id', $request->UnitInfoJON)
                    ->update([
                        'WSToA' => $request->UnitInfoToA,
                        'WSStatus' => $request->UnitInfoStatus,
                        'WSUnitType' => $request->UnitInfoUType,
                    ]);

        $result='';
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

        $divTotalCap = '
            <div class=""><label class="font-medium">TOTAL CAPACITY:</label></div>
            <div class="grid grid-cols-8 items-center ml-2">
                <div class="col-span-6 text-xs">
                    <label>TOYOTA IC JAPAN</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitTICJ" id="UnitTICJ" value="'.$CUnitTICJ.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>TOYOTA ELECTRIC JAPAN</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitTEJ" id="UnitTEJ" value="'.$CUnitTEJ.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>TOYOTA IC CHINA</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitTICC" id="UnitTICC" value="'.$CUnitTICC.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>TOYOTA ELECTRIC CHINA</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitTEC" id="UnitTEC" value="'.$CUnitTEC.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>TOYOTA REACH TRUCK</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitTRT" id="UnitTRT" value="'.$CUnitTRT.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>BT REACH TRUCK</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitBTRT" id="UnitBTRT" value="'.$CUnitBTRT.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>BT STACKER</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitBTS" id="UnitBTS" value="'.$CUnitBTS.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>RAYMOND REACH TRUCK</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitRRT" id="UnitRRT" value="'.$CUnitRTR.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>RAYMOND STACKER</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitRS" id="UnitRS" value="'.$CUnitRS.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>STACKER TAILIFT</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitST" id="UnitST" value="'.$CUnitST.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>PPT</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitPPT" id="UnitPPT" value="'.$CUnitPPT.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>OPC</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitOPC" id="UnitOPC" value="'.$CUnitOPC.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label>HPT</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitHPT" id="UnitHPT" value="'.$CUnitHPT.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                </div>
            </div>
            <hr class="mt-0.5 mb-0.5">
            <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                <div class="col-span-6 text-xs">
                    <label style="float: right;" class="mr-5">TOTAL</label>
                </div>
                <div class="col-span-2">
                    <input type="text" name="UnitTotal" id="UnitTotal" value="'.$CUnitTotal.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-md text-center pointer-events-none" placeholder="0">
                </div>
            </div>
        ';

        $result = array('TotalCap' => $divTotalCap,);

        return response()->json($result);
    }

    public function saveTargetActivity(Request $request){
        UnitWorkshop::where('id', $request->JONum)
                    ->update([
                        'WSATIDS' => $request->TIStart,
                        'WSATIDE' => $request->TIEnd,
                        'WSATRDS' => $request->TRStart,
                        'WSATRDE' => $request->TREnd,
                    ]);
    }

    public function updateIDS(Request $request){
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAAIDS' => $request->AIDStart,
                            // 'WSStatus' => 2,
                        ]);
    }

    public function updateIDE(Request $request){
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAAIDE' => $request->AIDEnd,
                        ]);
    }

    public function updateRDS(Request $request){
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAARDS' => $request->ARDStart,
                            // 'WSStatus' => 3,
                        ]);
    }

    public function updateRDE(Request $request){
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAARDE' => $request->ARDEnd,
                            // 'WSStatus' => 4,
                        ]);
    }

    public function resetActual(Request $request){
        $WS = UnitWorkshop::find($request->JONum);
        $WS->WSAAIDS = '';
        $WS->WSAAIDE = '';
        $WS->WSAARDS = '';
        $WS->WSAARDE = '';
        // $WS->WSStatus = 1;
        $WS->update();
    }

    public function saveDowntime(Request $request){
        if($request->DTID == ''){
            $downtime = new UnitDowntime();
            $downtime->DTJONum = $request->JONum;
            $downtime->DTSDate = $request->DTSDate;
            $downtime->DTEDate = $request->DTEDate;
            $downtime->DTReason = $request->DTReason;
            $downtime->DTRemarks = strtoupper($request->DTRemarks);
            $downtime->DTTDays = $request->DTTDays;
            $downtime->save();
        }else{
            $downtime = UnitDowntime::find($request->DTID);
            $downtime->DTJONum = $request->JONum;
            $downtime->DTSDate = $request->DTSDate;
            $downtime->DTEDate = $request->DTEDate;
            $downtime->DTReason = $request->DTReason;
            $downtime->DTRemarks = strtoupper($request->DTRemarks);
            $downtime->DTTDays = $request->DTTDays;
            $downtime->update();
        }

        $result ='';
        $DTime = DB::SELECT('SELECT * FROM unit_downtimes WHERE DTJONum=?',[$request->JONum]);

        $DTtable = '';
        $totalDTTDays = 0;

        $dtReasons = array(
            'LACK OF SPACE' => 0,
            'LACK OF TECHNICIAN' => 0,
            'NO WORK' => 0,
            'WAITING FOR MACHINING' => 0,
            'WAITING FOR PARTS' => 0,
            'WAITING FOR PO' => 0
        );

        if(count($DTime)>0){
            foreach ($DTime as $DT) {
                if($DT->DTReason == 1){
                    $DTReason = 'LACK OF SPACE';
                }else if($DT->DTReason == 2){
                    $DTReason = 'LACK OF TECHNICIAN';
                }else if($DT->DTReason == 3){
                    $DTReason = 'NO WORK';
                }else if($DT->DTReason == 4){
                    $DTReason = 'WAITING FOR MACHINING';
                }else if($DT->DTReason ==5){
                    $DTReason = 'WAITING FOR PARTS';
                }else{
                    $DTReason = 'WAITING FOR PO';
                }

                $DTtable .='<tr class="bg-white border-b hover:bg-gray-200">
                                <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                    <span data-id="'.$DT->id.'">
                                    '.$DT->DTSDate.'
                                    </span>
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DT->DTEDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DTReason.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DT->DTRemarks.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DT->DTTDays.'
                                </td>
                                <td class="hidden">
                                    '.$DT->id.'
                                </td>
                            </tr>
                ';

                $totalDTTDays += $DT->DTTDays;
                $dtReason = '';
                switch ($DT->DTReason) {
                    case 1:
                        $dtReason = 'LACK OF SPACE';
                        break;
                    case 2:
                        $dtReason = 'LACK OF TECHNICIAN';
                        break;
                    case 3:
                        $dtReason = 'NO WORK';
                        break;
                    case 4:
                        $dtReason = 'WAITING FOR MACHINING';
                        break;
                    case 5:
                        $dtReason = 'WAITING FOR PARTS';
                        break;
                    case 6:
                        $dtReason = 'WAITING FOR PO';
                        break;
                }
                $dtReasons[$dtReason] += $DT->DTTDays;
                // $totalDTTDays += $WS->DTTDays;
                    
                $result = array(
                    'DTTable' => $DTtable,
                    'Total_DTTDays' => $totalDTTDays,
                    'DTTotalsLOS' => $dtReasons['LACK OF SPACE'],
                    'DTTotalsLOT' => $dtReasons['LACK OF TECHNICIAN'],
                    'DTTotalsNW' => $dtReasons['NO WORK'],
                    'DTTotalsWFM' => $dtReasons['WAITING FOR MACHINING'],
                    'DTTotalsWFP' => $dtReasons['WAITING FOR PARTS'],
                    'DTTotalsWFPO' => $dtReasons['WAITING FOR PO'],
                );
            }
        }else{
            $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }
        return response()->json($result);
    }

    public function getDowntime(Request $request){
        $downtime = DB::TABLE('unit_downtimes')->WHERE('id', $request->DTID)->first();

        $result = array(
            'DTID' => $downtime->id,
            'DTSDate' => $downtime->DTSDate,
            'DTEDate' => $downtime->DTEDate,
            'DTReason' => $downtime->DTReason,
            'DTRemarks' => $downtime->DTRemarks,
            'DTTDays' => $downtime->DTTDays,
        );
        return json_encode($result);
    }

    public function deleteDowntime(Request $request){
        $DT = UnitDowntime::find($request->DTID);
        $DT->delete();

        $result ='';
        $DTime = DB::SELECT('SELECT * FROM unit_downtimes WHERE DTJONum=?',[$request->JONum]);

        $DTtable = '';
        $totalDTTDays = 0;

        $dtReasons = array(
            'LACK OF SPACE' => 0,
            'LACK OF TECHNICIAN' => 0,
            'NO WORK' => 0,
            'WAITING FOR MACHINING' => 0,
            'WAITING FOR PARTS' => 0,
            'WAITING FOR PO' => 0
        );

        if(count($DTime)>0){
            foreach ($DTime as $DT) {
                if($DT->DTReason == 1){
                    $DTReason = 'LACK OF SPACE';
                }else if($DT->DTReason == 2){
                    $DTReason = 'LACK OF TECHNICIAN';
                }else if($DT->DTReason == 3){
                    $DTReason = 'NO WORK';
                }else if($DT->DTReason == 4){
                    $DTReason = 'WAITING FOR MACHINING';
                }else if($DT->DTReason == 5){
                    $DTReason = 'WAITING FOR PARTS';
                }else{
                    $DTReason = 'WAITING FOR PO';
                }

                $DTtable .='<tr class="bg-white border-b hover:bg-gray-200">
                                <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                    <span data-id="'.$DT->id.'">
                                    '.$DT->DTSDate.'
                                    </span>
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DT->DTEDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DTReason.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DT->DTRemarks.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DT->DTTDays.'
                                </td>
                                <td class="hidden">
                                    '.$DT->id.'
                                </td>
                            </tr>
                ';

                $totalDTTDays += $DT->DTTDays;
                $dtReason = '';
                switch ($DT->DTReason) {
                    case 1:
                        $dtReason = 'LACK OF SPACE';
                        break;
                    case 2:
                        $dtReason = 'LACK OF TECHNICIAN';
                        break;
                    case 3:
                        $dtReason = 'NO WORK';
                        break;
                    case 4:
                        $dtReason = 'WAITING FOR MACHINING';
                        break;
                    case 5:
                        $dtReason = 'WAITING FOR PARTS';
                        break;
                    case 6:
                        $dtReason = 'WAITING FOR PO';
                        break;
                }
                $dtReasons[$dtReason] += $DT->DTTDays;
                // $totalDTTDays += $WS->DTTDays;
                    
                $result = array(
                    'DTTable' => $DTtable,
                    'Total_DTTDays' => $totalDTTDays,
                    'DTTotalsLOS' => $dtReasons['LACK OF SPACE'],
                    'DTTotalsLOT' => $dtReasons['LACK OF TECHNICIAN'],
                    'DTTotalsNW' => $dtReasons['NO WORK'],
                    'DTTotalsWFM' => $dtReasons['WAITING FOR MACHINING'],
                    'DTTotalsWFP' => $dtReasons['WAITING FOR PARTS'],
                    'DTTotalsWFPO' => $dtReasons['WAITING FOR PO'],
                );
            }
        }else{
            $DTtable .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
                $result = array(
                    'DTTable' => $DTtable,
                    'Total_DTTDays' => 0,
                );
        }
        return response()->json($result);
    }

    public function getPI(Request $request){
        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled=""',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=','']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else{
                    $PIReason = "Machining";
                }

                $result1 .= '
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI1->id.'"></span>
                                '.$PI1->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI1->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateReq.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateRec.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                            '.$PIReason.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result1 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result2 = '';
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!=""',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=','']])->count();

        if(count($partinfo2)>0){
            foreach($partinfo2 as $PI2){

                $result2 .= '
                        <tr class="bg-white border-b hover:bg-gray-200" data-id="'.$PI2->id.'">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI2->id.'"></span>
                                '.$PI2->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI2->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDateInstalled.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result2 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result = array(
                'result1' => $result1,
                'result2' => $result2,
                'count1' => $partcount1,
                'count2' => $partcount2,
        );
        
        return json_encode($result);
    }
    
    public function savePI(Request $request){
        if($request ->PIID == null){
            $partinfo = new UnitParts();
            $partinfo->PIJONum = $request->PIJONum;
            $partinfo->PIMRINum = $request->PIMRINum;
            $partinfo->PIPartNum = $request->PIPartNum;
            $partinfo->PIDescription = $request->PIDescription;
            $partinfo->PIQuantity = $request->PIQuantity;
            $partinfo->PIDateReq = $request->PIDateReq;
            $partinfo->PIDateRec = $request->PIDateRec;
            $partinfo->PIReason = $request->PIReason;
            $partinfo->PIDateInstalled = '';
            $partinfo->PIRemarks = '';
            $partinfo->save();
        }else{
            $partinfo = UnitParts::find($request->PIID);
            $partinfo->PIJONum = $request->PIJONum;
            $partinfo->PIMRINum = $request->PIMRINum;
            $partinfo->PIPartNum = $request->PIPartNum;
            $partinfo->PIDescription = $request->PIDescription;
            $partinfo->PIQuantity = $request->PIQuantity;
            $partinfo->PIDateReq = $request->PIDateReq;
            $partinfo->PIDateRec = $request->PIDateRec;
            $partinfo->PIReason = $request->PIReason;
            $partinfo->PIDateInstalled = '';
            $partinfo->PIRemarks = '';
            $partinfo->update();
        }
        
        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled=""',[$request->PIJONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->PIJONum],['PIDateInstalled','=','']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else{
                    $PIReason = "Machining";
                }

                $result1 .= '
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI1->id.'"></span>
                                '.$PI1->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI1->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateReq.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateRec.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                            '.$PIReason.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result1 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result2 = '';
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!=""',[$request->PIJONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->PIJONum],['PIDateInstalled','!=','']])->count();

        if(count($partinfo2)>0){
            foreach($partinfo2 as $PI2){

                $result2 .= '
                        <tr class="bg-white border-b hover:bg-gray-200" data-id="'.$PI2->id.'">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI2->id.'"></span>
                                '.$PI2->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI2->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDateInstalled.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result2 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result = array(
                'result1' => $result1,
                'result2' => $result2,
                'count1' => $partcount1,
                'count2' => $partcount2,
        );
        
        return json_encode($result);
    }

    public function getPInfo(Request $request){
        $pinfo = DB::TABLE('unit_parts')->WHERE('id', $request->PIID)->first();

        $result = array(
            'PIID' => $pinfo->id,
            'PIMRINum' => $pinfo->PIMRINum,
            'PIPartNum' => $pinfo->PIPartNum,
            'PIDescription' => $pinfo->PIDescription,
            'PIQuantity' => $pinfo->PIQuantity,
            'PIDateReq' => $pinfo->PIDateReq,
            'PIDateRec' => $pinfo->PIDateRec,
            'PIReason' => $pinfo->PIReason,
        );
        return json_encode($result);
    }

    public function deletePI(Request $request){
        $partinfo = UnitParts::find($request->PIID);
        $partinfo->delete();
        
        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled=""',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=','']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else{
                    $PIReason = "Machining";
                }

                $result1 .= '
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI1->id.'"></span>
                                '.$PI1->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI1->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateReq.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateRec.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                            '.$PIReason.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result1 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result2 = '';
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!=""',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=','']])->count();

        if(count($partinfo2)>0){
            foreach($partinfo2 as $PI2){

                $result2 .= '
                        <tr class="bg-white border-b hover:bg-gray-200" data-id="'.$PI2->id.'">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI2->id.'"></span>
                                '.$PI2->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI2->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDateInstalled.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result2 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result = array(
                'result1' => $result1,
                'result2' => $result2,
                'count1' => $partcount1,
                'count2' => $partcount2,
        );
        
        return json_encode($result);
    }

    public function installPI(Request $request){
        $partinfo = UnitParts::find($request->PIID);
        $partinfo->PIDateInstalled = $request->PIDateInstalled;
        $partinfo->update();

        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled=""',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=','']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else{
                    $PIReason = "Machining";
                }

                $result1 .= '
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI1->id.'"></span>
                                '.$PI1->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI1->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateReq.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateRec.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                            '.$PIReason.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result1 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result2 = '';
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!=""',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=','']])->count();

        if(count($partinfo2)>0){
            foreach($partinfo2 as $PI2){

                $result2 .= '
                        <tr class="bg-white border-b hover:bg-gray-200" data-id="'.$PI2->id.'">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI2->id.'"></span>
                                '.$PI2->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI2->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDateInstalled.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result2 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result = array(
                'result1' => $result1,
                'result2' => $result2,
                'count1' => $partcount1,
                'count2' => $partcount2,
        );
        
        return json_encode($result);
    }

    public function deleteIParts(Request $request){
        $RevertParts = UnitParts::find($request->id);
        $RevertParts->delete();

        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled=""',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=','']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else{
                    $PIReason = "Machining";
                }

                $result1 .= '
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI1->id.'"></span>
                                '.$PI1->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI1->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateReq.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateRec.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                            '.$PIReason.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result1 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result2 = '';
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!=""',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=','']])->count();

        if(count($partinfo2)>0){
            foreach($partinfo2 as $PI2){

                $result2 .= '
                        <tr class="bg-white border-b hover:bg-gray-200" data-id="'.$PI2->id.'">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI2->id.'"></span>
                                '.$PI2->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI2->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDateInstalled.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result2 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result = array(
                'result1' => $result1,
                'result2' => $result2,
                'count1' => $partcount1,
                'count2' => $partcount2,
        );
        
        return json_encode($result);
    }

    public function revertParts(Request $request){
        $RevertParts = UnitParts::find($request->id);
        $RevertParts->PIDateInstalled = '';
        $RevertParts->update();

        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled=""',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=','']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else{
                    $PIReason = "Machining";
                }

                $result1 .= '
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI1->id.'"></span>
                                '.$PI1->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI1->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateReq.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI1->PIDateRec.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                            '.$PIReason.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result1 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result2 = '';
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!=""',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=','']])->count();

        if(count($partinfo2)>0){
            foreach($partinfo2 as $PI2){

                $result2 .= '
                        <tr class="bg-white border-b hover:bg-gray-200" data-id="'.$PI2->id.'">
                            <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                <span data-id="'.$PI2->id.'"></span>
                                '.$PI2->PIPartNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDescription.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                               '.$PI2->PIQuantity.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIMRINum.'
                            </td>
                            <td class="px-1 py-0.5 text-center text-xs">
                                '.$PI2->PIDateInstalled.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result2 .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }

        $result = array(
                'result1' => $result1,
                'result2' => $result2,
                'count1' => $partcount1,
                'count2' => $partcount2,
        );
        
        return json_encode($result);
    }

    public function viewSchedule(Request $request){
        $bay = $request->bay;
        $TechSDate = $request->TechSDate;
        $TechEDate = $request->TechEDate;

        $result = '';
        $TechSchedule = DB::select('SELECT technician_schedules.id, technician_schedules.techid, technicians.id AS techid1, technicians.initials AS techname, technician_schedules.baynum, 
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus
                                    FROM technician_schedules
                                    INNER JOIN technicians on technicians.id = technician_schedules.techid
                                    WHERE baynum=? and scheddate BETWEEN ? and ?', [$request->bay, $TechSDate, $TechEDate]);

        if(count($TechSchedule)>0){
            foreach ($TechSchedule as $TS) {
                if($TS->TSStatus == 1){
                    $TStatus = 'PENDING';
                }else{
                    $TStatus = 'DONE';
                }

                $result .='
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                    '.$TS->scheddate.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$TS->techname.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$TS->scopeofwork.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$TS->activity.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$TStatus.'
                                </td>
                            </tr>
                ';
            }
        }else{
            $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }
        return response()->json($result);
    }

    public function saveActivity(Request $request){
        $bay = $request->bay;
        $date = $request->output;

        TechnicianSchedule::where('id', $request->UnitTSID)
        ->update([
            'status' => $request->UnitActivityStatus,
            'remarks' => $request->UnitInfoRemarks,
        ]);

        $result = '';
        $TechSchedule = DB::TABLE('technician_schedules')->WHERE('baynum','=',$bay)
                                                        ->WHERE('scheddate','=',$date)
                                                        ->WHERE('status','!=',3)
                                                        ->first();
                                                        
        if ($TechSchedule === null) {
            $result = array(
                            'UnitTSID' => '',
                            'Activity' => '',
                            'UnitActivityStatus' => '',
                            'UnitInfoRemarks' => '',
            );
        } else {
            $result = array(
                            'UnitTSID' => $TechSchedule->id,
                            'Activity' => $TechSchedule->activity,
                            'UnitActivityStatus' => $TechSchedule->status,
                            'UnitInfoRemarks' => $TechSchedule->remarks,
            );
        }
        return response()->json($result);
    }

    public function saveRemarks(Request $request){
        UnitWorkshop::where('id', $request->WSJONum)
        ->update([
            'WSRemarks' => $request->URemarks,
        ]);
    }

    public function getTransferData(Request $request){
        $result='';
        $bayres = '';
        $WorkShop = DB::TABLE('unit_pull_outs')->WHERE('id','=',$request->WSPOUID)
                                                    ->first();
        
        $bays = DB::select('SELECT bay_areas.id as BayID, bay_areas.area_name as BayName FROM bay_areas WHERE bay_areas.section = ? AND bay_areas.category=1 and status=1', [$WorkShop->POUTransferArea]);

        $curBay = DB::table('bay_areas')->where('id', $request->UnitBayNum)->first();
        $bayres .= '<option hidden value="'.$curBay->id.'">'.$curBay->area_name.'</option>';

        foreach($bays as $bay){
            $bayres .= '<option value="'.$bay->BayID.'">'.$bay->BayName.'</option>';
        }

        $result = array(
            'TransferStatus' => $WorkShop->POUStatus,
            'TransferArea' => $WorkShop->POUTransferArea,
            'TransferBay' => $bayres,
            'TransferRemarks' => $WorkShop->POUTransferRemarks,
        );
        // SELECT('SELECT * FROM unit_pull_outs WHERE unit_pull_outs.id = ?', [$request->WSPOUID]);
        return response()->json($result);
    }

    public function saveTransferUnit(Request $request){
        UnitConfirm::WHERE('POUID', $request->WSPOUID)
                    ->UPDATE([
                        'CUTransferStatus' => $request->UnitStatus,
                        'CUTransferArea' => $request->UnitArea,
                        'CUTransferBay' => $request->UnitBay,
                        'CUTransferRemarks' => $request->UnitRemarks,
                    ]);
 
        UnitPullOut::WHERE('id', $request->WSPOUID)
                    ->UPDATE([
                        'POUStatus' => $request->UnitStatus,
                        'POUTransferArea' => $request->UnitArea,
                        'POUTransferBay' => $request->UnitBay,
                        'POUTransferRemarks' => $request->UnitRemarks,
                    ]);

            if($request->UnitArea == 7){
                $ToA = "3";
            }else if(($request->UnitArea >= 14)){
                $ToA = "1";
            }else if(($request->UnitArea <= 3)){
                $ToA = "1";
            }else{
                $ToA = "2";
            } 

        UnitWorkshop::WHERE('WSPOUID', $request->WSPOUID)
                    ->UPDATE([
                        'WSToA' => $ToA,
                        'WSBayNum' => $request->UnitBay,
                        'WSStatus' => $request->UnitStatus,
                    ]);

        TechnicianSchedule::WHERE('JONumber', $request->UnitInfoJON)
                            ->UPDATE([
                                'baynum' => $request->UnitBay,
                            ]);

        BayArea::WHERE('id',$request->UnitBayNum)
                ->UPDATE([
                    'category' => 1
                ]);

        BayArea::WHERE('id',$request->UnitBay)
                ->UPDATE([
                    'category' => 2
                ]);
    }


    // -------------------------------------------------------------REPORT------------------------------------------------------------------//

    public function indexR(){
        $brand = DB::SELECT('SELECT * FROM brands WHERE status="1"');
        $section = DB::SELECT('SELECT * FROM sections WHERE status="1"');
        $technician = DB::SELECT('SELECT * FROM technicians WHERE status="1"');
        $bay = DB::SELECT('SELECT * FROM bay_areas WHERE category="1" and status="1" ORDER BY bay_areas.id');

        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=2');
        
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=2');

        $cunit = DB::SELECT('SELECT unit_confirms.id, unit_confirms.POUID, unit_confirms.CUTransferDate, unit_confirms.CUTransferRemarks, unit_confirms.CUTransferStatus, unit_confirms.CUTransferArea, unit_confirms.CUTransferBay,
                            unit_pull_outs.POUUnitType, unit_pull_outs.POUCode, unit_pull_outs.POUModel, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastHeight, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, 
                            unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks
                            FROM unit_confirms
                            INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_confirms.POUID
                            WHERE unit_pull_outs.POUBrand = 2
                            ');

        $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_workshops.isBrandNew=0
                        ');

        return view('workshop-ms.bt-workshop.report',compact('brand','section','technician','bay','bnunit','pounit','cunit', 'workshop'));
    }

    public function sortBrand(Request $request){
        $brand = $request->unitBrand;

        $result = '';
        if($brand == 'BrandALL'){
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_workshops.isBrandNew = 0
                        ');
        }else if($brand == 'BrandToyota'){
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_pull_outs.POUBrand = 1 AND unit_workshops.isBrandNew = 0
                                ');
        }else if($brand == 'BrandBT'){
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_pull_outs.POUBrand = 2 AND unit_workshops.isBrandNew = 0
                        ');
        }else{
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_pull_outs.POUBrand = 3 AND unit_workshops.isBrandNew = 0
                        ');
        }

        if(count($workshop)>0){
            foreach ($workshop as $WS) {
                $result .=' 
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                '.$WS->area_name.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->POUCustomer.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->name.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->POUModel.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->POUCode.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->POUSerialNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->POUMastType.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->WSAAIDS.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->WSATIDS.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$WS->initials.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }
        echo $result;
    }

    public function sortPullOut(Request $request){
        $UStatus = $request->unitStatus;

        $result = '';
        if($UStatus == 'pouRadioPOU'){
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND isBrandNew=0 AND POUBrand=2');
        }else if($UStatus == 'pouRadioCU'){
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus=2 AND isBrandNew=0 AND POUBrand=2');
        }else if($UStatus == 'pouRadioDU'){
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus=3 AND isBrandNew=0 AND POUBrand=2');
        }else{
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE isBrandNew=0 AND POUBrand=2');
        }

        if(count($pounit)>0){
            foreach ($pounit as $POU) {
                if ($POU->POUClassification == '1'){
                    $Class = "CLASS A";
                }else if ($POU->POUClassification == '2'){
                    $Class = "CLASS B";
                }elseif ($POU->POUClassification == '3'){
                    $Class = "CLASS C";
                }else{
                    $Class = "CLASS D";
                }

                if($UStatus == 'pouRadioPOU'){
                    $result .=' 
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-3.5 p-1 whitespace-nowrap">
                                    <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                    <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                    <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUDelete" id="btnPOUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                    <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" data-poremarks="'.$POU->POURemarks.'" class="btnPOUTransfer" id="btnPOUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                                </td>
                                <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                    '.$POU->POUArrivalDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUModel.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUSerialNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUMastHeight.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUCustomer.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUCustAddress.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POURemarks.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$Class.'
                                </td>
                            </tr>
                    ';
                }else{
                    $result .=' 
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-3.5 p-1 whitespace-nowrap">
                                    <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                    <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                </td>
                                <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                    '.$POU->POUArrivalDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUModel.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUSerialNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUMastHeight.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUCustomer.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POUCustAddress.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$POU->POURemarks.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$Class.'
                                </td>
                            </tr>
                    ';
                }
            }
        }else{
            $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="px-1 py-0.5 col-span-7 text-center items-center">
                                No data.
                            </td>
                        </tr>
                ';
        }
        echo $result;
    }

    // public function getUnitStatus(Request $request){
    //     $id = $request->id;

    //     $result='';
    //     if($id == 'pouRadioPOU'){
    //         $POUS = DB::SELECT('SELECT * FROM unit_pull_outs WHERE unit_pull_outs.POUStatus=0 AND unit_pull_outs.POUBrand=2');
    //     }else if($id == 'pouRadioCU'){
    //         $POUS = DB::SELECT('SELECT * FROM unit_pull_outs WHERE unit_pull_outs.POUStatus BETWEEN 1 AND 5 AND pull_out_units.POUBrand=2');
    //     }else if($id == 'pouRadioDU'){
    //         $POUS = DB::SELECT('SELECT * FROM unit_pull_outs WHERE unit_pull_outs.POUTransferTo="6" AND unit_pull_outs.POUBrand=2');
    //     }else{
    //         $POUS = DB::SELECT('SELECT * FROM unit_pull_outs WHERE unit_pull_outs.POUBrand=2');
    //     }

    //     if(count($POUS)>0){
    //         foreach ($POUS as $POU) {
    //             if($POU->POUClass == 1){
    //                 $POUClass = 'CLASS A';
    //             }else if($POU->POUClass == 2){
    //                 $POUClass = 'CLASS B';
    //             }else if($POU->POUClass == 3){
    //                 $POUClass = 'CLASS C';
    //             }else{
    //                 $POUClass = 'CLASS D';
    //             }

    //             if($id == "pouRadioPOU"){
    //                 $result .= '<tr class="bg-white border-b hover:bg-gray-200">
    //                                 <td class="w-3.5 p-1 whitespace-nowrap">
    //                                     <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
    //                                     <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
    //                                     <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUDelete" id="btnPOUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
    //                                     <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" data-poremarks="'.$POU->POURemarks.'" class="btnPOUTransfer" id="btnPOUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
    //                                 </td>
    //                                 <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
    //                                     '.$POU->POUArrivalDate.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUCode.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUModel.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUSerialNum.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUMastHeight.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUCustomer.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUCustAddress.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POURemarks.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POUClass.'
    //                                 </td>
    //                             </tr>
    //                 ';
    //             }else{
    //                 $result .= '<tr class="bg-white border-b hover:bg-gray-200">
    //                                 <td class="w-3.5 p-1 whitespace-nowrap">
    //                                     <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
    //                                 </td>
    //                                 <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
    //                                     '.$POU->POUArrivalDate.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUCode.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUModel.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUSerialNum.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUMastHeight.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUCustomer.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POUCustAddress.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POU->POURemarks.'
    //                                 </td>
    //                                 <td class="px-1 py-0.5 text-center">
    //                                     '.$POUClass.'
    //                                 </td>
    //                             </tr>
    //                 ';
    //             }
                
    //         }
    //     }else{
    //         $result .='
    //                     <tr class="bg-white border-b hover:bg-gray-200">
    //                         <td class="px-1 py-0.5 col-span-7 text-center items-center">
    //                             No data.
    //                         </td>
    //                     </tr>
    //             ';
    //     }
    //     echo $result;
    // }

    public function savePullOut(Request $request){
        $POUIDe = $request->POUIDe;
        $PUnitType = $request->POUUnitType;

        if($POUIDe == null){
            if($PUnitType == 1){
                $POU = new UnitPullOut();
                $POU->isBrandNew = 0;
                $POU->POUUnitType = strtoupper($request->POUUnitType);
                $POU->POUArrivalDate = strtoupper($request->POUArrivalDate);
                $POU->POUBrand = strtoupper($request->POUBrand);
                $POU->POUClassification = strtoupper($request->POUClassification);
                $POU->POUModel = strtoupper($request->POUModel);
                $POU->POUSerialNum = strtoupper($request->POUSerialNum);
                $POU->POUCode = strtoupper($request->POUCode);
                $POU->POUMastType = strtoupper($request->POUMastType);
                $POU->POUMastHeight = strtoupper($request->POUMastHeight);
                $POU->POUForkSize = strtoupper($request->POUForkSize);
                    if($request->has('POUwAttachment')){
                        $POU->POUwAttachment = $request->POUwAttachment;
                        if($request->POUAttType != null){
                            $POU->POUAttType = $request->POUAttType;
                        }else{
                            $POU->POUAttType = "";
                        }

                        if($request->POUAttModel != null){
                            $POU->POUAttModel = $request->POUAttModel;
                        }else{
                            $POU->POUAttModel = "";
                        }

                        if($request->POUAttSerialNum != null){
                            $POU->POUAttSerialNum = $request->POUAttSerialNum;
                        }else{
                            $POU->POUAttSerialNum = "";
                        }
                    }else{
                        $POU->POUwAttachment = "";
                        $POU->POUAttType = "";
                        $POU->POUAttModel = "";
                        $POU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('POUwAccesories')){
                        $POU->POUwAccesories = $request->POUwAccesories;
                            if($request->has('POUAccISite')){
                                $POU->POUAccISite = $request->POUAccISite;
                            }else{
                                $POU->POUAccISite = "";
                            }

                            if($request->has('POUAccLiftCam')){
                                $POU->POUAccLiftCam = $request->POUAccLiftCam;
                            }else{
                                $POU->POUAccLiftCam = "";
                            }

                            if($request->has('POUAccRedLight')){
                                $POU->POUAccRedLight = $request->POUAccRedLight;
                            }else{
                                $POU->POUAccRedLight = "";
                            }

                            if($request->has('POUAccBlueLight')){
                                $POU->POUAccBlueLight = $request->POUAccBlueLight;
                            }else{
                                $POU->POUAccBlueLight = "";
                            }

                            if($request->has('POUAccFireExt')){
                                $POU->POUAccFireExt = $request->POUAccFireExt;
                            }else{
                                $POU->POUAccFireExt = "";
                            }

                            if($request->has('POUAccStLight')){
                                $POU->POUAccStLight = $request->POUAccStLight;
                            }else{
                                $POU->POUAccStLight = "";
                            }

                            if($request->has('POUAccOthers')){
                                $POU->POUAccOthers = $request->POUAccOthers;
                                $POU->POUAccOthersDetail = $request->POUAccOthersDetail;
                            }else{
                                $POU->POUAccOthers = "";
                                $POU->POUAccOthersDetail = "";
                            }
                    }else{
                        $POU->POUwAccesories = "";
                        $POU->POUAccISite = "";
                        $POU->POUAccLiftCam = "";
                        $POU->POUAccRedLight = "";
                        $POU->POUAccBlueLight = "";
                        $POU->POUAccFireExt = "";
                        $POU->POUAccStLight = "";
                        $POU->POUAccOthers = "";
                        $POU->POUAccOthersDetail = "";
                    }
                $POU->POUTechnician1 = $request->POUTechnician1;
                    if($request->POUTechnician2 != null){
                        $POU->POUTechnician2 = $request->POUTechnician2;
                    }else{
                        $POU->POUTechnician2 = "";
                    }

                    if($request->POUSalesman != null){
                        $POU->POUSalesman = $request->POUSalesman;
                    }else{
                        $POU->POUSalesman = "";
                    }
                    
                    if($request->POUCustomer != null){
                        $POU->POUCustomer = $request->POUCustomer;
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = $request->POUCustAddress;
                    }else{
                        $POU->POUCustAddress = "";
                    }

                $POU->POURemarks = $request->POURemarks;
                $POU->POUStatus = "";
                $POU->POUTransferArea = "";
                $POU->POUTransferBay = "";
                $POU->POUTransferRemarks = "";
                $POU->save();
            }else{
                $POU = new UnitPullOut();
                $POU->isBrandNew = 0;
                $POU->POUUnitType = strtoupper($request->POUUnitType);
                $POU->POUArrivalDate = strtoupper($request->POUArrivalDate);
                $POU->POUBrand = strtoupper($request->POUBrand);
                $POU->POUClassification = strtoupper($request->POUClassification);
                $POU->POUModel = strtoupper($request->POUModel);
                $POU->POUSerialNum = strtoupper($request->POUSerialNum);
                $POU->POUCode = strtoupper($request->POUCode);
                $POU->POUMastType = strtoupper($request->POUMastType);
                $POU->POUMastHeight = strtoupper($request->POUMastHeight);
                $POU->POUForkSize = strtoupper($request->POUForkSize);
                    if($request->has('POUwAttachment')){
                        $POU->POUwAttachment = $request->POUwAttachment;
                        if($request->POUAttType != null){
                            $POU->POUAttType = $request->POUAttType;
                        }else{
                            $POU->POUAttType = "";
                        }

                        if($request->POUAttModel != null){
                            $POU->POUAttModel = $request->POUAttModel;
                        }else{
                            $POU->POUAttModel = "";
                        }

                        if($request->POUAttSerialNum != null){
                            $POU->POUAttSerialNum = $request->POUAttSerialNum;
                        }else{
                            $POU->POUAttSerialNum = "";
                        }
                    }else{
                        $POU->POUwAttachment = "";
                        $POU->POUAttType = "";
                        $POU->POUAttModel = "";
                        $POU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('POUwAccesories')){
                        $POU->POUwAccesories = $request->POUwAccesories;
                            if($request->has('POUAccISite')){
                                $POU->POUAccISite = $request->POUAccISite;
                            }else{
                                $POU->POUAccISite = "";
                            }

                            if($request->has('POUAccLiftCam')){
                                $POU->POUAccLiftCam = $request->POUAccLiftCam;
                            }else{
                                $POU->POUAccLiftCam = "";
                            }

                            if($request->has('POUAccRedLight')){
                                $POU->POUAccRedLight = $request->POUAccRedLight;
                            }else{
                                $POU->POUAccRedLight = "";
                            }

                            if($request->has('POUAccBlueLight')){
                                $POU->POUAccBlueLight = $request->POUAccBlueLight;
                            }else{
                                $POU->POUAccBlueLight = "";
                            }

                            if($request->has('POUAccFireExt')){
                                $POU->POUAccFireExt = $request->POUAccFireExt;
                            }else{
                                $POU->POUAccFireExt = "";
                            }

                            if($request->has('POUAccStLight')){
                                $POU->POUAccStLight = $request->POUAccStLight;
                            }else{
                                $POU->POUAccStLight = "";
                            }

                            if($request->has('POUAccOthers')){
                                $POU->POUAccOthers = $request->POUAccOthers;
                                $POU->POUAccOthersDetail = $request->POUAccOthersDetail;
                            }else{
                                $POU->POUAccOthers = "";
                                $POU->POUAccOthersDetail = "";
                            }
                    }else{
                        $POU->POUwAccesories = "";
                        $POU->POUAccISite = "";
                        $POU->POUAccLiftCam = "";
                        $POU->POUAccRedLight = "";
                        $POU->POUAccBlueLight = "";
                        $POU->POUAccFireExt = "";
                        $POU->POUAccStLight = "";
                        $POU->POUAccOthers = "";
                        $POU->POUAccOthersDetail = "";
                    }
                $POU->POUTechnician1 = $request->POUTechnician1;
                    if($request->POUTechnician2 != null){
                        $POU->POUTechnician2 = $request->POUTechnician2;
                    }else{
                        $POU->POUTechnician2 = "";
                    }

                    if($request->POUSalesman != null){
                        $POU->POUSalesman = $request->POUSalesman;
                    }else{
                        $POU->POUSalesman = "";
                    }
                    
                    if($request->POUCustomer != null){
                        $POU->POUCustomer = $request->POUCustomer;
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = $request->POUCustAddress;
                    }else{
                        $POU->POUCustAddress = "";
                    }

                $POU->POURemarks = $request->POURemarks;
                $POU->POUStatus = "";
                $POU->POUTransferArea = "";
                $POU->POUTransferBay = "";
                $POU->POUTransferRemarks = "";
                $POU->save();

                $POUB = new UnitPullOutBat();
                $POUB->POUID = $POU->id;
                $POUB->POUBABrand = strtoupper($request->POUBABrand);
                $POUB->POUBABatType = strtoupper($request->POUBABatType);
                $POUB->POUBASerialNum = strtoupper($request->POUBASerialNum);
                $POUB->POUBACode = strtoupper($request->POUBACode);
                $POUB->POUBAAmper = strtoupper($request->POUBAAmper);
                $POUB->POUBAVolt = strtoupper($request->POUBAVolt);
                $POUB->POUBACCable = strtoupper($request->POUBACCable);
                $POUB->POUBACTable = strtoupper($request->POUBACTable);
                
                if($request->has('POUwBatSpare1')){
                    $POUB->POUwSpareBat1 = strtoupper($request->POUwBatSpare1);
                    $POUB->POUSB1Brand = strtoupper($request->POUSB1Brand);
                    $POUB->POUSB1BatType = strtoupper($request->POUSB1BatType);
                    $POUB->POUSB1SerialNum = strtoupper($request->POUSB1SerialNum);
                    $POUB->POUSB1Code = strtoupper($request->POUSB1Code);
                    $POUB->POUSB1Amper = strtoupper($request->POUSB1Amper);
                    $POUB->POUSB1Volt = strtoupper($request->POUSB1Volt);
                    $POUB->POUSB1CCable = strtoupper($request->POUSB1CCable);
                    $POUB->POUSB1CTable = strtoupper($request->POUSB1CTable);
                }else{
                    $POUB->POUwSpareBat1 = "";
                    $POUB->POUSB1Brand = "";
                    $POUB->POUSB1BatType = "";
                    $POUB->POUSB1SerialNum = "";
                    $POUB->POUSB1Code = "";
                    $POUB->POUSB1Amper = "";
                    $POUB->POUSB1Volt = "";
                    $POUB->POUSB1CCable = "";
                    $POUB->POUSB1CTable = "";
                }
                
                if($request->has('POUwBatSpare2')){
                    $POUB->POUwSpareBat2 = strtoupper($request->POUwBatSpare2);
                    $POUB->POUSB2Brand = strtoupper($request->POUSB2Brand);
                    $POUB->POUSB2BatType = strtoupper($request->POUSB2BatType);
                    $POUB->POUSB2SerialNum = strtoupper($request->POUSB2SerialNum);
                    $POUB->POUSB2Code = strtoupper($request->POUSB2Code);
                    $POUB->POUSB2Amper = strtoupper($request->POUSB2Amper);
                    $POUB->POUSB2Volt = strtoupper($request->POUSB2Volt);
                    $POUB->POUSB2CCable = strtoupper($request->POUSB2CCable);
                    $POUB->POUSB2CTable = strtoupper($request->POUSB2CTable);
                }else{
                    $POUB->POUwSpareBat2 = "";
                    $POUB->POUSB2Brand = "";
                    $POUB->POUSB2BatType = "";
                    $POUB->POUSB2SerialNum = "";
                    $POUB->POUSB2Code = "";
                    $POUB->POUSB2Amper = "";
                    $POUB->POUSB2Volt = "";
                    $POUB->POUSB2CCable = "";
                    $POUB->POUSB2CTable = "";
                }
                $POUB->POUCBrand = strtoupper($request->POUCBrand);
                $POUB->POUCModel = strtoupper($request->POUCModel);
                $POUB->POUCSerialNum = strtoupper($request->POUCSerialNum);
                $POUB->POUCCode = strtoupper($request->POUCCode);
                $POUB->POUCAmper = strtoupper($request->POUCAmper);
                $POUB->POUCVolt = strtoupper($request->POUCVolt);
                $POUB->POUCInput = strtoupper($request->POUCInput);
                $POUB->save();
            }
        }else{
            if($PUnitType == 1){
                $POU = UnitPullOut::find($POUIDe);
                $POU->POUUnitType = strtoupper($request->POUUnitType);
                $POU->POUArrivalDate = strtoupper($request->POUArrivalDate);
                $POU->POUBrand = strtoupper($request->POUBrand);
                $POU->POUClassification = strtoupper($request->POUClassification);
                $POU->POUModel = strtoupper($request->POUModel);
                $POU->POUSerialNum = strtoupper($request->POUSerialNum);
                $POU->POUCode = strtoupper($request->POUCode);
                $POU->POUMastType = strtoupper($request->POUMastType);
                $POU->POUMastHeight = strtoupper($request->POUMastHeight);
                $POU->POUForkSize = strtoupper($request->POUForkSize);
                    if($request->has('POUwAttachment')){
                        $POU->POUwAttachment = $request->POUwAttachment;
                        if($request->POUAttType != null){
                            $POU->POUAttType = $request->POUAttType;
                        }else{
                            $POU->POUAttType = "";
                        }

                        if($request->POUAttModel != null){
                            $POU->POUAttModel = $request->POUAttModel;
                        }else{
                            $POU->POUAttModel = "";
                        }

                        if($request->POUAttSerialNum != null){
                            $POU->POUAttSerialNum = $request->POUAttSerialNum;
                        }else{
                            $POU->POUAttSerialNum = "";
                        }
                    }else{
                        $POU->POUwAttachment = "";
                        $POU->POUAttType = "";
                        $POU->POUAttModel = "";
                        $POU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('POUwAccesories')){
                        $POU->POUwAccesories = $request->POUwAccesories;
                            if($request->has('POUAccISite')){
                                $POU->POUAccISite = $request->POUAccISite;
                            }else{
                                $POU->POUAccISite = "";
                            }

                            if($request->has('POUAccLiftCam')){
                                $POU->POUAccLiftCam = $request->POUAccLiftCam;
                            }else{
                                $POU->POUAccLiftCam = "";
                            }

                            if($request->has('POUAccRedLight')){
                                $POU->POUAccRedLight = $request->POUAccRedLight;
                            }else{
                                $POU->POUAccRedLight = "";
                            }

                            if($request->has('POUAccBlueLight')){
                                $POU->POUAccBlueLight = $request->POUAccBlueLight;
                            }else{
                                $POU->POUAccBlueLight = "";
                            }

                            if($request->has('POUAccFireExt')){
                                $POU->POUAccFireExt = $request->POUAccFireExt;
                            }else{
                                $POU->POUAccFireExt = "";
                            }

                            if($request->has('POUAccStLight')){
                                $POU->POUAccStLight = $request->POUAccStLight;
                            }else{
                                $POU->POUAccStLight = "";
                            }

                            if($request->has('POUAccOthers')){
                                $POU->POUAccOthers = $request->POUAccOthers;
                                $POU->POUAccOthersDetail = $request->POUAccOthersDetail;
                            }else{
                                $POU->POUAccOthers = "";
                                $POU->POUAccOthersDetail = "";
                            }
                    }else{
                        $POU->POUwAccesories = "";
                        $POU->POUAccISite = "";
                        $POU->POUAccLiftCam = "";
                        $POU->POUAccRedLight = "";
                        $POU->POUAccBlueLight = "";
                        $POU->POUAccFireExt = "";
                        $POU->POUAccStLight = "";
                        $POU->POUAccOthers = "";
                        $POU->POUAccOthersDetail = "";
                    }
                $POU->POUTechnician1 = $request->POUTechnician1;
                    if($request->POUTechnician2 != null){
                        $POU->POUTechnician2 = $request->POUTechnician2;
                    }else{
                        $POU->POUTechnician2 = "";
                    }

                    if($request->POUSalesman != null){
                        $POU->POUSalesman = $request->POUSalesman;
                    }else{
                        $POU->POUSalesman = "";
                    }
                    
                    if($request->POUCustomer != null){
                        $POU->POUCustomer = $request->POUCustomer;
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = $request->POUCustAddress;
                    }else{
                        $POU->POUCustAddress = "";
                    }

                $POU->POURemarks = $request->POURemarks;
                $POU->update();
            }else{
                $POU = UnitPullOut::find($POUIDe);
                $POU->POUUnitType = strtoupper($request->POUUnitType);
                $POU->POUArrivalDate = strtoupper($request->POUArrivalDate);
                $POU->POUBrand = strtoupper($request->POUBrand);
                $POU->POUClassification = strtoupper($request->POUClassification);
                $POU->POUModel = strtoupper($request->POUModel);
                $POU->POUSerialNum = strtoupper($request->POUSerialNum);
                $POU->POUCode = strtoupper($request->POUCode);
                $POU->POUMastType = strtoupper($request->POUMastType);
                $POU->POUMastHeight = strtoupper($request->POUMastHeight);
                $POU->POUForkSize = strtoupper($request->POUForkSize);
                    if($request->has('POUwAttachment')){
                        $POU->POUwAttachment = $request->POUwAttachment;
                        if($request->POUAttType != null){
                            $POU->POUAttType = $request->POUAttType;
                        }else{
                            $POU->POUAttType = "";
                        }

                        if($request->POUAttModel != null){
                            $POU->POUAttModel = $request->POUAttModel;
                        }else{
                            $POU->POUAttModel = "";
                        }

                        if($request->POUAttSerialNum != null){
                            $POU->POUAttSerialNum = $request->POUAttSerialNum;
                        }else{
                            $POU->POUAttSerialNum = "";
                        }
                    }else{
                        $POU->POUwAttachment = "0";
                        $POU->POUAttType = "";
                        $POU->POUAttModel = "";
                        $POU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('POUwAccesories')){
                        $POU->POUwAccesories = $request->POUwAccesories;
                            if($request->has('POUAccISite')){
                                $POU->POUAccISite = $request->POUAccISite;
                            }else{
                                $POU->POUAccISite = "0";
                            }

                            if($request->has('POUAccLiftCam')){
                                $POU->POUAccLiftCam = $request->POUAccLiftCam;
                            }else{
                                $POU->POUAccLiftCam = "";
                            }

                            if($request->has('POUAccRedLight')){
                                $POU->POUAccRedLight = $request->POUAccRedLight;
                            }else{
                                $POU->POUAccRedLight = "";
                            }

                            if($request->has('POUAccBlueLight')){
                                $POU->POUAccBlueLight = $request->POUAccBlueLight;
                            }else{
                                $POU->POUAccBlueLight = "";
                            }

                            if($request->has('POUAccFireExt')){
                                $POU->POUAccFireExt = $request->POUAccFireExt;
                            }else{
                                $POU->POUAccFireExt = "";
                            }

                            if($request->has('POUAccStLight')){
                                $POU->POUAccStLight = $request->POUAccStLight;
                            }else{
                                $POU->POUAccStLight = "";
                            }

                            if($request->has('POUAccOthers')){
                                $POU->POUAccOthers = $request->POUAccOthers;
                                $POU->POUAccOthersDetail = $request->POUAccOthersDetail;
                            }else{
                                $POU->POUAccOthers = "";
                                $POU->POUAccOthersDetail = "";
                            }
                    }else{
                        $POU->POUwAccesories = "";
                        $POU->POUAccISite = "";
                        $POU->POUAccLiftCam = "";
                        $POU->POUAccRedLight = "";
                        $POU->POUAccBlueLight = "";
                        $POU->POUAccFireExt = "";
                        $POU->POUAccStLight = "";
                        $POU->POUAccOthers = "";
                        $POU->POUAccOthersDetail = "";
                    }
                $POU->POUTechnician1 = $request->POUTechnician1;
                    if($request->POUTechnician2 != null){
                        $POU->POUTechnician2 = $request->POUTechnician2;
                    }else{
                        $POU->POUTechnician2 = "";
                    }

                    if($request->POUSalesman != null){
                        $POU->POUSalesman = $request->POUSalesman;
                    }else{
                        $POU->POUSalesman = "";
                    }
                    
                    if($request->POUCustomer != null){
                        $POU->POUCustomer = $request->POUCustomer;
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = $request->POUCustAddress;
                    }else{
                        $POU->POUCustAddress = "";
                    }
                $POU->POURemarks = $request->POURemarks;
                $POU->update();

                $POUB = new UnitPullOutBat();
                $POUB->POUID = $POU->id;
                $POUB->POUBABrand = strtoupper($request->POUBABrand);
                $POUB->POUBABatType = strtoupper($request->POUBABatType);
                $POUB->POUBASerialNum = strtoupper($request->POUBASerialNum);
                $POUB->POUBACode = strtoupper($request->POUBACode);
                $POUB->POUBAAmper = strtoupper($request->POUBAAmper);
                $POUB->POUBAVolt = strtoupper($request->POUBAVolt);
                $POUB->POUBACCable = strtoupper($request->POUBACCable);
                $POUB->POUBACTable = strtoupper($request->POUBACTable);
                
                if($request->has('POUBatSpare1')){
                    $POUB->POUwSpareBat1 = strtoupper($request->POUwBatSpare1);
                    $POUB->POUSB1Brand = strtoupper($request->POUSB1Brand);
                    $POUB->POUSB1BatType = strtoupper($request->POUSB1BatType);
                    $POUB->POUSB1SerialNum = strtoupper($request->POUSB1SerialNum);
                    $POUB->POUSB1Code = strtoupper($request->POUSB1Code);
                    $POUB->POUSB1Amper = strtoupper($request->POUSB1Amper);
                    $POUB->POUSB1Volt = strtoupper($request->POUSB1Volt);
                    $POUB->POUSB1CCable = strtoupper($request->POUSB1CCable);
                    $POUB->POUSB1CTable = strtoupper($request->POUSB1CTable);
                }else{
                    $POUB->POUwSpareBat1 = "";
                    $POUB->POUSB1Brand = "";
                    $POUB->POUSB1BatType = "";
                    $POUB->POUSB1SerialNum = "";
                    $POUB->POUSB1Code = "";
                    $POUB->POUSB1Amper = "";
                    $POUB->POUSB1Volt = "";
                    $POUB->POUSB1CCable = "";
                    $POUB->POUSB1CTable = "";
                }
                
                if($request->has('POUBatSpare2')){
                    $POUB->POUwSpareBat2 = strtoupper($request->POUwBatSpare2);
                    $POUB->POUSB2Brand = strtoupper($request->POUSB2Brand);
                    $POUB->POUSB2BatType = strtoupper($request->POUSB2BatType);
                    $POUB->POUSB2SerialNum = strtoupper($request->POUSB2SerialNum);
                    $POUB->POUSB2Code = strtoupper($request->POUSB2Code);
                    $POUB->POUSB2Amper = strtoupper($request->POUSB2Amper);
                    $POUB->POUSB2Volt = strtoupper($request->POUSB2Volt);
                    $POUB->POUSB2CCable = strtoupper($request->POUSB2CCable);
                    $POUB->POUSB2CTable = strtoupper($request->POUSB2CTable);
                }else{
                    $POUB->POUwSpareBat2 = "";
                    $POUB->POUSB2Brand = "";
                    $POUB->POUSB2BatType = "";
                    $POUB->POUSB2SerialNum = "";
                    $POUB->POUSB2Code = "";
                    $POUB->POUSB2Amper = "";
                    $POUB->POUSB2Volt = "";
                    $POUB->POUSB2CCable = "";
                    $POUB->POUSB2CTable = "";
                }
                $POUB->POUCModel = strtoupper($request->POUCModel);
                $POUB->POUCSerialNum = strtoupper($request->POUCSerialNum);
                $POUB->POUCCode = strtoupper($request->POUCCode);
                $POUB->POUCAmper = strtoupper($request->POUCAmper);
                $POUB->POUCVolt = strtoupper($request->POUCVolt);
                $POUB->POUCInput = strtoupper($request->POUCInput);
                $POUB->update();
            }
        }

        $result = "";
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=2');

        if(count($pounit)>0){
            foreach ($pounit as $POU) {
                if($POU->POUClassification == '1'){
                    $PClass = "CLASS A";
                }else if($POU->POUClassification == '2'){
                    $PClass = "CLASS B";
                }else if($POU->POUClassification == '3'){
                    $PClass = "CLASS C";
                }else{
                    $PClass = "CLASS D";
                }
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="w-3.5 p-1 whitespace-nowrap">
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUDelete" id="btnPOUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" data-poremarks="'.$POU->POURemarks.'" class="btnPOUTransfer" id="btnPOUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                            </td>
                            <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                '.$POU->POUArrivalDate.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCode.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUModel.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUSerialNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUMastHeight.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCustomer.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCustAddress.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POURemarks.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$PClass.'
                            </td>
                        </tr>
                ';
            }

        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }

        echo $result;
    }

    public function getPOUData(Request $request){
        if($request->utype == 1){
            $pounit = DB::TABLE('unit_pull_outs')
                        ->select('unit_pull_outs.id as POUnitIDx','unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'unit_pull_outs.POUBrand', 'unit_pull_outs.POUClassification', 'unit_pull_outs.POUModel', 
                                'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUMastType', 'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 'unit_pull_outs.POUwAttachment', 
                                'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUwAccesories', 'unit_pull_outs.POUAccISite', 'unit_pull_outs.POUAccLiftCam', 
                                'unit_pull_outs.POUAccRedLight', 'unit_pull_outs.POUAccBlueLight', 'unit_pull_outs.POUAccFireExt', 'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthers', 'unit_pull_outs.POUAccOthersDetail', 
                                'unit_pull_outs.POUTechnician1', 'unit_pull_outs.POUTechnician2', 'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks')
                        ->WHERE('unit_pull_outs.id', $request->id)
                        ->first();

                    $result = array(
                        'POUnitIDx' => $pounit->POUnitIDx,
                        'POUUnitType' => $pounit->POUUnitType,
                        'POUArrivalDate' => $pounit->POUArrivalDate,
                        'POUBrand' => $pounit->POUBrand,
                        'POUClassification' => $pounit->POUClassification,
                        'POUModel' => $pounit->POUModel,
                        'POUSerialNum' => $pounit->POUSerialNum,
                        'POUCode' => $pounit->POUCode,
                        'POUMastType' => $pounit->POUMastType,
                        'POUMastHeight' => $pounit->POUMastHeight,
                        'POUForkSize' => $pounit->POUForkSize,
                        'POUwAttachment' => $pounit->POUwAttachment,
                        'POUAttType' => $pounit->POUAttType,
                        'POUAttModel' => $pounit->POUAttModel,
                        'POUAttSerialNum' => $pounit->POUAttSerialNum,
                        'POUwAccesories' => $pounit->POUwAccesories,
                        'POUAccISite' => $pounit->POUAccISite,
                        'POUAccLiftCam' => $pounit->POUAccLiftCam,
                        'POUAccRedLight' => $pounit->POUAccRedLight,
                        'POUAccBlueLight' => $pounit->POUAccBlueLight,
                        'POUAccFireExt' => $pounit->POUAccFireExt,
                        'POUAccStLight' => $pounit->POUAccStLight,
                        'POUAccOthers' => $pounit->POUAccOthers,
                        'POUAccOthersDetail' => $pounit->POUAccOthersDetail,
                        'POUTechnician1' => $pounit->POUTechnician1,
                        'POUTechnician2' => $pounit->POUTechnician2,
                        'POUSalesman' => $pounit->POUSalesman,
                        'POUCustomer' => $pounit->POUCustomer,
                        'POUCustAddress' => $pounit->POUCustAddress,
                        'POURemarks' => $pounit->POURemarks,
                );
        }else{
            $pounit = DB::TABLE('unit_pull_outs')
                                                ->select('unit_pull_outs.id as POUnitIDx','unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'unit_pull_outs.POUBrand', 'unit_pull_outs.POUClassification', 'unit_pull_outs.POUModel', 'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUMastType', 'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 'unit_pull_outs.POUwAttachment', 'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUwAccesories', 'unit_pull_outs.POUAccISite', 'unit_pull_outs.POUAccLiftCam', 'unit_pull_outs.POUAccRedLight', 'unit_pull_outs.POUAccBlueLight', 'unit_pull_outs.POUAccFireExt', 'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthers', 'unit_pull_outs.POUAccOthersDetail', 'unit_pull_outs.POUTechnician1', 'unit_pull_outs.POUTechnician2', 'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks', 'unit_pull_out_bats.id as BatID', 'unit_pull_out_bats.POUID as BatPOUID', 'unit_pull_out_bats.POUBABrand', 'unit_pull_out_bats.POUBABatType', 'unit_pull_out_bats.POUBASerialNum', 'unit_pull_out_bats.POUBACode', 'unit_pull_out_bats.POUBAAmper', 'unit_pull_out_bats.POUBAVolt', 'unit_pull_out_bats.POUBACCable', 'unit_pull_out_bats.POUBACTable', 'unit_pull_out_bats.POUwSpareBat1', 'unit_pull_out_bats.POUSB1Brand', 'unit_pull_out_bats.POUSB1BatType', 'unit_pull_out_bats.POUSB1SerialNum', 'unit_pull_out_bats.POUSB1Code', 'unit_pull_out_bats.POUSB1Amper', 'unit_pull_out_bats.POUSB1Volt', 'unit_pull_out_bats.POUSB1CCable', 'unit_pull_out_bats.POUSB1CTable', 'unit_pull_out_bats.POUwSpareBat2', 'unit_pull_out_bats.POUSB2Brand', 'unit_pull_out_bats.POUSB2BatType', 'unit_pull_out_bats.POUSB2SerialNum', 'unit_pull_out_bats.POUSB2Code', 'unit_pull_out_bats.POUSB2Amper', 'unit_pull_out_bats.POUSB2Volt', 'unit_pull_out_bats.POUSB2CCable', 'unit_pull_out_bats.POUSB2CTable', 'unit_pull_out_bats.POUCBrand', 'unit_pull_out_bats.POUCModel', 'unit_pull_out_bats.POUCSerialNum', 'unit_pull_out_bats.POUCCode', 'unit_pull_out_bats.POUCAmper', 'unit_pull_out_bats.POUCVolt', 'unit_pull_out_bats.POUCInput')
                                                ->join('unit_pull_out_bats', 'unit_pull_outs.id', '=', 'unit_pull_out_bats.POUID')
                                                ->WHERE('unit_pull_outs.id', $request->id)
                                                ->first();

                $result = array(
                    'POUnitIDx' => $pounit->POUnitIDx,
                    'POUUnitType' => $pounit->POUUnitType,
                    'POUArrivalDate' => $pounit->POUArrivalDate,
                    'POUBrand' => $pounit->POUBrand,
                    'POUClassification' => $pounit->POUClassification,
                    'POUModel' => $pounit->POUModel,
                    'POUSerialNum' => $pounit->POUSerialNum,
                    'POUCode' => $pounit->POUCode,
                    'POUMastType' => $pounit->POUMastType,
                    'POUMastHeight' => $pounit->POUMastHeight,
                    'POUForkSize' => $pounit->POUForkSize,
                    'POUwAttachment' => $pounit->POUwAttachment,
                    'POUAttType' => $pounit->POUAttType,
                    'POUAttModel' => $pounit->POUAttModel,
                    'POUAttSerialNum' => $pounit->POUAttSerialNum,
                    'POUwAccesories' => $pounit->POUwAccesories,
                    'POUAccISite' => $pounit->POUAccISite,
                    'POUAccLiftCam' => $pounit->POUAccLiftCam,
                    'POUAccRedLight' => $pounit->POUAccRedLight,
                    'POUAccBlueLight' => $pounit->POUAccBlueLight,
                    'POUAccFireExt' => $pounit->POUAccFireExt,
                    'POUAccStLight' => $pounit->POUAccStLight,
                    'POUAccOthers' => $pounit->POUAccOthers,
                    'POUAccOthersDetail' => $pounit->POUAccOthersDetail,
                    'POUTechnician1' => $pounit->POUTechnician1,
                    'POUTechnician2' => $pounit->POUTechnician2,
                    'POUSalesman' => $pounit->POUSalesman,
                    'POUCustomer' => $pounit->POUCustomer,
                    'POUCustAddress' => $pounit->POUCustAddress,
                    'POUBABrand' => $pounit->POUBABrand,
                    'POUBABatType' => $pounit->POUBABatType,
                    'POUBASerialNum' => $pounit->POUBASerialNum,
                    'POUBACode' => $pounit->POUBACode,
                    'POUBAAmper' => $pounit->POUBAAmper,
                    'POUBAVolt' => $pounit->POUBAVolt,
                    'POUBACCable' => $pounit->POUBACCable,
                    'POUBACTable' => $pounit->POUBACTable,
                    'POUwSpareBat1' => $pounit->POUwSpareBat1,
                    'POUSB1Brand' => $pounit->POUSB1Brand,
                    'POUSB1BatType' => $pounit->POUSB1BatType,
                    'POUSB1SerialNum' => $pounit->POUSB1SerialNum,
                    'POUSB1Code' => $pounit->POUSB1Code,
                    'POUSB1Amper' => $pounit->POUSB1Amper,
                    'POUSB1Volt' => $pounit->POUSB1Volt,
                    'POUSB1CCable' => $pounit->POUSB1CCable,
                    'POUSB1CTable' => $pounit->POUSB1CTable,
                    'POUwSpareBat2' => $pounit->POUwSpareBat2,
                    'POUSB2Brand' => $pounit->POUSB2Brand,
                    'POUSB2BatType' => $pounit->POUSB2BatType,
                    'POUSB2SerialNum' => $pounit->POUSB2SerialNum,
                    'POUSB2Code' => $pounit->POUSB2Code,
                    'POUSB2Amper' => $pounit->POUSB2Amper,
                    'POUSB2Volt' => $pounit->POUSB2Volt,
                    'POUSB2CCable' => $pounit->POUSB2CCable,
                    'POUSB2CTable' => $pounit->POUSB2CTable,
                    'POUCBrand' => $pounit->POUCBrand,
                    'POUCModel' => $pounit->POUCModel,
                    'POUCSerialNum' => $pounit->POUCSerialNum,
                    'POUCCode' => $pounit->POUCCode,
                    'POUCAmper' => $pounit->POUCAmper,
                    'POUCVolt' => $pounit->POUCVolt,
                    'POUCInput' => $pounit->POUCInput,
                    'POURemarks' => $pounit->POURemarks,
            );
        }
        return json_encode($result);
    }

    public function deletePOU(Request $request){
        if($request->unittype == 1){
            $POU = UnitPullOut::find($request->id);
            $POU->delete();
        }else{
            $POU = UnitPullOut::find($request->id);
            $POU->delete();

            UnitPullOutBat::WHERE('POUID', $request->id)->DELETE();
        }

        $result = "";
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=2');

        if(count($pounit)>0){
            foreach ($pounit as $POU) {
                if($POU->POUClassification == '1'){
                    $PClass = "CLASS A";
                }else if($POU->POUClassification == '2'){
                    $PClass = "CLASS B";
                }else if($POU->POUClassification == '3'){
                    $PClass = "CLASS C";
                }else{
                    $PClass = "CLASS D";
                }
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="w-3.5 p-1 whitespace-nowrap">
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUDelete" id="btnPOUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" data-poremarks="'.$POU->POURemarks.'" class="btnPOUTransfer" id="btnPOUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                            </td>
                            <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                '.$POU->POUArrivalDate.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCode.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUModel.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUSerialNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUMastHeight.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCustomer.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCustAddress.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POURemarks.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$PClass.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }

        echo $result;
    }

    public function getBay(Request $request){
        $result = '<option value=""></option>';
        if($request->area == ''){
            $bay = DB::SELECT('SELECT * FROM bay_areas WHERE category="1" AND status="1" ORDER BY bay_areas.id');
        }else{
            $bay = DB::SELECT('SELECT * FROM bay_areas WHERE category="1" AND status="1" AND section=? ORDER BY bay_areas.id',[$request->area]);
        }

        foreach ($bay as $bays) {
            $result .='
                        <option value="'.$bays->id.'">'.$bays->area_name.'</option>
                    ';
        }

        echo $result;
    }

    public function transferPullOut(Request $request){

        $CU = new UnitConfirm();
        $CU->POUID = $request->POUIDx;
        $CU->CUTransferDate = $request->POUTransferDate;
        $CU->CUTransferRemarks = $request->POURemarksT;
        $CU->CUTransferStatus = $request->POUStatus;
        $CU->CUTransferArea	= $request->POUArea;
        $CU->CUTransferBay = $request->POUBay;
        $CU->save();

        if($request->POUArea == 7){
            $ToA = "3";
        }else if(($request->POUArea >= 14)){
            $ToA = "1";
        }else if(($request->POUArea <= 3)){
            $ToA = "2";
        }else{
            $ToA = "2";
        }        

        $WS = new UnitWorkshop();
        $WS->isBrandNew = 0;
        $WS->WSPOUID = $request->POUIDx;
        $WS->WSBayNum = $request->POUBay;
        $WS->WSToA = $ToA;
        $WS->WSStatus = $request->POUStatus;
        $WS->WSUnitType = "";
        $WS->WSVerifiedBy = "";
        $WS->WSUnitCondition = "2";
        $WS->WSATIDS = "";
        $WS->WSATIDE = "";
        $WS->WSATRDS = "";
        $WS->WSATRDE = "";
        $WS->WSAAIDS = "";
        $WS->WSAAIDE = "";
        $WS->WSAARDS = "";
        $WS->WSAARDE = "";
        $WS->WSRemarks = "";
        // $WS->WSStatus = "1";
        $WS->save();

        UnitPullOut::WHERE('id', $request->POUIDx)
                    ->UPDATE([
                        'POUStatus' => strtoupper($request->POUStatus),
                        'POUTransferArea' => strtoupper($request->POUArea),
                        'POUTransferBay' => strtoupper($request->POUBay),
                        'POUTransferRemarks' => strtoupper($request->POURemarksT)
                        ]);

        BayArea::WHERE('id', $request->POUBay)
                ->UPDATE(['category' => "2"]);

        $result = '';
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=2');

        if(count($pounit)>0){
            foreach ($pounit as $POU) {
                if($POU->POUClassification == '1'){
                    $PClass = "CLASS A";
                }else if($POU->POUClassification == '2'){
                    $PClass = "CLASS B";
                }else if($POU->POUClassification == '3'){
                    $PClass = "CLASS C";
                }else{
                    $PClass = "CLASS D";
                }
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="w-3.5 p-1 whitespace-nowrap">
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" class="btnPOUDelete" id="btnPOUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$POU->id.'" data-unittype="'.$POU->POUUnitType.'" data-poremarks="'.$POU->POURemarks.'" class="btnPOUTransfer" id="btnPOUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                            </td>
                            <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                '.$POU->POUArrivalDate.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCode.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUModel.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUSerialNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUMastHeight.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCustomer.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POUCustAddress.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$POU->POURemarks.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$PClass.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }

        echo $result;
    }

    public function deleteCU(Request $request){
        UnitPullOut::WHERE('id', $request->id)
                    ->UPDATE([
                        'POUStatus' => "",
                        'POUTransferArea' => "",
                        'POUTransferBay' => "",
                        'POUTransferRemarks' => ""
                        ]);
        
        BayArea::WHERE('id', $request->cubay)
        ->UPDATE(['category' => "1"]);

        DB::TABLE('unit_confirms')->WHERE('POUID', $request->id)->DELETE();
        DB::TABLE('unit_workshops')->WHERE('WSPOUID', $request->id)->DELETE();
        DB::TABLE('technician_schedules')->WHERE('POUID', $request->id)->DELETE();

        $result = "";
        $cunit = DB::SELECT('SELECT unit_confirms.id, unit_confirms.POUID, unit_confirms.CUTransferDate, unit_confirms.CUTransferRemarks, unit_confirms.CUTransferStatus, unit_confirms.CUTransferArea, unit_confirms.CUTransferBay,
                            unit_pull_outs.POUUnitType, unit_pull_outs.POUCode, unit_pull_outs.POUModel, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastHeight, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks
                            FROM unit_confirms
                            INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_confirms.POUID
                            WHERE unit_pull_outs.POUBrand = 2
                        ');
        if(count($cunit)>0){
            foreach ($cunit as $CU) {
                if($CU->POUClassification == '1'){
                    $PClass = "CLASS A";
                }else if($CU->POUClassification == '2'){
                    $PClass = "CLASS B";
                }else if($CU->POUClassification == '3'){
                    $PClass = "CLASS C";
                }else{
                    $PClass = "CLASS D";
                }

                if($CU->POUStatus == '1'){
                    $PStatus = "WAITING FOR REPAIR UNIT";
                }else if($CU->POUStatus == '2'){
                    $PStatus = "UNDER REPAIR UNIT";
                }else if($CU->POUStatus == '3'){
                    $PStatus = "GOOD UNIT";
                }else if($CU->POUStatus == '4'){
                    $PStatus = "SERVICE UNIT";
                }else{
                    $PStatus = "FOR SCRAP UNIT";
                }

                $result .='
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-3.5 p-1 whitespace-nowrap">
                                    <button type="button" data-id="'.$CU->POUID.'" data-unittype="'.$CU->POUUnitType.'" class="btnCUView" id="btnCUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                    <button type="button" data-id="'.$CU->POUID.'" data-unittype="'.$CU->POUUnitType.'" class="btnCUEdit" id="btnCUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                    <button type="button" data-id="'.$CU->POUID.'" data-cubay="'.$CU->CUTransferBay.'" class="btnCUDelete" id="btnCUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                    <button type="button" data-id="'.$CU->POUID.'" class="btnCUTransfer" id="btnCUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$CU->POUCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$CU->POUModel.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$CU->POUSerialNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$CU->POUMastHeight.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$CU->CUTransferRemarks.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$PClass.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$PStatus.'
                                </td>
                            </tr>
                ';
            }
        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }

        echo $result;
    }

    // BRAND NEW
    public function saveBrandNew(Request $request){
        $NewUnitIDe = $request->BNUIDe;
        $NewUnitType = $request->BNUnitType;

        if($NewUnitIDe == null){
            if($NewUnitType == 1){
                $BNU = new UnitPullOut();
                $BNU->isBrandNew = 1;
                $BNU->POUUnitType = strtoupper($request->BNUnitType);
                $BNU->POUArrivalDate = strtoupper($request->BNUArrivalDate);
                $BNU->POUBrand = strtoupper($request->BNUBrand);
                $BNU->POUClassification = '';
                $BNU->POUModel = strtoupper($request->BNUModel);
                $BNU->POUSerialNum = strtoupper($request->BNUSerialNum);
                $BNU->POUCode = strtoupper($request->BNUCode);
                $BNU->POUMastType = strtoupper($request->BNUMastType);
                $BNU->POUMastHeight = strtoupper($request->BNUMastHeight);
                $BNU->POUForkSize = strtoupper($request->BNUForkSize);
                    if($request->has('BNUwAttachment')){
                        $BNU->POUwAttachment = $request->BNUwAttachment;
                        if($request->BNUAttType != null){
                            $BNU->POUAttType = $request->BNUAttType;
                        }else{
                            $BNU->POUAttType = "";
                        }

                        if($request->BNUAttModel != null){
                            $BNU->POUAttModel = $request->BNUAttModel;
                        }else{
                            $BNU->POUAttModel = "";
                        }

                        if($request->BNUAttSerialNum != null){
                            $BNU->POUAttSerialNum = $request->BNUAttSerialNum;
                        }else{
                            $BNU->POUAttSerialNum = "";
                        }
                    }else{
                        $BNU->POUwAttachment = "";
                        $BNU->POUAttType = "";
                        $BNU->POUAttModel = "";
                        $BNU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('BNUwAccesories')){
                        $BNU->POUwAccesories = $request->BNUwAccesories;
                            if($request->has('BNUAccISite')){
                                $BNU->POUAccISite = $request->BNUAccISite;
                            }else{
                                $BNU->POUAccISite = "";
                            }

                            if($request->has('BNUAccLiftCam')){
                                $BNU->POUAccLiftCam = $request->BNUAccLiftCam;
                            }else{
                                $BNU->POUAccLiftCam = "";
                            }

                            if($request->has('BNUAccRedLight')){
                                $BNU->POUAccRedLight = $request->BNUAccRedLight;
                            }else{
                                $BNU->POUAccRedLight = "";
                            }

                            if($request->has('BNUAccBlueLight')){
                                $BNU->POUAccBlueLight = $request->BNUAccBlueLight;
                            }else{
                                $BNU->POUAccBlueLight = "";
                            }

                            if($request->has('BNUAccFireExt')){
                                $BNU->POUAccFireExt = $request->BNUAccFireExt;
                            }else{
                                $BNU->POUAccFireExt = "";
                            }

                            if($request->has('BNUAccStLight')){
                                $BNU->POUAccStLight = $request->BNUAccStLight;
                            }else{
                                $BNU->POUAccStLight = "";
                            }

                            if($request->has('BNUAccOthers')){
                                $BNU->POUAccOthers = $request->BNUAccOthers;
                                $BNU->POUAccOthersDetail = $request->BNUAccOthersDetail;
                            }else{
                                $BNU->POUAccOthers = "";
                                $BNU->POUAccOthersDetail = "";
                            }
                    }else{
                        $BNU->POUwAccesories = "";
                        $BNU->POUAccISite = "";
                        $BNU->POUAccLiftCam = "";
                        $BNU->POUAccRedLight = "";
                        $BNU->POUAccBlueLight = "";
                        $BNU->POUAccFireExt = "";
                        $BNU->POUAccStLight = "";
                        $BNU->POUAccOthers = "";
                        $BNU->POUAccOthersDetail = "";
                    }
                $BNU->POUTechnician1 = $request->BNUTechnician1;
                    if($request->BNUTechnician2 != null){
                        $BNU->POUTechnician2 = $request->BNUTechnician2;
                    }else{
                        $BNU->POUTechnician2 = "";
                    }

                    if($request->BNUSalesman != null){
                        $BNU->POUSalesman = $request->BNUSalesman;
                    }else{
                        $BNU->POUSalesman = "";
                    }
                    
                    if($request->BNUCustomer != null){
                        $BNU->POUCustomer = $request->BNUCustomer;
                    }else{
                        $BNU->POUCustomer = "";
                    }

                    if($request->BNUCustAddress != null){
                        $BNU->POUCustAddress = $request->BNUCustAddress;
                    }else{
                        $BNU->POUCustAddress = "";
                    }

                $BNU->POURemarks = $request->BNURemarks;
                $BNU->POUStatus = "";
                $BNU->POUTransferArea = "";
                $BNU->POUTransferBay = "";
                $BNU->POUTransferRemarks = "";
                $BNU->save();
            }else{
                $BNU = new UnitPullOut();
                $BNU->isBrandNew = 1;
                $BNU->POUUnitType = strtoupper($request->BNUnitType);
                $BNU->POUArrivalDate = strtoupper($request->BNUArrivalDate);
                $BNU->POUBrand = strtoupper($request->BNUBrand);
                $BNU->POUClassification = '';
                $BNU->POUModel = strtoupper($request->BNUModel);
                $BNU->POUSerialNum = strtoupper($request->BNUSerialNum);
                $BNU->POUCode = strtoupper($request->BNUCode);
                $BNU->POUMastType = strtoupper($request->BNUMastType);
                $BNU->POUMastHeight = strtoupper($request->BNUMastHeight);
                $BNU->POUForkSize = strtoupper($request->BNUForkSize);
                    if($request->has('BNUwAttachment')){
                        $BNU->POUwAttachment = $request->BNUwAttachment;
                        if($request->BNUAttType != null){
                            $BNU->POUAttType = $request->BNUAttType;
                        }else{
                            $BNU->POUAttType = "";
                        }

                        if($request->BNUAttModel != null){
                            $BNU->POUAttModel = $request->BNUAttModel;
                        }else{
                            $BNU->POUAttModel = "";
                        }

                        if($request->BNUAttSerialNum != null){
                            $BNU->POUAttSerialNum = $request->BNUAttSerialNum;
                        }else{
                            $BNU->POUAttSerialNum = "";
                        }
                    }else{
                        $BNU->POUwAttachment = "";
                        $BNU->POUAttType = "";
                        $BNU->POUAttModel = "";
                        $BNU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('BNUwAccesories')){
                        $BNU->POUwAccesories = $request->BNUwAccesories;
                            if($request->has('BNUAccISite')){
                                $BNU->POUAccISite = $request->BNUAccISite;
                            }else{
                                $BNU->POUAccISite = "";
                            }

                            if($request->has('BNUAccLiftCam')){
                                $BNU->POUAccLiftCam = $request->BNUAccLiftCam;
                            }else{
                                $BNU->POUAccLiftCam = "";
                            }

                            if($request->has('BNUAccRedLight')){
                                $BNU->POUAccRedLight = $request->BNUAccRedLight;
                            }else{
                                $BNU->POUAccRedLight = "";
                            }

                            if($request->has('BNUAccBlueLight')){
                                $BNU->POUAccBlueLight = $request->BNUAccBlueLight;
                            }else{
                                $BNU->POUAccBlueLight = "";
                            }

                            if($request->has('BNUAccFireExt')){
                                $BNU->POUAccFireExt = $request->BNUAccFireExt;
                            }else{
                                $BNU->POUAccFireExt = "";
                            }

                            if($request->has('BNUAccStLight')){
                                $BNU->POUAccStLight = $request->BNUAccStLight;
                            }else{
                                $BNU->POUAccStLight = "";
                            }

                            if($request->has('BNUAccOthers')){
                                $BNU->POUAccOthers = $request->BNUAccOthers;
                                $BNU->POUAccOthersDetail = $request->BNUAccOthersDetail;
                            }else{
                                $BNU->POUAccOthers = "";
                                $BNU->POUAccOthersDetail = "";
                            }
                    }else{
                        $BNU->POUwAccesories = "";
                        $BNU->POUAccISite = "";
                        $BNU->POUAccLiftCam = "";
                        $BNU->POUAccRedLight = "";
                        $BNU->POUAccBlueLight = "";
                        $BNU->POUAccFireExt = "";
                        $BNU->POUAccStLight = "";
                        $BNU->POUAccOthers = "";
                        $BNU->POUAccOthersDetail = "";
                    }
                $BNU->POUTechnician1 = $request->BNUTechnician1;
                    if($request->BNUTechnician2 != null){
                        $BNU->POUTechnician2 = $request->BNUTechnician2;
                    }else{
                        $BNU->POUTechnician2 = "";
                    }

                    if($request->BNUSalesman != null){
                        $BNU->POUSalesman = $request->BNUSalesman;
                    }else{
                        $BNU->POUSalesman = "";
                    }
                    
                    if($request->BNUCustomer != null){
                        $BNU->POUCustomer = $request->BNUCustomer;
                    }else{
                        $BNU->POUCustomer = "";
                    }

                    if($request->BNUCustAddress != null){
                        $BNU->POUCustAddress = $request->BNUCustAddress;
                    }else{
                        $BNU->POUCustAddress = "";
                    }

                $BNU->POURemarks = $request->BNURemarks;
                $BNU->POUStatus = "";
                $BNU->POUTransferArea = "";
                $BNU->POUTransferBay = "";
                $BNU->POUTransferRemarks = "";
                $BNU->save();

                $BNUB = new UnitPullOutBat();
                $BNUB->POUID = $BNU->id;
                $BNUB->POUBABrand = strtoupper($request->BNUBABrand);
                $BNUB->POUBABatType = strtoupper($request->BNUBABatType);
                $BNUB->POUBASerialNum = strtoupper($request->BNUBASerialNum);
                $BNUB->POUBACode = strtoupper($request->BNUBACode);
                $BNUB->POUBAAmper = strtoupper($request->BNUBAAmper);
                $BNUB->POUBAVolt = strtoupper($request->BNUBAVolt);
                $BNUB->POUBACCable = strtoupper($request->BNUBACCable);
                $BNUB->POUBACTable = strtoupper($request->BNUBACTable);
                
                if($request->has('BNUwBatSpare1')){
                    $BNUB->POUwSpareBat1 = strtoupper($request->BNUwBatSpare1);
                    $BNUB->POUSB1Brand = strtoupper($request->BNUSB1Brand);
                    $BNUB->POUSB1BatType = strtoupper($request->BNUSB1BatType);
                    $BNUB->POUSB1SerialNum = strtoupper($request->BNUSB1SerialNum);
                    $BNUB->POUSB1Code = strtoupper($request->BNUSB1Code);
                    $BNUB->POUSB1Amper = strtoupper($request->BNUSB1Amper);
                    $BNUB->POUSB1Volt = strtoupper($request->BNUSB1Volt);
                    $BNUB->POUSB1CCable = strtoupper($request->BNUSB1CCable);
                    $BNUB->POUSB1CTable = strtoupper($request->BNUSB1CTable);
                }else{
                    $BNUB->POUwSpareBat1 = "";
                    $BNUB->POUSB1Brand = "";
                    $BNUB->POUSB1BatType = "";
                    $BNUB->POUSB1SerialNum = "";
                    $BNUB->POUSB1Code = "";
                    $BNUB->POUSB1Amper = "";
                    $BNUB->POUSB1Volt = "";
                    $BNUB->POUSB1CCable = "";
                    $BNUB->POUSB1CTable = "";
                }
                
                if($request->has('BNUwBatSpare2')){
                    $BNUB->POUwSpareBat2 = strtoupper($request->BNUwBatSpare2);
                    $BNUB->POUSB2Brand = strtoupper($request->BNUSB2Brand);
                    $BNUB->POUSB2BatType = strtoupper($request->BNUSB2BatType);
                    $BNUB->POUSB2SerialNum = strtoupper($request->BNUSB2SerialNum);
                    $BNUB->POUSB2Code = strtoupper($request->BNUSB2Code);
                    $BNUB->POUSB2Amper = strtoupper($request->BNUSB2Amper);
                    $BNUB->POUSB2Volt = strtoupper($request->BNUSB2Volt);
                    $BNUB->POUSB2CCable = strtoupper($request->BNUSB2CCable);
                    $BNUB->POUSB2CTable = strtoupper($request->BNUSB2CTable);
                }else{
                    $BNUB->POUwSpareBat2 = "";
                    $BNUB->POUSB2Brand = "";
                    $BNUB->POUSB2BatType = "";
                    $BNUB->POUSB2SerialNum = "";
                    $BNUB->POUSB2Code = "";
                    $BNUB->POUSB2Amper = "";
                    $BNUB->POUSB2Volt = "";
                    $BNUB->POUSB2CCable = "";
                    $BNUB->POUSB2CTable = "";
                }
                $BNUB->POUCBrand = strtoupper($request->BNUCBrand);
                $BNUB->POUCModel = strtoupper($request->BNUCModel);
                $BNUB->POUCSerialNum = strtoupper($request->BNUCSerialNum);
                $BNUB->POUCCode = strtoupper($request->BNUCCode);
                $BNUB->POUCAmper = strtoupper($request->BNUCAmper);
                $BNUB->POUCVolt = strtoupper($request->BNUCVolt);
                $BNUB->POUCInput = strtoupper($request->BNUCInput);
                $BNUB->save();
            }
        }else{
            if($NewUnitType == 1){
                $BNU = UnitPullOut::find($NewUnitIDe);
                $BNU->isBrandNew = 1;
                $BNU->POUUnitType = strtoupper($request->BNUnitType);
                $BNU->POUArrivalDate = strtoupper($request->BNUArrivalDate);
                $BNU->POUBrand = strtoupper($request->BNUBrand);
                $BNU->POUClassification = '';
                $BNU->POUModel = strtoupper($request->BNUModel);
                $BNU->POUSerialNum = strtoupper($request->BNUSerialNum);
                $BNU->POUCode = strtoupper($request->BNUCode);
                $BNU->POUMastType = strtoupper($request->BNUMastType);
                $BNU->POUMastHeight = strtoupper($request->BNUMastHeight);
                $BNU->POUForkSize = strtoupper($request->BNUForkSize);
                    if($request->has('BNUwAttachment')){
                        $BNU->POUwAttachment = $request->BNUwAttachment;
                        if($request->BNUAttType != null){
                            $BNU->POUAttType = $request->BNUAttType;
                        }else{
                            $BNU->POUAttType = "";
                        }

                        if($request->BNUAttModel != null){
                            $BNU->POUAttModel = $request->BNUAttModel;
                        }else{
                            $BNU->POUAttModel = "";
                        }

                        if($request->BNUAttSerialNum != null){
                            $BNU->POUAttSerialNum = $request->BNUAttSerialNum;
                        }else{
                            $BNU->POUAttSerialNum = "";
                        }
                    }else{
                        $BNU->POUwAttachment = "";
                        $BNU->POUAttType = "";
                        $BNU->POUAttModel = "";
                        $BNU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('BNUwAccesories')){
                        $BNU->POUwAccesories = $request->BNUwAccesories;
                            if($request->has('BNUAccISite')){
                                $BNU->POUAccISite = $request->BNUAccISite;
                            }else{
                                $BNU->POUAccISite = "";
                            }

                            if($request->has('BNUAccLiftCam')){
                                $BNU->POUAccLiftCam = $request->BNUAccLiftCam;
                            }else{
                                $BNU->POUAccLiftCam = "";
                            }

                            if($request->has('BNUAccRedLight')){
                                $BNU->POUAccRedLight = $request->BNUAccRedLight;
                            }else{
                                $BNU->POUAccRedLight = "";
                            }

                            if($request->has('BNUAccBlueLight')){
                                $BNU->POUAccBlueLight = $request->BNUAccBlueLight;
                            }else{
                                $BNU->POUAccBlueLight = "";
                            }

                            if($request->has('BNUAccFireExt')){
                                $BNU->POUAccFireExt = $request->BNUAccFireExt;
                            }else{
                                $BNU->POUAccFireExt = "";
                            }

                            if($request->has('BNUAccStLight')){
                                $BNU->POUAccStLight = $request->BNUAccStLight;
                            }else{
                                $BNU->POUAccStLight = "";
                            }

                            if($request->has('BNUAccOthers')){
                                $BNU->POUAccOthers = $request->BNUAccOthers;
                                $BNU->POUAccOthersDetail = $request->BNUAccOthersDetail;
                            }else{
                                $BNU->POUAccOthers = "";
                                $BNU->POUAccOthersDetail = "";
                            }
                    }else{
                        $BNU->POUwAccesories = "";
                        $BNU->POUAccISite = "";
                        $BNU->POUAccLiftCam = "";
                        $BNU->POUAccRedLight = "";
                        $BNU->POUAccBlueLight = "";
                        $BNU->POUAccFireExt = "";
                        $BNU->POUAccStLight = "";
                        $BNU->POUAccOthers = "";
                        $BNU->POUAccOthersDetail = "";
                    }
                $BNU->POUTechnician1 = $request->BNUTechnician1;
                    if($request->BNUTechnician2 != null){
                        $BNU->POUTechnician2 = $request->BNUTechnician2;
                    }else{
                        $BNU->POUTechnician2 = "";
                    }

                    if($request->BNUSalesman != null){
                        $BNU->POUSalesman = $request->BNUSalesman;
                    }else{
                        $BNU->POUSalesman = "";
                    }
                    
                    if($request->BNUCustomer != null){
                        $BNU->POUCustomer = $request->BNUCustomer;
                    }else{
                        $BNU->POUCustomer = "";
                    }

                    if($request->BNUCustAddress != null){
                        $BNU->POUCustAddress = $request->BNUCustAddress;
                    }else{
                        $BNU->POUCustAddress = "";
                    }

                $BNU->POURemarks = $request->BNURemarks;
                $BNU->update();
            }else{
                $BNU = UnitPullOut::find($NewUnitIDe);
                $BNU->isBrandNew = 1;
                $BNU->POUUnitType = strtoupper($request->BNUnitType);
                $BNU->POUArrivalDate = strtoupper($request->BNUArrivalDate);
                $BNU->POUBrand = strtoupper($request->BNUBrand);
                $BNU->POUClassification = '';
                $BNU->POUModel = strtoupper($request->BNUModel);
                $BNU->POUSerialNum = strtoupper($request->BNUSerialNum);
                $BNU->POUCode = strtoupper($request->BNUCode);
                $BNU->POUMastType = strtoupper($request->BNUMastType);
                $BNU->POUMastHeight = strtoupper($request->BNUMastHeight);
                $BNU->POUForkSize = strtoupper($request->BNUForkSize);
                    if($request->has('BNUwAttachment')){
                        $BNU->POUwAttachment = $request->BNUwAttachment;
                        if($request->BNUAttType != null){
                            $BNU->POUAttType = $request->BNUAttType;
                        }else{
                            $BNU->POUAttType = "";
                        }

                        if($request->BNUAttModel != null){
                            $BNU->POUAttModel = $request->BNUAttModel;
                        }else{
                            $BNU->POUAttModel = "";
                        }

                        if($request->BNUAttSerialNum != null){
                            $BNU->POUAttSerialNum = $request->BNUAttSerialNum;
                        }else{
                            $BNU->POUAttSerialNum = "";
                        }
                    }else{
                        $BNU->POUwAttachment = "0";
                        $BNU->POUAttType = "";
                        $BNU->POUAttModel = "";
                        $BNU->POUAttSerialNum = "";
                    }
                    
                    if($request->has('BNUwAccesories')){
                        $BNU->POUwAccesories = $request->BNUwAccesories;
                            if($request->has('BNUAccISite')){
                                $BNU->POUAccISite = $request->BNUAccISite;
                            }else{
                                $BNU->POUAccISite = "0";
                            }

                            if($request->has('BNUAccLiftCam')){
                                $BNU->POUAccLiftCam = $request->BNUAccLiftCam;
                            }else{
                                $BNU->POUAccLiftCam = "";
                            }

                            if($request->has('BNUAccRedLight')){
                                $BNU->POUAccRedLight = $request->BNUAccRedLight;
                            }else{
                                $BNU->POUAccRedLight = "";
                            }

                            if($request->has('BNUAccBlueLight')){
                                $BNU->POUAccBlueLight = $request->BNUAccBlueLight;
                            }else{
                                $BNU->POUAccBlueLight = "";
                            }

                            if($request->has('BNUAccFireExt')){
                                $BNU->POUAccFireExt = $request->BNUAccFireExt;
                            }else{
                                $BNU->POUAccFireExt = "";
                            }

                            if($request->has('BNUAccStLight')){
                                $BNU->POUAccStLight = $request->BNUAccStLight;
                            }else{
                                $BNU->POUAccStLight = "";
                            }

                            if($request->has('BNUAccOthers')){
                                $BNU->POUAccOthers = $request->BNUAccOthers;
                                $BNU->POUAccOthersDetail = $request->BNUAccOthersDetail;
                            }else{
                                $BNU->POUAccOthers = "";
                                $BNU->POUAccOthersDetail = "";
                            }
                    }else{
                        $BNU->POUwAccesories = "";
                        $BNU->POUAccISite = "";
                        $BNU->POUAccLiftCam = "";
                        $BNU->POUAccRedLight = "";
                        $BNU->POUAccBlueLight = "";
                        $BNU->POUAccFireExt = "";
                        $BNU->POUAccStLight = "";
                        $BNU->POUAccOthers = "";
                        $BNU->POUAccOthersDetail = "";
                    }
                $BNU->POUTechnician1 = $request->BNUTechnician1;
                    if($request->BNUTechnician2 != null){
                        $BNU->POUTechnician2 = $request->BNUTechnician2;
                    }else{
                        $BNU->POUTechnician2 = "";
                    }

                    if($request->BNUSalesman != null){
                        $BNU->POUSalesman = $request->BNUSalesman;
                    }else{
                        $BNU->POUSalesman = "";
                    }
                    
                    if($request->BNUCustomer != null){
                        $BNU->POUCustomer = $request->BNUCustomer;
                    }else{
                        $BNU->POUCustomer = "";
                    }

                    if($request->BNUCustAddress != null){
                        $BNU->POUCustAddress = $request->BNUCustAddress;
                    }else{
                        $BNU->POUCustAddress = "";
                    }
                $BNU->BNURemarks = $request->BNURemarks;
                $BNU->update();

                $BNUB = new UnitPullOutBat();
                $BNUB->POUID = $BNU->id;
                $BNUB->POUBABrand = strtoupper($request->BNUBABrand);
                $BNUB->POUBABatType = strtoupper($request->BNUBABatType);
                $BNUB->POUBASerialNum = strtoupper($request->BNUBASerialNum);
                $BNUB->POUBACode = strtoupper($request->BNUBACode);
                $BNUB->POUBAAmper = strtoupper($request->BNUBAAmper);
                $BNUB->POUBAVolt = strtoupper($request->BNUBAVolt);
                $BNUB->POUBACCable = strtoupper($request->BNUBACCable);
                $BNUB->POUBACTable = strtoupper($request->BNUBACTable);
                
                if($request->has('BNUBatSpare1')){
                    $BNUB->POUwSpareBat1 = strtoupper($request->BNUwBatSpare1);
                    $BNUB->POUSB1Brand = strtoupper($request->BNUSB1Brand);
                    $BNUB->POUSB1BatType = strtoupper($request->BNUSB1BatType);
                    $BNUB->POUSB1SerialNum = strtoupper($request->BNUSB1SerialNum);
                    $BNUB->POUSB1Code = strtoupper($request->BNUSB1Code);
                    $BNUB->POUSB1Amper = strtoupper($request->BNUSB1Amper);
                    $BNUB->POUSB1Volt = strtoupper($request->BNUSB1Volt);
                    $BNUB->POUSB1CCable = strtoupper($request->BNUSB1CCable);
                    $BNUB->POUSB1CTable = strtoupper($request->BNUSB1CTable);
                }else{
                    $BNUB->POUwSpareBat1 = "";
                    $BNUB->POUSB1Brand = "";
                    $BNUB->POUSB1BatType = "";
                    $BNUB->POUSB1SerialNum = "";
                    $BNUB->POUSB1Code = "";
                    $BNUB->POUSB1Amper = "";
                    $BNUB->POUSB1Volt = "";
                    $BNUB->POUSB1CCable = "";
                    $BNUB->POUSB1CTable = "";
                }
                
                if($request->has('BNUBatSpare2')){
                    $BNUB->POUwSpareBat2 = strtoupper($request->BNUwBatSpare2);
                    $BNUB->POUSB2Brand = strtoupper($request->BNUSB2Brand);
                    $BNUB->POUSB2BatType = strtoupper($request->BNUSB2BatType);
                    $BNUB->POUSB2SerialNum = strtoupper($request->BNUSB2SerialNum);
                    $BNUB->POUSB2Code = strtoupper($request->BNUSB2Code);
                    $BNUB->POUSB2Amper = strtoupper($request->BNUSB2Amper);
                    $BNUB->POUSB2Volt = strtoupper($request->BNUSB2Volt);
                    $BNUB->POUSB2CCable = strtoupper($request->BNUSB2CCable);
                    $BNUB->POUSB2CTable = strtoupper($request->BNUSB2CTable);
                }else{
                    $BNUB->POUwSpareBat2 = "";
                    $BNUB->POUSB2Brand = "";
                    $BNUB->POUSB2BatType = "";
                    $BNUB->POUSB2SerialNum = "";
                    $BNUB->POUSB2Code = "";
                    $BNUB->POUSB2Amper = "";
                    $BNUB->POUSB2Volt = "";
                    $BNUB->POUSB2CCable = "";
                    $BNUB->POUSB2CTable = "";
                }
                $BNUB->POUCModel = strtoupper($request->BNUCModel);
                $BNUB->POUCSerialNum = strtoupper($request->BNUCSerialNum);
                $BNUB->POUCCode = strtoupper($request->BNUCCode);
                $BNUB->POUCAmper = strtoupper($request->BNUCAmper);
                $BNUB->POUCVolt = strtoupper($request->BNUCVolt);
                $BNUB->POUCInput = strtoupper($request->BNUCInput);
                $BNUB->update();
            }
        }

        $result = "";
        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=2');

        if(count($bnunit)>0){
            foreach ($bnunit as $BNU) {
                
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="w-3.5 p-1 whitespace-nowrap">
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUView" id="btnBNUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUEdit" id="btnBNUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUDelete" id="btnBNUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" data-uremarks="'.$BNU->POURemarks.'" class="btnBNUTransfer" id="btnBNUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                            </td>
                            <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                '.$BNU->POUArrivalDate.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUCode.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUModel.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUSerialNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUMastHeight.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUCustomer.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUCustAddress.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POURemarks.'
                            </td>
                        </tr>
                ';
            }

        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }

        echo $result;
    }

    public function getBNUData(Request $request){
        if($request->utype == 1){
            $bnunit = DB::TABLE('unit_pull_outs')
                        ->select('unit_pull_outs.id as POUnitIDx','unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'unit_pull_outs.POUBrand', 'unit_pull_outs.POUClassification', 'unit_pull_outs.POUModel', 
                                'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUMastType', 'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 'unit_pull_outs.POUwAttachment', 
                                'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUwAccesories', 'unit_pull_outs.POUAccISite', 'unit_pull_outs.POUAccLiftCam', 
                                'unit_pull_outs.POUAccRedLight', 'unit_pull_outs.POUAccBlueLight', 'unit_pull_outs.POUAccFireExt', 'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthers', 'unit_pull_outs.POUAccOthersDetail', 
                                'unit_pull_outs.POUTechnician1', 'unit_pull_outs.POUTechnician2', 'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks')
                        ->WHERE('unit_pull_outs.id', $request->id)
                        ->first();

                    $result = array(
                        'BNUnitIDx' => $bnunit->POUnitIDx,
                        'BNUnitType' => $bnunit->POUUnitType,
                        'BNUArrivalDate' => $bnunit->POUArrivalDate,
                        'BNUBrand' => $bnunit->POUBrand,
                        'BNUClassification' => $bnunit->POUClassification,
                        'BNUModel' => $bnunit->POUModel,
                        'BNUSerialNum' => $bnunit->POUSerialNum,
                        'BNUCode' => $bnunit->POUCode,
                        'BNUMastType' => $bnunit->POUMastType,
                        'BNUMastHeight' => $bnunit->POUMastHeight,
                        'BNUForkSize' => $bnunit->POUForkSize,
                        'BNUwAttachment' => $bnunit->POUwAttachment,
                        'BNUAttType' => $bnunit->POUAttType,
                        'BNUAttModel' => $bnunit->POUAttModel,
                        'BNUAttSerialNum' => $bnunit->POUAttSerialNum,
                        'BNUwAccesories' => $bnunit->POUwAccesories,
                        'BNUAccISite' => $bnunit->POUAccISite,
                        'BNUAccLiftCam' => $bnunit->POUAccLiftCam,
                        'BNUAccRedLight' => $bnunit->POUAccRedLight,
                        'BNUAccBlueLight' => $bnunit->POUAccBlueLight,
                        'BNUAccFireExt' => $bnunit->POUAccFireExt,
                        'BNUAccStLight' => $bnunit->POUAccStLight,
                        'BNUAccOthers' => $bnunit->POUAccOthers,
                        'BNUAccOthersDetail' => $bnunit->POUAccOthersDetail,
                        'BNUTechnician1' => $bnunit->POUTechnician1,
                        'BNUTechnician2' => $bnunit->POUTechnician2,
                        'BNUSalesman' => $bnunit->POUSalesman,
                        'BNUCustomer' => $bnunit->POUCustomer,
                        'BNUCustAddress' => $bnunit->POUCustAddress,
                        'BNURemarks' => $bnunit->POURemarks,
                );
        }else{
            $bnunit = DB::TABLE('unit_pull_outs')
                                                ->select('unit_pull_outs.id as POUnitIDx','unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'unit_pull_outs.POUBrand', 
                                                        'unit_pull_outs.POUClassification', 'unit_pull_outs.POUModel', 'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 
                                                        'unit_pull_outs.POUMastType', 'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 'unit_pull_outs.POUwAttachment', 
                                                        'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUwAccesories', 
                                                        'unit_pull_outs.POUAccISite', 'unit_pull_outs.POUAccLiftCam', 'unit_pull_outs.POUAccRedLight', 'unit_pull_outs.POUAccBlueLight', 
                                                        'unit_pull_outs.POUAccFireExt', 'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthers', 'unit_pull_outs.POUAccOthersDetail', 
                                                        'unit_pull_outs.POUTechnician1', 'unit_pull_outs.POUTechnician2', 'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 
                                                        'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks', 'unit_pull_out_bats.id as BatID', 'unit_pull_out_bats.POUID as BatPOUID', 
                                                        'unit_pull_out_bats.POUBABrand', 'unit_pull_out_bats.POUBABatType', 'unit_pull_out_bats.POUBASerialNum', 'unit_pull_out_bats.POUBACode', 
                                                        'unit_pull_out_bats.POUBAAmper', 'unit_pull_out_bats.POUBAVolt', 'unit_pull_out_bats.POUBACCable', 'unit_pull_out_bats.POUBACTable', 
                                                        'unit_pull_out_bats.POUwSpareBat1', 'unit_pull_out_bats.POUSB1Brand', 'unit_pull_out_bats.POUSB1BatType', 'unit_pull_out_bats.POUSB1SerialNum', 
                                                        'unit_pull_out_bats.POUSB1Code', 'unit_pull_out_bats.POUSB1Amper', 'unit_pull_out_bats.POUSB1Volt', 'unit_pull_out_bats.POUSB1CCable', 
                                                        'unit_pull_out_bats.POUSB1CTable', 'unit_pull_out_bats.POUwSpareBat2', 'unit_pull_out_bats.POUSB2Brand', 'unit_pull_out_bats.POUSB2BatType', 
                                                        'unit_pull_out_bats.POUSB2SerialNum', 'unit_pull_out_bats.POUSB2Code', 'unit_pull_out_bats.POUSB2Amper', 'unit_pull_out_bats.POUSB2Volt', 
                                                        'unit_pull_out_bats.POUSB2CCable', 'unit_pull_out_bats.POUSB2CTable', 'unit_pull_out_bats.POUCBrand', 'unit_pull_out_bats.POUCModel', 
                                                        'unit_pull_out_bats.POUCSerialNum', 'unit_pull_out_bats.POUCCode', 'unit_pull_out_bats.POUCAmper', 'unit_pull_out_bats.POUCVolt', 'unit_pull_out_bats.POUCInput')
                                                ->join('unit_pull_out_bats', 'unit_pull_outs.id', '=', 'unit_pull_out_bats.POUID')
                                                ->WHERE('unit_pull_outs.id', $request->id)
                                                ->first();

                $result = array(
                    'BNUnitIDx' => $bnunit->POUnitIDx,
                    'BNUnitType' => $bnunit->POUUnitType,
                    'BNUArrivalDate' => $bnunit->POUArrivalDate,
                    'BNUBrand' => $bnunit->POUBrand,
                    'BNUClassification' => $bnunit->POUClassification,
                    'BNUModel' => $bnunit->POUModel,
                    'BNUSerialNum' => $bnunit->POUSerialNum,
                    'BNUCode' => $bnunit->POUCode,
                    'BNUMastType' => $bnunit->POUMastType,
                    'BNUMastHeight' => $bnunit->POUMastHeight,
                    'BNUForkSize' => $bnunit->POUForkSize,
                    'BNUwAttachment' => $bnunit->POUwAttachment,
                    'BNUAttType' => $bnunit->POUAttType,
                    'BNUAttModel' => $bnunit->POUAttModel,
                    'BNUAttSerialNum' => $bnunit->POUAttSerialNum,
                    'BNUwAccesories' => $bnunit->POUwAccesories,
                    'BNUAccISite' => $bnunit->POUAccISite,
                    'BNUAccLiftCam' => $bnunit->POUAccLiftCam,
                    'BNUAccRedLight' => $bnunit->POUAccRedLight,
                    'BNUAccBlueLight' => $bnunit->POUAccBlueLight,
                    'BNUAccFireExt' => $bnunit->POUAccFireExt,
                    'BNUAccStLight' => $bnunit->POUAccStLight,
                    'BNUAccOthers' => $bnunit->POUAccOthers,
                    'BNUAccOthersDetail' => $bnunit->POUAccOthersDetail,
                    'BNUTechnician1' => $bnunit->POUTechnician1,
                    'BNUTechnician2' => $bnunit->POUTechnician2,
                    'BNUSalesman' => $bnunit->POUSalesman,
                    'BNUCustomer' => $bnunit->POUCustomer,
                    'BNUCustAddress' => $bnunit->POUCustAddress,
                    'BNUBABrand' => $bnunit->POUBABrand,
                    'BNUBABatType' => $bnunit->POUBABatType,
                    'BNUBASerialNum' => $bnunit->POUBASerialNum,
                    'BNUBACode' => $bnunit->POUBACode,
                    'BNUBAAmper' => $bnunit->POUBAAmper,
                    'BNUBAVolt' => $bnunit->POUBAVolt,
                    'BNUBACCable' => $bnunit->POUBACCable,
                    'BNUBACTable' => $bnunit->POUBACTable,
                    'BNUwSpareBat1' => $bnunit->POUwSpareBat1,
                    'BNUSB1Brand' => $bnunit->POUSB1Brand,
                    'BNUSB1BatType' => $bnunit->POUSB1BatType,
                    'BNUSB1SerialNum' => $bnunit->POUSB1SerialNum,
                    'BNUSB1Code' => $bnunit->POUSB1Code,
                    'BNUSB1Amper' => $bnunit->POUSB1Amper,
                    'BNUSB1Volt' => $bnunit->POUSB1Volt,
                    'BNUSB1CCable' => $bnunit->POUSB1CCable,
                    'BNUSB1CTable' => $bnunit->POUSB1CTable,
                    'BNUwSpareBat2' => $bnunit->POUwSpareBat2,
                    'BNUSB2Brand' => $bnunit->POUSB2Brand,
                    'BNUSB2BatType' => $bnunit->POUSB2BatType,
                    'BNUSB2SerialNum' => $bnunit->POUSB2SerialNum,
                    'BNUSB2Code' => $bnunit->POUSB2Code,
                    'BNUSB2Amper' => $bnunit->POUSB2Amper,
                    'BNUSB2Volt' => $bnunit->POUSB2Volt,
                    'BNUSB2CCable' => $bnunit->POUSB2CCable,
                    'BNUSB2CTable' => $bnunit->POUSB2CTable,
                    'BNUCBrand' => $bnunit->POUCBrand,
                    'BNUCModel' => $bnunit->POUCModel,
                    'BNUCSerialNum' => $bnunit->POUCSerialNum,
                    'BNUCCode' => $bnunit->POUCCode,
                    'BNUCAmper' => $bnunit->POUCAmper,
                    'BNUCVolt' => $bnunit->POUCVolt,
                    'BNUCInput' => $bnunit->POUCInput,
                    'BNURemarks' => $bnunit->POURemarks,
            );
        }
        return json_encode($result);
    }

    public function deleteBNU(Request $request){
        if($request->unittype == 1){
            $BNU = UnitPullOut::find($request->id);
            $BNU->DELETE();
        }else{
            $BNU = UnitPullOut::find($request->id);
            $BNU->DELETE();

            UnitPullOutBat::WHERE('POUUID', $request->id)->DELETE();
        }

        $result = "";
        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=2');

        if(count($bnunit)>0){
            foreach ($bnunit as $BNU) {
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                            <td class="w-3.5 p-1 whitespace-nowrap">
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUView" id="btnBNUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUEdit" id="btnBNUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUDelete" id="btnBNUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" data-uremarks="'.$BNU->POURemarks.'" class="btnBNUTransfer" id="btnBNUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                            </td>
                            <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                '.$BNU->POUArrivalDate.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUCode.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUModel.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUSerialNum.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUMastHeight.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUCustomer.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POUCustAddress.'
                            </td>
                            <td class="px-1 py-0.5 text-center">
                                '.$BNU->POURemarks.'
                            </td>
                        </tr>
                ';
            }
        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }

        echo $result;
    }

    public function transferNewUnit(Request $request){

        if($request->BNUArea == 7){
            $ToA = "3";
        }else if(($request->BNUArea >= 14)){
            $ToA = "1";
        // }else if(($request->BNUArea == 3)){
        //     $ToA = "2";
        }else{
            $ToA = "2";
        }

        $WS = new UnitWorkshop();
        $WS->isBrandNew = 1;
        $WS->WSPOUID = $request->BNUIDx;
        $WS->WSBayNum = $request->BNUBay;
        $WS->WSToA = $ToA;
        $WS->WSStatus = $request->BNUStatus;
        // $WS->WSStatus = "1";
        $WS->WSUnitType = "";
        $WS->WSVerifiedBy = "";
        $WS->WSUnitCondition = "1";
        $WS->WSATIDS = "";
        $WS->WSATIDE = "";
        $WS->WSATRDS = "";
        $WS->WSATRDE = "";
        $WS->WSAAIDS = "";
        $WS->WSAAIDE = "";
        $WS->WSAARDS = "";
        $WS->WSAARDE = "";
        $WS->WSRemarks = "";
        $WS->save();

        UnitPullOut::WHERE('id', $request->BNUIDx)
                    ->UPDATE([
                        'POUStatus' => $request->BNUStatus,
                        'POUTransferArea' => $request->BNUArea,
                        'POUTransferBay' => $request->BNUBay,
                        'POUTransferRemarks' => $request->BNURemarksT
                        ]);

        BayArea::WHERE('id', $request->BNUBay)
                ->UPDATE(['category' => "2"]);

        $result = '';
        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=2');

        if(count($bnunit)>0){
            foreach ($bnunit as $BNU) {
                $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="w-3.5 p-1 whitespace-nowrap">
                            <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUView" id="btnBNUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                            <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUEdit" id="btnBNUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                            <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" class="btnBNUDelete" id="btnBNUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                            <button type="button" data-id="'.$BNU->id.'" data-unittype="'.$BNU->POUUnitType.'" data-uremarks="'.$BNU->POURemarks.'" class="btnBNUTransfer" id="btnBNUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                        </td>
                        <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                            '.$BNU->POUArrivalDate.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POUCode.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POUModel.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POUSerialNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POUMastHeight.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POUCustomer.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POUCustAddress.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$BNU->POURemarks.'
                        </td>
                    </tr>
                ';
            }
        }else{
            $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="px-1 py-0.5 col-span-7 text-center items-center">
                            No data.
                        </td>
                    </tr>
            ';
        }
        echo $result;
    }
}
