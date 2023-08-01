<?php

namespace App\Http\Controllers;

use App\Models\BayArea;
use App\Models\TechnicianSchedule;
use App\Models\UnitConfirm;
use App\Models\UnitDelivery;
use App\Models\UnitPullOut;
use App\Models\UnitWorkshop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WStorage8Controller extends Controller
{
    public function index(){
        $bays = DB::TABLE('bay_areas')
                ->WHERE('status','1')
                ->orderBy('id', 'asc')
                ->orderBy('area_name','asc')->get();
        
        $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType,
                                bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastType, unit_pull_outs.POUClassification,
                                unit_pull_outs.POUMastHeight, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUTransferDate,
                                unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
                                WHERE unit_workshops.WSDelTransfer = 0
                            ');
        
        $sectionT = DB::SELECT('SELECT * FROM sections WHERE status="1"');

        $baysT = DB::TABLE('bay_areas')
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

        
        return view('workshop-ms.w-storage8.index',compact('bays', 'workshop', 'sectionT','baysT','CUnitTICJ','CUnitTEJ','CUnitTICC','CUnitTEC','CUnitTRT','CUnitBTRT','CUnitBTS','CUnitRTR','CUnitRS','CUnitST','CUnitPPT','CUnitOPC','CUnitHPT','CUnitTotal'));
    }

    public function getBayData(Request $request){
        $bay = $request->bay;
        $date = $request->output;
        
        $workshop = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID, unit_workshops.WSBayNum, unit_workshops.WSToA, unit_workshops.WSStatus, unit_workshops.WSUnitType, 
                                unit_workshops.WSATIDS, unit_workshops.WSATIDE, unit_workshops.WSATRDS, unit_workshops.WSATRDE, 
                                unit_workshops.WSAAIDS, unit_workshops.WSAAIDE, unit_workshops.WSAARDS, unit_workshops.WSAARDE, unit_workshops.WSRemarks,
                                unit_workshops.WSVerifiedBy, unit_workshops.WSUnitCondition,
                                bay_areas.area_name, brands.name,
                                unit_pull_outs.POUBrand, unit_pull_outs.POUCustomer, unit_pull_outs.POUCustAddress, unit_pull_outs.POUSalesman, unit_pull_outs.POUBrand, unit_pull_outs.POUModel, unit_pull_outs.POUCode, 
                                unit_pull_outs.POUSerialNum, unit_pull_outs.POUArrivalDate, unit_pull_outs.POUAttType, unit_pull_outs.POUMastType, unit_pull_outs.POUMastHeight, unit_pull_outs.POUClassification, unit_pull_outs.POUTransferDate,
                                unit_pull_outs.POURemarks, unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks, unit_pull_outs.POUTechnician1, 
                                technicians.initials
                                FROM unit_workshops
                                INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_workshops.WSPOUID
                                INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum
                                INNER JOIN technicians on technicians.id = unit_pull_outs.POUTechnician1
                                INNER JOIN brands on brands.id = unit_pull_outs.POUBrand
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
                                    'WSUnitCondition' => $WS->WSUnitCondition,
                                    'WSRemarks' => $WS->WSRemarks,

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

    public function saveTransferData(Request $request){
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

    public function saveUnitData(Request $request){
        UnitWorkshop::where('id', $request->UnitInfoJON)
                    ->update([
                        'WSToA' => $request->UnitInfoToA,
                        'WSStatus' => $request->UnitInfoStatus,
                        'WSUnitType' => $request->WHUnitType,
                        'WSVerifiedBy' => strtoupper($request->WHVB),
                        'WSRemarks' => strtoupper($request->WSRemarks),
                        'WSUnitCondition' => $request->input('Radio_Unit'),
                    ]);
    }
}
