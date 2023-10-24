<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogsController extends Controller
{
    public function index(){
        $logs = DB::table('wms_activity_logs')
        ->select('table', 'table_key', 'action', 'description', 'field', 'before', 'after', 'name', 'ipaddress','wms_activity_logs.created_at','wms_activity_logs.updated_at')
        ->leftJoin('wms_users', 'wms_activity_logs.user_id', 'wms_users.id')
        ->whereIn('wms_activity_logs.id', function ($query) {
            $query->select(DB::raw('MIN(id)'))
                ->from('wms_activity_logs')
                ->groupBy('table_key');
        })
        ->get();

        return view('system-management.activity-logs.index', compact('logs'));
    }

    public function getLogs(Request $request){
        $activity = DB::table('wms_activity_logs')
                ->select('table', 'table_key', 'action', 'description', 'field', 'before', 'after', 'name', 'ipaddress','wms_activity_logs.created_at','wms_activity_logs.updated_at')
                ->leftJoin('wms_users', 'wms_activity_logs.user_id', 'wms_users.id')
                ->where('table_key',$request->tableKey)
                ->orderBy('wms_activity_logs.created_at', 'desc') 
                ->get();

        // dd($activity);

        $result = '';
        $content = '';

        $x = 1;
        foreach ($activity as $act) {
            $ndate = $act->created_at;
            if ($x == 1) {
                $cdate = $act->created_at;
            }

            if ($ndate == $cdate) {
                if($act->field == 'IsBrandNew'){
                    $label = "Brand New Unit";
                }else if($act->field == 'POUUnitType'){
                    $label = "Unit Type";
                }else if($act->field == 'POUArrivalDate'){
                    $label = "Arrival Date";
                }else if($act->field == 'POUBrand'){
                    $label = "Brand";
                }else if($act->field == 'POUClassification'){
                    $label = "Classification";
                }else if($act->field == 'POUModel'){
                    $label = "Model";
                }else if($act->field == 'POUSerialNum'){
                    $label = "Serial Number";
                }else if($act->field == 'POUMastType'){
                    $label = "Mast Type";
                }else if($act->field == 'POUMastHeight'){
                    $label = "Mast Height";
                }else if($act->field == 'POUForkSize'){
                    $label = "Fork Size";
                }else if($act->field == 'POUwAttachment'){
                    $label = "Unit with Attachment";
                }else if($act->field == 'POUwAttachment'){
                    $label = "Unit with Attachment";
                }else if($act->field == 'POUwAttachment'){
                    $label = "Unit with Attachment";
                }else if($act->field == 'POUwAttachment'){
                    $label = "Unit with Attachment";
                }else if($act->field == 'POUAttType'){
                    $label = "Attachment Type";
                }else if($act->field == 'POUAttModel'){
                    $label = "Attachment Model";
                }else if($act->field == 'POUAttSerialNum'){
                    $label = "Attachment Serial Number";
                }else if($act->field == 'POUwAccesories'){
                    $label = "Unit with Accessories";
                }else if($act->field == 'POUAccISite'){
                    $label = "Unit Accessories - I-Site";
                }else if($act->field == 'POUAccLiftCam'){
                    $label = "Unit Accessories - Lift Cam";
                }else if($act->field == 'POUAccRedLight'){
                    $label = "Unit Accessories - Red Light";
                }else if($act->field == 'POUAccBlueLight'){
                    $label = "Unit Accessories - Blue Light";
                }else if($act->field == 'POUAccFireExt'){
                    $label = "Unit Accessories - Fire Extinguiser";
                }else if($act->field == 'POUAccStLight'){
                    $label = "Unit Accessories - Strobe Light";
                }else if($act->field == 'POUAccOthers'){
                    $label = "Unit Accessories - Others";
                }else if($act->field == 'POUAccOthersDetail'){
                    $label = "Unit Accessories - Others-Detail";
                }else if($act->field == 'POUTechnician1'){
                    $label = "Technician 1";
                }else if($act->field == 'POUTechnician2'){
                    $label = "Technician 2";
                }else if($act->field == 'POUSalesman'){
                    $label = "Salesman";
                }else if($act->field == 'POUCustomer'){
                    $label = "Customer";
                }else if($act->field == 'POUCustAddress'){
                    $label = "Customer Address";
                }else if($act->field == 'POURemarks'){
                    $label = "Remarks";
                }else if($act->field == 'POUStatus'){
                    $label = "Unit Status";
                }else if($act->field == 'POUTransferArea'){
                    $label = "Transfer Area";
                }else if($act->field == 'POUTransferBay'){
                    $label = "Transfer Bay";
                }else if($act->field == 'POUTransferDate'){
                    $label = "Transfer Date";
                }else if($act->field == 'POUTransferRemarks'){
                    $label = "Transfer Remarks";
                }else if($act->field == 'POUID'){
                    $label = "Unit ID";
                }else if($act->field == 'POUBABrand'){
                    $label = "Battery Attached - Brand";
                }else if($act->field == 'POUBABatType'){
                    $label = "Battery Attached - Battery Type";
                }else if($act->field == 'POUBASerialNum'){
                    $label = "Battery Attached - Serial Number";
                }else if($act->field == 'POUBACode'){
                    $label = "Battery Attached - Code";
                }else if($act->field == 'POUBAAmper'){
                    $label = "Battery Attached - Amper";
                }else if($act->field == 'POUBAVolt'){
                    $label = "Battery Attached - Volt";
                }else if($act->field == 'POUBACCable'){
                    $label = "Battery Attached - C Cable";
                }else if($act->field == 'POUBACTable'){
                    $label = "Battery Attached - C Table";
                }else if($act->field == 'POUwSpareBat1'){
                    $label = "Unit with Spare Battery 1";
                }else if($act->field == 'POUSB1Brand'){
                    $label = "Spare Battery 1 - Brand";
                }else if($act->field == 'POUSB1BatType'){
                    $label = "Spare Battery 1 - Battery Type";
                }else if($act->field == 'POUSB1SerialNum'){
                    $label = "Spare Battery 1 - Serial Number";
                }else if($act->field == 'POUSB1Code'){
                    $label = "Spare Battery 1 - Code";
                }else if($act->field == 'POUSB1Amper'){
                    $label = "Spare Battery 1 - Amper";
                }else if($act->field == 'POUSB1Volt'){
                    $label = "Spare Battery 1 - Volt";
                }else if($act->field == 'POUSB1CCable'){
                    $label = "Spare Battery 1 - C Cable";
                }else if($act->field == 'POUSB1CTable'){
                    $label = "Spare Battery 1 - C Table";
                }else if($act->field == 'POUwSpareBat2'){
                    $label = "Unit with Spare Battery 2";
                }else if($act->field == 'POUSB2Brand'){
                    $label = "Spare Battery 2 - Brand";
                }else if($act->field == 'POUSB2BatType'){
                    $label = "Spare Battery 2 - Battery Type";
                }else if($act->field == 'POUSB2SerialNum'){
                    $label = "Spare Battery 2 - Serial Number";
                }else if($act->field == 'POUSB2Code'){
                    $label = "Spare Battery 2 - Code";
                }else if($act->field == 'POUSB2Amper'){
                    $label = "Spare Battery 2 - Amper";
                }else if($act->field == 'POUSB2Volt'){
                    $label = "Spare Battery 2 - Volt";
                }else if($act->field == 'POUSB2CCable'){
                    $label = "Spare Battery 2 - C Cable";
                }else if($act->field == 'POUSB2CTable'){
                    $label = "Spare Battery 2 - C Table";
                }else if($act->field == 'POUCBrand'){
                    $label = "Charger - Brand";
                }else if($act->field == 'POUCModel'){
                    $label = "Charger - Model";
                }else if($act->field == 'POUCSerialNum'){
                    $label = "Charger - Serial Number";
                }else if($act->field == 'POUCCode'){
                    $label = "Charger - Code";
                }else if($act->field == 'POUCAmper'){
                    $label = "Charger - Amper";
                }else if($act->field == 'POUCVolt'){
                    $label = "Charger - Volt";
                }else if($act->field == 'POUCInput'){
                    $label = "Charger - Input";
                }else{
                    $label = $act->field;
                }

// ADD 
                if($act->action == "ADD"){
                    $additionalClass = "bg-blue-300";
                    if($act->field != 'Is Deleted'){
                        if($act->field == "Role"){
                            if($act->after == 0){
                                $value = "Super Admin";
                            }else if($act->after == 1){
                                $value = "Admin";
                            }else{
                                $value = "User";
                            }
                        }else if($act->field == "Dept"){
                            $value = DB::table('departments')->where('id',$act->after)->first()->name;
                        }else if($act->field == "IsBrandNew"){
                            if($act->after == 0){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUUnitType"){
                                if($act->after == 1){
                                    $value = "DIESEL/GASOLINE/LPG";
                                }else{
                                    $value = "BATTERY";
                                }
                        }else if($act->field == "POUBrand"){
                            $value = DB::table('brands')->where('id',$act->after)->first()->name;
                        }else if($act->field == "POUClassification"){
                            if($act->after == 1){
                                $value = "CLASS A";
                            }else if($act->after == 2){
                                $value = "CLASS B";
                            }else if($act->after == 3){
                                $value = "CLASS C";
                            }else{
                                $value = "CLASS D";
                            }
                        }else if($act->field == "POUwAttachment"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAttType"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUAttModel"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUAttSerialNum"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUwAccesories"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccISite"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccLiftCam"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccRedLight"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccBlueLight"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccFireExt"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccStLight"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccOthers"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUAccOthersDetail"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUTechnician1"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                            }
                        }else if($act->field == "POUTechnician2"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                            }
                        }else if($act->field == "POUStatus"){
                            if($act->after == 1){
                                $value = "WAITING FOR REPAIR UNIT";
                            }else if($act->after == 2){
                                $value = "UNDER REPAIR UNIT";
                            }else if($act->after == 3){
                                $value = "USED GOOD UNIT";
                            }else if($act->after == 4){
                                $value = "SERVICE UNIT";
                            }else if($act->after == 5){
                                $value = "FOR SCRAP UNIT";
                            }else if($act->after == 6){
                                $value = "FOR SALE UNIT";
                            }else if($act->after == 7){
                                $value = "WAITING PARTS";
                            }else if($act->after == 8){
                                $value = "WAITING BACK ORDER";
                            }else if($act->after == 9){
                                $value = "WAITING SPARE BATT";
                            }else if($act->after == 10){
                                $value = "STOCK UNIT";
                            }else if($act->after == 11){
                                $value = "WAITING FOR MCI";
                            }else if($act->after == 12){
                                $value = "WAITING FOR PDI";
                            }else{
                                $value = "DONE PDI (WFD)";
                            }
                        }else if($act->field == "POUTransferArea"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = DB::table('sections')->where('id',$act->after)->first()->name;
                            }
                        }else if($act->field == "POUTransferBay"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                            }
                        }else if($act->field == "POUTransferDate"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUTransferRemarks"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUwSpareBat1"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUSB1Brand"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1BatType"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1SerialNum"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1Code"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1Amper"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1Volt"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1CCable"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB1CTable"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUwSpareBat2"){
                            if($act->after == 0 || $act->after == null ){
                                $value = "No";
                            }else{
                                $value = "Yes";
                            }
                        }else if($act->field == "POUSB2Brand"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2BatType"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2SerialNum"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2Code"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2Amper"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2Volt"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2CCable"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else if($act->field == "POUSB2CTable"){
                            if($act->after == null ){
                                $value = "-";
                            }else{
                                $value = $act->after;
                            }
                        }else{
                            $value = $act->after;
                        }
                        $content .= '
                                    <div>
                                        <div class="">'.$label.': '.$value.' </div>
                                    </div>
                        ';
                    }
// UPDATE
                }else if($act->action == "UPDATE"){
                    $additionalClass = "bg-green-300";
                    if($act->field != 'Is Deleted'){
                        // BEFORE
                            if($act->field == "Role"){
                                if($act->before == 0){
                                    $valueB = "Super Admin";
                                }else if($act->before == 1){
                                    $valueB = "Admin";
                                }else{
                                    $valueB = "User";
                                }
                            }else if($act->field == "Dept"){
                                $valueB = DB::table('departments')->where('id',$act->before)->first()->name;
                            }else if($act->field == "IsBrandNew"){
                                if($act->before == 0){
                                    $valueB = "No";
                                }else{
                                    $valueB = "Yes";
                                }
                            }else if($act->field == "POUUnitType"){
                                    if($act->before == 1){
                                        $valueB = "DIESEL/GASOLINE/LPG";
                                    }else{
                                        $valueB = "BATTERY";
                                    }
                            }else if($act->field == "POUBrand"){
                                $valueB = DB::table('brands')->where('id',$act->before)->first()->name;
                            }else{
                                $valueB = $act->before;
                            }

                        // AFTER
                            if($act->field == "Role"){
                                if($act->after == 0){
                                    $valueA = "Super Admin";
                                }else if($act->after == 1){
                                    $valueA = "Admin";
                                }else{
                                    $valueA = "User";
                                }
                            }else if($act->field == "Dept"){
                                $valueA = DB::table('departments')->where('id',$act->after)->first()->name;
                            }else if($act->field == "IsBrandNew"){
                                if($act->after == 0){
                                    $valueA = "No";
                                }else{
                                    $valueA = "Yes";
                                }
                            }else if($act->field == "POUUnitType"){
                                    if($act->after == 1){
                                        $valueA = "DIESEL/GASOLINE/LPG";
                                    }else{
                                        $valueA = "BATTERY";
                                    }
                            }else if($act->field == "POUBrand"){
                                $valueA = DB::table('brands')->where('id',$act->after)->first()->name;
                            }else{
                                $valueA = $act->after;
                            }

                        $content .= '
                                    <div>
                                        <div class="">'.$label.': '.$valueB.' ⇒ '.$valueA.'</div>
                                    </div>
                        ';
                    }
// DELETE
                }else{
                    $additionalClass = "bg-red-300";
                    $content .= 'Deleted.';
                }
                
                $action = $act->action;
                $desc = $act->description;
            }
            if($ndate != $cdate || $x == $activity->count()){
                $result .='
                            <li class="mb-10 ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" id="user">
                                            <path fill="#C4E6FF" fill-rule="evenodd" d="M3 20C3 16.8289 5.10851 14.1503 8 13.2898V18C8 18.5522 8.44772 19 9 19C11.1785 19 20.9291 19 20.9291 19C20.9758 19.3266 21 19.6604 21 20C21 21.6568 19.6569 23 18 23H6C4.34315 23 3 21.6568 3 20Z" clip-rule="evenodd"></path>
                                            <path fill="#1E93FF" fill-rule="evenodd" d="M12 3C10.3431 3 9 4.34315 9 6C9 7.65685 10.3431 9 12 9C13.6569 9 15 7.65685 15 6C15 4.34315 13.6569 3 12 3ZM7 6C7 3.23858 9.23858 1 12 1C14.7614 1 17 3.23858 17 6C17 8.76142 14.7614 11 12 11C9.23858 11 7 8.76142 7 6Z" clip-rule="evenodd"></path>
                                            <path fill="#024493" fill-rule="evenodd" d="M3 20C3 16.134 6.13401 13 10 13H14C17.866 13 21 16.134 21 20C21 21.6569 19.6569 23 18 23H6C4.34315 23 3 21.6569 3 20ZM10 15C7.23858 15 5 17.2386 5 20C5 20.5523 5.44772 21 6 21H18C18.5523 21 19 20.5523 19 20C19 17.2386 16.7614 15 14 15H10Z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                    <div class="items-center justify-between mb-3 sm:flex">
                                        <div class="text-sm font-normal text-gray-500 lex">'.$act->name.' <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">'.$act->created_at.'</time></div>
                                        <div class="text-sm font-normal text-gray-500 lex">'.$desc.'</div>
                                        <div class="text-sm font-normal text-gray-500 lex">'.$action.'</div>
                                    </div>
                                    <div class="p-3 text-xs italic font-normal text-gray-500 border border-gray-200 rounded-lg ' . $additionalClass . '">
                                        '.$content.'
                                    </div>
                                </div>
                            </li>
                    ';

                $content = "";
                if($ndate != $cdate && $x == $activity->count()){
                    if($act->field == 'IsBrandNew'){
                        $label = "Brand New Unit";
                    }else if($act->field == 'POUUnitType'){
                        $label = "Unit Type";
                    }else if($act->field == 'POUArrivalDate'){
                        $label = "Arrival Date";
                    }
    
                    if($act->action == "ADD"){
                        $additionalClass = "bg-blue-300";
                        $content .= '
                                    <div>
                                        <div class="">'.$label.': '.$act->after.' </div>
                                    </div>
                        ';
                    }else if($act->action == "UPDATE"){
                        $additionalClass = "bg-green-300";
                        $content .= '
                                    <div>
                                        <div class="">'.$label.': '.$act->before.' ⇒ '.$act->after.'</div>
                                    </div>
                        ';
                    }else{
                        $additionalClass = "bg-red-300";
                        $content .= 'Deleted.';
                    }
                    $action = $act->action;
                    $desc = $act->description;

                    $result .='
                                <li class="mb-10 ml-6">
                                        <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" id="user">
                                                <path fill="#C4E6FF" fill-rule="evenodd" d="M3 20C3 16.8289 5.10851 14.1503 8 13.2898V18C8 18.5522 8.44772 19 9 19C11.1785 19 20.9291 19 20.9291 19C20.9758 19.3266 21 19.6604 21 20C21 21.6568 19.6569 23 18 23H6C4.34315 23 3 21.6568 3 20Z" clip-rule="evenodd"></path>
                                                <path fill="#1E93FF" fill-rule="evenodd" d="M12 3C10.3431 3 9 4.34315 9 6C9 7.65685 10.3431 9 12 9C13.6569 9 15 7.65685 15 6C15 4.34315 13.6569 3 12 3ZM7 6C7 3.23858 9.23858 1 12 1C14.7614 1 17 3.23858 17 6C17 8.76142 14.7614 11 12 11C9.23858 11 7 8.76142 7 6Z" clip-rule="evenodd"></path>
                                                <path fill="#024493" fill-rule="evenodd" d="M3 20C3 16.134 6.13401 13 10 13H14C17.866 13 21 16.134 21 20C21 21.6569 19.6569 23 18 23H6C4.34315 23 3 21.6569 3 20ZM10 15C7.23858 15 5 17.2386 5 20C5 20.5523 5.44772 21 6 21H18C18.5523 21 19 20.5523 19 20C19 17.2386 16.7614 15 14 15H10Z" clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                                        <div class="items-center justify-between mb-3 sm:flex">
                                            <div class="text-sm font-normal text-gray-500 lex">'.$act->name.' <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">'.$act->created_at.'</time></div>
                                            <div class="text-sm font-normal text-gray-500 lex">'.$desc.'</div>
                                            <div class="text-sm font-normal text-gray-500 lex">'.$action.'</div>
                                        </div>
                                        <div class="p-3 text-xs italic font-normal text-gray-500 border border-gray-200 rounded-lg ' . $additionalClass . '">
                                            '.$content.'
                                        </div>
                                    </div>
                                </li>
                        ';
                }

                if($x != $activity->count()){
                    if($act->field == 'Itemno'){
                        $label = "Item Number";
                    }else if($act->field == 'Partno'){
                        $label = "Part Number";
                    }else if($act->field == 'Partname'){
                        $label = "Part Name";
                    }
    
                    if($act->action == "ADD"){
                        $additionalClass = "bg-blue-300";
                        $content .= '
                                    <div>
                                        <div class="">'.$label.': '.$act->after.' </div>
                                    </div>
                        ';
                    }else if($act->action == "UPDATE"){
                        $additionalClass = "bg-green-300";
                        $content .= '
                                    <div>
                                        <div class="">'.$label.': '.$act->before.' ⇒ '.$act->after.'</div>
                                    </div>
                        ';
                    }else{
                        $additionalClass = "bg-red-300";
                        $content .= 'Deleted.';
                    }
                    $action = $act->action;
                }
                $cdate = $ndate;
            }
            $x++;
        }
        return response()->json($result);
    }
}
