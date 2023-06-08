<?php

namespace App\Http\Controllers;

use App\Models\UnitPullOut;
use App\Models\UnitPullOutBat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMonitor extends Controller
{
    public function index(){
        return view('workshop-ms.admin_monitoring.index');
    }

    public function indexR(){
        $brand = DB::SELECT('SELECT * FROM brands WHERE status="1"');
        $section = DB::SELECT('SELECT * FROM sections WHERE status="1"');
        $technician = DB::SELECT('SELECT * FROM technicians WHERE status="1"');
        $bay = DB::SELECT('SELECT * FROM bay_areas WHERE category="1" and status="1" ORDER BY bay_areas.id');

        $bnunit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=1');
        
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0');

        $cunit = DB::SELECT('SELECT unit_confirms.id, unit_confirms.POUID, unit_confirms.CUTransferDate, unit_confirms.CUTransferRemarks, unit_confirms.CUTransferStatus, unit_confirms.CUTransferArea, unit_confirms.CUTransferBay,
                            unit_pull_outs.POUUnitType, unit_pull_outs.POUCode, unit_pull_outs.POUModel, unit_pull_outs.POUSerialNum, unit_pull_outs.POUMastHeight, unit_pull_outs.POUClassification, unit_pull_outs.POURemarks, 
                            unit_pull_outs.POUStatus, unit_pull_outs.POUTransferRemarks
                            FROM unit_confirms
                            INNER JOIN unit_pull_outs on unit_pull_outs.id = unit_confirms.POUID
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

        return view('workshop-ms.admin_monitoring.report',compact('brand','section','technician','bay','bnunit','pounit','cunit', 'workshop'));
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
        $pounit = DB::SELECT('SELECT * FROM unit_pull_outs WHERE POUStatus="" AND POUTransferArea="" AND POUTransferBay="" AND isBrandNew=0');

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
                                                ->select('unit_pull_outs.id as POUnitIDx','unit_pull_outs.POUUnitType', 'unit_pull_outs.POUArrivalDate', 'unit_pull_outs.POUBrand', 'unit_pull_outs.POUClassification', 
                                                'unit_pull_outs.POUModel', 'unit_pull_outs.POUSerialNum', 'unit_pull_outs.POUCode', 'unit_pull_outs.POUMastType', 'unit_pull_outs.POUMastHeight', 'unit_pull_outs.POUForkSize', 
                                                'unit_pull_outs.POUwAttachment', 'unit_pull_outs.POUAttType', 'unit_pull_outs.POUAttModel', 'unit_pull_outs.POUAttSerialNum', 'unit_pull_outs.POUwAccesories', 
                                                'unit_pull_outs.POUAccISite', 'unit_pull_outs.POUAccLiftCam', 'unit_pull_outs.POUAccRedLight', 'unit_pull_outs.POUAccBlueLight', 'unit_pull_outs.POUAccFireExt', 
                                                'unit_pull_outs.POUAccStLight', 'unit_pull_outs.POUAccOthers', 'unit_pull_outs.POUAccOthersDetail', 'unit_pull_outs.POUTechnician1', 'unit_pull_outs.POUTechnician2', 
                                                'unit_pull_outs.POUSalesman', 'unit_pull_outs.POUCustomer', 'unit_pull_outs.POUCustAddress', 'unit_pull_outs.POURemarks', 'unit_pull_out_bats.id as BatID', 
                                                'unit_pull_out_bats.POUID as BatPOUID', 'unit_pull_out_bats.POUBABrand', 'unit_pull_out_bats.POUBABatType', 'unit_pull_out_bats.POUBASerialNum', 'unit_pull_out_bats.POUBACode', 
                                                'unit_pull_out_bats.POUBAAmper', 'unit_pull_out_bats.POUBAVolt', 'unit_pull_out_bats.POUBACCable', 'unit_pull_out_bats.POUBACTable', 'unit_pull_out_bats.POUwSpareBat1', 
                                                'unit_pull_out_bats.POUSB1Brand', 'unit_pull_out_bats.POUSB1BatType', 'unit_pull_out_bats.POUSB1SerialNum', 'unit_pull_out_bats.POUSB1Code', 'unit_pull_out_bats.POUSB1Amper', 
                                                'unit_pull_out_bats.POUSB1Volt', 'unit_pull_out_bats.POUSB1CCable', 'unit_pull_out_bats.POUSB1CTable', 'unit_pull_out_bats.POUwSpareBat2', 'unit_pull_out_bats.POUSB2Brand', 
                                                'unit_pull_out_bats.POUSB2BatType', 'unit_pull_out_bats.POUSB2SerialNum', 'unit_pull_out_bats.POUSB2Code', 'unit_pull_out_bats.POUSB2Amper', 'unit_pull_out_bats.POUSB2Volt', 
                                                'unit_pull_out_bats.POUSB2CCable', 'unit_pull_out_bats.POUSB2CTable', 'unit_pull_out_bats.POUCBrand', 'unit_pull_out_bats.POUCModel', 'unit_pull_out_bats.POUCSerialNum', 
                                                'unit_pull_out_bats.POUCCode', 'unit_pull_out_bats.POUCAmper', 'unit_pull_out_bats.POUCVolt', 'unit_pull_out_bats.POUCInput')
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
}
