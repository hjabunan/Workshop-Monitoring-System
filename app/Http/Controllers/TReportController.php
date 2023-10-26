<?php

namespace App\Http\Controllers;

use App\Models\BayArea;
use App\Models\CannibalizedParts;
use App\Models\CannibalizedUnit;
use App\Models\DRMonitoring;
use App\Models\DRParts;
use App\Models\Parts;
use App\Models\TechnicianSchedule;
use App\Models\UnitConfirm;
use App\Models\UnitDelivery;
use App\Models\UnitDowntime;
use App\Models\UnitParts;
use App\Models\UnitPullOut;
use App\Models\UnitPullOutBat;
use App\Models\UnitWorkshop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use League\Csv\Writer;

class TReportController extends Controller
{
    // -------------------------------------------------------------MONITORING--------------------------------------------------------------//
    public function index(){
        $bays = DB::TABLE('wms_bay_areas')
                ->WHERE('section','1')
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
                                WHERE unit_workshops.WSDelTransfer = 0
                            ');
                            
        $scl = DB::TABLE('stagings')->get();

        $reason = DB::TABLE('wms_reasons')->where('reason_status','=','1')->get();


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
        
        return view('workshop-ms.t-workshop.index',compact('bays', 'baysT', 'sectionT', 'workshop', 'scl', 'reason','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
    }
    
    public function getEvents(Request $request){
        $events = DB::table('technician_schedules')
                    ->select('technician_schedules.id as TID','baynum','name','scopeofwork','activity', 'scheddate','time_start','time_end','technician_schedules.status as TStatus','remarks')
                    ->leftJoin('wms_technicians','wms_technicians.id','techid')
                    ->where('baynum', '=', $request->bay)
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
        $WSf = (DB::TABLE('unit_workshops')->WHERE('WSBayNum',$bay)->where('WSDelTransfer', 0)->first());
        if($WSf != null){
            $WSIDf =$WSf->id;
            $DT = (DB::TABLE('unit_downtimes')->WHERE('DTJONum',$WSIDf)->get())->count();
        }

        
        $result = '';

        if ($DT>0){
            $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
                                    unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
                                    unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE, unit_workshops.WSRemarks,
                                    wms_bay_areas.area_name, brands.name,
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUTransferDate,
                                    unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, 
                                    unit_downtimes.id as DTID, unit_downtimes.DTJONum, unit_downtimes.DTSDate, unit_downtimes.DTEDate, unit_downtimes.DTReason, unit_downtimes.DTRemarks, unit_downtimes.DTTDays,
                                    unit_confirms.CUTransferDate
                                    FROM unit_workshops
                                    INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                    INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                    INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                    INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                    INNER JOIN unit_downtimes on unit_workshops.id = unit_downtimes.DTJONum
                                    LEFT JOIN unit_confirms on unit_confirms.POUID = unit_workshops.WSPOUID
                                    WHERE unit_workshops.WSDelTransfer = 0 AND WSBayNum = ?',[$bay]
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
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=','']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=','']])->count();

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
                                    unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                    unit_pull_outs.POUTransferDate, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, wms_technicians.initials,
                                    unit_confirms.CUTransferDate
                                    FROM unit_workshops
                                    INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                    INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                    INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                    INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                    LEFT JOIN unit_confirms on unit_confirms.POUID = unit_workshops.WSPOUID
                                    WHERE unit_workshops.WSDelTransfer = 0 AND WSBayNum = ?',[$bay]
                                );

            if(count($workshop)>0){
                foreach($workshop as $WS){
                    $TechICharge = DB::SELECT('SELECT DISTINCT(techid), wms_technicians.initials as TInitials  
                                                FROM technician_schedules 
                                                INNER JOIN wms_technicians on techid=wms_technicians.id 
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
                            $partcount1 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','=','']])->count();
                            $partcount2 = DB::TABLE('unit_parts')->WHERE([['PIJONum','=',$WS->WSID],['PIDateInstalled','!=','']])->count();

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
        UnitWorkshop::where('id', $request->UnitInfoJON)
                    ->update([
                        'WSToA' => $request->UnitInfoToA,
                        'WSStatus' => $request->UnitInfoStatus,
                        'WSUnitType' => $request->UnitInfoUType,
                        'WSRemarks' => $request->WSRemarks,
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
        if($request ->PIID == null){
            $partinfo = new UnitParts();
            $partinfo->PIJONum = $request->PIJONum;
            $partinfo->PIMRINum = $request->PIMRINum;
            $partinfo->PIPartID = $request->PIPartIDx;
            $partinfo->PIPartNum = $request->PIPartNum;
            $partinfo->PIDescription = $request->PIDescription;
            $partinfo->PIPrice = $request->PIPrice;
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
            $partinfo->PIPartID = $request->PIPartIDx;
            $partinfo->PIPartNum = $request->PIPartNum;
            $partinfo->PIDescription = $request->PIDescription;
            $partinfo->PIPrice = $request->PIPrice;
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

        $pinfo = UnitParts::with('part')->where('id', $request->PIID)->first();

        // $pinfo = DB::TABLE('unit_parts')->WHERE('id', $request->PIID)->first();

        $result = '';

        $result = array(
            'PIID' => $pinfo->id,
            'PIMRINum' => $pinfo->PIMRINum,
            'PIPartID' => $pinfo->part->id,
            'PIPartNum' => $pinfo->part->partno,
            'PIDescription' => $pinfo->part->partname,
            'PIPrice' => $pinfo->part->price,
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
        $partinfo->PIPartNum = $request->PartNum;
        $partinfo->PIDescription = $request->Description;
        $partinfo->PIPrice = $request->Price;
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
        // $result .= "";

        echo json_encode(Parts::where('id',$request->id)->first());
        // $part = DB::SELECT('SELECT * from parts where id=?',[$request->id]);

        // foreach ($part as $parts) {
        //     $result = array(
        //             'partid' => $parts->id,
        //             'id' => $parts->partname,
        //             'partno' => $parts->partno,
        //             'partname' => $parts->partname,
        //             'price' => $parts->price,
        //     );
        // }

        // return json_encode($result);
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

    public function saveTActivity(Request $request){
        TechnicianSchedule::where('id', $request->TAID)
        ->update([
            'time_start' => $request->TASTime,
            'time_end' => $request->TAETime,
            'status' => $request->TAStatus,
            'remarks' => $request->TARemarks,
        ]);
    }

    public function saveRemarks(Request $request){

        UnitParts::where('PIJONum', $request->WSJONum)
        ->update([
            'PIRemarks' => $request->URemarks,
        ]);
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
            'TransferStatus' => $WorkShop->POUStatus,
            'TransferArea' => $WorkShop->POUTransferArea,
            'TransferBay' => $bayres,
            'TransferRemarks' => $WorkShop->POUTransferRemarks,
        );
        // SELECT('SELECT * FROM unit_pull_outs WHERE unit_pull_outs.id = ?', [$request->WSPOUID]);
        return response()->json($result);
    }

    public function saveTransferUnit(Request $request){
        if($request->input('Radio_Transfer') == 1){
            UnitConfirm::WHERE('POUID', $request->POUIDx)
                        ->UPDATE([
                            'CUTransferStatus' => $request->UnitStatus,
                            'CUTransferArea' => $request->UnitArea,
                            'CUTransferBay' => $request->UnitBay,
                            'CUTransferRemarks' => $request->UnitRemarksT,
                        ]);
     
            UnitPullOut::WHERE('id', $request->POUIDx)
                        ->UPDATE([
                            'POUStatus' => $request->UnitStatus,
                            'POUTransferArea' => $request->UnitArea,
                            'POUTransferBay' => $request->UnitBay,
                            'POUTransferDate' => $request->UnitTransferDate,
                            'POUTransferRemarks' => $request->UnitRemarksT,
                        ]);
    
                if($request->UnitArea == 7){
                    $ToA = "3";
                }else if(($request->UnitArea >= 14)){
                    $ToA = "1";
                }else if(($request->UnitArea <= 3)){
                    $ToA = "2";
                }else{
                    $ToA = "2";
                }
    
            UnitWorkshop::WHERE('WSPOUID', $request->POUIDx)
                        ->UPDATE([
                            'WSToA' => $ToA,
                            'WSBayNum' => $request->UnitBay,
                            'WSStatus' => $request->UnitStatus,
                        ]);
    
            TechnicianSchedule::WHERE('JONumber', $request->UnitInfoJON)
                                ->UPDATE([
                                    'baynum' => $request->UnitBay,
                                ]);
    
            BayArea::WHERE('id',$request->BayID)
                    ->UPDATE([
                        'category' => 1
                    ]);
    
            BayArea::WHERE('id',$request->UnitBay)
                    ->UPDATE([
                        'category' => 2
                    ]);
        }else{
            UnitConfirm::WHERE('POUID', $request->POUIDx)
                        ->UPDATE([
                            'CUDelTransfer' => 1,
                        ]);
    
            UnitWorkshop::WHERE('WSPOUID', $request->POUIDx)
                        ->UPDATE([
                            'WSDelTransfer' => 1,
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


    // -------------------------------------------------------------REPORT------------------------------------------------------------------//

    public function indexR(){
        $brand = DB::SELECT('SELECT * FROM brands WHERE status="1"');
        $section = DB::SELECT('SELECT * FROM wms_sections WHERE status="1"');
        $technician = DB::SELECT('SELECT * FROM wms_technicians WHERE status="1"');
        $bay = DB::SELECT('SELECT * FROM wms_bay_areas WHERE category="1" and status="1" ORDER BY wms_bay_areas.id');
        $bayR = DB::SELECT('SELECT * FROM wms_bay_areas WHERE status="1" ORDER BY wms_bay_areas.id');

        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=1 AND is_PPT=0');
        
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=1 AND is_PPT=0');

        $cunit = DB::SELECT('SELECT unit_confirms.id, unit_confirms.POUID, unit_confirms.CUTransferDate, unit_confirms.CUTransferRemarks, unit_confirms.CUTransferStatus, unit_confirms.CUTransferArea, unit_confirms.CUTransferBay,
                            unit_pull_outs.POUUnitType, unit_pull_outs.POUCode, unit_pull_outs.POUModel, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastHeight, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, 
                            unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks
                            FROM unit_confirms
                            INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_confirms.POUID
                            WHERE unit_confirms.CUDelTransfer=0 AND unit_pull_outs.POUBrand = 1 AND is_PPT=0
                            ');

        $dunit = DB::SELECT('SELECT unit_deliveries.id, unit_deliveries.POUID, unit_deliveries.DUTransferDate, unit_deliveries.DURemarks, unit_deliveries.DUDelDate,
                            unit_pull_outs.POUUnitType, unit_pull_outs.POUCode, unit_pull_outs.POUModel, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastHeight, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUClassification, 
                            unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, 
                            unit_confirms.CUTransferBay
                            FROM unit_deliveries
                            INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_deliveries.POUID
                            INNER JOIN unit_confirms on unit_deliveries.POUID = unit_confirms.POUID
                            WHERE unit_pull_outs.POUBrand = 1 AND is_PPT=0
                    ');

        $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                wms_sections.name as SecName
                            FROM cannibalized_units
                            INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                            INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                            INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                            ORDER BY cast(CanPartCUID as UNSIGNED), CanPartPartNum ASC
                        ');

        $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                            FROM d_r_monitorings
                            LEFT JOIN d_r_parts ON d_r_monitorings.id = d_r_parts.DRPartMonID
                            ORDER BY d_r_monitorings.id ASC, d_r_parts.id ASC
                            ');

        $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                wms_technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_workshops.isBrandNew=0 AND unit_workshops.WSDelTransfer=0
                        ');

        return view('workshop-ms.t-workshop.report',compact('brand','section','technician','bay','bayR','bnunit','pounit','cunit','dunit','canunit','drmon','workshop'));
    }

    public function sortBrand(Request $request){
        $brand = $request->unitBrand;

        $result = '';
        if($brand == 'BrandALL'){
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                wms_technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_workshops.isBrandNew=0 AND unit_workshops.WSDelTransfer=0 AND is_PPT=0
                        ');
        }else if($brand == 'BrandToyota'){
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                wms_technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_pull_outs.POUBrand = 1 AND unit_workshops.isBrandNew=0 AND unit_workshops.WSDelTransfer=0 AND is_PPT=0
                                ');
        }else if($brand == 'BrandBT'){
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                wms_technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_pull_outs.POUBrand = 2 AND unit_workshops.isBrandNew=0 AND unit_workshops.WSDelTransfer=0 AND is_PPT=0
                        ');
        }else{
            $workshop = DB::SELECT('SELECT unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name, unit_workshops.WSATIDS, unit_workshops.WSAAIDS,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, 
                                unit_pull_outs.POUMastType, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1,
                                wms_technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN wms_technicians on wms_technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_pull_outs.POUBrand = 3 AND unit_workshops.isBrandNew=0 AND unit_workshops.WSDelTransfer=0 AND is_PPT=0
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
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND isBrandNew=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'pouRadioCU'){
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs INNER JOIN unit_confirms ON unit_pull_outs.id = unit_confirms.POUID WHERE isBrandNew=0 AND POUBrand=1 AND CUDelTransfer=0 AND is_PPT=0');
        }else if($UStatus == 'pouRadioDU'){
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs INNER JOIN unit_deliveries ON unit_pull_outs.id = unit_deliveries.POUID WHERE isBrandNew=0 AND POUBrand=1 AND is_PPT=0');
        }else{
            $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE isBrandNew=0 AND POUBrand=1 AND is_PPT=0');
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
                        $POU->POUCustomer = strtoupper($request->POUCustomer);
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = strtoupper($request->POUCustAddress);
                    }else{
                        $POU->POUCustAddress = "";
                    }

                $POU->POURemarks = $request->POURemarks;
                $POU->POUStatus = "";
                $POU->POUTransferArea = "";
                $POU->POUTransferBay = "";
                $POU->POUTransferDate = "";
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
                        $POU->POUCustomer = strtoupper($request->POUCustomer);
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = strtoupper($request->POUCustAddress);
                    }else{
                        $POU->POUCustAddress = "";
                    }

                $POU->POURemarks = $request->POURemarks;
                $POU->POUStatus = "";
                $POU->POUTransferArea = "";
                $POU->POUTransferBay = "";
                $POU->POUTransferDate = "";
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
                        $POU->POUCustomer = strtoupper($request->POUCustomer);
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = strtoupper($request->POUCustAddress);
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
                        $POU->POUCustomer = strtoupper($request->POUCustomer);
                    }else{
                        $POU->POUCustomer = "";
                    }

                    if($request->POUCustAddress != null){
                        $POU->POUCustAddress = strtoupper($request->POUCustAddress);
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
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=1 AND is_PPT=0');

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
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=1 AND is_PPT=0');

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
        $WS->WSVerifiedBy = "";
        $WS->WSUnitCondition = "2";
        $WS->WSUnitType = "";
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

        UnitPullOut::WHERE('id', $request->POUIDx)
                    ->UPDATE([
                        'POUStatus' => $request->POUStatus,
                        'POUTransferArea' => $request->POUArea,
                        'POUTransferBay' => $request->POUBay,
                        'POUTransferDate' => $request->POUTransferDate,
                        'POUTransferRemarks' => $request->POURemarksT
                        ]);

        BayArea::WHERE('id', $request->POUBay)
                ->UPDATE(['category' => "2"]);

        $result = '';
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0 AND POUBrand=1 AND is_PPT=0');

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

    // CONFIRM UNITS
    public function deleteCU(Request $request){
        UnitPullOut::WHERE('id', $request->id)
                    ->UPDATE([
                        'POUStatus' => "",
                        'POUTransferArea' => "",
                        'POUTransferBay' => "",
                        'POUTransferDate' => "",
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
                            WHERE unit_pull_outs.POUBrand = 1 AND unit_confirms.CUDElTransfer = 0 AND is_PPT=0
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
    
    public function sortConfirm(Request $request){
        $UStatus = $request->unitStatus;

        $result = '';
        if($UStatus == 'cuAllStatus'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuWFRU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=1 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuURU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=2 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuUGU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=3 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuSeU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=4 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuFScU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=5 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuFSaU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=6 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuWP'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=7 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuWBO'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=8 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuWSB'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=9 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuStU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=10 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuRU'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=11 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuWFM'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=12 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuWFP'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=13 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else if($UStatus == 'cuDP'){
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUTransferStatus=14 AND CUDelTransfer=0 AND POUBrand=1 AND is_PPT=0');
        }else{
            $cunit = DB::SELECT('SELECT * FROM unit_confirms LEFT JOIN unit_pull_outs on unit_confirms.POUID = unit_pull_outs.id WHERE CUDelTransfer=1 AND POUBrand=1 AND is_PPT=0');
        }
        
        if(count($cunit)>0){
            foreach ($cunit as $CU) {
                if ($CU->POUClassification == '1'){
                    $Class = "CLASS A";
                }else if ($CU->POUClassification == '2'){
                    $Class = "CLASS B";
                }elseif ($CU->POUClassification == '3'){
                    $Class = "CLASS C";
                }else{
                    $Class = "CLASS D";
                }

                if ($CU->CUTransferStatus == '1'){
                    $TStatus = "WAITING FOR REPAIR";
                }else if($CU->CUTransferStatus == '2'){
                    $TStatus = "UNDER REPAIR UNIT";
                }else if($CU->CUTransferStatus == '3'){
                    $TStatus = "USED GOOD UNIT";
                }else if($CU->CUTransferStatus == '4'){
                    $TStatus = "SERVICE UNIT";
                }else if($CU->CUTransferStatus == '5'){
                    $TStatus = "FOR SCRAP UNIT";
                }else if($CU->CUTransferStatus == '6'){
                    $TStatus = "FOR SALE UNIT";
                }else if($CU->CUTransferStatus == '7'){
                    $TStatus = "WAITING PARTS";
                }else if($CU->CUTransferStatus == '8'){
                    $TStatus = "WAITING BACK ORDER";
                }else if($CU->CUTransferStatus == '9'){
                    $TStatus = "WAITING SPARE BATT";
                }else if($CU->CUTransferStatus == '10'){
                    $TStatus = "STOCK UNIT";
                }else if($CU->CUTransferStatus == '11'){
                    $TStatus = "RESERVED UNIT";
                }else if($CU->CUTransferStatus == '12'){
                    $TStatus = "WAITING FOR MCI";
                }else if($CU->CUTransferStatus == '13'){
                    $TStatus = "WAITING FOR PDI";
                }else if($CU->CUTransferStatus == '14'){
                    $TStatus = "DONE PDI (WFD)";
                }else{
                    $TStatus = "DELIVERED UNIT";
                }

                if($UStatus == 'cuDU'){
                    $result .='
                                    <tr class="bg-white border-b hover:bg-gray-200">
                                    <td class="w-3.5 p-1 whitespace-nowrap">
                                        <button type="button" data-id="'.$CU->POUID.'" data-unittype="'.$CU->POUUnitType.'" class="btnCUView" id="btnCUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
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
                                        '.$Class.'
                                    </td>
                                    <td class="px-1 py-0.5 text-center">
                                        '.$TStatus.'
                                    </td>
                                </tr>
                            ';
                }else{
                    $result .='
                                <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-3.5 p-1 whitespace-nowrap">
                                    <button type="button" data-id="'.$CU->POUID.'" data-unittype="'.$CU->POUUnitType.'" class="btnCUView" id="btnCUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                    <button type="button" data-id="'.$CU->POUID.'" data-unittype="'.$CU->POUUnitType.'" class="btnCUEdit" id="btnCUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                    <button type="button" data-id="'.$CU->POUID.'" data-cubay="'.$CU->CUTransferBay.'" class="btnCUDelete" id="btnCUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
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
                                    '.$Class.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$TStatus.'
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

    // DELIVERED UNITS
    public function deleteDU(Request $request){
        UnitConfirm::WHERE('POUID', $request->id)
                    ->UPDATE([
                        'CUDelTransfer' => 0,
                    ]);
    
        UnitWorkshop::WHERE('WSPOUID', $request->id)
                    ->UPDATE([
                        'WSDelTransfer' => 0,
                    ]);
    
        BayArea::WHERE('id',$request->cubay)
                ->UPDATE([
                    'category' => 2
                ]);

        DB::TABLE('unit_deliveries')->WHERE('id', $request->duid)->DELETE();

        $result = "";
        $dunit = DB::SELECT('SELECT unit_deliveries.id, unit_deliveries.POUID, unit_deliveries.DUTransferDate, unit_deliveries.DURemarks, unit_deliveries.DUDelDate,
                            unit_pull_outs.POUUnitType, unit_pull_outs.POUCode, unit_pull_outs.POUModel, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastHeight, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUClassification, 
                            unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, 
                            unit_confirms.CUTransferBay
                            FROM unit_deliveries
                            INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_deliveries.POUID
                            INNER JOIN unit_confirms on unit_deliveries.POUID = unit_confirms.POUID
                            WHERE unit_pull_outs.POUBrand = 1
                        ');

        if(count($dunit)>0){
            foreach ($dunit as $DU) {
                if($DU->POUClassification == '1'){
                    $PClass = "CLASS A";
                }else if($DU->POUClassification == '2'){
                    $PClass = "CLASS B";
                }else if($DU->POUClassification == '3'){
                    $PClass = "CLASS C";
                }else{
                    $PClass = "CLASS D";
                }

                $result .='
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-3.5 p-1 whitespace-nowrap">
                                    <button type="button" data-id="'.$DU->POUID.'" data-unittype="'.$DU->POUUnitType.'" class="btnDUView" id="btnDUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                    <button type="button" data-id="'.$DU->POUID.'" data-unittype="'.$DU->POUUnitType.'" class="btnDUEdit" id="btnDUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                    <button type="button" data-id="'.$DU->POUID.'" data-unittype="'.$DU->POUUnitType.'" data-duid="'.$DU->id.'" data-cubay="'.$DU->CUTransferBay.'" class="btnDUDelete" id="btnDUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->DUTransferDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->POUCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->POUModel.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->POUSerialNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->POUMastHeight.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->POUCustomer.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->POUCustAddress.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$PClass.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$DU->DUDelDate.'
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
                $BNU->POUTransferDate = "";
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
                $BNU->POUTransferDate = "";
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
        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=1 AND is_PPT=0');

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
        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=1 AND is_PPT=0');

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
                        'POUTransferDate' => $request->BNUTransferDate,
                        'POUTransferRemarks' => $request->BNURemarksT
                        ]);

        BayArea::WHERE('id', $request->BNUBay)
                ->UPDATE(['category' => "2"]);

        $result = '';
        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1 AND POUBrand=1 AND is_PPT=0');

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
    
    // REPORTS
    public function getBayR(Request $request){
        $result = '<option value=""></option>';
        if($request->area == ''){
            $bay = DB::SELECT('SELECT * FROM wms_bay_areas ORDER BY wms_bay_areas.id');
        }else{
            $bay = DB::SELECT('SELECT * FROM wms_bay_areas WHERE section=? ORDER BY wms_bay_areas.id',[$request->area]);
        }

        foreach ($bay as $bays) {
            $result .='
                        <option value="'.$bays->id.'">'.$bays->area_name.'</option>
                    ';
        }

        echo $result;
    }

    public function generateBrandReport(Request $request){
        if ($request->UBrand == 1) {
            $brand = "TOYOTA";
        } else if ($request->UBrand == 2) {
            $brand = "BT";
        }else {
            $brand = "RAYMOND";
        }
        
        $title = "BRAND REPORT";

        $datas = DB::table('unit_workshops')
            ->select('unit_workshops.id','wms_bay_areas.area_name', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUModel', 'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUMastType', 'unit_workshops.WSRemarks', 'unit_workshops.WSATRDE', 'unit_workshops.WSAAIDS', 
            'unit_workshops.WSAARDE', 'wms_technicians.initials'
                    )
            ->join('unit_pull_outs', 'unit_pull_outs.id', '=', 'unit_workshops.WSPOUID')
            ->join('wms_bay_areas', 'wms_bay_areas.id', '=', 'unit_workshops.WSBayNum')
            ->join('wms_technicians', 'wms_technicians.id', '=', 'unit_pull_outs.POUTechnician1')
            ->leftJoin('unit_pull_out_bats', 'unit_pull_out_bats.POUID', '=', 'unit_pull_outs.id')
            ->join('brands', 'unit_pull_outs.POUBrand', '=', 'brands.id')
            ->where('unit_workshops.isBrandNew','=',0)
            ->where('unit_pull_outs.POUBrand','=',$request->UBrand)
            ->whereBetween('POUArrivalDate',[$request->fromDate, $request->toDate])
            ->orderBy('unit_pull_outs.id', 'asc')
            ->get();
    
        $csv = Writer::createFromString('');
    
        $csv->insertOne(['']);
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['FROM:', $request->fromDate]);
        $csv->insertOne(['TO:', $request->toDate]);
        $csv->insertOne(['']);
        $csv->insertOne(['BRAND: ', $brand]);
        $csv->insertOne(['']);
        $csv->insertOne(['id', 'Bay Number', 'Code', 'Company', 'Model', 'Serial Number', 'Mast Type', 'Remarks', 'Target Date', 'Date Started', 'Date End', 'Person in Charge']);

        foreach ($datas as $row) {
            $csv->insertOne([$row->id, $row->area_name, $row->POUCode, $row->POUCustomer, $row->POUModel, $row->POUSerialNum, $row->POUMastType, $row->WSRemarks, $row->WSATRDE, $row->WSAAIDS, $row->WSAARDE, $row->initials]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');

    }

    public function generateBayReport(Request $request){
        
        $title = "BAY REPORT";

        $datas = DB::table('unit_workshops')
            ->select('unit_workshops.id','wms_bay_areas.area_name', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUModel', 'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUMastType', 'unit_workshops.WSRemarks', 'unit_workshops.WSATRDE', 'unit_workshops.WSAAIDS', 
            'unit_workshops.WSAARDE', 'wms_technicians.initials', 'unit_workshops.WSDelTransfer'
                    )
            ->join('unit_pull_outs', 'unit_pull_outs.id', '=', 'unit_workshops.WSPOUID')
            ->join('wms_bay_areas', 'wms_bay_areas.id', '=', 'unit_workshops.WSBayNum')
            ->join('wms_technicians', 'wms_technicians.id', '=', 'unit_pull_outs.POUTechnician1')
            ->leftJoin('unit_pull_out_bats', 'unit_pull_out_bats.POUID', '=', 'unit_pull_outs.id')
            ->join('brands', 'unit_pull_outs.POUBrand', '=', 'brands.id')
            ->where('unit_workshops.isBrandNew','=',0)
            ->where('unit_workshops.WSBayNum','=',$request->bayNum)
            ->whereBetween('POUArrivalDate',[$request->fromDate, $request->toDate])
            ->orderBy('unit_pull_outs.id', 'asc')
            ->get();
    
        $csv = Writer::createFromString('');
    
        $csv->insertOne(['']);
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['FROM:', $request->fromDate]);
        $csv->insertOne(['TO:', $request->toDate]);
        $csv->insertOne(['']);
        $csv->insertOne(['BAY: ', $request->bayName]);
        $csv->insertOne(['']);
        $csv->insertOne(['id', 'Bay Number', 'Code', 'Company', 'Model', 'Serial Number', 'Mast Type', 'Remarks', 'Target Date', 'Date Started', 'Date End', 'Person in Charge','Delivered?']);

        foreach ($datas as $row) {
            if($row->WSDelTransfer == 1){
                $Transfer = "Yes";
            }else{
                $Transfer = "No";
            }
            $csv->insertOne([$row->id, $row->area_name, $row->POUCode, $row->POUCustomer, $row->POUModel, $row->POUSerialNum, $row->POUMastType, $row->WSRemarks, $row->WSATRDE, $row->WSAAIDS, $row->WSAARDE, $row->initials, $Transfer]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');

    }

    public function searchRPU(Request $request){
        $column = $request->CatSearch;
        $term = $request->RPUTableSearch;
        $query = DB::table('unit_workshops')
                    ->select('unit_workshops.id as WSID','brands.name as BName','POUModel','POUCode','POUSerialNum','wms_technicians.initials as TInitials')
                    ->leftJoin('unit_pull_outs','unit_pull_outs.id','=','unit_workshops.WSPOUID')
                    ->leftJoin('wms_technicians','wms_technicians.id','=','unit_pull_outs.POUTechnician1')
                    ->leftJoin('brands','unit_pull_outs.POUBrand','=','brands.id');

        if ($request->CatSearch == ' ' || $request->CatSearch == null) {
            $data = $query->WHERE(function ($where) use ($term){
                $columnsL = ['brands.name','POUModel','POUCode','POUSerialNum','initials'];
                foreach($columnsL as $columns){
                    $where->orWhere($columns,'LIKE',"%$term%");
                }
            });
        }else{
            $data = $query->WHERE($column,'LIKE',"%$term%");
        }
        $fquery = $data->get();

        $result = "";
        if(count($fquery)>0){
            foreach ($fquery as $spu) {
                $result .='
                            <tr class="bg-white border-b hover:bg-gray-200 text-xs">
                                <td class="px-1 whitespace-nowrap gap-1">
                                    <div class="flex justify-center items-center">
                                        <button type="button" data-id="'.$spu->WSID.'" class="btnPrint mr-1" id="btnPrint">
                                            <svg fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier"> <g id="Printer"> 
                                                    <path d="M57.7881012,14.03125H52.5v-8.0625c0-2.2091999-1.7909012-4-4-4h-33c-2.2091999,0-4,1.7908001-4,4v8.0625H6.2119002 C2.7871001,14.03125,0,16.8183498,0,20.2431507V46.513649c0,3.4248009,2.7871001,6.2119026,6.2119002,6.2119026h2.3798995 c0.5527,0,1-0.4472008,1-1c0-0.5527-0.4473-1-1-1H6.2119002C3.8896,50.7255516,2,48.8359489,2,46.513649V20.2431507 c0-2.3223,1.8896-4.2119007,4.2119002-4.2119007h51.5762024C60.1102982,16.03125,62,17.9208508,62,20.2431507V46.513649 c0,2.3223-1.8897018,4.2119026-4.2118988,4.2119026H56c-0.5527992,0-1,0.4473-1,1c0,0.5527992,0.4472008,1,1,1h1.7881012 C61.2128983,52.7255516,64,49.9384499,64,46.513649V20.2431507C64,16.8183498,61.2128983,14.03125,57.7881012,14.03125z M13.5,5.96875c0-1.1027999,0.8971996-2,2-2h33c1.1027985,0,2,0.8972001,2,2v8h-37V5.96875z"></path> 
                                                    <path d="M44,45.0322495H20c-0.5517998,0-0.9990005,0.4472008-0.9990005,0.9990005S19.4482002,47.0302505,20,47.0302505h24 c0.5517006,0,0.9990005-0.4472008,0.9990005-0.9990005S44.5517006,45.0322495,44,45.0322495z"></path> <path d="M44,52.0322495H20c-0.5517998,0-0.9990005,0.4472008-0.9990005,0.9990005S19.4482002,54.0302505,20,54.0302505h24 c0.5517006,0,0.9990005-0.4472008,0.9990005-0.9990005S44.5517006,52.0322495,44,52.0322495z"></path>
                                                    <circle cx="7.9590998" cy="21.8405495" r="2"></circle> <circle cx="14.2856998" cy="21.8405495" r="2"></circle> <circle cx="20.6121998" cy="21.8405495" r="2"></circle> 
                                                    <path d="M11,62.03125h42v-26H11V62.03125z M13.4036999,38.4349518h37.1925964v21.1925964H13.4036999V38.4349518z"></path> 
                                                </g> </g>
                                            </svg>
                                        </button>
                                        <button type="button" data-id="'.$spu->POUSerialNum.'" class="btnPrintAll" id="btnPrintAll">
                                        <svg fill="#0212e8" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve" stroke="#0212e8">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                            <g id="SVGRepo_iconCarrier"> <g id="Printer"> 
                                                <path d="M57.7881012,14.03125H52.5v-8.0625c0-2.2091999-1.7909012-4-4-4h-33c-2.2091999,0-4,1.7908001-4,4v8.0625H6.2119002 C2.7871001,14.03125,0,16.8183498,0,20.2431507V46.513649c0,3.4248009,2.7871001,6.2119026,6.2119002,6.2119026h2.3798995 c0.5527,0,1-0.4472008,1-1c0-0.5527-0.4473-1-1-1H6.2119002C3.8896,50.7255516,2,48.8359489,2,46.513649V20.2431507 c0-2.3223,1.8896-4.2119007,4.2119002-4.2119007h51.5762024C60.1102982,16.03125,62,17.9208508,62,20.2431507V46.513649 c0,2.3223-1.8897018,4.2119026-4.2118988,4.2119026H56c-0.5527992,0-1,0.4473-1,1c0,0.5527992,0.4472008,1,1,1h1.7881012 C61.2128983,52.7255516,64,49.9384499,64,46.513649V20.2431507C64,16.8183498,61.2128983,14.03125,57.7881012,14.03125z M13.5,5.96875c0-1.1027999,0.8971996-2,2-2h33c1.1027985,0,2,0.8972001,2,2v8h-37V5.96875z"></path> 
                                                <path d="M44,45.0322495H20c-0.5517998,0-0.9990005,0.4472008-0.9990005,0.9990005S19.4482002,47.0302505,20,47.0302505h24 c0.5517006,0,0.9990005-0.4472008,0.9990005-0.9990005S44.5517006,45.0322495,44,45.0322495z"></path> <path d="M44,52.0322495H20c-0.5517998,0-0.9990005,0.4472008-0.9990005,0.9990005S19.4482002,54.0302505,20,54.0302505h24 c0.5517006,0,0.9990005-0.4472008,0.9990005-0.9990005S44.5517006,52.0322495,44,52.0322495z"></path> 
                                                <circle cx="7.9590998" cy="21.8405495" r="2"></circle> <circle cx="14.2856998" cy="21.8405495" r="2"></circle> <circle cx="20.6121998" cy="21.8405495" r="2"></circle> 
                                                <path d="M11,62.03125h42v-26H11V62.03125z M13.4036999,38.4349518h37.1925964v21.1925964H13.4036999V38.4349518z"></path> 
                                            </g> </g>
                                        </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$spu->BName.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$spu->POUModel.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$spu->POUCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$spu->POUSerialNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center">
                                    '.$spu->TInitials.'
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

    public function generateUnitReport(Request $request){
        $id = $request->id;
        $title = "UNIT REPORT";

        $datas = DB::table('unit_workshops')
                    ->select('unit_workshops.id as WSID','WSAAIDS','WSAARDE','WSATRDE','brands.name as BName','POUMastType','POUModel','POUCode','POUSerialNum','tech1.initials as T1Initials','tech2.initials as T2Initials','POUCustomer','area_name',
                    'PIPartNum','PIDescription','PIQuantity','PIMRINum','PIDateReq','PIDateRec','PIDateInstalled')
                    ->leftJoin('unit_pull_outs','unit_pull_outs.id','=','unit_workshops.WSPOUID')
                    ->leftJoin('wms_technicians as tech1','tech1.id','=','unit_pull_outs.POUTechnician1')
                    ->leftJoin('wms_technicians as tech2','tech2.id','=','unit_pull_outs.POUTechnician2')
                    ->leftJoin('brands','unit_pull_outs.POUBrand','=','brands.id')
                    ->leftJoin('wms_bay_areas','wms_bay_areas.id','=','unit_workshops.WSBayNum')
                    ->leftJoin('unit_parts','unit_parts.PIJONum','=','unit_workshops.id')
                    ->where('unit_workshops.id','=',$id)
                    ->get();
    
        $csv = Writer::createFromString('');
        $csv->setOutputBOM(Writer::BOM_UTF8); 
    
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['Bay Num:', $datas[0]->area_name,'','Code:',$datas[0]->POUCode,'','Activity:']);
        $csv->insertOne(['Company Name:', $datas[0]->POUCustomer,'','','','','Person-in-Charge:',$datas[0]->T1Initials]);
        $csv->insertOne(['','','','','','','',$datas[0]->T2Initials]);
        $csv->insertOne(['']);
        $csv->insertOne(['Brand:',$datas[0]->BName,'','Serial Num:',$datas[0]->POUSerialNum,'','Date Started:',$datas[0]->WSAAIDS,'','Date Started:',$datas[0]->WSAARDE]);
        $csv->insertOne(['Model:',$datas[0]->POUModel,'','Mast Type:',$datas[0]->POUMastType,'','Target Date:',$datas[0]->WSATRDE,]);
        $csv->insertOne(['']);
        $csv->insertOne(['Parts Number', 'Description', 'Quantity', 'MRI Number', 'Date Requested', 'Date Received', 'Date Installed', 'Parts Status']);

        foreach ($datas as $row) {
            if($row->PIDateInstalled != '' || $row->PIDateInstalled != null){
                $status = "INSTALLED";
            }else{
                $status = "PENDING";
            }

            $csv->insertOne([$row->PIPartNum,$row->PIDescription,$row->PIQuantity,$row->PIMRINum,$row->PIDateReq,$row->PIDateRec,$row->PIDateInstalled,$status]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');
    }

    public function generateUnitRecord(Request $request){
        $id = $request->snum;
        $title = "UNIT REPORT";

        $datas = DB::table('unit_workshops')
                    ->select('unit_workshops.id as WSID','WSAAIDS','WSAARDE','WSATRDE','brands.name as BName','POUMastType','POUModel','POUCode','POUSerialNum','tech1.initials as T1Initials','tech2.initials as T2Initials','POUCustomer','area_name',
                    'PIPartNum','PIDescription','PIQuantity','PIMRINum','PIDateReq','PIDateRec','PIDateInstalled')
                    ->leftJoin('unit_pull_outs','unit_pull_outs.id','=','unit_workshops.WSPOUID')
                    ->leftJoin('wms_technicians as tech1','tech1.id','=','unit_pull_outs.POUTechnician1')
                    ->leftJoin('wms_technicians as tech2','tech2.id','=','unit_pull_outs.POUTechnician2')
                    ->leftJoin('brands','unit_pull_outs.POUBrand','=','brands.id')
                    ->leftJoin('wms_bay_areas','wms_bay_areas.id','=','unit_workshops.WSBayNum')
                    ->leftJoin('unit_parts','unit_parts.PIJONum','=','unit_workshops.id')
                    ->where('unit_pull_outs.POUSerialNum','=',$id)
                    ->get();
    
        $csv = Writer::createFromString('');
        $csv->setOutputBOM(Writer::BOM_UTF8); 
    
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['Bay Num:', $datas[0]->area_name,'','Code:',$datas[0]->POUCode,'','Activity:']);
        $csv->insertOne(['Company Name:', $datas[0]->POUCustomer,'','','','','Person-in-Charge:',$datas[0]->T1Initials]);
        $csv->insertOne(['','','','','','','',$datas[0]->T2Initials]);
        $csv->insertOne(['']);
        $csv->insertOne(['Brand:',$datas[0]->BName,'','Serial Num:',$datas[0]->POUSerialNum,'','Date Started:',$datas[0]->WSAAIDS,'','Date Started:',$datas[0]->WSAARDE]);
        $csv->insertOne(['Model:',$datas[0]->POUModel,'','Mast Type:',$datas[0]->POUMastType,'','Target Date:',$datas[0]->WSATRDE,]);
        $csv->insertOne(['']);
        $csv->insertOne(['Parts Number', 'Description', 'Quantity', 'MRI Number', 'Date Requested', 'Date Received', 'Date Installed', 'Parts Status']);

        foreach ($datas as $row) {
            if($row->PIDateInstalled != '' || $row->PIDateInstalled != null){
                $status = "INSTALLED";
            }else{
                $status = "PENDING";
            }

            $csv->insertOne([$row->PIPartNum,$row->PIDescription,$row->PIQuantity,$row->PIMRINum,$row->PIDateReq,$row->PIDateRec,$row->PIDateInstalled,$status]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');
    }

    public function generatePOUReport(Request $request){
        $title = "PULL OUT UNITS REPORT";

        $datas = DB::table('unit_pull_outs')
            ->select('unit_pull_outs.id', 'unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'brands.name as brand', 'unit_pull_outs.POUClassification', 'unit_pull_outs.POUModel', 'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUMastType', 
                    'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUAccISite', 'unit_pull_outs.POUAccLiftCam', 'unit_pull_outs.POUAccRedLight', 
                    'unit_pull_outs.POUAccBlueLight', 'unit_pull_outs.POUAccFireExt', 'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthersDetail', 'unit_pull_outs.POUTechnician1', 'unit_pull_outs.POUTechnician2', 'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 
                    'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks', 'unit_pull_outs.POURemarks', 
                    'unit_pull_out_bats.POUBABrand', 'unit_pull_out_bats.POUBABatType', 'unit_pull_out_bats.POUBASerialNum', 'unit_pull_out_bats.POUBACode', 'unit_pull_out_bats.POUBAAmper', 'unit_pull_out_bats.POUBAVolt', 'unit_pull_out_bats.POUBACCable', 'unit_pull_out_bats.POUBACTable', 
                    'unit_pull_out_bats.POUSB1Brand', 'unit_pull_out_bats.POUSB1BatType', 'unit_pull_out_bats.POUSB1SerialNum', 'unit_pull_out_bats.POUSB1Code', 'unit_pull_out_bats.POUSB1Amper', 'unit_pull_out_bats.POUSB1Volt', 'unit_pull_out_bats.POUSB1CCable', 'unit_pull_out_bats.POUSB1CTable',
                    'unit_pull_out_bats.POUSB2Brand', 'unit_pull_out_bats.POUSB2BatType', 'unit_pull_out_bats.POUSB2SerialNum', 'unit_pull_out_bats.POUSB2Code', 'unit_pull_out_bats.POUSB2Amper', 'unit_pull_out_bats.POUSB2Volt', 'unit_pull_out_bats.POUSB2CCable', 'unit_pull_out_bats.POUSB2CTable',
                    'unit_pull_out_bats.POUCBrand', 'unit_pull_out_bats.POUCModel', 'unit_pull_out_bats.POUCSerialNum', 'unit_pull_out_bats.POUCCode', 'unit_pull_out_bats.POUCAmper', 'unit_pull_out_bats.POUCVolt', 'unit_pull_out_bats.POUCInput'
                    )
            ->join('brands', 'unit_pull_outs.POUBrand', '=', 'brands.id')
            ->leftJoin('unit_pull_out_bats', 'unit_pull_outs.id', '=', 'unit_pull_out_bats.POUID')
            ->where('isBrandNew','=',0)
            ->whereBetween('POUArrivalDate',[$request->fromDate, $request->toDate])
            ->orderBy('unit_pull_outs.id', 'asc')
            ->get();
    
        $csv = Writer::createFromString('');
    
        $csv->insertOne(['']);
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['FROM:', $request->fromDate]);
        $csv->insertOne(['TO:', $request->toDate]);
        $csv->insertOne(['']);
        $csv->insertOne(['id', 'Unit Type', 'Arrival Date', 'Brand', 'Classification', 'Model', 'Serial Number', 'Code', 'Mast Type', 'Mast Height', 'Fork Size', 'Att. Type', 'Att. Model', 'Att. Serial Number', 'Acc. I-Site', 'Acc. Lift Cam', 'Acc. Red Light', 'Acc. Blue Light', 
                        'Acc. Fire Ext.', 'Acc. Strobe Light', 'Other Accessories', 'Technician 1', 'Technician 2', 'Salesman', 'Customer', 'Cust. Address', 'Unit Remarks', 'Unit Remarks', 'Battery Brand', 'Battery Type', 'Battery Serial Number', 'Battery Code', 'Battery Amper', 
                        'Battery Volt', 'Battery CCable', 'Battery CTable', 'Spare Bat1 Brand', 'Spare Bat1 Type', 'Spare Bat1 Serial Number', 'Spare Bat1 Code', 'Spare Bat1 Amper', 'Spare Bat1 Volt', 'Spare Bat1 CCable', 'Spare Bat1 CTable', 'Spare Bat2 Brand', 
                        'Spare Bat2 Type', 'Spare Bat2 Serial Number', 'Spare Bat2 Code', 'Spare Bat2 Amper', 'Spare Bat2 Volt', 'Spare Bat2 CCable', 'Spare Bat2 CTable', 'Charger Brand', 'Charger Model', 'Charger Serial Number', 'Charger Code', 'Charger Amper', 'Charger Volt', 'Charger Input']);

        foreach ($datas as $row) {
            if($row->POUUnitType == 1){
                $UType = "DIESEL/GASOLINE/LPG";
            }else{
                $UType = "BATTERY";
            }

            if($row->POUClassification == 1){
                $Class = "CLASS A";
            }else if($row->POUClassification == 2){
                $Class = "CLASS B";
            }else if($row->POUClassification == 3){
                $Class = "CLASS C";
            }else{
                $Class = "CLASS D";
            }

            $csv->insertOne([$row->id, $UType, $row->POUArrivalDate, $row->brand, $Class, $row->POUModel, $row->POUSerialNum, $row->POUCode, $row->POUMastType, $row->POUMastHeight, $row->POUForkSize, $row->POUAttType, $row->POUAttModel, $row->POUAttSerialNum, $row->POUAccISite, 
                            $row->POUAccLiftCam, $row->POUAccRedLight, $row->POUAccBlueLight, $row->POUAccFireExt, $row->POUAccStLight, $row->POUAccOthersDetail, $row->POUTechnician1, $row->POUTechnician2, $row->POUSalesman, $row->POUCustomer, $row->POUCustAddress, $row->POURemarks, 
                            $row->POUBABrand, $row->POUBABatType, $row->POUBASerialNum, $row->POUBACode, $row->POUBAAmper, $row->POUBAVolt, $row->POUBACCable, $row->POUBACTable, $row->POUSB1Brand, $row->POUSB1BatType, $row->POUSB1SerialNum, $row->POUSB1Code, $row->POUSB1Amper, $row->POUSB1Volt, 
                            $row->POUSB1CCable, $row->POUSB1CTable, $row->POUSB2Brand, $row->POUSB2BatType, $row->POUSB2SerialNum, $row->POUSB2Code, $row->POUSB2Amper, $row->POUSB2Volt, $row->POUSB2CCable, $row->POUSB2CTable, $row->POUCBrand, $row->POUCModel, $row->POUCSerialNum, 
                            $row->POUCCode, $row->POUCAmper, $row->POUCVolt, $row->POUCInput]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');
    }

    public function generateDUReport(Request $request){
        $title = "DELIVERED UNITS REPORT";

        $datas = DB::table('unit_deliveries')
            ->select('unit_deliveries.id as DUID', 'unit_deliveries.DUDelDate', 'unit_deliveries.DURemarks', 'unit_pull_outs.id as POUID', 'unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'brands.name as brand', 'unit_pull_outs.POUClassification', 'unit_pull_outs.POUModel', 
                    'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUMastType', 'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUAccISite', 
                    'unit_pull_outs.POUAccLiftCam', 'unit_pull_outs.POUAccRedLight', 'unit_pull_outs.POUAccBlueLight', 'unit_pull_outs.POUAccFireExt', 'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthersDetail', 'unit_pull_outs.POUTechnician1', 
                    'unit_pull_outs.POUTechnician2', 't1.initials as tech1', 't2.initials as tech2', 'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks', 'unit_pull_outs.POURemarks', 
                    'unit_pull_out_bats.POUBABrand', 'unit_pull_out_bats.POUBABatType', 'unit_pull_out_bats.POUBASerialNum', 'unit_pull_out_bats.POUBACode', 'unit_pull_out_bats.POUBAAmper', 'unit_pull_out_bats.POUBAVolt', 'unit_pull_out_bats.POUBACCable', 'unit_pull_out_bats.POUBACTable', 
                    'unit_pull_out_bats.POUSB1Brand', 'unit_pull_out_bats.POUSB1BatType', 'unit_pull_out_bats.POUSB1SerialNum', 'unit_pull_out_bats.POUSB1Code', 'unit_pull_out_bats.POUSB1Amper', 'unit_pull_out_bats.POUSB1Volt', 'unit_pull_out_bats.POUSB1CCable', 'unit_pull_out_bats.POUSB1CTable',
                    'unit_pull_out_bats.POUSB2Brand', 'unit_pull_out_bats.POUSB2BatType', 'unit_pull_out_bats.POUSB2SerialNum', 'unit_pull_out_bats.POUSB2Code', 'unit_pull_out_bats.POUSB2Amper', 'unit_pull_out_bats.POUSB2Volt', 'unit_pull_out_bats.POUSB2CCable', 'unit_pull_out_bats.POUSB2CTable',
                    'unit_pull_out_bats.POUCBrand', 'unit_pull_out_bats.POUCModel', 'unit_pull_out_bats.POUCSerialNum', 'unit_pull_out_bats.POUCCode', 'unit_pull_out_bats.POUCAmper', 'unit_pull_out_bats.POUCVolt', 'unit_pull_out_bats.POUCInput'
                    )
            ->join('unit_pull_outs', 'unit_pull_outs.id', '=', 'unit_deliveries.POUID')
            ->join('brands', 'unit_pull_outs.POUBrand', '=', 'brands.id')
            ->leftJoin('wms_technicians as t1', 'unit_pull_outs.POUTechnician1', '=', 't1.id')
            ->leftJoin('wms_technicians as t2', 'unit_pull_outs.POUTechnician2', '=', 't2.id')
            ->leftJoin('unit_pull_out_bats', 'unit_pull_outs.id', '=', 'unit_pull_out_bats.POUID')
            ->where('isBrandNew','=',0)
            ->whereBetween('POUArrivalDate',[$request->fromDate, $request->toDate])
            ->orderBy('unit_pull_outs.id', 'asc')
            ->get();
    
        $csv = Writer::createFromString('');
    
        $csv->insertOne(['']);
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['FROM:', $request->fromDate]);
        $csv->insertOne(['TO:', $request->toDate]);
        $csv->insertOne(['']);
        $csv->insertOne(['id', 'Delivered Date', 'Deliver Remarks', 'Unit Type', 'Arrival Date', 'Brand', 'Classification', 'Model', 'Serial Number', 'Code', 'Mast Type', 'Mast Height', 'Fork Size', 'Att. Type', 'Att. Model', 'Att. Serial Number', 'Acc. I-Site', 'Acc. Lift Cam', 'Acc. Red Light', 'Acc. Blue Light', 
                        'Acc. Fire Ext.', 'Acc. Strobe Light', 'Other Accessories', 'Technician 1', 'Technician 2', 'Salesman', 'Customer', 'Cust. Address', 'Unit Remarks', 'Unit Remarks', 'Battery Brand', 'Battery Type', 'Battery Serial Number', 'Battery Code', 'Battery Amper', 
                        'Battery Volt', 'Battery CCable', 'Battery CTable', 'Spare Bat1 Brand', 'Spare Bat1 Type', 'Spare Bat1 Serial Number', 'Spare Bat1 Code', 'Spare Bat1 Amper', 'Spare Bat1 Volt', 'Spare Bat1 CCable', 'Spare Bat1 CTable', 'Spare Bat2 Brand', 
                        'Spare Bat2 Type', 'Spare Bat2 Serial Number', 'Spare Bat2 Code', 'Spare Bat2 Amper', 'Spare Bat2 Volt', 'Spare Bat2 CCable', 'Spare Bat2 CTable', 'Charger Brand', 'Charger Model', 'Charger Serial Number', 'Charger Code', 'Charger Amper', 'Charger Volt', 'Charger Input']);

        foreach ($datas as $row) {
            if($row->POUUnitType == 1){
                $UType = "DIESEL/GASOLINE/LPG";
            }else{
                $UType = "BATTERY";
            }

            if($row->POUClassification == 1){
                $Class = "CLASS A";
            }else if($row->POUClassification == 2){
                $Class = "CLASS B";
            }else if($row->POUClassification == 3){
                $Class = "CLASS C";
            }else{
                $Class = "CLASS D";
            }

            $csv->insertOne([$row->DUID, $row->DUDelDate, $row->DURemarks, $UType, $row->POUArrivalDate, $row->brand, $Class, $row->POUModel, $row->POUSerialNum, $row->POUCode, $row->POUMastType, $row->POUMastHeight, $row->POUForkSize, $row->POUAttType, $row->POUAttModel, $row->POUAttSerialNum, $row->POUAccISite, 
                            $row->POUAccLiftCam, $row->POUAccRedLight, $row->POUAccBlueLight, $row->POUAccFireExt, $row->POUAccStLight, $row->POUAccOthersDetail, $row->tech1, $row->tech2, $row->POUSalesman, $row->POUCustomer, $row->POUCustAddress, $row->POURemarks, 
                            $row->POUBABrand, $row->POUBABatType, $row->POUBASerialNum, $row->POUBACode, $row->POUBAAmper, $row->POUBAVolt, $row->POUBACCable, $row->POUBACTable, $row->POUSB1Brand, $row->POUSB1BatType, $row->POUSB1SerialNum, $row->POUSB1Code, $row->POUSB1Amper, $row->POUSB1Volt, 
                            $row->POUSB1CCable, $row->POUSB1CTable, $row->POUSB2Brand, $row->POUSB2BatType, $row->POUSB2SerialNum, $row->POUSB2Code, $row->POUSB2Amper, $row->POUSB2Volt, $row->POUSB2CCable, $row->POUSB2CTable, $row->POUCBrand, $row->POUCModel, $row->POUCSerialNum, 
                            $row->POUCCode, $row->POUCAmper, $row->POUCVolt, $row->POUCInput]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');
    }

    public function generateCanUnitReport(Request $request){
        $title = "CANNIBALIZED UNITS REPORT";

        $datas = DB::table('cannibalized_units')
            ->select('cannibalized_units.id as CanUnitID', 'cannibalized_units.CanUnitDate', 'cannibalized_units.CanUnitCONum', 'cannibalized_parts.CanPartPartNum', 'cannibalized_parts.CanPartDescription', 'cannibalized_parts.CanPartQuantity', 'cannibalized_units.CanUnitITCustomer', 
                    'cannibalized_units.CanUnitITCustAddress', 'brands.name as BName', 'cannibalized_units.CanUnitCFModelNum', 'cannibalized_units.CanUnitITModelNum', 'wms_technicians.initials', 'cannibalized_parts.CanPartRemarks', 'wms_sections.name as SName', 'cannibalized_parts.CanPartStatus',
                    'cannibalized_units.CanUnitRPRetDate', 'cannibalized_units.CanUnitRPRecBy','cannibalized_units.CanUnitDocRefNum'
                    )
            ->leftjoin('cannibalized_parts', 'cannibalized_units.id', '=', 'cannibalized_parts.CanPartCUID')
            ->leftjoin('brands', 'brands.id', '=', 'cannibalized_units.CanUnitBrand')
            ->leftjoin('wms_technicians', 'wms_technicians.id', '=', 'cannibalized_units.CanUnitCFPIC')
            ->leftjoin('wms_sections', 'wms_sections.id', '=', 'cannibalized_units.CanUnitCFSection')
            ->whereBetween('CanUnitDate',[$request->fromDate, $request->toDate])
            ->orderBy('cannibalized_units.id', 'asc')
            ->get();
    
        $csv = Writer::createFromString('');
    
        $csv->insertOne(['']);
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['FROM:', $request->fromDate]);
        $csv->insertOne(['TO:', $request->toDate]);
        $csv->insertOne(['']);
        $csv->insertOne(['ID', 'MONTH', 'DATE', 'CO NUMBER', 'PARTS NUMBER','DESCRIPTION', 'QTY', 'CUSTOMER', 'CUST. ADDRESS', 'BRAND', 'UNIT FROM', 'INSTALLED TO', 'SUPPLY TO', 'CANNIBALIZED BY', 'CANNIBALIZED TO', 'REMARKS', 'SECTION', 'STATUS', 'DATE RETURNED', 'RETURNED PARTS RECEIVED BY',
                        'MRI NUM/DR REFERENCE (FOR RETUNRED PARTS)'
                        ]);

        foreach ($datas as $row) {
            $thisMonth = date('F', strtotime($row->CanUnitDate));

            if ($row->CanPartStatus == 1) {
                $PStatus = "CLOSED";
            }else if ($row->CanPartStatus == 2) {
                $PStatus = "PENDING";
            }else if ($row->CanPartStatus == 3) {
                $PStatus = "NOT FOR RETURN";
            }else{
                $PStatus = "CANCELLED";
            }

            $csv->insertOne([$row->CanUnitID, $thisMonth, $row->CanUnitDate, $row->CanUnitCONum, $row->CanPartPartNum, $row->CanPartDescription, $row->CanPartQuantity, $row->CanUnitITCustomer, $row->CanUnitITCustAddress, $row->BName, $row->CanUnitCFModelNum, $row->CanUnitITModelNum, "N/A",
                            $row->initials, $row->BName, $row->CanPartRemarks, $row->SName, $PStatus, $row->CanUnitRPRetDate, $row->CanUnitRPRecBy, $row->CanUnitDocRefNum
                            ]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');
    }

    public function generateDRMonReport(Request $request){
        $title = "D.R. MONITORING REPORT";

        $datas = DB::table('d_r_monitorings')
            ->select('d_r_monitorings.id as DRMonID', 'd_r_monitorings.DRMonDate', 'd_r_monitorings.DRMonStatus','d_r_monitorings.DRMonCustomer', 'd_r_monitorings.DRMonCustAddress', 'd_r_monitorings.DRMonSupplier', 'd_r_monitorings.DRMonPRNum', 
                    'd_r_monitorings.LDRMonCode', 'd_r_monitorings.LDRMonModel', 'd_r_monitorings.LDRMonSerial', 'd_r_monitorings.LDRMonDRNum', 'd_r_monitorings.LDRMonPUDate', 'd_r_monitorings.LDRMonReqBy', 
                    'd_r_monitorings.RDRMonQNum', 'd_r_monitorings.RDRMonQDate', 'd_r_monitorings.RDRMonBSNum', 'd_r_monitorings.RDRMonDRNum', 'd_r_monitorings.RDRMonRetDate', 'd_r_monitorings.RDRMonRecBy',
                    'd_r_parts.DRPartPartNum', 'd_r_parts.DRPartDescription', 'd_r_parts.DRPartQuantity', 'd_r_parts.DRPartPurpose', 'd_r_parts.DRPartRemarks', 'd_r_parts.DRPartStatus'
                    )
            ->leftjoin('d_r_parts', 'd_r_parts.DRPartMonID', '=', 'd_r_monitorings.id')
            ->whereBetween('DRMonDate',[$request->fromDate, $request->toDate])
            ->get();
    
        $csv = Writer::createFromString('');
    
        $csv->insertOne(['']);
        $csv->insertOne([$title]);
        $csv->insertOne(['']);
        $csv->insertOne(['FROM:', $request->fromDate]);
        $csv->insertOne(['TO:', $request->toDate]);
        $csv->insertOne(['']);
        $csv->insertOne(['ID', 'DATE', 'STATUS', 'DR NUMBER', 'PR NUMBER', 'PART NUMBER', 'DESCRIPTION', 'QTY', 'SUPPLIER', 'UNIT', 'REMARKS', 'UPDATE/STATUS'
                        ]);

        foreach ($datas as $row) {
            // $thisMonth = date('F', strtotime($row->CanUnitDate));
            if ($row->DRMonStatus == 1) {
                $UStatus = "PENDING";
            }else if ($row->DRMonStatus == 2) {
                $UStatus = "PARTIAL";
            }else if ($row->DRMonStatus == 3) {
                $UStatus = "CLOSED";
            }else{
                $UStatus = "CANCELLED";
            }

            if ($row->DRPartStatus == 1) {
                $PStatus = "PENDING";
            }else if ($row->DRPartStatus == 2) {
                $PStatus = "PARTIAL";
            }else if ($row->DRPartStatus == 3) {
                $PStatus = "CLOSED";
            }else{
                $PStatus = "CANCELLED";
            }

            $csv->insertOne([$row->DRMonID, $row->DRMonDate, $PStatus,$row->LDRMonDRNum, $row->DRMonPRNum, $row->DRPartPartNum, $row->DRPartDescription, $row->DRPartQuantity, $row->DRMonSupplier, $row->LDRMonCode, $row->DRPartRemarks,$UStatus
                            ]);
        }
    
        $csvContent = $csv->getContent();
    
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="data.csv"');
    }

    // CANNIBALIZED UNIT
    public function saveCanUnit(Request $request){
        $id = $request->CanUnitID;

        if($id == null){
            $CanUnit = new CannibalizedUnit();
            $CanUnit->CanUnitCONum = strtoupper($request->CanUnitCONum);
            $CanUnit->CanUnitBrand = strtoupper($request->CanUnitBrand);
            $CanUnit->CanUnitStatus = strtoupper($request->CanUnitStatus);
            $CanUnit->CanUnitDate = strtoupper($request->CanUnitDate);
            $CanUnit->CanUnitCFModelNum = strtoupper($request->CanUnitCFModelNum);
            $CanUnit->CanUnitCFSerialNum = strtoupper($request->CanUnitCFSerialNum);
            $CanUnit->CanUnitCFRentalCode = strtoupper($request->CanUnitCFRentalCode);
            $CanUnit->CanUnitCFSection = strtoupper($request->CanUnitCFSection);
            $CanUnit->CanUnitCFPIC = strtoupper($request->CanUnitCFPIC);
            $CanUnit->CanUnitCFPrepBy = strtoupper($request->CanUnitCFPrepBy);
            $CanUnit->CanUnitCFPrepDate = strtoupper($request->CanUnitCFPrepDate);
            $CanUnit->CanUnitCFStartTime = strtoupper($request->CanUnitCFStartTime);
            $CanUnit->CanUnitCFEndTime = strtoupper($request->CanUnitCFEndTime);
            $CanUnit->CanUnitITModelNum = strtoupper($request->CanUnitITModelNum);
            $CanUnit->CanUnitITSerialNum = strtoupper($request->CanUnitITSerialNum);
            $CanUnit->CanUnitITRentalCode = strtoupper($request->CanUnitITRentalCode);
            $CanUnit->CanUnitITCustomer = strtoupper($request->CanUnitITCustomer);
            $CanUnit->CanUnitITCustAddress = strtoupper($request->CanUnitITCustAddress);
            $CanUnit->CanUnitITCustArea = strtoupper($request->CanUnitITCustArea);
            $CanUnit->CanUnitITSupMRI = strtoupper($request->CanUnitITSupMRI);
            $CanUnit->CanUnitITSupSTO = strtoupper($request->CanUnitITSupSTO);
            $CanUnit->CanUnitITRecBy = strtoupper($request->CanUnitITRecBy);
            $CanUnit->CanUnitCPrepBy = strtoupper($request->CanUnitCPrepBy);
            $CanUnit->CanUnitRPRetBy = strtoupper($request->CanUnitRPRetBy);
            $CanUnit->CanUnitRPRetDate = strtoupper($request->CanUnitRPRetDate);
            $CanUnit->CanUnitRPRecBy = strtoupper($request->CanUnitRPRecBy);
            $CanUnit->CanUnitDocRefNum = strtoupper($request->CanUnitDocRefNum);
            $CanUnit->save();

            for($i = 1; $i <= 10; $i++){
                $CanPartStatus = 'CanPartStatus'.$i;
                $partnum = 'CanUnitPartNum'.$i;
                $desc = 'CanUnitDescription'.$i;
                $quantt = 'CanUnitQuantity'.$i;
                $remarks = 'CanUnitRemarks'.$i;
    
                if ($request->$partnum == null){
                    break;
                }

                if($request->has($CanPartStatus)){
                    $PartStat = $request->input($CanPartStatus);
                } else {
                    $PartStat = $request->CanUnitStatus;
                }
                $CanPart = new CannibalizedParts();
                $CanPart->CanPartDate = $request->CanUnitDate;
                $CanPart->CanPartCUID = $CanUnit->id;
                $CanPart->CanPartPartNum = strtoupper($request->$partnum);
                $CanPart->CanPartDescription = strtoupper($request->$desc);
                $CanPart->CanPartQuantity = strtoupper($request->$quantt);
                $CanPart->CanPartRemarks = strtoupper($request->$remarks);
                $CanPart->CanPartStatus = strtoupper($PartStat);
                $CanPart->save();
            }
        }else{
            $CanUnit = CannibalizedUnit::find($id);
            $CanUnit->CanUnitCONum = strtoupper($request->CanUnitCONum);
            $CanUnit->CanUnitBrand = strtoupper($request->CanUnitBrand);
            $CanUnit->CanUnitStatus = strtoupper($request->CanUnitStatus);
            $CanUnit->CanUnitDate = strtoupper($request->CanUnitDate);
            $CanUnit->CanUnitCFModelNum = strtoupper($request->CanUnitCFModelNum);
            $CanUnit->CanUnitCFSerialNum = strtoupper($request->CanUnitCFSerialNum);
            $CanUnit->CanUnitCFRentalCode = strtoupper($request->CanUnitCFRentalCode);
            $CanUnit->CanUnitCFSection = strtoupper($request->CanUnitCFSection);
            $CanUnit->CanUnitCFPIC = strtoupper($request->CanUnitCFPIC);
            $CanUnit->CanUnitCFPrepBy = strtoupper($request->CanUnitCFPrepBy);
            $CanUnit->CanUnitCFPrepDate = strtoupper($request->CanUnitCFPrepDate);
            $CanUnit->CanUnitCFStartTime = strtoupper($request->CanUnitCFStartTime);
            $CanUnit->CanUnitCFEndTime = strtoupper($request->CanUnitCFEndTime);
            $CanUnit->CanUnitITModelNum = strtoupper($request->CanUnitITModelNum);
            $CanUnit->CanUnitITSerialNum = strtoupper($request->CanUnitITSerialNum);
            $CanUnit->CanUnitITRentalCode = strtoupper($request->CanUnitITRentalCode);
            $CanUnit->CanUnitITCustomer = strtoupper($request->CanUnitITCustomer);
            $CanUnit->CanUnitITCustAddress = strtoupper($request->CanUnitITCustAddress);
            $CanUnit->CanUnitITCustArea = strtoupper($request->CanUnitITCustArea);
            $CanUnit->CanUnitITSupMRI = strtoupper($request->CanUnitITSupMRI);
            $CanUnit->CanUnitITSupSTO = strtoupper($request->CanUnitITSupSTO);
            $CanUnit->CanUnitITRecBy = strtoupper($request->CanUnitITRecBy);
            $CanUnit->CanUnitCPrepBy = strtoupper($request->CanUnitCPrepBy);
            $CanUnit->CanUnitRPRetBy = strtoupper($request->CanUnitRPRetBy);
            $CanUnit->CanUnitRPRetDate = strtoupper($request->CanUnitRPRetDate);
            $CanUnit->CanUnitRPRecBy = strtoupper($request->CanUnitRPRecBy);
            $CanUnit->CanUnitDocRefNum = strtoupper($request->CanUnitDocRefNum);
            $CanUnit->update();

            for($i = 1; $i <= 10; $i++){
                $CanPartStatus = 'CanPartStatus'.$i;
                $partnum = 'CanUnitPartNum'.$i;
                $desc = 'CanUnitDescription'.$i;
                $quantt = 'CanUnitQuantity'.$i;
                $remarks = 'CanUnitRemarks'.$i;
                $PartCUID = 'CanUnitID'.$i;
    
                if ($request->$partnum == null){
                    break;
                }

                if($request->has($CanPartStatus)){
                    $PartStat = $request->input($CanPartStatus);
                } else {
                    $PartStat = $request->CanUnitStatus;
                }

                if($request->$PartCUID == null){
                    $CanPart = new CannibalizedParts();
                    $CanPart->CanPartDate = strtoupper($request->CanUnitDate);
                    $CanPart->CanPartCUID = strtoupper($CanUnit->id);
                    $CanPart->CanPartPartNum = strtoupper($request->$partnum);
                    $CanPart->CanPartDescription = strtoupper($request->$desc);
                    $CanPart->CanPartQuantity = strtoupper($request->$quantt);
                    $CanPart->CanPartRemarks = strtoupper($request->$remarks);
                    $CanPart->CanPartStatus = strtoupper($PartStat);
                    $CanPart->save();
                }
                else{
                    DB::table('cannibalized_parts')
                        ->where('id', $request->$PartCUID)
                        ->update([
                            'CanPartPartNum' => strtoupper($request->$partnum),
                            'CanPartDescription' => strtoupper($request->$desc),
                            'CanPartQuantity' => strtoupper($request->$quantt),
                            'CanPartRemarks' => strtoupper($request->$remarks),
                            'CanPartStatus' => strtoupper($PartStat),
                        ]);
                }
            }
        }

        $result = '';
        $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');

        if (count($canunit) > 0){
            foreach($canunit as $CUnit){
                $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="w-4 p-1">
                            <button type="button" class="btnCanUnitEdit" id="btnCanUnitEdit" data-canunitid="'.$CUnit->CanUnitID.'" data-partid="'.$CUnit->CanPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                            <button type="button" class="btnCanUnitDelete" id="btnCanUnitDelete" data-canunitid="'.$CUnit->CanUnitID.'" data-partid="'.$CUnit->CanPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                        </td>
                        <td scope="row" class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitDate.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitCONum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanPartPartNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanPartDescription.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->SecName.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitITCustomer.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CustAddress.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitCFPrepBy.'
                        </td>
                        <td class="hidden">
                            '.$CUnit->CanUnitStatus.'
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

    public function getCanParts(Request $request){
        $CanUnit = CannibalizedUnit::where('id',$request->CanUnitID)->first();
        $CanParts = CannibalizedParts::where('CanPartCUID',$request->CanUnitID)->get();

        $result1 = '';
        $i = 1;
        foreach ($CanParts as $CP) {

            if($i == 1){
                $btn = '<button id="addCanUnitDIVX" class="addCanUnitDIVX"><svg width="24px" height="24px" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style> .cls-1 { fill: #699f4c; fill-rule: evenodd; } </style></defs><path class="cls-1" d="M1080,270a30,30,0,1,1,30-30A30,30,0,0,1,1080,270Zm14-34h-10V226a4,4,0,0,0-8,0v10h-10a4,4,0,0,0,0,8h10v10a4,4,0,0,0,8,0V244h10A4,4,0,0,0,1094,236Z" id="add" transform="translate(-1050 -210)"></path></g></svg></button>';
            }else{
                $btn = '';
            }

            $result1 .= '
                    <div id="$CanUnitPartsContent'.$i.'" class="grid grid-cols-10 gap-2 mt-1">
                        <div class="col-span-3 grid grid-cols-8 gap-2">
                            <div class="col-span-3">
                            <select name="CanUnitStatus'.$i.'" id="CanUnitStatus'.$i.'" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 text-center">
                                <option value="" selected></option>
                                <option value="1" '.($CP->CanPartStatus == 1 ? 'selected' : '').'>CLOSED</option>
                                <option value="2" '.($CP->CanPartStatus == 2 ? 'selected' : '').'>PENDING</option>
                                <option value="3" '.($CP->CanPartStatus == 3 ? 'selected' : '').'>NOT FOR RETURN</option>
                                <option value="4" '.($CP->CanPartStatus == 4 ? 'selected' : '').'>CANCELLED</option>
                            </select>
                            </div>
                            <div class="col-span-5">
                                <input type="text" id="CanUnitPartNum'.$i.'" name="CanUnitPartNum'.$i.'" value="'.$CP->CanPartPartNum.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                            </div>
                        </div>
                        <div class="col-span-2">
                            <input type="text" id="CanUnitDescription'.$i.'" name="CanUnitDescription'.$i.'" value="'.$CP->CanPartDescription.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                        </div>
                        <div class="col-span-1">
                            <input type="text" id="CanUnitQuantity'.$i.'" name="CanUnitQuantity'.$i.'" value="'.$CP->CanPartQuantity.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                        </div>
                        <div class="col-span-3">
                            <input type="text" id="CanUnitRemarks'.$i.'" name="CanUnitRemarks'.$i.'" value="'.$CP->CanPartRemarks.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                        </div>
                        <div class="">
                            <input type="hidden" id="CanUnitID'.$i.'" name="CanUnitID'.$i.'" value="'.$CP->id.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                            '.$btn.'
                        </div>
                    </div>
            ';
            $i++;
        }
        
        $result = array(
            'CanUnitID' => $CanUnit->id,
            'CanUnitCONum' => $CanUnit->CanUnitCONum,
            'CanUnitBrand' => $CanUnit->CanUnitBrand,
            'CanUnitStatus' => $CanUnit->CanUnitStatus,
            'CanUnitDate' => $CanUnit->CanUnitDate,
            'CanUnitCFModelNum' => $CanUnit->CanUnitCFModelNum,
            'CanUnitCFSerialNum' => $CanUnit->CanUnitCFSerialNum,
            'CanUnitCFRentalCode' => $CanUnit->CanUnitCFRentalCode,
            'CanUnitCFSection' => $CanUnit->CanUnitCFSection,
            'CanUnitCFPIC' => $CanUnit->CanUnitCFPIC,
            'CanUnitCFPrepBy' => $CanUnit->CanUnitCFPrepBy,
            'CanUnitCFPrepDate' => $CanUnit->CanUnitCFPrepDate,
            'CanUnitCFStartTime' => $CanUnit->CanUnitCFStartTime,
            'CanUnitCFEndTime' => $CanUnit->CanUnitCFEndTime,
            'CanUnitITModelNum' => $CanUnit->CanUnitITModelNum,
            'CanUnitITSerialNum' => $CanUnit->CanUnitITSerialNum,
            'CanUnitITRentalCode' => $CanUnit->CanUnitITRentalCode,
            'CanUnitITCustomer' => $CanUnit->CanUnitITCustomer,
            'CanUnitITCustAddress' => $CanUnit->CanUnitITCustAddress,
            'CanUnitITCustArea' => $CanUnit->CanUnitITCustArea,
            'CanUnitITSupMRI' => $CanUnit->CanUnitITSupMRI,
            'CanUnitITSupSTO' => $CanUnit->CanUnitITSupSTO,
            'CanUnitITRecBy' => $CanUnit->CanUnitITRecBy,
            'CanUnitCPrepBy' => $CanUnit->CanUnitCPrepBy,
            'CanUnitRPRetBy' => $CanUnit->CanUnitRPRetBy,
            'CanUnitRPRetDate' => $CanUnit->CanUnitRPRetDate,
            'CanUnitRPRecBy' => $CanUnit->CanUnitRPRecBy,
            'CanUnitDocRefNum' => $CanUnit->CanUnitDocRefNum,
            'cuparts' => $result1,

        );

        return json_encode($result);
    }

    public function deleteCanUnit(Request $request){
        $canunitid = $request->canunitid;
        $canpartid = $request->canpartid;

        if((DB::TABLE('cannibalized_parts')->WHERE('CanPartCUID',$canunitid)->count()) == 1){

            $DCUnit1 = CannibalizedUnit::find($request->canunitid);
            $DCUnit1->delete();

            DB::TABLE('cannibalized_parts')->WHERE('CanPartCUID',$canunitid)->delete();
        }else{
            DB::TABLE('cannibalized_parts')->WHERE('id',$canpartid)->delete();
        }

        $result = '';
        $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');

        if (count($canunit) > 0){
            foreach($canunit as $CUnit){
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="w-4 p-1">
                            <button type="button" class="btnCanUnitEdit" id="btnCanUnitEdit" data-canunitid="'.$CUnit->CanUnitID.'" data-partid="'.$CUnit->CanPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                            <button type="button" class="btnCanUnitDelete" id="btnCanUnitDelete" data-canunitid="'.$CUnit->CanUnitID.'" data-partid="'.$CUnit->CanPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                        </td>
                        <td scope="row" class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitDate.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitCONum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanPartPartNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanPartDescription.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->SecName.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitITCustomer.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CustAddress.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitCFPrepBy.'
                        </td>
                        <td class="hidden">
                            '.$CUnit->CanUnitStatus.'
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

    public function getCanUnitStatus(Request $request){
        $id = $request->id;
        
        $result = '';
        if($id == "CanUnitALL"){
            $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                    cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                    cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                    cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                    cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                    cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                    cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                    wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');
        }else if($id == "CanUnitClosed"){
            $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                    cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                    cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                    cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                    cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                    cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                    cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                    wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                WHERE cannibalized_parts.CanPartStatus = 1
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');
        }else if($id == "CanUnitPending"){
            $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                    cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                    cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                    cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                    cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                    cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                    cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                    wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                WHERE cannibalized_parts.CanPartStatus = 2
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');
        }else if($id == "CanUnitNFR"){
            $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                    cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                    cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                    cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                    cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                    cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                    cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                    wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                WHERE cannibalized_parts.CanPartStatus = 3
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');
        }else{
            $canunit = DB::SELECT('SELECT cannibalized_units.id as CanUnitID, cannibalized_units.CanUnitCONum, cannibalized_units.CanUnitBrand, cannibalized_units.CanUnitStatus, cannibalized_units.CanUnitDate, 
                                    cannibalized_units.CanUnitCFModelNum, cannibalized_units.CanUnitCFSerialNum, cannibalized_units.CanUnitCFRentalCode, cannibalized_units.CanUnitCFSection, cannibalized_units.CanUnitCFPIC, 
                                    cannibalized_units.CanUnitCFPrepBy, cannibalized_units.CanUnitCFPrepDate, cannibalized_units.CanUnitCFStartTime, cannibalized_units.CanUnitCFEndTime, cannibalized_units.CanUnitITModelNum, 
                                    cannibalized_units.CanUnitITSerialNum, cannibalized_units.CanUnitITRentalCode, cannibalized_units.CanUnitITCustomer, cannibalized_units.CanUnitITCustAddress as CustAddress, 
                                    cannibalized_units.CanUnitITCustArea, cannibalized_units.CanUnitITSupMRI, cannibalized_units.CanUnitITSupSTO, cannibalized_units.CanUnitITRecBy, cannibalized_units.CanUnitCPrepBy, 
                                    cannibalized_units.CanUnitRPRetBy, cannibalized_units.CanUnitRPRetDate, cannibalized_units.CanUnitRPRecBy, cannibalized_units.CanUnitDocRefNum,
                                    cannibalized_parts.id as CanPartID, cannibalized_parts.CanPartDate, cannibalized_parts.CanPartPartNum, cannibalized_parts.CanPartDescription, cannibalized_parts.CanPartQuantity, cannibalized_parts.CanPartRemarks,
                                    wms_sections.name as SecName
                                FROM cannibalized_units
                                INNER JOIN cannibalized_parts ON cannibalized_units.id = cannibalized_parts.CanPartCUID
                                INNER JOIN wms_sections ON wms_sections.id = cannibalized_units.CanUnitCFSection
                                INNER JOIN wms_technicians ON wms_technicians.id = cannibalized_units.CanUnitCFPIC
                                WHERE cannibalized_parts.CanPartStatus = 4
                                ORDER BY cast(CanPartCUID as int), CanPartPartNum ASC
                            ');
        }

        if (count($canunit) > 0){
            foreach($canunit as $CUnit){
                $result .='
                        <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="w-4 p-1">
                            <button type="button" class="btnCanUnitEdit" id="btnCanUnitEdit" data-canunitid="'.$CUnit->CanUnitID.'" data-partid="'.$CUnit->CanPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                            <button type="button" class="btnCanUnitDelete" id="btnCanUnitDelete" data-canunitid="'.$CUnit->CanUnitID.'" data-partid="'.$CUnit->CanPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                        </td>
                        <td scope="row" class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitDate.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitCONum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanPartPartNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanPartDescription.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->SecName.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitITCustomer.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CustAddress.'
                        </td>
                        <td class="px-1 py-0.5 text-center">
                            '.$CUnit->CanUnitCFPrepBy.'
                        </td>
                        <td class="hidden">
                            '.$CUnit->CanUnitStatus.'
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

    // DR MONITORING
    public function saveDRMon(Request $request){
        $MonID = $request->DRMonID;

        if($MonID == null){
            $DRM = new DRMonitoring();
            $DRM->DRMonStatus = strtoupper($request->DRMonStatus);
            $DRM->DRMonDate = $request->DRMonDate;
            $DRM->DRMonCustomer = strtoupper($request->DRMonCustomer);
            $DRM->DRMonCustAddress = strtoupper($request->DRMonCustAddress);
            $DRM->DRMonSupplier = strtoupper($request->DRMonSupplier);
            $DRM->DRMonPRNum = strtoupper($request->DRMonPRNum);
            $DRM->LDRMonCode = strtoupper($request->LDRMonCode);
            $DRM->LDRMonModel = strtoupper($request->LDRMonModel);
            $DRM->LDRMonSerial = strtoupper($request->LDRMonSerial);
            $DRM->LDRMonDRNum = strtoupper($request->LDRMonDRNum);
            $DRM->LDRMonPUDate = $request->LDRMonPUDate;
            $DRM->LDRMonReqBy = strtoupper($request->LDRMonReqBy);
            $DRM->RDRMonQNum = strtoupper($request->RDRMonQNum);
            $DRM->RDRMonQDate = $request->RDRMonQDate;
            $DRM->RDRMonBSNum = strtoupper($request->RDRMonBSNum);
            $DRM->RDRMonDRNum = strtoupper($request->RDRMonDRNum);
            $DRM->RDRMonRetDate = $request->RDRMonRetDate;
            $DRM->RDRMonRecBy = strtoupper($request->RDRMonRecBy);
            $DRM->save();

            for($i = 1; $i <= 10; $i++){
                $DRMonCB = 'DRMonCB'.$i;
                $partnum = 'DRMonPartNum'.$i;
                $desc = 'DRMonDescription'.$i;
                $quantt = 'DRMonQuantity'.$i;
                $purpose = 'DRMonPurpose'.$i;
                $remarks = 'DRMonRemarks'.$i;
    
                if ($request->$partnum == null){
                    break;
                }

                if($request->has($DRMonCB)){
                    $PartStat = $request->input($DRMonCB);
                } else {
                    $PartStat = $request->DRMonStatus;
                }
                
                $DRP = new DRParts();
                $DRP->DRPartDate = $request->DRMonDate;
                $DRP->DRPartMonID = $DRM->id;
                $DRP->DRPartPartNum = strtoupper($request->$partnum);
                $DRP->DRPartDescription = strtoupper($request->$desc);
                $DRP->DRPartQuantity = strtoupper($request->$quantt);
                $DRP->DRPartPurpose = strtoupper($request->$purpose);
                $DRP->DRPartRemarks = strtoupper($request->$remarks);
                $DRP->DRPartStatus = strtoupper($PartStat);
                $DRP->save();
            }
        }else{
            $DRM = DRMonitoring::find($MonID);
            $DRM->DRMonStatus = strtoupper($request->DRMonStatus);
            $DRM->DRMonDate = $request->DRMonDate;
            $DRM->DRMonCustomer = strtoupper($request->DRMonCustomer);
            $DRM->DRMonCustAddress = strtoupper($request->DRMonCustAddress);
            $DRM->DRMonSupplier = strtoupper($request->DRMonSupplier);
            $DRM->DRMonPRNum = strtoupper($request->DRMonPRNum);
            $DRM->LDRMonCode = strtoupper($request->LDRMonCode);
            $DRM->LDRMonModel = strtoupper($request->LDRMonModel);
            $DRM->LDRMonSerial = strtoupper($request->LDRMonSerial);
            $DRM->LDRMonDRNum = strtoupper($request->LDRMonDRNum);
            $DRM->LDRMonPUDate = $request->LDRMonPUDate;
            $DRM->LDRMonReqBy = strtoupper($request->LDRMonReqBy);
            $DRM->RDRMonQNum = strtoupper($request->RDRMonQNum);
            $DRM->RDRMonQDate = $request->RDRMonQDate;
            $DRM->RDRMonBSNum = strtoupper($request->RDRMonBSNum);
            $DRM->RDRMonDRNum = strtoupper($request->RDRMonDRNum);
            $DRM->RDRMonRetDate = $request->RDRMonRetDate;
            $DRM->RDRMonRecBy = strtoupper($request->RDRMonRecBy);
            $DRM->update();

            for($i = 1; $i <= 10; $i++){
                $DRMonCB = 'DRMonCB'.$i;
                $partnum = 'DRMonPartNum'.$i;
                $desc = 'DRMonDescription'.$i;
                $quantt = 'DRMonQuantity'.$i;
                $purpose = 'DRMonPurpose'.$i;
                $remarks = 'DRMonRemarks'.$i;
                $DRMonID = 'DRMonID'.$i;
    
                if ($request->$partnum == null){
                    break;
                }

                if($request->has($DRMonCB)){
                    $PartStat = $request->input($DRMonCB);
                } else {
                    $PartStat = $request->DRMonStatus;
                }

                if($request->$DRMonID == null){
                    $DRP = new DRParts();
                    $DRP->DRPartDate = $request->DRMonDate;
                    $DRP->DRPartMonID = $DRM->id;
                    $DRP->DRPartPartNum = strtoupper($request->$partnum);
                    $DRP->DRPartDescription = strtoupper($request->$desc);
                    $DRP->DRPartQuantity = strtoupper($request->$quantt);
                    $DRP->DRPartPurpose = strtoupper($request->$purpose);
                    $DRP->DRPartRemarks = strtoupper($request->$remarks);
                    $DRP->DRPartStatus = strtoupper($PartStat);
                    $DRP->save();
                }
                else{
                    DB::table('d_r_parts')
                        ->where('id', $request->$DRMonID)
                        ->update([
                            'DRPartPartNum' => strtoupper($request->$partnum),
                            'DRPartDescription' => strtoupper($request->$desc),
                            'DRPartQuantity' => strtoupper($request->$quantt),
                            'DRPartPurpose' => strtoupper($request->$purpose),
                            'DRPartRemarks' => strtoupper($request->$remarks),
                            'DRPartStatus' => strtoupper($PartStat),
                        ]);
                }
            }
        }

        $result = '';
        $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                            d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                            d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                            d_r_monitorings.RDRMonRecBy,
                            d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                            FROM d_r_monitorings
                            INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                            ORDER BY DRPartMonID, DRPartPartNum
                        ');
        if(count($drmon)>0){
            foreach ($drmon as $DRM) {
                if($DRM->DRPartStatus == 1){
                    $DRStat = "PENDING";
                }else if($DRM->DRPartStatus == 2){
                    $DRStat = "ONGOING";
                }else if($DRM->DRPartStatus == 3){
                    $DRStat = "CANCELLED";
                }else{
                    $DRStat = "DONE";
                }

                $result .='
                    <tr class="bg-white border-b hover:bg-gray-200">
                        <td class="w-6 p-1">
                            <button type="button" class="btnDRMonEdit" id="btnDRMonEdit" data-drmonid="'.$DRM->DRMonID.'" data-drpartid="'.$DRM->DRPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                            <button type="button" class="btnDRMonDelete" id="btnDRMonDelete" data-drmonid="'.$DRM->DRMonID.'" data-drpartid="'.$DRM->DRPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                        </td>
                        <td scope="row" class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->DRMonDate.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->LDRMonCode.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->DRPartPartNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->DRPartDescription.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->DRMonCustomer.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->DRMonCustAddress.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->DRMonSupplier.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->LDRMonDRNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRM->LDRMonDRNum.'
                        </td>
                        <td class="px-1 py-0.5 text-center text-xs">
                            '.$DRStat.'
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

    public function getDRParts(Request $request){
        $drmon = DRMonitoring::WHERE('id',$request->DRMonID)->first();

        $drmonP = DRParts::WHERE('DRPartMonID',$request->DRMonID)->get();

        $result2 = '';
        $i = 1;
        foreach ($drmonP as $DRM) {

            if($i == 1){
                $btn = '<button id="addDRMonDIVX" class="addDRMonDIVX"><svg width="24px" height="24px" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><defs><style> .cls-1 { fill: #699f4c; fill-rule: evenodd; } </style></defs><path class="cls-1" d="M1080,270a30,30,0,1,1,30-30A30,30,0,0,1,1080,270Zm14-34h-10V226a4,4,0,0,0-8,0v10h-10a4,4,0,0,0,0,8h10v10a4,4,0,0,0,8,0V244h10A4,4,0,0,0,1094,236Z" id="add" transform="translate(-1050 -210)"></path></g></svg></button>';
            }else{
                $btn = '';
            }

            if($DRM->DRPartStatus == 3){
                $CB = '<input id="DRMonCB'.$i.'" name="DRMonCB'.$i.'" value="3" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" checked>';
            }else{
                $CB = '<input id="DRMonCB'.$i.'" name="DRMonCB'.$i.'" value="3" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">';
            }

            $result2 .= '
                        <div id="DRMonPartsContent'.$i.'" class="grid grid-cols-12 gap-2 mt-1">
                            <div class="col-span-2 grid grid-cols-12">
                                <div class="">
                                    '.$CB.'
                                </div>
                                <div class=""></div>
                                <div class="col-span-10">
                                    <input type="text" id="DRMonPartNum'.$i.'" name="DRMonPartNum'.$i.'" value="'.$DRM->DRPartPartNum.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                                </div>
                            </div>
                            <div class="col-span-3">
                                <input type="text" id="DRMonDescription'.$i.'" name="DRMonDescription'.$i.'" value="'.$DRM->DRPartDescription.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                            </div>
                            <div class="col-span-1">
                                <input type="text" id="DRMonQuantity'.$i.'" name="DRMonQuantity'.$i.'" value="'.$DRM->DRPartQuantity.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="DRMonPurpose'.$i.'" name="DRMonPurpose'.$i.'" value="'.$DRM->DRPartPurpose.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                            </div>
                            <div class="col-span-3">
                                <input type="text" id="DRMonRemarks'.$i.'" name="DRMonRemarks'.$i.'" value="'.$DRM->DRPartRemarks.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                            </div>
                            <div class="">
                                <input type="hidden" id="DRMonID'.$i.'" name="DRMonID'.$i.'" value="'.$DRM->id.'" class="uppercase bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" >
                                '.$btn.'
                            </div>
                        </div>
            ';
            $i++;
        }
        
        $result = array(
            'DRMonID' => $drmon->id,
            'DRMonDate' => $drmon->DRMonDate,
            'DRMonStatus' => $drmon->DRMonStatus,
            'DRMonCustomer' => $drmon->DRMonCustomer,
            'DRMonCustAddress' => $drmon->DRMonCustAddress,
            'DRMonSupplier' => $drmon->DRMonSupplier,
            'DRMonPRNum' => $drmon->DRMonPRNum,
            'LDRMonCode' => $drmon->LDRMonCode,
            'LDRMonModel' => $drmon->LDRMonModel,
            'LDRMonSerial' => $drmon->LDRMonSerial,
            'LDRMonDRNum' => $drmon->LDRMonDRNum,
            'LDRMonPUDate' => $drmon->LDRMonPUDate,
            'LDRMonReqBy' => $drmon->LDRMonReqBy,
            'RDRMonQNum' => $drmon->RDRMonQNum,
            'RDRMonQDate' => $drmon->RDRMonQDate,
            'RDRMonBSNum' => $drmon->RDRMonBSNum,
            'RDRMonDRNum' => $drmon->RDRMonDRNum,
            'RDRMonRetDate' => $drmon->RDRMonRetDate,
            'RDRMonRecBy' => $drmon->RDRMonRecBy,
            'drparts' => $result2,

        );
        return json_encode($result);
    }
    
    public function deleteDRMon(Request $request){
        $drmonid = $request->drmonid;
        $drpartid = $request->drpartid;

        if((DRParts::WHERE('DRPartMonID',$drmonid)->count()) == 1){
            $DRMon = DRMonitoring::find($drmonid);
            $DRMon->delete();
            
            DB::TABLE('d_r_parts')->WHERE('DRPartMonID',$drmonid)->delete();
        }else{
            DB::TABLE('d_r_parts')->WHERE('id',$drpartid)->delete();
        }

        $result = '';
        $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                            FROM d_r_monitorings
                            INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                            ORDER BY DRPartMonID, DRPartPartNum
                    ');

        if (count($drmon) > 0){
            foreach($drmon as $DRM){
                if($DRM->DRPartStatus == 1){
                    $DRStat = "PENDING";
                }else if($DRM->DRPartStatus == 2){
                    $DRStat = "PARTIAL";
                }else if($DRM->DRPartStatus == 3){
                    $DRStat = "CLOSED";
                }else{
                    $DRStat = "CANCELLED";
                }
                $result .='
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-6 p-1">
                                    <button type="button" class="btnDRMonEdit" id="btnDRMonEdit" data-drmonid="'.$DRM->DRMonID.'" data-drpartid="'.$DRM->DRPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                    <button type="button" class="btnDRMonDelete" id="btnDRMonDelete" data-drmonid="'.$DRM->DRMonID.'" data-drpartid="'.$DRM->DRPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                </td>
                                <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->LDRMonCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRPartPartNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRPartDescription.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonCustomer.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonCustAddress.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonSupplier.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->LDRMonDRNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->LDRMonDRNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRStat.'
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

    public function getDRMonStatus(Request $request){
        $id = $request->id;

        $result = '';
        if($id == "DRMonALL"){
            $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                                FROM d_r_monitorings
                                INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                                ORDER BY cast(DRPartMonID as int), DRPartPartNum ASC
                            ');
        }else if($id == "DRMonPending"){
            $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                                FROM d_r_monitorings
                                INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                                WHERE d_r_parts.DRPartStatus = 1
                                ORDER BY cast(DRPartMonID as int), DRPartPartNum ASC
                            ');
        }else if($id == "DRMonOnGoing"){
            $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                                FROM d_r_monitorings
                                INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                                WHERE d_r_parts.DRPartStatus = 2
                                ORDER BY cast(DRPartMonID as int), DRPartPartNum ASC
                            ');
        }else if($id == "DRMonCancelled"){
            $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                                FROM d_r_monitorings
                                INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                                WHERE d_r_parts.DRPartStatus = 3
                                ORDER BY cast(DRPartMonID as int), DRPartPartNum ASC
                            ');
        }else{
            $drmon = DB::SELECT('SELECT d_r_monitorings.id as DRMonID, d_r_monitorings.DRMonStatus, d_r_monitorings.DRMonDate, d_r_monitorings.DRMonCustomer, d_r_monitorings.DRMonCustAddress, d_r_monitorings.DRMonSupplier,
                                d_r_monitorings.DRMonPRNum, d_r_monitorings.LDRMonCode, d_r_monitorings.LDRMonModel, d_r_monitorings.LDRMonSerial, d_r_monitorings.LDRMonDRNum, d_r_monitorings.LDRMonPUDate, 
                                d_r_monitorings.LDRMonReqBy, d_r_monitorings.RDRMonQNum, d_r_monitorings.RDRMonQDate, d_r_monitorings.RDRMonBSNum, d_r_monitorings.RDRMonDRNum, d_r_monitorings.RDRMonRetDate,
                                d_r_monitorings.RDRMonRecBy,
                                d_r_parts.id as DRPartID, d_r_parts.DRPartMonID, d_r_parts.DRPartPartNum, d_r_parts.DRPartDescription, d_r_parts.DRPartQuantity, d_r_parts.DRPartPurpose, d_r_parts.DRPartRemarks, d_r_parts.DRPartStatus
                                FROM d_r_monitorings
                                INNER JOIN d_r_parts on d_r_monitorings.id = d_r_parts.DRPartMonID
                                WHERE d_r_parts.DRPartStatus = 4
                                ORDER BY cast(DRPartMonID as int), DRPartPartNum ASC
                            ');
        }

        if (count($drmon) > 0){
            foreach($drmon as $DRM){
                if($DRM->DRPartStatus == 1){
                    $DRStat = "PENDING";
                }else if($DRM->DRPartStatus == 2){
                    $DRStat = "PARTIAL";
                }else if($DRM->DRPartStatus == 3){
                    $DRStat = "CLOSED";
                }else{
                    $DRStat = "CANCELLED";
                }
                $result .='
                            <tr class="bg-white border-b hover:bg-gray-200">
                                <td class="w-6 p-1">
                                    <button type="button" class="btnDRMonEdit" id="btnDRMonEdit" data-drmonid="'.$DRM->DRMonID.'" data-drpartid="'.$DRM->DRPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                    <button type="button" class="btnDRMonDelete" id="btnDRMonDelete" data-drmonid="'.$DRM->DRMonID.'" data-drpartid="'.$DRM->DRPartID.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                </td>
                                <td scope="row" class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonDate.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->LDRMonCode.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRPartPartNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRPartDescription.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonCustomer.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonCustAddress.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->DRMonSupplier.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->LDRMonDRNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRM->LDRMonDRNum.'
                                </td>
                                <td class="px-1 py-0.5 text-center text-xs">
                                    '.$DRStat.'
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
