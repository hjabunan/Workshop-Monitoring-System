<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BayArea;
use App\Models\Parts;
use App\Models\TechnicianSchedule;
use App\Models\UnitConfirm;
use App\Models\UnitDelivery;
use App\Models\UnitDowntime;
use App\Models\UnitParts;
use App\Models\UnitPullOut;
use App\Models\UnitWorkshop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OVHLReportController extends Controller
{
    // -------------------------------------------------------------MONITORING---------------------------------------------------------------//
    public function index(){
        $bays = DB::TABLE('wms_bay_areas')
                ->WHERE('section','4')
                ->WHERE('status','1')
                ->orderBy('id','asc')->get();

        $baysT = DB::TABLE('wms_bay_areas')
                ->WHERE('status','1')
                ->orderBy('area_name','asc')->get();

                
        $sectionT = DB::SELECT('SELECT * FROM wms_sections WHERE status="1"');
        
        $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification,
                                unit_pull_outs.POUMastHeight, unit_pull_outs.POUTransferDate, 
                                unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, wms_technicians.initials,
                                unit_confirms.CUTransferDate
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                LEFT JOIN unit_confirms on unit_confirms.POUID = unit_workshops.WSPOUID
                                WHERE unit_workshops.WSDelTransfer = 0 and unit_workshops.is_deleted=0 and unit_pull_outs.is_deleted=0 and unit_confirms.is_deleted=0
                            ');
        
        $scl = DB::TABLE('stagings')->get();


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
        
        return view('workshop-ms.ovhl-workshop.index',compact('bays', 'baysT', 'sectionT', 'workshop', 'scl','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
    }
    
    public function getEvents(Request $request){
        $events = DB::table('technician_schedules')
                    ->select('technician_schedules.id as TID','JONumber','baynum','name','scopeofwork','activity', 'scheddate','time_start','time_end','technician_schedules.status as TStatus','remarks')
                    ->leftJoin('wms_technicians','wms_technicians.id','techid')
                    ->where('baynum', '=', $request->bay)
                    ->where('JONumber', '=', $request->JON)
                    ->where('technician_schedules.is_deleted', '=', 0)
                    ->get();
        
        $formattedEvents = [];

        foreach ($events as $event) {
            if($event->TStatus == 1){
                $ecolor = "#FF0000";
            }else if($event->TStatus == 2){
                $ecolor = "#0000FF";
            }else{
                $ecolor = "#008000";
            }

            $formattedEvents[] = [
                'id' => $event->TID,
                'jonum' => $event->JONumber,
                'baynum' => $event->baynum,
                'technician' =>$event->name,
                'scheddate' => $event->scheddate,
                'stime' => $event->time_start,
                'etime' => $event->time_end,
                'sow' => $event->scopeofwork,
                'activity' => $event->activity,
                'status' => $event->TStatus,
                'remarks' => $event->remarks,

                'title' => $event->activity,
                'start' => \Carbon\Carbon::parse($event->scheddate . ' ' . $event->time_start)->toIso8601String(),
                'end' => \Carbon\Carbon::parse($event->scheddate . ' ' . $event->time_end)->toIso8601String(),
                'color' => $ecolor,
            ];
        }
    
        return response()->json($formattedEvents);
    }

    public function getBayData(Request $request){
        $bay = $request->bay;
        $date = $request->output;

        $DT = 0;
        $WSf = (DB::TABLE('unit_workshops')->WHERE('WSBayNum',$bay)->where('WSDelTransfer', 0)->where('is_deleted', 0)->first());
        if($WSf != null){
            $WSIDf =$WSf->id;
            $DT = (DB::TABLE('unit_downtimes')->WHERE('DTJONum',$WSIDf)->where('is_deleted', 0)->get())->count();
        }

        
        $result = '';

        if ($DT>0){
            $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
                                    unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
                                    unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE, unit_workshops.WSRemarks,
                                    wms_bay_areas.area_name, brands.name,
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, 
                                    unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POUTransferDate, 
                                    unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, wms_technicians.initials,
                                    unit_downtimes.id as DTID, unit_downtimes.DTJONum, unit_downtimes.DTSDate, unit_downtimes.DTEDate, unit_downtimes.DTReason, unit_downtimes.DTRemarks, unit_downtimes.DTTDays,
                                    unit_confirms.CUTransferDate
                                    FROM unit_workshops
                                    INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                    INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                    INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                    INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                    LEFT JOIN unit_confirms on unit_confirms.POUID = unit_workshops.WSPOUID
                                    INNER JOIN unit_downtimes on unit_workshops.id = unit_downtimes.DTJONum
                                    WHERE unit_workshops.is_deleted = 0 AND unit_confirms.is_deleted = 0 AND unit_downtimes.is_deleted = 0 AND unit_workshops.WSDelTransfer = 0 AND WSBayNum = ?',[$bay]
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
                    $TechICharge = DB::SELECT('SELECT DISTINCT(techid), wms_technicians.initials as TInitials  
                                                FROM technician_schedules 
                                                INNER JOIN wms_technicians on techid=wms_technicians.id 
                                                WHERE technician_schedules.is_deleted=0 AND baynum=? AND JONumber=?',[$request->bay,$WSIDf]);

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
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

                            $result = array(
                                            'TransferDate' => $WS->POUTransferDate,
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
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

                            $result = array(
                                            'TransferDate' => $WS->POUTransferDate,
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
                                    wms_bay_areas.area_name, brands.name,
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, 
                                    unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POUTransferDate, 
                                    unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, wms_technicians.initials,
                                    unit_confirms.CUTransferDate
                                    FROM unit_workshops
                                    INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                    INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                    INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                    INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                    LEFT JOIN unit_confirms on unit_confirms.POUID = unit_workshops.WSPOUID
                                    WHERE unit_workshops.WSDelTransfer = 0 AND unit_workshops.is_deleted = 0 AND unit_confirms.is_deleted = 0 AND WSBayNum = ?',[$bay]
                                );

            if(count($workshop)>0){
                foreach($workshop as $WS){
                    $TechICharge = DB::SELECT('SELECT DISTINCT(techid), wms_technicians.initials as TInitials  
                                                FROM technician_schedules 
                                                INNER JOIN wms_technicians on techid=wms_technicians.id 
                                                WHERE technician_schedules.is_deleted=0 AND baynum=? AND JONumber=?',[$request->bay,$WSIDf]);

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
                                                                    ->WHERE('is_deleted',0)
                                                                    ->first();
                    
                        if($TechSchedule != null){
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

                            $result = array(
                                            'TransferDate' => $WS->POUTransferDate,
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
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

                            $result = array(
                                            'TransferDate' => $WS->POUTransferDate,
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
        $POUB = UnitPullOut::where('id', $request->UnitInfoPOUID)->WHERE('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->UnitInfoJON)->WHERE('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->UnitInfoJON)
                    ->update([
                        'WSToA' => $request->UnitInfoToA,
                        'WSStatus' => $request->UnitInfoStatus,
                        'WSUnitType' => $request->UnitInfoUType,
                        'WSRemarks' => $request->WSRemarks,
                    ]);

            $updates = DB::table('unit_workshops')
            ->where('id', $request->UnitInfoJON)
            ->WHERE('is_deleted',0)
            ->select('*')
            ->first();

            $excludedFields = ['id', 'created_at', 'updated_at'];

            foreach ($updates as $field => $newValue) {
                if (in_array($field, $excludedFields)) {
                    continue;
                }
            
                $oldValue = $WSB->$field;
            
                if ($oldValue !== $newValue) {
                    $field = ucwords(str_replace('_', ' ', $field));
            
                    $newLog = new ActivityLog();
                    $newLog->table = 'Workshop Table';
                    $newLog->table_key = $request->UnitInfoJON;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }

            UnitPullOut::where('id', $request->UnitInfoPOUID)
            ->update([
                'POUUnitType2' => $request->UnitInfoUType,
            ]);

            $updates1 = DB::table('unit_pull_outs')
            ->where('id', $request->UnitInfoPOUID)
            ->WHERE('is_deleted',0)
            ->select('*')
            ->first();

            $excludedFields1 = ['id', 'created_at', 'updated_at'];

            foreach ($updates1 as $field1 => $newValue) {
                if (in_array($field1, $excludedFields1)) {
                    continue;
                }
            
                $oldValue = $POUB->$field1;
            
                if ($oldValue !== $newValue) {
                    $field1 = ucwords(str_replace('_', ' ', $field1));
            
                    $newLog = new ActivityLog();
                    $newLog->table = 'Pullout Table';
                    $newLog->table_key = $POUB->id;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field1;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }

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
        $POUB = UnitPullOut::where('id', $request->PulloutID)->where('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->JONum)->where('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->JONum)
                    ->update([
                        'WSATIDS' => $request->TIStart,
                        'WSATIDE' => $request->TIEnd,
                        'WSATRDS' => $request->TRStart,
                        'WSATRDE' => $request->TREnd,
                    ]);

            $updates = DB::table('unit_workshops')
            ->where('id', $request->JONum)
            ->where('is_deleted',0)
            ->select('*')
            ->first();

            $excludedFields = ['id', 'created_at', 'updated_at'];

            foreach ($updates as $field => $newValue) {
                if (in_array($field, $excludedFields)) {
                    continue;
                }
            
                $oldValue = $WSB->$field;
            
                if ($oldValue !== $newValue) {
                    $field = ucwords(str_replace('_', ' ', $field));
            
                    $newLog = new ActivityLog();
                    $newLog->table = 'Workshop Table';
                    $newLog->table_key = $request->JONum;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }
    }

    public function updateIDS(Request $request){
        $POUB = UnitPullOut::where('id', $request->PulloutID)->where('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->JONum)->where('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAAIDS' => $request->AIDStart,
                            // 'WSStatus' => 2,
                        ]);

            $updates = DB::table('unit_workshops')
            ->where('id', $request->JONum)
            ->where('is_deleted',0)
            ->select('*')
            ->first();

            $excludedFields = ['id', 'created_at', 'updated_at'];
        
            foreach ($updates as $field => $newValue) {
                if (in_array($field, $excludedFields)) {
                    continue;
                }
        
                $oldValue = $WSB->$field;
        
                if ($oldValue !== $newValue) {
                    $field = ucwords(str_replace('_', ' ', $field));
        
                    $newLog = new ActivityLog();
                    $newLog->table = 'Workshop Table';
                    $newLog->table_key = $request->JONum;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }
    }

    public function updateIDE(Request $request){
        $POUB = UnitPullOut::where('id', $request->PulloutID)->where('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->JONum)->where('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAAIDE' => $request->AIDEnd,
                        ]);

            $updates = DB::table('unit_workshops')
            ->where('id', $request->JONum)
            ->where('is_deleted',0)
            ->select('*')
            ->first();

            $excludedFields = ['id', 'created_at', 'updated_at'];
        
            foreach ($updates as $field => $newValue) {
                if (in_array($field, $excludedFields)) {
                    continue;
                }
        
                $oldValue = $WSB->$field;
        
                if ($oldValue !== $newValue) {
                    $field = ucwords(str_replace('_', ' ', $field));
        
                    $newLog = new ActivityLog();
                    $newLog->table = 'Workshop Table';
                    $newLog->table_key = $request->JONum;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }
    }

    public function updateRDS(Request $request){
        $POUB = UnitPullOut::where('id', $request->PulloutID)->where('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->JONum)->where('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAARDS' => $request->ARDStart,
                        ]);

            $updates = DB::table('unit_workshops')
            ->where('id', $request->JONum)
            ->select('*')
            ->first();

            $excludedFields = ['id', 'created_at', 'updated_at'];
        
            foreach ($updates as $field => $newValue) {
                if (in_array($field, $excludedFields)) {
                    continue;
                }
        
                $oldValue = $WSB->$field;
        
                if ($oldValue !== $newValue) {
                    $field = ucwords(str_replace('_', ' ', $field));
        
                    $newLog = new ActivityLog();
                    $newLog->table = 'Workshop Table';
                    $newLog->table_key = $request->JONum;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }
    }

    public function updateRDE(Request $request){
        $POUB = UnitPullOut::where('id', $request->PulloutID)->where('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->JONum)->where('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->JONum)
                        ->update([
                            'WSAARDE' => $request->ARDEnd,
                        ]);

            $updates = DB::table('unit_workshops')
            ->where('id', $request->JONum)
            ->where('is_deleted',0)
            ->select('*')
            ->first();

            $excludedFields = ['id', 'created_at', 'updated_at'];
        
            foreach ($updates as $field => $newValue) {
                if (in_array($field, $excludedFields)) {
                    continue;
                }
        
                $oldValue = $WSB->$field;
        
                if ($oldValue !== $newValue) {
                    $field = ucwords(str_replace('_', ' ', $field));
        
                    $newLog = new ActivityLog();
                    $newLog->table = 'Workshop Table';
                    $newLog->table_key = $request->JONum;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress = request()->ip();
                    $newLog->save();
                }
            }
    }

    public function resetActual(Request $request){
        $POUB = UnitPullOut::where('id', $request->PulloutID)->where('is_deleted',0)->first();
        $WS = UnitWorkshop::find($request->JONum);
        $WS->WSAAIDS = '';
        $WS->WSAAIDE = '';
        $WS->WSAARDS = '';
        $WS->WSAARDE = '';
            $dirtyAttributes = $WS->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $WS->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Workshop Table';
                $newLog->table_key = $request->JONum;
                $newLog->action = 'UPDATE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $WS->update();
    }

    public function saveDowntime(Request $request){
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        if($request->DTID == ''){
            $downtime = new UnitDowntime();
            $downtime->DTJONum = $request->JONum;
            $downtime->DTSDate = $request->DTSDate;
            $downtime->DTEDate = $request->DTEDate;
            $downtime->DTReason = $request->DTReason;
            $downtime->DTRemarks = strtoupper($request->DTRemarks);
            $downtime->DTTDays = $request->DTTDays;
                $dirtyAttributes = $downtime->getDirty();
            $downtime->save();
                foreach($dirtyAttributes as $attribute => $newValue){
                    $oldValue = $downtime->getOriginal($attribute);

                    $field = ucwords(str_replace('_', ' ', $attribute));

                    $newLog = new ActivityLog();
                    $newLog->table = 'Downtime Table';
                    $newLog->table_key = $downtime->id;
                    $newLog->action = 'ADD';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress =  request()->ip();
                    $newLog->save();
                }
        }else{
            $downtime = UnitDowntime::find($request->DTID);
            $downtime->DTJONum = $request->JONum;
            $downtime->DTSDate = $request->DTSDate;
            $downtime->DTEDate = $request->DTEDate;
            $downtime->DTReason = $request->DTReason;
            $downtime->DTRemarks = strtoupper($request->DTRemarks);
            $downtime->DTTDays = $request->DTTDays;
                $dirtyAttributes = $downtime->getDirty();
                foreach($dirtyAttributes as $attribute => $newValue){
                    $oldValue = $downtime->getOriginal($attribute);

                    $field = ucwords(str_replace('_', ' ', $attribute));

                    $newLog = new ActivityLog();
                    $newLog->table = 'Downtime Table';
                    $newLog->table_key = $request->DTID;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress =  request()->ip();
                    $newLog->save();
                }
            $downtime->update();
        }

        $result ='';
        $DTime = DB::SELECT('SELECT * FROM unit_downtimes WHERE DTJONum=? AND is_deleted=0',[$request->JONum]);

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
        $downtime = DB::TABLE('unit_downtimes')->WHERE('id', $request->DTID)->WHERE('is_deleted', 0)->first();

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
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->first();
        $DT = UnitDowntime::find($request->DTID);
        $DT->is_deleted = 1;
            $dirtyAttributes = $DT->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $DT->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Downtime Table';
                $newLog->table_key = $request->DTID;
                $newLog->action = 'DELETE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $DT->update();

        $result ='';
        $DTime = DB::SELECT('SELECT * FROM unit_downtimes WHERE DTJONum=? AND is_deleted=0',[$request->JONum]);

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
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled="" AND is_deleted=?',[$request->JONum,0]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else if($PI1->PIReason == 2){
                    $PIReason = "Machining";
                }else{
                    $PIReason = "Received";
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
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!="" AND is_deleted=?',[$request->JONum,0]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

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
        
        $PIRemarks = DB::SELECT('SELECT DISTINCT PIRemarks from unit_parts where PIJONum=?',[$request->JONum]);

        $Remarks = '';
        foreach($PIRemarks as $REM){
            $Remarks .= $REM->PIRemarks;
        }

            $result = array(
                    'result1' => $result1,
                    'result2' => $result2,
                    'count1' => $partcount1,
                    'count2' => $partcount2,
                    'remarks' => $Remarks,
            );
        
        return json_encode($result);
    }
    
    public function savePI(Request $request){
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->PIJONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        if($request ->PIID == null){
            $partinfo = new UnitParts();
            $partinfo->PIJONum = $request->PIJONum;
            $partinfo->PIMRINum = $request->PIMRINum;
            $partinfo->PIPartNum = $request->PIPartNum;
            $partinfo->PIPartID = $request->PIPartIDx;
            $partinfo->PIDescription = $request->PIDescription;
            $partinfo->PIQuantity = $request->PIQuantity;
            $partinfo->PIPrice = $request->PIPrice;
            $partinfo->PIDateReq = $request->PIDateReq;
            $partinfo->PIDateRec = $request->PIDateRec;
            $partinfo->PIReason = $request->PIReason;
            $partinfo->PIDateInstalled = '';
            $partinfo->PIRemarks = '';
                $dirtyAttributes = $partinfo->getDirty();
            $partinfo->save();
                foreach($dirtyAttributes as $attribute => $newValue){
                    $oldValue = $partinfo->getOriginal($attribute);

                    $field = ucwords(str_replace('_', ' ', $attribute));

                    $newLog = new ActivityLog();
                    $newLog->table = 'Unit Parts Info Table';
                    $newLog->table_key = $partinfo->id;
                    $newLog->action = 'ADD';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress =  request()->ip();
                    $newLog->save();
                }
        }else{
            $partinfo = UnitParts::find($request->PIID);
            $partinfo->PIJONum = $request->PIJONum;
            $partinfo->PIMRINum = $request->PIMRINum;
            $partinfo->PIPartNum = $request->PIPartNum;
            $partinfo->PIPartID = $request->PIPartIDx;
            $partinfo->PIDescription = $request->PIDescription;
            $partinfo->PIQuantity = $request->PIQuantity;
            $partinfo->PIPrice = $request->PIPrice;
            $partinfo->PIDateReq = $request->PIDateReq;
            $partinfo->PIDateRec = $request->PIDateRec;
            $partinfo->PIReason = $request->PIReason;
            $partinfo->PIDateInstalled = '';
            $partinfo->PIRemarks = '';
                $dirtyAttributes = $partinfo->getDirty();
                foreach($dirtyAttributes as $attribute => $newValue){
                    $oldValue = $partinfo->getOriginal($attribute);

                    $field = ucwords(str_replace('_', ' ', $attribute));

                    $newLog = new ActivityLog();
                    $newLog->table = 'Unit Parts Info Table';
                    $newLog->table_key = $request->PIID;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress =  request()->ip();
                    $newLog->save();
                }
            $partinfo->update();
        }
        
        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled="" AND is_deleted=0',[$request->PIJONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->PIJONum],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else if($PI1->PIReason == 2){
                    $PIReason = "Machining";
                }else{
                    $PIReason = "Received";
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
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!="" AND is_deleted=0',[$request->PIJONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->PIJONum],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

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
        $pinfo = DB::TABLE('unit_parts')->WHERE('id', $request->PIID)->WHERE('is_deleted',0)->first();

        $result = array(
            'PIID' => $pinfo->id,
            'PIMRINum' => $pinfo->PIMRINum,
            'PIPartNum' => $pinfo->PIPartNum,
            'PIDescription' => $pinfo->PIDescription,
            'PIQuantity' => $pinfo->PIQuantity,
            'PIPrice' => $pinfo->PIPrice,
            'PIDateReq' => $pinfo->PIDateReq,
            'PIDateRec' => $pinfo->PIDateRec,
            'PIReason' => $pinfo->PIReason,
        );
        return json_encode($result);
    }

    public function deletePI(Request $request){
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        $partinfo = UnitParts::find($request->PIID);
        $partinfo->is_deleted = 1;
            $dirtyAttributes = $partinfo->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $partinfo->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Unit Parts Info Table';
                $newLog->table_key = $request->PIID;
                $newLog->action = 'DELETE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $partinfo->update();
        
        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled="" AND is_deleted=0',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else if($PI1->PIReason == 2){
                    $PIReason = "Machining";
                }else{
                    $PIReason = "Received";
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
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!="" AND is_deleted=0',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

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
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        $partinfo = UnitParts::find($request->PIID);
        $partinfo->PIPartNum = $request->PartNum;
        $partinfo->PIDescription = $request->Description;
        $partinfo->PIPrice = $request->Price;
        $partinfo->PIDateInstalled = $request->PIDateInstalled;
            $dirtyAttributes = $partinfo->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $partinfo->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Unit Parts Info Table';
                $newLog->table_key = $request->PIID;
                $newLog->action = 'UPDATE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $partinfo->update();

        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled="" AND is_deleted=0',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else if($PI1->PIReason == 2){
                    $PIReason = "Machining";
                }else{
                    $PIReason = "Received";
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
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!="" AND is_deleted=0',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

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
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        $RevertParts = UnitParts::find($request->id);
        $RevertParts->is_deleted = 1;
            $dirtyAttributes = $RevertParts->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $RevertParts->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Unit Parts Info Table';
                $newLog->table_key = $request->id;
                $newLog->action = 'DELETE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $RevertParts->update();

        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled="" AND is_deleted=0',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else if($PI1->PIReason == 2){
                    $PIReason = "Machining";
                }else{
                    $PIReason = "Received";
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
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!="" AND is_deleted=0',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

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
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        $RevertParts = UnitParts::find($request->id);
        $RevertParts->PIDateInstalled = '';
            $dirtyAttributes = $RevertParts->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $RevertParts->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Unit Parts Info Table';
                $newLog->table_key = $request->id;
                $newLog->action = 'UPDATE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $RevertParts->update();

        $result = '';

        $result1 = '';
        $partinfo1 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled="" AND is_deleted=0',[$request->JONum]);
        $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','=',''],['is_deleted','=','0']])->count();

        if(count($partinfo1)>0){
            foreach($partinfo1 as $PI1){
                if($PI1->PIReason == 1){
                    $PIReason = "Back Order";
                }else if($PI1->PIReason == 2){
                    $PIReason = "Machining";
                }else{
                    $PIReason = "Received";
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
        $partinfo2 = DB::SELECT('SELECT * from unit_parts where PIJONum=? and PIDateInstalled!="" AND is_deleted=0',[$request->JONum]);
        $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$request->JONum],['PIDateInstalled','!=',''],['is_deleted','=','0']])->count();

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

    public function search(Request $request){
        $query = $request->value;

        $matches = Parts::where('partno', 'like', "$query%")
                    ->orderBy('partno')
                    ->get();

        $partno = "";
        foreach ($matches as $parts){
            $partno .= '<li data-id="'.$parts->id.'" class="p-2 first:border-0 border-t border-gray-300 hover:bg-gray-200 cursor-pointer">'.$parts->partno.'</li>';
        }

        $partname = $matches->pluck('partname')->first();

        $result = array(
                    'partno' => $partno,
                    'partname' => $partname,
        );

        return json_encode($result);
    }

    public function getPartsInfox(Request $request){
        echo json_encode(Parts::where('id',$request->id)->first());
    }

    public function viewSchedule(Request $request){
        $bay = $request->bay;
        $TechSDate = $request->TechSDate;
        $TechEDate = $request->TechEDate;

        $result = '';
        $TechSchedule = DB::select('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.id AS techid1, wms_technicians.initials AS techname, technician_schedules.baynum, 
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus
                                    FROM technician_schedules
                                    INNER JOIN wms_technicians on wms_technicians.id = technician_schedules.techid
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
        
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->JONum)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();

        $tesc = TechnicianSchedule::where('id', $request->UnitTSID)->latest()->first();
        TechnicianSchedule::where('id', $request->UnitTSID)
        ->update([
            'status' => $request->UnitActivityStatus,
            'remarks' => $request->UnitInfoRemarks,
        ]);

        $updates = DB::table('technician_schedules')
                    ->where('id', $request->UnitTSID)
                    ->where('is_deleted', 0)
                    ->select('*')
                    ->first();

        $excludedFields = ['id', 'created_at', 'updated_at'];
    
        foreach ($updates as $field => $newValue) {
            if (in_array($field, $excludedFields)) {
                continue;
            }
    
            $oldValue = $tesc->$field;
    
            if ($oldValue !== $newValue) {
                $field = ucwords(str_replace('_', ' ', $field));
    
                $newLog = new ActivityLog();
                $newLog->table = 'Tech. Schedule Table';
                $newLog->table_key = $request->UnitTSID;
                $newLog->action = 'UPDATE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress = request()->ip();
                $newLog->save();
            }
        }

        $result = '';
        $TechSchedule = DB::TABLE('technician_schedules')->WHERE('baynum','=',$bay)
                                                        ->WHERE('scheddate','=',$date)
                                                        ->WHERE('status','!=',3)
                                                        ->WHERE('is_deleted',0)
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

    public function saveTActivity(Request $request){
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->TAJONumber)->first();
        $tesc = TechnicianSchedule::where('id', $request->TAID)->latest()->first();

        TechnicianSchedule::where('id', $request->TAID)
        ->update([
            'time_start' => $request->TASTime,
            'time_end' => $request->TAETime,
            'status' => $request->TAStatus,
            'remarks' => $request->TARemarks,
        ]);

        $updates = DB::table('technician_schedules')
                    ->where('id', $request->TAID)
                    ->where('is_deleted', 0)
                    ->select('*')
                    ->first();

        $excludedFields = ['id', 'created_at', 'updated_at'];
    
        foreach ($updates as $field => $newValue) {
            if (in_array($field, $excludedFields)) {
                continue;
            }
    
            $oldValue = $tesc->$field;
    
            if ($oldValue !== $newValue) {
                $field = ucwords(str_replace('_', ' ', $field));
    
                $newLog = new ActivityLog();
                $newLog->table = 'Tech. Schedule Table';
                $newLog->table_key = $request->TAID;
                $newLog->action = 'UPDATE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress = request()->ip();
                $newLog->save();
            }
        }
    }

    public function saveRemarks(Request $request){
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.id', $request->WSJONum)->first();
        $uparts = UnitParts::where('PIJONum', $request->WSJONum)->latest()->first();

        UnitParts::where('PIJONum', $request->WSJONum)
        ->update([
            'PIRemarks' => $request->URemarks,
        ]);
        
        $updates = DB::table('unit_parts')
                    ->where('PIJONum', $request->WSJONum)
                    ->where('is_deleted', '0')
                    ->get();

        foreach ($updates as $updatedRecord) {
            $field = 'PIRemarks';

            $newValue = $updatedRecord->PIRemarks;
            $oldValue = $uparts->PIRemarks;

            if ($oldValue !== $newValue) {
                $field = ucwords(str_replace('_', ' ', $field));

                $newLog = new ActivityLog();
                $newLog->table = 'Unit Parts Info Table';
                $newLog->table_key = $updatedRecord->id;
                $newLog->action = 'UPDATE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress = request()->ip();
                $newLog->save();
            }
        }
    }

    public function getTransferData(Request $request){
        $result='';
        $bayres = '';
        $WorkShop = DB::TABLE('unit_pull_outs')->WHERE('id','=',$request->WSPOUID)
                                                    ->first();
        
        $bays = DB::select('SELECT wms_bay_areas.id as BayID, wms_bay_areas.area_name as BayName FROM wms_bay_areas WHERE wms_bay_areas.section = ? AND wms_bay_areas.category=1 and status=1', [$WorkShop->POUTransferArea]);

        $curBay = DB::table('wms_bay_areas')->where('id', $request->UnitBayNum)->first();
        $bayres .= '<option hidden value="'.$curBay->id.'">'.$curBay->area_name.'</option>';

        foreach($bays as $bay){
            $bayres .= '<option value="'.$bay->BayID.'">'.$bay->BayName.'</option>';
        }

        $result = array(
            'TransferDate' => $WorkShop->POUTransferDate,
            'TransferStatus' => $WorkShop->POUStatus,
            'TransferArea' => $WorkShop->POUTransferArea,
            'TransferBay' => $bayres,
            'TransferRemarks' => $WorkShop->POUTransferRemarks,
        );
        return response()->json($result);
    }

    public function saveTransferUnit(Request $request){
        $POUB = UnitWorkshop::leftJoin('unit_pull_outs','unit_pull_outs.id','unit_workshops.WSPOUID')->where('unit_workshops.WSPOUID', $request->POUIDx)->where('unit_workshops.is_deleted',0)->where('unit_pull_outs.is_deleted',0)->first();
        if($request->input('Radio_Transfer') == 1){
            $CUnit = UnitConfirm::WHERE('POUID',$request->POUIDx)->WHERE('is_deleted',0)->latest()->first();
            UnitConfirm::WHERE('POUID', $request->POUIDx)
                        ->UPDATE([
                            'CUTransferStatus' => $request->UnitStatus,
                            'CUTransferArea' => $request->UnitArea,
                            'CUTransferBay' => $request->UnitBay,
                            'CUTransferRemarks' => $request->UnitRemarksT,
                        ]);
                $updates1 = DB::table('unit_confirms')->where('is_deleted',0)
                ->where('POUID', $request->POUIDx)
                ->select('*')
                ->first();

                $excludedFields = ['id', 'created_at', 'updated_at'];

                foreach ($updates1 as $field => $newValue) {
                    if (in_array($field, $excludedFields)) {
                        continue;
                    }
            
                    $oldValue = $CUnit->$field;
            
                    if ($oldValue !== $newValue) {
                        $field = ucwords(str_replace('_', ' ', $field));
            
                        $newLog = new ActivityLog();
                        $newLog->table = 'Confirm Table';
                        $newLog->table_key = $CUnit->id;
                        $newLog->action = 'UPDATE';
                        $newLog->description = $POUB->POUSerialNum;
                        $newLog->field = $field;
                        $newLog->before = $oldValue;
                        $newLog->after = $newValue;
                        $newLog->user_id = Auth::user()->id;
                        $newLog->ipaddress = request()->ip();
                        $newLog->save();
                    }
                }
        
            // -----------------------------------------------------------------------------------------------------------------------//
            $POUnit = UnitPullOut::WHERE('id',$request->POUIDx)->where('is_deleted',0)->latest()->first();
            UnitPullOut::WHERE('id', $request->POUIDx)
                        ->UPDATE([
                            'POUStatus' => $request->UnitStatus,
                            'POUTransferArea' => $request->UnitArea,
                            'POUTransferBay' => $request->UnitBay,
                            'POUTransferDate' => $request->UnitTransferDate,
                            'POUTransferRemarks' => $request->UnitRemarksT,
                        ]);
                $updates2 = DB::table('unit_pull_outs')
                ->where('id', $request->POUIDx)
                ->select('*')
                ->first();

                foreach ($updates2 as $field => $newValue1) {
                    if (in_array($field, $excludedFields)) {
                        continue;
                    }
            
                    $oldValue1 = $POUnit->$field;
            
                    if ($oldValue1 !== $newValue1) {
                        $field = ucwords(str_replace('_', ' ', $field));
            
                        $newLog = new ActivityLog();
                        $newLog->table = 'Pullout Table';
                        $newLog->table_key = $POUnit->id;
                        $newLog->action = 'UPDATE';
                        $newLog->description = $POUB->POUSerialNum;
                        $newLog->field = $field;
                        $newLog->before = $oldValue1;
                        $newLog->after = $newValue1;
                        $newLog->user_id = Auth::user()->id;
                        $newLog->ipaddress = request()->ip();
                        $newLog->save();
                    }
                }
    
                if($request->UnitArea == 7){
                    $ToA = "3";
                }else if(($request->UnitArea >= 14)){
                    $ToA = "1";
                }else if(($request->UnitArea <= 3)){
                    $ToA = "2";
                }else{
                    $ToA = "2";
                }

            // -----------------------------------------------------------------------------------------------------------------------//
            $WS = UnitWorkshop::WHERE('WSPOUID',$request->POUIDx)->WHERE('is_deleted',0)->latest()->first();
            UnitWorkshop::WHERE('WSPOUID', $request->POUIDx)
                        ->UPDATE([
                            'WSToA' => $ToA,
                            'WSBayNum' => $request->UnitBay,
                            'WSStatus' => $request->UnitStatus,
                        ]);
                $updates3 = DB::table('unit_workshops')
                ->where('WSPOUID', $request->POUIDx)
                ->where('is_deleted', 0)
                ->select('*')
                ->first();

                foreach ($updates3 as $field => $newValue2) {
                    if (in_array($field, $excludedFields)) {
                        continue;
                    }
            
                    $oldValue2 = $WS->$field;
            
                    if ($oldValue2 !== $newValue2) {
                        $field = ucwords(str_replace('_', ' ', $field));
            
                        $newLog = new ActivityLog();
                        $newLog->table = 'Workshop Table';
                        $newLog->table_key = $WS->id;
                        $newLog->action = 'UPDATE';
                        $newLog->description = $POUB->POUSerialNum;
                        $newLog->field = $field;
                        $newLog->before = $oldValue2;
                        $newLog->after = $newValue2;
                        $newLog->user_id = Auth::user()->id;
                        $newLog->ipaddress = request()->ip();
                        $newLog->save();
                    }
                }
    
            // -----------------------------------------------------------------------------------------------------------------------//    
            $TS = TechnicianSchedule::WHERE('POUID',$request->POUIDx)->WHERE('is_deleted',0)->latest()->first();
            
                if ($TS) {
                    TechnicianSchedule::WHERE('JONumber', $request->UnitInfoJON)
                                        ->UPDATE([
                                            'baynum' => $request->UnitBay,
                                        ]);
                        $updates4 = DB::table('technician_schedules')
                        ->where('POUID', $request->POUIDx)
                        ->where('is_deleted',0)
                        ->select('*')
                        ->first();
        
                        foreach ($updates4 as $field => $newValue3) {
                            if (in_array($field, $excludedFields)) {
                                continue;
                            }
                    
                            $oldValue3 = $TS->$field;
                    
                            if ($oldValue3 !== $newValue3) {
                                $field = ucwords(str_replace('_', ' ', $field));
                    
                                $newLog = new ActivityLog();
                                $newLog->table = 'Tech. Schedule Table';
                                $newLog->table_key = $TS->id;
                                $newLog->action = 'UPDATE';
                                $newLog->description = $POUB->POUSerialNum;
                                $newLog->field = $field;
                                $newLog->before = $oldValue3;
                                $newLog->after = $newValue3;
                                $newLog->user_id = Auth::user()->id;
                                $newLog->ipaddress = request()->ip();
                                $newLog->save();
                            }
                        }
                }
            // -----------------------------------------------------------------------------------------------------------------------// 
            BayArea::WHERE('id',$request->BayID)
                    ->UPDATE([
                        'category' => 1
                    ]);
    
            BayArea::WHERE('id',$request->UnitBay)
                    ->UPDATE([
                        'category' => 2
                    ]);
        }else{
            $CUnit = UnitConfirm::WHERE('POUID',$request->POUIDx)->WHERE('is_deleted',0)->latest()->first();
            UnitConfirm::WHERE('POUID', $request->POUIDx)
                        ->UPDATE([
                            'CUDelTransfer' => 1,
                        ]);
                $updates1 = DB::table('unit_confirms')
                ->where('POUID', $request->POUIDx)
                ->WHERE('is_deleted',0)
                ->select('*')
                ->first();

                $excludedFields = ['id', 'created_at', 'updated_at'];

                foreach ($updates1 as $field => $newValue) {
                    if (in_array($field, $excludedFields)) {
                        continue;
                    }
            
                    $oldValue = $CUnit->$field;
            
                    if ($oldValue !== $newValue) {
                        $field = ucwords(str_replace('_', ' ', $field));
            
                        $newLog = new ActivityLog();
                        $newLog->table = 'Confirm Table';
                        $newLog->table_key = $CUnit->id;
                        $newLog->action = 'UPDATE';
                        $newLog->description = $POUB->POUSerialNum;
                        $newLog->field = $field;
                        $newLog->before = $oldValue;
                        $newLog->after = $newValue;
                        $newLog->user_id = Auth::user()->id;
                        $newLog->ipaddress = request()->ip();
                        $newLog->save();
                    }
                }
    
            // ---------------------------------------------- //    
            $WS = UnitWorkshop::WHERE('WSPOUID',$request->POUIDx)->WHERE('is_deleted',0)->latest()->first();
            UnitWorkshop::WHERE('WSPOUID', $request->POUIDx)
                        ->UPDATE([
                            'WSDelTransfer' => 1,
                        ]);
                $updates3 = DB::table('unit_workshops')
                ->where('WSPOUID', $request->POUIDx)
                ->where('is_deleted',0)
                ->select('*')
                ->first();

                foreach ($updates3 as $field => $newValue2) {
                    if (in_array($field, $excludedFields)) {
                        continue;
                    }
            
                    $oldValue2 = $WS->$field;
            
                    if ($oldValue2 !== $newValue2) {
                        $field = ucwords(str_replace('_', ' ', $field));
            
                        $newLog = new ActivityLog();
                        $newLog->table = 'Workshop Table';
                        $newLog->table_key = $WS->id;
                        $newLog->action = 'UPDATE';
                        $newLog->description = $POUB->POUSerialNum;
                        $newLog->field = $field;
                        $newLog->before = $oldValue2;
                        $newLog->after = $newValue2;
                        $newLog->user_id = Auth::user()->id;
                        $newLog->ipaddress = request()->ip();
                        $newLog->save();
                    }
                }
            // -----------------------------------------------------------------------------------------------------------------------//  
            $TS = TechnicianSchedule::WHERE('POUID',$request->POUIDx)->WHERE('is_deleted',0)->latest()->first();

                if ($TS) {
                    TechnicianSchedule::WHERE('JONumber', $request->UnitInfoJON)
                                        ->UPDATE([
                                            'baynum' => $request->UnitBay,
                                        ]);
                        $updates4 = DB::table('technician_schedules')
                        ->where('POUID', $request->POUIDx)
                        ->where('is_deleted',0)
                        ->select('*')
                        ->first();
        
                        foreach ($updates4 as $field => $newValue3) {
                            if (in_array($field, $excludedFields)) {
                                continue;
                            }
                    
                            $oldValue3 = $TS->$field;
                    
                            if ($oldValue3 !== $newValue3) {
                                $field = ucwords(str_replace('_', ' ', $field));
                    
                                $newLog = new ActivityLog();
                                $newLog->table = 'Tech. Schedule Table';
                                $newLog->table_key = $TS->id;
                                $newLog->action = 'UPDATE';
                                $newLog->description = $POUB->POUSerialNum;
                                $newLog->field = $field;
                                $newLog->before = $oldValue3;
                                $newLog->after = $newValue3;
                                $newLog->user_id = Auth::user()->id;
                                $newLog->ipaddress = request()->ip();
                                $newLog->save();
                            }
                        }
                }
            // -----------------------------------------------------------------------------------------------------------------------//  
    
            BayArea::WHERE('id',$request->BayID)
            ->UPDATE([
                'category' => 1
            ]);

                $currentDate = Carbon::now();
                $formattedDate = $currentDate->format('m/d/Y');

            $DU = new UnitDelivery();
            $DU->POUID = $request->POUIDx;
            $DU->DUTransferDate = $formattedDate;
            $DU->DURemarks = strtoupper($request->UnitDelRemarksT);
            $DU->DUDelDate = $request->UnitDelDate;
            $DU->save();
    
            BayArea::WHERE('id',$request->BayID)
                    ->UPDATE([
                        'category' => 1
                    ]);
            
        } 
    }

    public function getBay(Request $request){
        $result = '<option value=""></option>';
        if($request->area == ''){
            $bay = DB::SELECT('SELECT * FROM wms_bay_areas WHERE category="1" AND status="1" ORDER BY wms_bay_areas.id');
        }else{
            $bay = DB::SELECT('SELECT * FROM wms_bay_areas WHERE category="1" AND status="1" AND section=? ORDER BY wms_bay_areas.id',[$request->area]);
        }

        foreach ($bay as $bays) {
            $result .='
                        <option value="'.$bays->id.'">'.$bays->area_name.'</option>
                    ';
        }

        echo $result;
    }
}
