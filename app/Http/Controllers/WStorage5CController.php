<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BayArea;
use App\Models\TechnicianSchedule;
use App\Models\UnitConfirm;
use App\Models\UnitDelivery;
use App\Models\UnitPullOut;
use App\Models\UnitWorkshop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WStorage5CController extends Controller
{
    public function index(){
        $bays = DB::TABLE('wms_bay_areas')
                ->WHERE('status','1')
                ->orderBy('id', 'asc')
                ->orderBy('area_name','asc')->get();
        
        $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                wms_bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification,
                                unit_pull_outs.POUMastHeight, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUArrivalDate, unit_pull_outs.POUTransferDate,
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
        
        $sectionT = DB::SELECT('SELECT * FROM wms_sections WHERE status="1"');

        $baysT = DB::TABLE('wms_bay_areas')
                ->WHERE('status','1')
                ->orderBy('area_name','asc')->get();



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

        
        return view('workshop-ms.w-storage5c.index',compact('bays', 'workshop', 'scl', 'sectionT','baysT','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
    }

    public function getBayData(Request $request){
        $bay = $request->bay;
        $date = $request->output;
        
        $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
                                unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
                                unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE, unit_workshops.WSRemarks,
                                unit_workshops.WSVerifiedBy, unit_workshops.WSUnitCondition,
                                wms_bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, 
                                unit_pull_outs.POUSerialNum, unit_pull_outs.POUArrivalDate, unit_pull_outs.POUAttType, unit_pull_outs.POUMastType, unit_pull_outs.POUMastHeight, unit_pull_outs.POUClassification, unit_pull_outs.POUTransferDate,
                                unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, 
                                wms_technicians.initials,
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
                    $result = array(
                                    'TransferDate' => $WS->POUTransferDate,
                                    'WSID' => $WS->WSID,
                                    'WSPOUID' => $WS->WSPOUID,
                                    'WSToA' => $WS->WSToA,
                                    'WSStatus' => $WS->WSStatus,
                                    'WSBayNum' => $WS->WSBayNum,
                                    'POUBrand' => $WS->name,
                                    'POUCode' => $WS->POUCode,
                                    'POUModel' => $WS->POUModel,
                                    'POUSerialNum' => $WS->POUSerialNum,
                                    'POUAttType' => $WS->POUAttType,
                                    'POUMastType' => $WS->POUMastType,
                                    'POUMastHeight' => $WS->POUMastHeight,
                                    'POUArrivalDate' => $WS->POUArrivalDate,
                                    'POUClassification' => $WS->POUClassification,
                                    'WSUnitType' => $WS->WSUnitType,
                                    'initials' => $WS->initials,
                                    'WSVerifiedBy' => $WS->WSVerifiedBy,
                                    'WSRemarks' => $WS->WSRemarks,
                                    'WSUnitCondition' => $WS->WSUnitCondition,

                                    'POUCustomer' => $WS->POUCustomer,
                                    'POUCustAddress' => $WS->POUCustAddress,
                                    'POUSalesman' => $WS->POUSalesman,
                                    'WSATIDS' => $WS->WSATIDS,
                                    'WSATIDE' => $WS->WSATIDE,
                                    'WSATRDS' => $WS->WSATRDS,
                                    'WSATRDE' => $WS->WSATRDE,
                                    'WSAAIDS' => $WS->WSAAIDS,
                                    'WSAAIDE' => $WS->WSAAIDE,
                                    'WSAARDS' => $WS->WSAARDS,
                                    'WSAARDE' => $WS->WSAARDE,
                                    // 'WSStatus' => $WS->WSStatus,
                        
                    );
                }
            }else{
                $result = " ";
            }
        return response()->json($result);
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

    public function saveTransferData(Request $request){
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

    public function saveUnitData(Request $request){
        $POUB = UnitPullOut::where('id', $request->UnitInfoPOUID)->WHERE('is_deleted',0)->first();
        $WSB = UnitWorkshop::where('id', $request->UnitInfoJON)->WHERE('is_deleted',0)->latest()->first();
        UnitWorkshop::where('id', $request->UnitInfoJON)
                    ->update([
                        'WSToA' => $request->UnitInfoToA,
                        'WSStatus' => $request->UnitInfoStatus,
                        'WSUnitType' => $request->WHUnitType,
                        'WSVerifiedBy' => strtoupper($request->WHVB),
                        'WSRemarks' => strtoupper($request->WSRemarks),
                        'WSUnitCondition' => $request->input('Radio_Unit'),
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
                'POUUnitType2' => $request->WHUnitType,
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
    }
}
