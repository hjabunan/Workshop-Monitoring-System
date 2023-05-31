<?php

namespace App\Http\Controllers;

use App\Models\BayArea;
use App\Models\TechnicianSchedule;
use App\Models\UnitConfirm;
use App\Models\UnitDowntime;
use App\Models\UnitParts;
use App\Models\UnitPullOut;
use App\Models\UnitPullOutBat;
use App\Models\UnitWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PPTReportController extends Controller
{
    // -------------------------------------------------------------MONITORING---------------------------------------------------------------//
    public function index(){
        $bays = DB::TABLE('bay_areas')
                ->WHERE('section','5')
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
                                WHERE unit_workshops.WSStatus <= 4
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
        
        return view('workshop-ms.ppt-workshop.index',compact('bays', 'baysT', 'sectionT', 'workshop','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
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
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand,
                                    unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, 
                                    unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials,
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
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, 
                                    unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, 
                                    unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials
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

        UnitWorkshop::WHERE('WSPOUID', $request->WSPOUID)
                    ->UPDATE([
                        'WSBayNum' => $request->UnitBay,
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
}

