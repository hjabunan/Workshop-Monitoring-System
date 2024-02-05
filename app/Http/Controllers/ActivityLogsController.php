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
                ->groupBy('table_key','table');
        })
        ->orderBy('wms_activity_logs.created_at', 'desc')
        ->take(100)
        ->paginate(25);

        return view('system-management.activity-logs.index', compact('logs'));
    }

    public function getLogs(Request $request){
        $activity = DB::table('wms_activity_logs')
                ->select('table', 'table_key', 'action', 'description', 'field', 'before', 'after', 'name', 'ipaddress','wms_activity_logs.created_at','wms_activity_logs.updated_at')
                ->leftJoin('wms_users', 'wms_activity_logs.user_id', 'wms_users.id')
                ->where('table_key',$request->tableKey)
                ->where('table',$request->tableX)
                ->where('description',$request->desc)
                ->orderBy('wms_activity_logs.created_at', 'desc')
                ->get();

        $result = '';
        $content = '';

        $x = 1;
        
        foreach ($activity as $act) {
            $ndate = $act->created_at;
            if ($x == 1) {
                $cdate = $act->created_at;
            }

// FIRST ENTRY
            if ($ndate == $cdate) {
                // Unit - PULLOUT
                    if($act->field == 'IsBrandNew'){
                        $label = "Brand New Unit";
                    }else if($act->field == 'POUUnitType'){
                        $label = "Unit";
                    }else if($act->field == 'POUArrivalDate'){
                        $label = "Arrival Date";
                    }else if($act->field == 'POUBrand'){
                        $label = "Brand";
                    }else if($act->field == 'POUUnitType2'){
                        $label = "Unit Type";
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
                        $label = "Pullout Unit ID";
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
                // CONFIRM
                    }else if($act->field == 'CUTransferDate'){
                        $label = "Confirm - Transfer Date";
                    }else if($act->field == 'CUTransferRemarks'){
                        $label = "Confirm - Transfer Remarks";
                    }else if($act->field == 'CUTransferStatus'){
                        $label = "Confirm - Transfer Status";
                    }else if($act->field == 'CUTransferArea'){
                        $label = "Confirm - Transfer Area";
                    }else if($act->field == 'CUTransferBay'){
                        $label = "Confirm - Transfer Bay";
                    }else if($act->field == 'CUDelTransfer'){
                        $label = "Confirm - Delivered?";
                // WORKSHOP
                    }else if($act->field == 'WSPOUID'){
                        $label = "Pullout - Unit ID";
                    }else if($act->field == 'WSBayNum'){ 
                        $label = "Workshop - Bay Number";
                    }else if($act->field == 'WSToA'){
                        $label = "Workshop - Type of Activity";
                    }else if($act->field == 'WSStatus'){
                        $label = "Workshop - Status";
                    }else if($act->field == 'WSUnitType'){
                        $label = "Workshop - Unit Type";
                    }else if($act->field == 'WSVerifiedBy'){
                        $label = "Workshop - Verified By";
                    }else if($act->field == 'WSUnitCondition'){
                        $label = "Workshop - Unit Condition";
                    }else if($act->field == 'WSATIDS'){
                        $label = "Workshop - Target Inspection Date Start";
                    }else if($act->field == 'WSATIDE'){
                        $label = "Workshop - Target Inspection Date End";
                    }else if($act->field == 'WSATRDS'){
                        $label = "Workshop - Target Repair Date Start";
                    }else if($act->field == 'WSATRDE'){
                        $label = "Workshop - Target Repair Date End";
                    }else if($act->field == 'WSAAIDS'){
                        $label = "Workshop - Actual Inspection Date Start";
                    }else if($act->field == 'WSAAIDE'){
                        $label = "Workshop - Actual Inspection Date End";
                    }else if($act->field == 'WSAARDS'){
                        $label = "Workshop - Actual Repair Date Start";
                    }else if($act->field == 'WSAARDE'){
                        $label = "Workshop - Actual Repair Date End";
                    }else if($act->field == 'WSRemarks'){
                        $label = "Workshop - Remarks";
                    }else if($act->field == 'WSDelTransfer'){
                        $label = "Workshop - Delivered?";
                // TECHNICIAN SCHEDULE
                    }else if($act->field == 'Techid'){
                        $label = "Tech. Schedule - Technician";
                    }else if($act->field == 'Baynum'){
                        $label = "Tech. Schedule - Bay Area";
                    }else if($act->field == 'JONumber'){
                        $label = "Tech. Schedule - J.O. Number";
                    }else if($act->field == 'Scheddate'){
                        $label = "Tech. Schedule - Sched. Date";
                    }else if($act->field == 'Scopeofwork'){
                        $label = "Tech. Schedule - Scope of Work";
                    }else if($act->field == 'Activity'){
                        $label = "Tech. Schedule - Activity";
                // Unit - PARTS
                    }else if($act->field == 'PIJONum'){
                        $label = "Unit Parts Info - JO Number";
                    }else if($act->field == 'PIMRINum'){
                        $label = "Unit Parts Info - MRI Number";
                    }else if($act->field == 'PIPartID'){
                        $label = "Unit Parts Info - Part ID";
                    }else if($act->field == 'PIPartNum'){
                        $label = "Unit Parts Info - Part Number";
                    }else if($act->field == 'PIDescription'){
                        $label = "Unit Parts Info - Description";
                    }else if($act->field == 'PIQuantity'){
                        $label = "Unit Parts Info - Quantity";
                    }else if($act->field == 'PIPrice'){
                        $label = "Unit Parts Info - Price";
                    }else if($act->field == 'PIDateReq'){
                        $label = "Unit Parts Info - Date Requested";
                    }else if($act->field == 'PIDateRec'){
                        $label = "Unit Parts Info - Date Received";
                    }else if($act->field == 'PIReason'){
                        $label = "Unit Parts Info - Reason";
                    }else if($act->field == 'PIDateInstalled'){
                        $label = "Unit Parts Info - Date Installed";
                    }else if($act->field == 'PIRemarks'){
                        $label = "Unit Parts Info - Remarks";
                // Units - DOWNTIME
                    }else if($act->field == 'DTJONum'){
                        $label = "Downtime - JO Number";
                    }else if($act->field == 'DTSDate'){
                        $label = "Downtime - Start Date";
                    }else if($act->field == 'DTEDate'){
                        $label = "Downtime - End Date";
                    }else if($act->field == 'DTReason'){
                        $label = "Downtime - Reason";
                    }else if($act->field == 'DTRemarks'){
                        $label = "Downtime - Remarks";
                    }else if($act->field == 'DTTDays'){
                        $label = "Downtime - Total Days";
                // Units - DELIVERED
                    }else if($act->field == 'DUTransferDate'){
                        $label = "Delivered - Transfer Date";
                    }else if($act->field == 'DURemarks'){
                        $label = "Delivered - Remarks";
                    }else if($act->field == 'DUDelDate'){
                        $label = "Delivered - Delivery Date";
                    }else{
                        $label = $act->field;
                    }

            // ADD
                if($act->action == "ADD"){
                    $additionalClass = "bg-blue-300";
                    
                    if($act->field != 'Is Deleted'){
                        // USER`
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
                        // Unit - PULLOUT
                            }else if($act->field == "POUUnitType"){
                                    if($act->after == 1){
                                        $value = "DIESEL/GASOLINE/LPG";
                                    }else{
                                        $value = "BATTERY";
                                    }
                            }else if($act->field == "POUBrand"){
                                $value = DB::table('brands')->where('id',$act->after)->first()->name;
                            }else if($act->field == "POUUnitType2"){
                                    if($act->after == 1){
                                        $value = "TOYOTA IC JAPAN";
                                    }else if($act->after == 2){
                                        $value = "TOYOTA ELECTRIC JAPAN";
                                    }else if($act->after == 3){
                                        $value = "TOYOTA IC CHINA";
                                    }else if($act->after == 4){
                                        $value = "TOYOTA ELECTRIC CHINA";
                                    }else if($act->after == 5){
                                        $value = "TOYOTA REACH TRUCK";
                                    }else if($act->after == 6){
                                        $value = "BT REACH TRUCK";
                                    }else if($act->after == 7){
                                        $value = "BT STACKER";
                                    }else if($act->after == 8){
                                        $value = "RAYMOND REACH TRUCK";
                                    }else if($act->after == 9){
                                        $value = "RAYMOND STACKER";
                                    }else if($act->after == 10){
                                        $value = "STACKER TAILIFT";
                                    }else if($act->after == 11){
                                        $value = "PPT";
                                    }else if($act->after == 12){
                                        $value = "OPH";
                                    }else{
                                        $value = "HPT";
                                    }
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
                                if($act->after == null ||$act->after == 0 ){
                                    $value = "-";
                                }else if($act->after == 1){
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
                        // Unit - CONFIRM
                            }else if($act->field == "CUTransferStatus"){
                                if($act->after == null ){
                                    $value = "-";
                                }else{
                                    if($act->after == null ||$act->after == 0 ){
                                        $value = "-";
                                    }else if($act->after == 1){
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
                                }
                            }else if($act->field == "CUTransferArea"){
                                if($act->after == null ){
                                    $value = "-";
                                }else{
                                    $value = DB::table('sections')->where('id',$act->after)->first()->name;
                                }
                            }else if($act->field == "CUTransferBay"){
                                if($act->after == null ){
                                    $value = "-";
                                }else{
                                    $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                }
                            }else if($act->field == "CUDelTransfer"){
                                if($act->after == 1 ){
                                    $value = "YES";
                                }else{
                                    $value = "NO";
                                }
                        // Unit - WORKSHOP
                            }else if($act->field == "WSBayNum"){
                                if($act->after == null ){
                                    $value = "-";
                                }else{
                                    $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                }
                            }else if($act->field == "WSToA"){
                                if($act->after == 1){
                                    $value = "WAREHOUSE";
                                }else if($act->after == 2){
                                    $value = "WORKSHOP";
                                }else{
                                    $value = "PDI";
                                }
                            }else if($act->field == "WSStatus"){
                                if($act->after == null ){
                                    $value = "-";
                                }else{
                                    if($act->after == null ||$act->after == 0 ){
                                        $value = "-";
                                    }else if($act->after == 1){
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
                                }
                            }else if($act->field == "WSUnitType"){
                                if($act->after == null ){
                                    $value = "-";
                                }else{
                                    if($act->after == null ||$act->after == 0 ){
                                        $value = "-";
                                    }else if($act->after == 1){
                                        $value = "TOYOTA IC JAPAN";
                                    }else if($act->after == 2){
                                        $value = "TOYOTA ELECTRIC JAPAN";
                                    }else if($act->after == 3){
                                        $value = "TOYOTA IC CHINA";
                                    }else if($act->after == 4){
                                        $value = "TOYOTA ELECTRIC CHINA";
                                    }else if($act->after == 5){
                                        $value = "TOYOTA REACH TRUCK";
                                    }else if($act->after == 6){
                                        $value = "BT REACH TRUCK";
                                    }else if($act->after == 7){
                                        $value = "BT STACKER";
                                    }else if($act->after == 8){
                                        $value = "RAYMOND REACH TRUCK";
                                    }else if($act->after == 9){
                                        $value = "RAYMOND STACKER";
                                    }else if($act->after == 10){
                                        $value = "STACKER TAILIFT";
                                    }else if($act->after == 11){
                                        $value = "PPT";
                                    }else if($act->after == 12){
                                        $value = "OPC";
                                    }else{
                                        $value = "HPT";
                                    }
                                }
                            }else if($act->field == "WSDelTransfer"){
                                if($act->after == 1 ){
                                    $value = "YES";
                                }else{
                                    $value = "NO";
                                }
                        // TECHNICIAN SCHEDULE
                            }else if($act->field == "Techid"){
                                $value = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                            }else if($act->field == "Baynum"){
                                $value = DB::table('wms_bay_areas')->where('id',$act->after)->first()->area_name;
                            }else if($act->field == "Status"){
                                if($act->after == 1){
                                    $value = "PENDING";
                                }else if($act->after == 2){
                                    $value = "ONGOING";
                                }else{
                                    $value = "DONE";
                                }
                        // Unit - PARTS INFORMATION
                            }else if($act->field == "PIReason"){
                                if($act->after == 1){
                                    $value = "(B) - Back Order";
                                }else if($act->after == 2){
                                    $value = "(M) - Machining";
                                }else{
                                    $value = "(R) - Received";
                                }
                        // Unit - DOWNTIME
                            }else if($act->field == "DTReason"){
                                if($act->after == 1){
                                    $value = "Lack of Space";
                                }else if($act->after == 2){
                                    $value = "Lack of Technician";
                                }else if($act->after == 3){
                                    $value = "No Work";
                                }else if($act->after == 4){
                                    $value = "Waiting for Machining";
                                }else if($act->after == 5){
                                    $value = "Waiting for Parts";
                                }else{
                                    $value = "Waiting for PO";
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
                            // USER
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
                            // Unit - PULLOUT
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
                                }else if($act->field == "POUUnitType2"){
                                        if($act->before == 1){
                                            $valueB = "TOYOTA IC JAPAN";
                                        }else if($act->before == 2){
                                            $valueB = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->before == 3){
                                            $valueB = "TOYOTA IC CHINA";
                                        }else if($act->before == 4){
                                            $valueB = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->before == 5){
                                            $valueB = "TOYOTA REACH TRUCK";
                                        }else if($act->before == 6){
                                            $valueB = "BT REACH TRUCK";
                                        }else if($act->before == 7){
                                            $valueB = "BT STACKER";
                                        }else if($act->before == 8){
                                            $valueB = "RAYMOND REACH TRUCK";
                                        }else if($act->before == 9){
                                            $valueB = "RAYMOND STACKER";
                                        }else if($act->before == 10){
                                            $valueB = "STACKER TAILIFT";
                                        }else if($act->before == 11){
                                            $valueB = "PPT";
                                        }else if($act->before == 12){
                                            $valueB = "OPH";
                                        }else{
                                            $valueB = "HPT";
                                        }
                                }else if($act->field == "POUClassification"){
                                    if($act->before == 1){
                                        $valueB = "CLASS A";
                                    }else if($act->before == 2){
                                        $valueB = "CLASS B";
                                    }else if($act->before == 3){
                                        $valueB = "CLASS C";
                                    }else{
                                        $valueB = "CLASS D";
                                    }
                                }else if($act->field == "POUwAttachment"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAttType"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUAttModel"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUAttSerialNum"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUwAccesories"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccISite"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccLiftCam"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccRedLight"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccBlueLight"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccFireExt"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccStLight"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccOthers"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUAccOthersDetail"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUTechnician1"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                    }
                                }else if($act->field == "POUTechnician2"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                    }
                                }else if($act->field == "POUStatus"){
                                    if($act->before == null ||$act->before == 0 ){
                                        $valueB = "-";
                                    }else if($act->before == 1){
                                        $valueB = "WAITING FOR REPAIR UNIT";
                                    }else if($act->before == 2){
                                        $valueB = "UNDER REPAIR UNIT";
                                    }else if($act->before == 3){
                                        $valueB = "USED GOOD UNIT";
                                    }else if($act->before == 4){
                                        $valueB = "SERVICE UNIT";
                                    }else if($act->before == 5){
                                        $valueB = "FOR SCRAP UNIT";
                                    }else if($act->before == 6){
                                        $valueB = "FOR SALE UNIT";
                                    }else if($act->before == 7){
                                        $valueB = "WAITING PARTS";
                                    }else if($act->before == 8){
                                        $valueB = "WAITING BACK ORDER";
                                    }else if($act->before == 9){
                                        $valueB = "WAITING SPARE BATT";
                                    }else if($act->before == 10){
                                        $valueB = "STOCK UNIT";
                                    }else if($act->before == 11){
                                        $valueB = "WAITING FOR MCI";
                                    }else if($act->before == 12){
                                        $valueB = "WAITING FOR PDI";
                                    }else{
                                        $valueB = "DONE PDI (WFD)";
                                    }
                                }else if($act->field == "POUTransferArea"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('sections')->where('id',$act->before)->first()->name;
                                    }
                                }else if($act->field == "POUTransferBay"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                    }
                                }else if($act->field == "POUTransferDate"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUTransferRemarks"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUwSpareBat1"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUSB1Brand"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1BatType"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1SerialNum"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1Code"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1Amper"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1Volt"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1CCable"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB1CTable"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUwSpareBat2"){
                                    if($act->before == 0 || $act->before == null ){
                                        $valueB = "No";
                                    }else{
                                        $valueB = "Yes";
                                    }
                                }else if($act->field == "POUSB2Brand"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2BatType"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2SerialNum"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2Code"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2Amper"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2Volt"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2CCable"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                                }else if($act->field == "POUSB2CTable"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = $act->before;
                                    }
                            // Unit - CONFIRM
                                }else if($act->field == "CUTransferStatus"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        if($act->before == null ||$act->before == 0 ){
                                            $valueB = "-";
                                        }else if($act->before == 1){
                                            $valueB = "WAITING FOR REPAIR UNIT";
                                        }else if($act->before == 2){
                                            $valueB = "UNDER REPAIR UNIT";
                                        }else if($act->before == 3){
                                            $valueB = "USED GOOD UNIT";
                                        }else if($act->before == 4){
                                            $valueB = "SERVICE UNIT";
                                        }else if($act->before == 5){
                                            $valueB = "FOR SCRAP UNIT";
                                        }else if($act->before == 6){
                                            $valueB = "FOR SALE UNIT";
                                        }else if($act->before == 7){
                                            $valueB = "WAITING PARTS";
                                        }else if($act->before == 8){
                                            $valueB = "WAITING BACK ORDER";
                                        }else if($act->before == 9){
                                            $valueB = "WAITING SPARE BATT";
                                        }else if($act->before == 10){
                                            $valueB = "STOCK UNIT";
                                        }else if($act->before == 11){
                                            $valueB = "WAITING FOR MCI";
                                        }else if($act->before == 12){
                                            $valueB = "WAITING FOR PDI";
                                        }else{
                                            $valueB = "DONE PDI (WFD)";
                                        }
                                    }
                                }else if($act->field == "CUTransferArea"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('sections')->where('id',$act->before)->first()->name;
                                    }
                                }else if($act->field == "CUTransferBay"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                    }
                                }else if($act->field == "CUDelTransfer"){
                                    if($act->before == 1 ){
                                        $valueB = "YES";
                                    }else{
                                        $valueB = "NO";
                                    }
                            // Unit - WORKSHOP
                                }else if($act->field == "WSBayNum"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                    }
                                }else if($act->field == "WSToA"){
                                    if($act->before == 1){
                                        $valueB = "WAREHOUSE";
                                    }else if($act->before == 2){
                                        $valueB = "WORKSHOP";
                                    }else{
                                        $valueB = "PDI";
                                    }
                                }else if($act->field == "WSStatus"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        if($act->before == null ||$act->before == 0 ){
                                            $valueB = "-";
                                        }else if($act->before == 1){
                                            $valueB = "WAITING FOR REPAIR UNIT";
                                        }else if($act->before == 2){
                                            $valueB = "UNDER REPAIR UNIT";
                                        }else if($act->before == 3){
                                            $valueB = "USED GOOD UNIT";
                                        }else if($act->before == 4){
                                            $valueB = "SERVICE UNIT";
                                        }else if($act->before == 5){
                                            $valueB = "FOR SCRAP UNIT";
                                        }else if($act->before == 6){
                                            $valueB = "FOR SALE UNIT";
                                        }else if($act->before == 7){
                                            $valueB = "WAITING PARTS";
                                        }else if($act->before == 8){
                                            $valueB = "WAITING BACK ORDER";
                                        }else if($act->before == 9){
                                            $valueB = "WAITING SPARE BATT";
                                        }else if($act->before == 10){
                                            $valueB = "STOCK UNIT";
                                        }else if($act->before == 11){
                                            $valueB = "WAITING FOR MCI";
                                        }else if($act->before == 12){
                                            $valueB = "WAITING FOR PDI";
                                        }else{
                                            $valueB = "DONE PDI (WFD)";
                                        }
                                    }
                                }else if($act->field == "WSUnitType"){
                                    if($act->before == null ){
                                        $valueB = "-";
                                    }else{
                                        if($act->before == null ||$act->before == 0 ){
                                            $valueB = "-";
                                        }else if($act->before == 1){
                                            $valueB = "TOYOTA IC JAPAN";
                                        }else if($act->before == 2){
                                            $valueB = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->before == 3){
                                            $valueB = "TOYOTA IC CHINA";
                                        }else if($act->before == 4){
                                            $valueB = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->before == 5){
                                            $valueB = "TOYOTA REACH TRUCK";
                                        }else if($act->before == 6){
                                            $valueB = "BT REACH TRUCK";
                                        }else if($act->before == 7){
                                            $valueB = "BT STACKER";
                                        }else if($act->before == 8){
                                            $valueB = "RAYMOND REACH TRUCK";
                                        }else if($act->before == 9){
                                            $valueB = "RAYMOND STACKER";
                                        }else if($act->before == 10){
                                            $valueB = "STACKER TAILIFT";
                                        }else if($act->before == 11){
                                            $valueB = "PPT";
                                        }else if($act->before == 12){
                                            $valueB = "OPC";
                                        }else{
                                            $valueB = "HPT";
                                        }
                                    }
                                }else if($act->field == "WSDelTransfer"){
                                    if($act->before == 1 ){
                                        $valueB = "YES";
                                    }else{
                                        $valueB = "NO";
                                    }
                            // TECHNICIAN SCHEDULE
                                }else if($act->field == "Techid"){
                                    $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                }else if($act->field == "Baynum"){
                                    $valueB = DB::table('wms_bay_areas')->where('id',$act->before)->first()->area_name;
                                }else if($act->field == "Status"){
                                    if($act->before == 1){
                                        $valueB = "PENDING";
                                    }else if($act->before == 2){
                                        $valueB = "ONGOING";
                                    }else{
                                        $valueB = "DONE";
                                    }
                            // Unit - PARTS INFORMATION
                                }else if($act->field == "PIReason"){
                                    if($act->before == 1){
                                        $valueB = "(B) - Back Order";
                                    }else if($act->before == 2){
                                        $valueB = "(M) - Machining";
                                    }else{
                                        $valueB = "(R) - Received";
                                    }
                            // Unit - DOWNTIME
                                }else if($act->field == "DTReason"){
                                    if($act->before == 1){
                                        $valueB = "Lack of Space";
                                    }else if($act->before == 2){
                                        $valueB = "Lack of Technician";
                                    }else if($act->before == 3){
                                        $valueB = "No Work";
                                    }else if($act->before == 4){
                                        $valueB = "Waiting for Machining";
                                    }else if($act->before == 5){
                                        $valueB = "Waiting for Parts";
                                    }else{
                                        $valueB = "Waiting for PO";
                                    }
                                }else{
                                    $valueB = $act->before;
                                }

                        // AFTER
                            // USER
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
                            // Unit - PULLOUT
                                }else if($act->field == "POUUnitType"){
                                        if($act->after == 1){
                                            $valueA = "DIESEL/GASOLINE/LPG";
                                        }else{
                                            $valueA = "BATTERY";
                                        }
                                }else if($act->field == "POUBrand"){
                                    $valueA = DB::table('brands')->where('id',$act->after)->first()->name;
                                }else if($act->field == "POUUnitType2"){
                                        if($act->after == 1){
                                            $valueA = "TOYOTA IC JAPAN";
                                        }else if($act->after == 2){
                                            $valueA = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->after == 3){
                                            $valueA = "TOYOTA IC CHINA";
                                        }else if($act->after == 4){
                                            $valueA = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->after == 5){
                                            $valueA = "TOYOTA REACH TRUCK";
                                        }else if($act->after == 6){
                                            $valueA = "BT REACH TRUCK";
                                        }else if($act->after == 7){
                                            $valueA = "BT STACKER";
                                        }else if($act->after == 8){
                                            $valueA = "RAYMOND REACH TRUCK";
                                        }else if($act->after == 9){
                                            $valueA = "RAYMOND STACKER";
                                        }else if($act->after == 10){
                                            $valueA = "STACKER TAILIFT";
                                        }else if($act->after == 11){
                                            $valueA = "PPT";
                                        }else if($act->after == 12){
                                            $valueA = "OPH";
                                        }else{
                                            $valueA = "HPT";
                                        }
                                }else if($act->field == "POUClassification"){
                                    if($act->after == 1){
                                        $valueA = "CLASS A";
                                    }else if($act->after == 2){
                                        $valueA = "CLASS B";
                                    }else if($act->after == 3){
                                        $valueA = "CLASS C";
                                    }else{
                                        $valueA = "CLASS D";
                                    }
                                }else if($act->field == "POUwAttachment"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAttType"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUAttModel"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUAttSerialNum"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUwAccesories"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccISite"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccLiftCam"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccRedLight"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccBlueLight"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccFireExt"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccStLight"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccOthers"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUAccOthersDetail"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUTechnician1"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                    }
                                }else if($act->field == "POUTechnician2"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                    }
                                }else if($act->field == "POUStatus"){
                                    if($act->after == null ||$act->after == 0 ){
                                        $valueA = "-";
                                    }else if($act->after == 1){
                                        $valueA = "WAITING FOR REPAIR UNIT";
                                    }else if($act->after == 2){
                                        $valueA = "UNDER REPAIR UNIT";
                                    }else if($act->after == 3){
                                        $valueA = "USED GOOD UNIT";
                                    }else if($act->after == 4){
                                        $valueA = "SERVICE UNIT";
                                    }else if($act->after == 5){
                                        $valueA = "FOR SCRAP UNIT";
                                    }else if($act->after == 6){
                                        $valueA = "FOR SALE UNIT";
                                    }else if($act->after == 7){
                                        $valueA = "WAITING PARTS";
                                    }else if($act->after == 8){
                                        $valueA = "WAITING BACK ORDER";
                                    }else if($act->after == 9){
                                        $valueA = "WAITING SPARE BATT";
                                    }else if($act->after == 10){
                                        $valueA = "STOCK UNIT";
                                    }else if($act->after == 11){
                                        $valueA = "WAITING FOR MCI";
                                    }else if($act->after == 12){
                                        $valueA = "WAITING FOR PDI";
                                    }else{
                                        $valueA = "DONE PDI (WFD)";
                                    }
                                }else if($act->field == "POUTransferArea"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('sections')->where('id',$act->after)->first()->name;
                                    }
                                }else if($act->field == "POUTransferBay"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "POUTransferDate"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUTransferRemarks"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUwSpareBat1"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUSB1Brand"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1BatType"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1SerialNum"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1Code"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1Amper"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1Volt"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1CCable"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB1CTable"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUwSpareBat2"){
                                    if($act->after == 0 || $act->after == null ){
                                        $valueA = "No";
                                    }else{
                                        $valueA = "Yes";
                                    }
                                }else if($act->field == "POUSB2Brand"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2BatType"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2SerialNum"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2Code"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2Amper"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2Volt"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2CCable"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                                }else if($act->field == "POUSB2CTable"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = $act->after;
                                    }
                            // Unit - CONFIRM
                                }else if($act->field == "CUTransferStatus"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $valueA = "-";
                                        }else if($act->after == 1){
                                            $valueA = "WAITING FOR REPAIR UNIT";
                                        }else if($act->after == 2){
                                            $valueA = "UNDER REPAIR UNIT";
                                        }else if($act->after == 3){
                                            $valueA = "USED GOOD UNIT";
                                        }else if($act->after == 4){
                                            $valueA = "SERVICE UNIT";
                                        }else if($act->after == 5){
                                            $valueA = "FOR SCRAP UNIT";
                                        }else if($act->after == 6){
                                            $valueA = "FOR SALE UNIT";
                                        }else if($act->after == 7){
                                            $valueA = "WAITING PARTS";
                                        }else if($act->after == 8){
                                            $valueA = "WAITING BACK ORDER";
                                        }else if($act->after == 9){
                                            $valueA = "WAITING SPARE BATT";
                                        }else if($act->after == 10){
                                            $valueA = "STOCK UNIT";
                                        }else if($act->after == 11){
                                            $valueA = "WAITING FOR MCI";
                                        }else if($act->after == 12){
                                            $valueA = "WAITING FOR PDI";
                                        }else{
                                            $valueA = "DONE PDI (WFD)";
                                        }
                                    }
                                }else if($act->field == "CUTransferArea"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('sections')->where('id',$act->after)->first()->name;
                                    }
                                }else if($act->field == "CUTransferBay"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "CUDelTransfer"){
                                    if($act->after == 1 ){
                                        $valueA = "YES";
                                    }else{
                                        $valueA = "NO";
                                    }
                            // Unit - WORKSHOP
                                }else if($act->field == "WSBayNum"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "WSToA"){
                                    if($act->after == 1){
                                        $valueA = "WAREHOUSE";
                                    }else if($act->after == 2){
                                        $valueA = "WORKSHOP";
                                    }else{
                                        $valueA = "PDI";
                                    }
                                }else if($act->field == "WSStatus"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $valueA = "-";
                                        }else if($act->after == 1){
                                            $valueA = "WAITING FOR REPAIR UNIT";
                                        }else if($act->after == 2){
                                            $valueA = "UNDER REPAIR UNIT";
                                        }else if($act->after == 3){
                                            $valueA = "USED GOOD UNIT";
                                        }else if($act->after == 4){
                                            $valueA = "SERVICE UNIT";
                                        }else if($act->after == 5){
                                            $valueA = "FOR SCRAP UNIT";
                                        }else if($act->after == 6){
                                            $valueA = "FOR SALE UNIT";
                                        }else if($act->after == 7){
                                            $valueA = "WAITING PARTS";
                                        }else if($act->after == 8){
                                            $valueA = "WAITING BACK ORDER";
                                        }else if($act->after == 9){
                                            $valueA = "WAITING SPARE BATT";
                                        }else if($act->after == 10){
                                            $valueA = "STOCK UNIT";
                                        }else if($act->after == 11){
                                            $valueA = "WAITING FOR MCI";
                                        }else if($act->after == 12){
                                            $valueA = "WAITING FOR PDI";
                                        }else{
                                            $valueA = "DONE PDI (WFD)";
                                        }
                                    }
                                }else if($act->field == "WSUnitType"){
                                    if($act->after == null ){
                                        $valueA = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $valueA = "-";
                                        }else if($act->after == 1){
                                            $valueA = "TOYOTA IC JAPAN";
                                        }else if($act->after == 2){
                                            $valueA = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->after == 3){
                                            $valueA = "TOYOTA IC CHINA";
                                        }else if($act->after == 4){
                                            $valueA = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->after == 5){
                                            $valueA = "TOYOTA REACH TRUCK";
                                        }else if($act->after == 6){
                                            $valueA = "BT REACH TRUCK";
                                        }else if($act->after == 7){
                                            $valueA = "BT STACKER";
                                        }else if($act->after == 8){
                                            $valueA = "RAYMOND REACH TRUCK";
                                        }else if($act->after == 9){
                                            $valueA = "RAYMOND STACKER";
                                        }else if($act->after == 10){
                                            $valueA = "STACKER TAILIFT";
                                        }else if($act->after == 11){
                                            $valueA = "PPT";
                                        }else if($act->after == 12){
                                            $valueA = "OPC";
                                        }else{
                                            $valueA = "HPT";
                                        }
                                    }
                                }else if($act->field == "WSDelTransfer"){
                                    if($act->after == 1 ){
                                        $valueA = "YES";
                                    }else{
                                        $valueA = "NO";
                                    }
                            // TECHNICIAN SCHEDULE
                                }else if($act->field == "Techid"){
                                    $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                }else if($act->field == "Baynum"){
                                    $valueA = DB::table('wms_bay_areas')->where('id',$act->after)->first()->area_name;
                                }else if($act->field == "Status"){
                                    if($act->after == 1){
                                        $valueA = "PENDING";
                                    }else if($act->after == 2){
                                        $valueA = "ONGOING";
                                    }else{
                                        $valueA = "DONE";
                                    }
                            // Unit - PARTS INFORMATION
                                }else if($act->field == "PIReason"){
                                    if($act->after == 1){
                                        $valueA = "(B) - Back Order";
                                    }else if($act->after == 2){
                                        $valueA = "(M) - Machining";
                                    }else{
                                        $valueA = "(R) - Received";
                                    }
                            // Unit - DOWNTIME
                                }else if($act->field == "DTReason"){
                                    if($act->after == 1){
                                        $valueA = "Lack of Space";
                                    }else if($act->after == 2){
                                        $valueA = "Lack of Technician";
                                    }else if($act->after == 3){
                                        $valueA = "No Work";
                                    }else if($act->after == 4){
                                        $valueA = "Waiting for Machining";
                                    }else if($act->after == 5){
                                        $valueA = "Waiting for Parts";
                                    }else{
                                        $valueA = "Waiting for PO";
                                    }
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
            // 
                $action = $act->action;
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
                                        <div class="text-sm font-normal text-gray-500 lex">'.$act->name.' <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">'.$cdate.'</time></div>
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
                    // Unit - PULLOUT
                        if($act->field == 'IsBrandNew'){
                            $label = "Brand New Unit";
                        }else if($act->field == 'POUUnitType'){
                            $label = "Unit";
                        }else if($act->field == 'POUArrivalDate'){
                            $label = "Arrival Date";
                        }else if($act->field == 'POUBrand'){
                            $label = "Brand";
                        }else if($act->field == 'POUUnitType2'){
                            $label = "Unit Type";
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
                            $label = "Pullout Unit ID";
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
                    // CONFIRM
                        }else if($act->field == 'CUTransferDate'){
                            $label = "Confirm - Transfer Date";
                        }else if($act->field == 'CUTransferRemarks'){
                            $label = "Confirm - Transfer Remarks";
                        }else if($act->field == 'CUTransferStatus'){
                            $label = "Confirm - Transfer Status";
                        }else if($act->field == 'CUTransferArea'){
                            $label = "Confirm - Transfer Area";
                        }else if($act->field == 'CUTransferBay'){
                            $label = "Confirm - Transfer Bay";
                        }else if($act->field == 'CUDelTransfer'){
                            $label = "Confirm - Delivered?";
                    // WORKSHOP
                        }else if($act->field == 'WSPOUID'){
                            $label = "Pullout - Unit ID";
                        }else if($act->field == 'WSBayNum'){ 
                            $label = "Workshop - Bay Number";
                        }else if($act->field == 'WSToA'){
                            $label = "Workshop - Type of Activity";
                        }else if($act->field == 'WSStatus'){
                            $label = "Workshop - Status";
                        }else if($act->field == 'WSUnitType'){
                            $label = "Workshop - Unit Type";
                        }else if($act->field == 'WSVerifiedBy'){
                            $label = "Workshop - Verified By";
                        }else if($act->field == 'WSUnitCondition'){
                            $label = "Workshop - Unit Condition";
                        }else if($act->field == 'WSATIDS'){
                            $label = "Workshop - Target Inspection Date Start";
                        }else if($act->field == 'WSATIDE'){
                            $label = "Workshop - Target Inspection Date End";
                        }else if($act->field == 'WSATRDS'){
                            $label = "Workshop - Target Repair Date Start";
                        }else if($act->field == 'WSATRDE'){
                            $label = "Workshop - Target Repair Date End";
                        }else if($act->field == 'WSAAIDS'){
                            $label = "Workshop - Actual Inspection Date Start";
                        }else if($act->field == 'WSAAIDE'){
                            $label = "Workshop - Actual Inspection Date End";
                        }else if($act->field == 'WSAARDS'){
                            $label = "Workshop - Actual Repair Date Start";
                        }else if($act->field == 'WSAARDE'){
                            $label = "Workshop - Actual Repair Date End";
                        }else if($act->field == 'WSRemarks'){
                            $label = "Workshop - Remarks";
                        }else if($act->field == 'WSDelTransfer'){
                            $label = "Workshop - Delivered?";
                    // TECHNICIAN SCHEDULE
                        }else if($act->field == 'Techid'){
                            $label = "Tech. Schedule - Technician";
                        }else if($act->field == 'Baynum'){
                            $label = "Tech. Schedule - Bay Area";
                        }else if($act->field == 'JONumber'){
                            $label = "Tech. Schedule - J.O. Number";
                        }else if($act->field == 'Scheddate'){
                            $label = "Tech. Schedule - Sched. Date";
                        }else if($act->field == 'Scopeofwork'){
                            $label = "Tech. Schedule - Scope of Work";
                        }else if($act->field == 'Activity'){
                            $label = "Tech. Schedule - Activity";
                    // Unit - PARTS
                        }else if($act->field == 'PIJONum'){
                            $label = "Unit Parts Info - JO Number";
                        }else if($act->field == 'PIMRINum'){
                            $label = "Unit Parts Info - MRI Number";
                        }else if($act->field == 'PIPartID'){
                            $label = "Unit Parts Info - Part ID";
                        }else if($act->field == 'PIPartNum'){
                            $label = "Unit Parts Info - Part Number";
                        }else if($act->field == 'PIDescription'){
                            $label = "Unit Parts Info - Description";
                        }else if($act->field == 'PIQuantity'){
                            $label = "Unit Parts Info - Quantity";
                        }else if($act->field == 'PIPrice'){
                            $label = "Unit Parts Info - Price";
                        }else if($act->field == 'PIDateReq'){
                            $label = "Unit Parts Info - Date Requested";
                        }else if($act->field == 'PIDateRec'){
                            $label = "Unit Parts Info - Date Received";
                        }else if($act->field == 'PIReason'){
                            $label = "Unit Parts Info - Reason";
                        }else if($act->field == 'PIDateInstalled'){
                            $label = "Unit Parts Info - Date Installed";
                        }else if($act->field == 'PIRemarks'){
                            $label = "Unit Parts Info - Remarks";
                    // Units - DOWNTIME
                        }else if($act->field == 'DTJONum'){
                            $label = "Downtime - JO Number";
                        }else if($act->field == 'DTSDate'){
                            $label = "Downtime - Start Date";
                        }else if($act->field == 'DTEDate'){
                            $label = "Downtime - End Date";
                        }else if($act->field == 'DTReason'){
                            $label = "Downtime - Reason";
                        }else if($act->field == 'DTRemarks'){
                            $label = "Downtime - Remarks";
                        }else if($act->field == 'DTTDays'){
                            $label = "Downtime - Total Days";
                        }else{
                            $label = $act->field;
                        }
    
                // ADD
                    if($act->action == "ADD"){
                        $additionalClass = "bg-blue-300";

                        if($act->field != 'Is Deleted'){
                            // USER
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
                            // Unit - PULLOUT
                                }else if($act->field == "POUUnitType"){
                                    if($act->after == 1){
                                        $value = "DIESEL/GASOLINE/LPG";
                                    }else{
                                        $value = "BATTERY";
                                    }
                                }else if($act->field == "POUBrand"){
                                    $value = DB::table('brands')->where('id',$act->after)->first()->name;
                                }else if($act->field == "POUUnitType2"){
                                    if($act->after == 1){
                                        $value = "TOYOTA IC JAPAN";
                                    }else if($act->after == 2){
                                        $value = "TOYOTA ELECTRIC JAPAN";
                                    }else if($act->after == 3){
                                        $value = "TOYOTA IC CHINA";
                                    }else if($act->after == 4){
                                        $value = "TOYOTA ELECTRIC CHINA";
                                    }else if($act->after == 5){
                                        $value = "TOYOTA REACH TRUCK";
                                    }else if($act->after == 6){
                                        $value = "BT REACH TRUCK";
                                    }else if($act->after == 7){
                                        $value = "BT STACKER";
                                    }else if($act->after == 8){
                                        $value = "RAYMOND REACH TRUCK";
                                    }else if($act->after == 9){
                                        $value = "RAYMOND STACKER";
                                    }else if($act->after == 10){
                                        $value = "STACKER TAILIFT";
                                    }else if($act->after == 11){
                                        $value = "PPT";
                                    }else if($act->after == 12){
                                        $value = "OPH";
                                    }else{
                                        $value = "HPT";
                                    }
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
                                    if($act->after == null ||$act->after == 0 ){
                                        $value = "-";
                                    }else if($act->after == 1){
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
                            // Unit - CONFIRM
                                }else if($act->field == "CUTransferStatus"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $value = "-";
                                        }else if($act->after == 1){
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
                                    }
                                }else if($act->field == "CUTransferArea"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        $value = DB::table('sections')->where('id',$act->after)->first()->name;
                                    }
                                }else if($act->field == "CUTransferBay"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "CUDelTransfer"){
                                    if($act->after == 1 ){
                                        $value = "YES";
                                    }else{
                                        $value = "NO";
                                    }
                            // Unit - WORKSHOP
                                }else if($act->field == "WSBayNum"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "WSToA"){
                                    if($act->after == 1){
                                        $value = "WAREHOUSE";
                                    }else if($act->after == 2){
                                        $value = "WORKSHOP";
                                    }else{
                                        $value = "PDI";
                                    }
                                }else if($act->field == "WSStatus"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $value = "-";
                                        }else if($act->after == 1){
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
                                    }
                                }else if($act->field == "WSUnitType"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $value = "-";
                                        }else if($act->after == 1){
                                            $value = "TOYOTA IC JAPAN";
                                        }else if($act->after == 2){
                                            $value = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->after == 3){
                                            $value = "TOYOTA IC CHINA";
                                        }else if($act->after == 4){
                                            $value = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->after == 5){
                                            $value = "TOYOTA REACH TRUCK";
                                        }else if($act->after == 6){
                                            $value = "BT REACH TRUCK";
                                        }else if($act->after == 7){
                                            $value = "BT STACKER";
                                        }else if($act->after == 8){
                                            $value = "RAYMOND REACH TRUCK";
                                        }else if($act->after == 9){
                                            $value = "RAYMOND STACKER";
                                        }else if($act->after == 10){
                                            $value = "STACKER TAILIFT";
                                        }else if($act->after == 11){
                                            $value = "PPT";
                                        }else if($act->after == 12){
                                            $value = "OPC";
                                        }else{
                                            $value = "HPT";
                                        }
                                    }
                                }else if($act->field == "WSDelTransfer"){
                                    if($act->after == 1 ){
                                        $value = "YES";
                                    }else{
                                        $value = "NO";
                                    }
                            // TECHNICIAN SCHEDULE
                                }else if($act->field == "Techid"){
                                    $value = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                }else if($act->field == "Baynum"){
                                    $value = DB::table('wms_bay_areas')->where('id',$act->after)->first()->area_name;
                                }else if($act->field == "Status"){
                                    if($act->after == 1){
                                        $value = "PENDING";
                                    }else if($act->after == 2){
                                        $value = "ONGOING";
                                    }else{
                                        $value = "DONE";
                                    }
                            // Unit - PARTS INFORMATION
                                }else if($act->field == "PIReason"){
                                    if($act->after == 1){
                                        $value = "(B) - Back Order";
                                    }else if($act->after == 2){
                                        $value = "(M) - Machining";
                                    }else{
                                        $value = "(R) - Received";
                                    }
                            // Unit - DOWNTIME
                                }else if($act->field == "DTReason"){
                                    if($act->after == 1){
                                        $value = "Lack of Space";
                                    }else if($act->after == 2){
                                        $value = "Lack of Technician";
                                    }else if($act->after == 3){
                                        $value = "No Work";
                                    }else if($act->after == 4){
                                        $value = "Waiting for Machining";
                                    }else if($act->after == 5){
                                        $value = "Waiting for Parts";
                                    }else{
                                        $value = "Waiting for PO";
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
                // EDIT
                    }else if($act->action == "UPDATE"){
                        $additionalClass = "bg-green-300";
                        if($act->field != 'Is Deleted'){
                            // BEFORE
                                // USER
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
                                // Unit - PULLOUT
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
                                    }else if($act->field == "POUUnitType2"){
                                        if($act->before == 1){
                                            $valueB = "TOYOTA IC JAPAN";
                                        }else if($act->before == 2){
                                            $valueB = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->before == 3){
                                            $valueB = "TOYOTA IC CHINA";
                                        }else if($act->before == 4){
                                            $valueB = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->before == 5){
                                            $valueB = "TOYOTA REACH TRUCK";
                                        }else if($act->before == 6){
                                            $valueB = "BT REACH TRUCK";
                                        }else if($act->before == 7){
                                            $valueB = "BT STACKER";
                                        }else if($act->before == 8){
                                            $valueB = "RAYMOND REACH TRUCK";
                                        }else if($act->before == 9){
                                            $valueB = "RAYMOND STACKER";
                                        }else if($act->before == 10){
                                            $valueB = "STACKER TAILIFT";
                                        }else if($act->before == 11){
                                            $valueB = "PPT";
                                        }else if($act->before == 12){
                                            $valueB = "OPH";
                                        }else{
                                            $valueB = "HPT";
                                        }
                                    }else if($act->field == "POUClassification"){
                                        if($act->before == 1){
                                            $valueB = "CLASS A";
                                        }else if($act->before == 2){
                                            $valueB = "CLASS B";
                                        }else if($act->before == 3){
                                            $valueB = "CLASS C";
                                        }else{
                                            $valueB = "CLASS D";
                                        }
                                    }else if($act->field == "POUwAttachment"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAttType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUAttModel"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUAttSerialNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUwAccesories"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccISite"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccLiftCam"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccRedLight"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccBlueLight"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccFireExt"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccStLight"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthers"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthersDetail"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUTechnician1"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "POUTechnician2"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "POUStatus"){
                                        if($act->before == null ||$act->before == 0 ){
                                            $valueB = "-";
                                        }else if($act->before == 1){
                                            $valueB = "WAITING FOR REPAIR UNIT";
                                        }else if($act->before == 2){
                                            $valueB = "UNDER REPAIR UNIT";
                                        }else if($act->before == 3){
                                            $valueB = "USED GOOD UNIT";
                                        }else if($act->before == 4){
                                            $valueB = "SERVICE UNIT";
                                        }else if($act->before == 5){
                                            $valueB = "FOR SCRAP UNIT";
                                        }else if($act->before == 6){
                                            $valueB = "FOR SALE UNIT";
                                        }else if($act->before == 7){
                                            $valueB = "WAITING PARTS";
                                        }else if($act->before == 8){
                                            $valueB = "WAITING BACK ORDER";
                                        }else if($act->before == 9){
                                            $valueB = "WAITING SPARE BATT";
                                        }else if($act->before == 10){
                                            $valueB = "STOCK UNIT";
                                        }else if($act->before == 11){
                                            $valueB = "WAITING FOR MCI";
                                        }else if($act->before == 12){
                                            $valueB = "WAITING FOR PDI";
                                        }else{
                                            $valueB = "DONE PDI (WFD)";
                                        }
                                    }else if($act->field == "POUTransferArea"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('sections')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "POUTransferBay"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                        }
                                    }else if($act->field == "POUTransferDate"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUTransferRemarks"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUwSpareBat1"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUSB1Brand"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1BatType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1SerialNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1Code"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1Amper"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1Volt"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1CCable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1CTable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUwSpareBat2"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUSB2Brand"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2BatType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2SerialNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2Code"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2Amper"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2Volt"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2CCable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2CTable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                // Unit - CONFIRM
                                    }else if($act->field == "CUTransferStatus"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            if($act->before == null ||$act->before == 0 ){
                                                $valueB = "-";
                                            }else if($act->before == 1){
                                                $valueB = "WAITING FOR REPAIR UNIT";
                                            }else if($act->before == 2){
                                                $valueB = "UNDER REPAIR UNIT";
                                            }else if($act->before == 3){
                                                $valueB = "USED GOOD UNIT";
                                            }else if($act->before == 4){
                                                $valueB = "SERVICE UNIT";
                                            }else if($act->before == 5){
                                                $valueB = "FOR SCRAP UNIT";
                                            }else if($act->before == 6){
                                                $valueB = "FOR SALE UNIT";
                                            }else if($act->before == 7){
                                                $valueB = "WAITING PARTS";
                                            }else if($act->before == 8){
                                                $valueB = "WAITING BACK ORDER";
                                            }else if($act->before == 9){
                                                $valueB = "WAITING SPARE BATT";
                                            }else if($act->before == 10){
                                                $valueB = "STOCK UNIT";
                                            }else if($act->before == 11){
                                                $valueB = "WAITING FOR MCI";
                                            }else if($act->before == 12){
                                                $valueB = "WAITING FOR PDI";
                                            }else{
                                                $valueB = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "CUTransferArea"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('sections')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "CUTransferBay"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                        }
                                    }else if($act->field == "CUDelTransfer"){
                                        if($act->before == 1 ){
                                            $valueB = "YES";
                                        }else{
                                            $valueB = "NO";
                                        }
                                // Unit - WORKSHOP
                                    }else if($act->field == "WSBayNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                        }
                                    }else if($act->field == "WSToA"){
                                        if($act->before == 1){
                                            $valueB = "WAREHOUSE";
                                        }else if($act->before == 2){
                                            $valueB = "WORKSHOP";
                                        }else{
                                            $valueB = "PDI";
                                        }
                                    }else if($act->field == "WSStatus"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            if($act->before == null ||$act->before == 0 ){
                                                $valueB = "-";
                                            }else if($act->before == 1){
                                                $valueB = "WAITING FOR REPAIR UNIT";
                                            }else if($act->before == 2){
                                                $valueB = "UNDER REPAIR UNIT";
                                            }else if($act->before == 3){
                                                $valueB = "USED GOOD UNIT";
                                            }else if($act->before == 4){
                                                $valueB = "SERVICE UNIT";
                                            }else if($act->before == 5){
                                                $valueB = "FOR SCRAP UNIT";
                                            }else if($act->before == 6){
                                                $valueB = "FOR SALE UNIT";
                                            }else if($act->before == 7){
                                                $valueB = "WAITING PARTS";
                                            }else if($act->before == 8){
                                                $valueB = "WAITING BACK ORDER";
                                            }else if($act->before == 9){
                                                $valueB = "WAITING SPARE BATT";
                                            }else if($act->before == 10){
                                                $valueB = "STOCK UNIT";
                                            }else if($act->before == 11){
                                                $valueB = "WAITING FOR MCI";
                                            }else if($act->before == 12){
                                                $valueB = "WAITING FOR PDI";
                                            }else{
                                                $valueB = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "WSUnitType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            if($act->before == null ||$act->before == 0 ){
                                                $valueB = "-";
                                            }else if($act->before == 1){
                                                $valueB = "TOYOTA IC JAPAN";
                                            }else if($act->before == 2){
                                                $valueB = "TOYOTA ELECTRIC JAPAN";
                                            }else if($act->before == 3){
                                                $valueB = "TOYOTA IC CHINA";
                                            }else if($act->before == 4){
                                                $valueB = "TOYOTA ELECTRIC CHINA";
                                            }else if($act->before == 5){
                                                $valueB = "TOYOTA REACH TRUCK";
                                            }else if($act->before == 6){
                                                $valueB = "BT REACH TRUCK";
                                            }else if($act->before == 7){
                                                $valueB = "BT STACKER";
                                            }else if($act->before == 8){
                                                $valueB = "RAYMOND REACH TRUCK";
                                            }else if($act->before == 9){
                                                $valueB = "RAYMOND STACKER";
                                            }else if($act->before == 10){
                                                $valueB = "STACKER TAILIFT";
                                            }else if($act->before == 11){
                                                $valueB = "PPT";
                                            }else if($act->before == 12){
                                                $valueB = "OPC";
                                            }else{
                                                $valueB = "HPT";
                                            }
                                        }
                                    }else if($act->field == "WSDelTransfer"){
                                        if($act->before == 1 ){
                                            $valueB = "YES";
                                        }else{
                                            $valueB = "NO";
                                        }
                                // TECHNICIAN SCHEDULE
                                    }else if($act->field == "Techid"){
                                        $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                    }else if($act->field == "Baynum"){
                                        $valueB = DB::table('wms_bay_areas')->where('id',$act->before)->first()->area_name;
                                    }else if($act->field == "Status"){
                                        if($act->before == 1){
                                            $valueB = "PENDING";
                                        }else if($act->before == 2){
                                            $valueB = "ONGOING";
                                        }else{
                                            $valueB = "DONE";
                                        }
                                // Unit - PARTS INFORMATION
                                    }else if($act->field == "PIReason"){
                                        if($act->before == 1){
                                            $valueB = "(B) - Back Order";
                                        }else if($act->before == 2){
                                            $valueB = "(M) - Machining";
                                        }else{
                                            $valueB = "(R) - Received";
                                        }
                                // Unit - DOWNTIME
                                    }else if($act->field == "DTReason"){
                                        if($act->before == 1){
                                            $valueB = "Lack of Space";
                                        }else if($act->before == 2){
                                            $valueB = "Lack of Technician";
                                        }else if($act->before == 3){
                                            $valueB = "No Work";
                                        }else if($act->before == 4){
                                            $valueB = "Waiting for Machining";
                                        }else if($act->before == 5){
                                            $valueB = "Waiting for Parts";
                                        }else{
                                            $valueB = "Waiting for PO";
                                        }
                                    }else{
                                        $valueB = $act->before;
                                    }
    
                            // AFTER
                                // USER
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
                                // Unit - PULLOUT
                                    }else if($act->field == "POUUnitType"){
                                        if($act->after == 1){
                                            $valueA = "DIESEL/GASOLINE/LPG";
                                        }else{
                                            $valueA = "BATTERY";
                                        }
                                    }else if($act->field == "POUBrand"){
                                        $valueA = DB::table('brands')->where('id',$act->after)->first()->name;
                                    }else if($act->field == "POUUnitType2"){
                                        if($act->after == 1){
                                            $valueA = "TOYOTA IC JAPAN";
                                        }else if($act->after == 2){
                                            $valueA = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->after == 3){
                                            $valueA = "TOYOTA IC CHINA";
                                        }else if($act->after == 4){
                                            $valueA = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->after == 5){
                                            $valueA = "TOYOTA REACH TRUCK";
                                        }else if($act->after == 6){
                                            $valueA = "BT REACH TRUCK";
                                        }else if($act->after == 7){
                                            $valueA = "BT STACKER";
                                        }else if($act->after == 8){
                                            $valueA = "RAYMOND REACH TRUCK";
                                        }else if($act->after == 9){
                                            $valueA = "RAYMOND STACKER";
                                        }else if($act->after == 10){
                                            $valueA = "STACKER TAILIFT";
                                        }else if($act->after == 11){
                                            $valueA = "PPT";
                                        }else if($act->after == 12){
                                            $valueA = "OPH";
                                        }else{
                                            $valueA = "HPT";
                                        }
                                    }else if($act->field == "POUClassification"){
                                        if($act->after == 1){
                                            $valueA = "CLASS A";
                                        }else if($act->after == 2){
                                            $valueA = "CLASS B";
                                        }else if($act->after == 3){
                                            $valueA = "CLASS C";
                                        }else{
                                            $valueA = "CLASS D";
                                        }
                                    }else if($act->field == "POUwAttachment"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAttType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUAttModel"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUAttSerialNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUwAccesories"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccISite"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccLiftCam"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccRedLight"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccBlueLight"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccFireExt"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccStLight"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthers"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthersDetail"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUTechnician1"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "POUTechnician2"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "POUStatus"){
                                        if($act->after == null ||$act->after == 0 ){
                                            $valueA = "-";
                                        }else if($act->after == 1){
                                            $valueA = "WAITING FOR REPAIR UNIT";
                                        }else if($act->after == 2){
                                            $valueA = "UNDER REPAIR UNIT";
                                        }else if($act->after == 3){
                                            $valueA = "USED GOOD UNIT";
                                        }else if($act->after == 4){
                                            $valueA = "SERVICE UNIT";
                                        }else if($act->after == 5){
                                            $valueA = "FOR SCRAP UNIT";
                                        }else if($act->after == 6){
                                            $valueA = "FOR SALE UNIT";
                                        }else if($act->after == 7){
                                            $valueA = "WAITING PARTS";
                                        }else if($act->after == 8){
                                            $valueA = "WAITING BACK ORDER";
                                        }else if($act->after == 9){
                                            $valueA = "WAITING SPARE BATT";
                                        }else if($act->after == 10){
                                            $valueA = "STOCK UNIT";
                                        }else if($act->after == 11){
                                            $valueA = "WAITING FOR MCI";
                                        }else if($act->after == 12){
                                            $valueA = "WAITING FOR PDI";
                                        }else{
                                            $valueA = "DONE PDI (WFD)";
                                        }
                                    }else if($act->field == "POUTransferArea"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('sections')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "POUTransferBay"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                        }
                                    }else if($act->field == "POUTransferDate"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUTransferRemarks"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUwSpareBat1"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUSB1Brand"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1BatType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1SerialNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1Code"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1Amper"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1Volt"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1CCable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1CTable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUwSpareBat2"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUSB2Brand"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2BatType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2SerialNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2Code"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2Amper"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2Volt"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2CCable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2CTable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                // Unit - CONFIRM
                                    }else if($act->field == "CUTransferStatus"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            if($act->after == null ||$act->after == 0 ){
                                                $valueA = "-";
                                            }else if($act->after == 1){
                                                $valueA = "WAITING FOR REPAIR UNIT";
                                            }else if($act->after == 2){
                                                $valueA = "UNDER REPAIR UNIT";
                                            }else if($act->after == 3){
                                                $valueA = "USED GOOD UNIT";
                                            }else if($act->after == 4){
                                                $valueA = "SERVICE UNIT";
                                            }else if($act->after == 5){
                                                $valueA = "FOR SCRAP UNIT";
                                            }else if($act->after == 6){
                                                $valueA = "FOR SALE UNIT";
                                            }else if($act->after == 7){
                                                $valueA = "WAITING PARTS";
                                            }else if($act->after == 8){
                                                $valueA = "WAITING BACK ORDER";
                                            }else if($act->after == 9){
                                                $valueA = "WAITING SPARE BATT";
                                            }else if($act->after == 10){
                                                $valueA = "STOCK UNIT";
                                            }else if($act->after == 11){
                                                $valueA = "WAITING FOR MCI";
                                            }else if($act->after == 12){
                                                $valueA = "WAITING FOR PDI";
                                            }else{
                                                $valueA = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "CUTransferArea"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('sections')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "CUTransferBay"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                        }
                                    }else if($act->field == "CUDelTransfer"){
                                        if($act->after == 1 ){
                                            $valueA = "YES";
                                        }else{
                                            $valueA = "NO";
                                        }
                                // Unit - WORKSHOP
                                    }else if($act->field == "WSBayNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                        }
                                    }else if($act->field == "WSToA"){
                                        if($act->after == 1){
                                            $valueA = "WAREHOUSE";
                                        }else if($act->after == 2){
                                            $valueA = "WORKSHOP";
                                        }else{
                                            $valueA = "PDI";
                                        }
                                    }else if($act->field == "WSStatus"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            if($act->after == null ||$act->after == 0 ){
                                                $valueA = "-";
                                            }else if($act->after == 1){
                                                $valueA = "WAITING FOR REPAIR UNIT";
                                            }else if($act->after == 2){
                                                $valueA = "UNDER REPAIR UNIT";
                                            }else if($act->after == 3){
                                                $valueA = "USED GOOD UNIT";
                                            }else if($act->after == 4){
                                                $valueA = "SERVICE UNIT";
                                            }else if($act->after == 5){
                                                $valueA = "FOR SCRAP UNIT";
                                            }else if($act->after == 6){
                                                $valueA = "FOR SALE UNIT";
                                            }else if($act->after == 7){
                                                $valueA = "WAITING PARTS";
                                            }else if($act->after == 8){
                                                $valueA = "WAITING BACK ORDER";
                                            }else if($act->after == 9){
                                                $valueA = "WAITING SPARE BATT";
                                            }else if($act->after == 10){
                                                $valueA = "STOCK UNIT";
                                            }else if($act->after == 11){
                                                $valueA = "WAITING FOR MCI";
                                            }else if($act->after == 12){
                                                $valueA = "WAITING FOR PDI";
                                            }else{
                                                $valueA = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "WSUnitType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            if($act->after == null ||$act->after == 0 ){
                                                $valueA = "-";
                                            }else if($act->after == 1){
                                                $valueA = "TOYOTA IC JAPAN";
                                            }else if($act->after == 2){
                                                $valueA = "TOYOTA ELECTRIC JAPAN";
                                            }else if($act->after == 3){
                                                $valueA = "TOYOTA IC CHINA";
                                            }else if($act->after == 4){
                                                $valueA = "TOYOTA ELECTRIC CHINA";
                                            }else if($act->after == 5){
                                                $valueA = "TOYOTA REACH TRUCK";
                                            }else if($act->after == 6){
                                                $valueA = "BT REACH TRUCK";
                                            }else if($act->after == 7){
                                                $valueA = "BT STACKER";
                                            }else if($act->after == 8){
                                                $valueA = "RAYMOND REACH TRUCK";
                                            }else if($act->after == 9){
                                                $valueA = "RAYMOND STACKER";
                                            }else if($act->after == 10){
                                                $valueA = "STACKER TAILIFT";
                                            }else if($act->after == 11){
                                                $valueA = "PPT";
                                            }else if($act->after == 12){
                                                $valueA = "OPC";
                                            }else{
                                                $valueA = "HPT";
                                            }
                                        }
                                    }else if($act->field == "WSDelTransfer"){
                                        if($act->after == 1 ){
                                            $valueA = "YES";
                                        }else{
                                            $valueA = "NO";
                                        }
                                // TECHNICIAN SCHEDULE
                                    }else if($act->field == "Techid"){
                                        $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                    }else if($act->field == "Baynum"){
                                        $valueA = DB::table('wms_bay_areas')->where('id',$act->after)->first()->area_name;
                                    }else if($act->field == "Status"){
                                        if($act->after == 1){
                                            $valueA = "PENDING";
                                        }else if($act->after == 2){
                                            $valueA = "ONGOING";
                                        }else{
                                            $valueA = "DONE";
                                        }
                                // Unit - PARTS INFORMATION
                                    }else if($act->field == "PIReason"){
                                        if($act->after == 1){
                                            $valueA = "(B) - Back Order";
                                        }else if($act->after == 2){
                                            $valueA = "(M) - Machining";
                                        }else{
                                            $valueA = "(R) - Received";
                                        }
                                // Unit - DOWNTIME
                                    }else if($act->field == "DTReason"){
                                        if($act->after == 1){
                                            $valueA = "Lack of Space";
                                        }else if($act->after == 2){
                                            $valueA = "Lack of Technician";
                                        }else if($act->after == 3){
                                            $valueA = "No Work";
                                        }else if($act->after == 4){
                                            $valueA = "Waiting for Machining";
                                        }else if($act->after == 5){
                                            $valueA = "Waiting for Parts";
                                        }else{
                                            $valueA = "Waiting for PO";
                                        }
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
                // 

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
                                            <div class="text-sm font-normal text-gray-500 lex">'.$action.'</div>
                                        </div>
                                        <div class="p-3 text-xs italic font-normal text-gray-500 border border-gray-200 rounded-lg ' . $additionalClass . '">
                                            '.$content.'
                                        </div>
                                    </div>
                                </li>
                    ';

                }else{
                    // Unit - PULLOUT
                        if($act->field == 'IsBrandNew'){
                            $label = "Brand New Unit";
                        }else if($act->field == 'POUUnitType'){
                            $label = "Unit";
                        }else if($act->field == 'POUArrivalDate'){
                            $label = "Arrival Date";
                        }else if($act->field == 'POUBrand'){
                            $label = "Brand";
                        }else if($act->field == 'POUUnitType2'){
                            $label = "Unit Type";
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
                            $label = "Pullout Unit ID";
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
                    // CONFIRM
                        }else if($act->field == 'CUTransferDate'){
                            $label = "Confirm - Transfer Date";
                        }else if($act->field == 'CUTransferRemarks'){
                            $label = "Confirm - Transfer Remarks";
                        }else if($act->field == 'CUTransferStatus'){
                            $label = "Confirm - Transfer Status";
                        }else if($act->field == 'CUTransferArea'){
                            $label = "Confirm - Transfer Area";
                        }else if($act->field == 'CUTransferBay'){
                            $label = "Confirm - Transfer Bay";
                        }else if($act->field == 'CUDelTransfer'){
                            $label = "Confirm - Delivered?";
                    // WORKSHOP
                        }else if($act->field == 'WSPOUID'){
                            $label = "Pullout - Unit ID";
                        }else if($act->field == 'WSBayNum'){ 
                            $label = "Workshop - Bay Number";
                        }else if($act->field == 'WSToA'){
                            $label = "Workshop - Type of Activity";
                        }else if($act->field == 'WSStatus'){
                            $label = "Workshop - Status";
                        }else if($act->field == 'WSUnitType'){
                            $label = "Workshop - Unit Type";
                        }else if($act->field == 'WSVerifiedBy'){
                            $label = "Workshop - Verified By";
                        }else if($act->field == 'WSUnitCondition'){
                            $label = "Workshop - Unit Condition";
                        }else if($act->field == 'WSATIDS'){
                            $label = "Workshop - Target Inspection Date Start";
                        }else if($act->field == 'WSATIDE'){
                            $label = "Workshop - Target Inspection Date End";
                        }else if($act->field == 'WSATRDS'){
                            $label = "Workshop - Target Repair Date Start";
                        }else if($act->field == 'WSATRDE'){
                            $label = "Workshop - Target Repair Date End";
                        }else if($act->field == 'WSAAIDS'){
                            $label = "Workshop - Actual Inspection Date Start";
                        }else if($act->field == 'WSAAIDE'){
                            $label = "Workshop - Actual Inspection Date End";
                        }else if($act->field == 'WSAARDS'){
                            $label = "Workshop - Actual Repair Date Start";
                        }else if($act->field == 'WSAARDE'){
                            $label = "Workshop - Actual Repair Date End";
                        }else if($act->field == 'WSRemarks'){
                            $label = "Workshop - Remarks";
                        }else if($act->field == 'WSDelTransfer'){
                            $label = "Workshop - Delivered?";
                    // TECHNICIAN SCHEDULE
                        }else if($act->field == 'Techid'){
                            $label = "Tech. Schedule - Technician";
                        }else if($act->field == 'Baynum'){
                            $label = "Tech. Schedule - Bay Area";
                        }else if($act->field == 'JONumber'){
                            $label = "Tech. Schedule - J.O. Number";
                        }else if($act->field == 'Scheddate'){
                            $label = "Tech. Schedule - Sched. Date";
                        }else if($act->field == 'Scopeofwork'){
                            $label = "Tech. Schedule - Scope of Work";
                        }else if($act->field == 'Activity'){
                            $label = "Tech. Schedule - Activity";
                    // Unit - PARTS
                        }else if($act->field == 'PIJONum'){
                            $label = "Unit Parts Info - JO Number";
                        }else if($act->field == 'PIMRINum'){
                            $label = "Unit Parts Info - MRI Number";
                        }else if($act->field == 'PIPartID'){
                            $label = "Unit Parts Info - Part ID";
                        }else if($act->field == 'PIPartNum'){
                            $label = "Unit Parts Info - Part Number";
                        }else if($act->field == 'PIDescription'){
                            $label = "Unit Parts Info - Description";
                        }else if($act->field == 'PIQuantity'){
                            $label = "Unit Parts Info - Quantity";
                        }else if($act->field == 'PIPrice'){
                            $label = "Unit Parts Info - Price";
                        }else if($act->field == 'PIDateReq'){
                            $label = "Unit Parts Info - Date Requested";
                        }else if($act->field == 'PIDateRec'){
                            $label = "Unit Parts Info - Date Received";
                        }else if($act->field == 'PIReason'){
                            $label = "Unit Parts Info - Reason";
                        }else if($act->field == 'PIDateInstalled'){
                            $label = "Unit Parts Info - Date Installed";
                        }else if($act->field == 'PIRemarks'){
                            $label = "Unit Parts Info - Remarks";
                    // Units - DOWNTIME
                        }else if($act->field == 'DTJONum'){
                            $label = "Downtime - JO Number";
                        }else if($act->field == 'DTSDate'){
                            $label = "Downtime - Start Date";
                        }else if($act->field == 'DTEDate'){
                            $label = "Downtime - End Date";
                        }else if($act->field == 'DTReason'){
                            $label = "Downtime - Reason";
                        }else if($act->field == 'DTRemarks'){
                            $label = "Downtime - Remarks";
                        }else if($act->field == 'DTTDays'){
                            $label = "Downtime - Total Days";
                        }else{
                            $label = $act->field;
                        }
    
                // ADD
                    if($act->action == "ADD"){
                        $additionalClass = "bg-blue-300";

                        if($act->field != 'Is Deleted'){
                            // USER
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
                            // Unit - PULLOUT
                                }else if($act->field == "POUUnitType"){
                                    if($act->after == 1){
                                        $value = "DIESEL/GASOLINE/LPG";
                                    }else{
                                        $value = "BATTERY";
                                    }
                                }else if($act->field == "POUBrand"){
                                    $value = DB::table('brands')->where('id',$act->after)->first()->name;
                                }else if($act->field == "POUUnitType2"){
                                    if($act->after == 1){
                                        $value = "TOYOTA IC JAPAN";
                                    }else if($act->after == 2){
                                        $value = "TOYOTA ELECTRIC JAPAN";
                                    }else if($act->after == 3){
                                        $value = "TOYOTA IC CHINA";
                                    }else if($act->after == 4){
                                        $value = "TOYOTA ELECTRIC CHINA";
                                    }else if($act->after == 5){
                                        $value = "TOYOTA REACH TRUCK";
                                    }else if($act->after == 6){
                                        $value = "BT REACH TRUCK";
                                    }else if($act->after == 7){
                                        $value = "BT STACKER";
                                    }else if($act->after == 8){
                                        $value = "RAYMOND REACH TRUCK";
                                    }else if($act->after == 9){
                                        $value = "RAYMOND STACKER";
                                    }else if($act->after == 10){
                                        $value = "STACKER TAILIFT";
                                    }else if($act->after == 11){
                                        $value = "PPT";
                                    }else if($act->after == 12){
                                        $value = "OPH";
                                    }else{
                                        $value = "HPT";
                                    }
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
                                    if($act->after == null ||$act->after == 0 ){
                                        $value = "-";
                                    }else if($act->after == 1){
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
                            // Unit - CONFIRM
                                }else if($act->field == "CUTransferStatus"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $value = "-";
                                        }else if($act->after == 1){
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
                                    }
                                }else if($act->field == "CUTransferArea"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        $value = DB::table('sections')->where('id',$act->after)->first()->name;
                                    }
                                }else if($act->field == "CUTransferBay"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "CUDelTransfer"){
                                    if($act->after == 1 ){
                                        $value = "YES";
                                    }else{
                                        $value = "NO";
                                    }
                            // Unit - WORKSHOP
                                }else if($act->field == "WSBayNum"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        $value = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                    }
                                }else if($act->field == "WSToA"){
                                    if($act->after == 1){
                                        $value = "WAREHOUSE";
                                    }else if($act->after == 2){
                                        $value = "WORKSHOP";
                                    }else{
                                        $value = "PDI";
                                    }
                                }else if($act->field == "WSStatus"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $value = "-";
                                        }else if($act->after == 1){
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
                                    }
                                }else if($act->field == "WSUnitType"){
                                    if($act->after == null ){
                                        $value = "-";
                                    }else{
                                        if($act->after == null ||$act->after == 0 ){
                                            $value = "-";
                                        }else if($act->after == 1){
                                            $value = "TOYOTA IC JAPAN";
                                        }else if($act->after == 2){
                                            $value = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->after == 3){
                                            $value = "TOYOTA IC CHINA";
                                        }else if($act->after == 4){
                                            $value = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->after == 5){
                                            $value = "TOYOTA REACH TRUCK";
                                        }else if($act->after == 6){
                                            $value = "BT REACH TRUCK";
                                        }else if($act->after == 7){
                                            $value = "BT STACKER";
                                        }else if($act->after == 8){
                                            $value = "RAYMOND REACH TRUCK";
                                        }else if($act->after == 9){
                                            $value = "RAYMOND STACKER";
                                        }else if($act->after == 10){
                                            $value = "STACKER TAILIFT";
                                        }else if($act->after == 11){
                                            $value = "PPT";
                                        }else if($act->after == 12){
                                            $value = "OPC";
                                        }else{
                                            $value = "HPT";
                                        }
                                    }
                                }else if($act->field == "WSDelTransfer"){
                                    if($act->after == 1 ){
                                        $value = "YES";
                                    }else{
                                        $value = "NO";
                                    }
                            // TECHNICIAN SCHEDULE
                                }else if($act->field == "Techid"){
                                    $value = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                }else if($act->field == "Baynum"){
                                    $value = DB::table('wms_bay_areas')->where('id',$act->after)->first()->area_name;
                                }else if($act->field == "Status"){
                                    if($act->after == 1){
                                        $value = "PENDING";
                                    }else if($act->after == 2){
                                        $value = "ONGOING";
                                    }else{
                                        $value = "DONE";
                                    }
                            // Unit - PARTS INFORMATION
                                }else if($act->field == "PIReason"){
                                    if($act->after == 1){
                                        $value = "(B) - Back Order";
                                    }else if($act->after == 2){
                                        $value = "(M) - Machining";
                                    }else{
                                        $value = "(R) - Received";
                                    }
                            // Unit - DOWNTIME
                                }else if($act->field == "DTReason"){
                                    if($act->after == 1){
                                        $value = "Lack of Space";
                                    }else if($act->after == 2){
                                        $value = "Lack of Technician";
                                    }else if($act->after == 3){
                                        $value = "No Work";
                                    }else if($act->after == 4){
                                        $value = "Waiting for Machining";
                                    }else if($act->after == 5){
                                        $value = "Waiting for Parts";
                                    }else{
                                        $value = "Waiting for PO";
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
                                // USER
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
                                // Unit - PULLOUT
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
                                    }else if($act->field == "POUUnitType2"){
                                        if($act->before == 1){
                                            $valueB = "TOYOTA IC JAPAN";
                                        }else if($act->before == 2){
                                            $valueB = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->before == 3){
                                            $valueB = "TOYOTA IC CHINA";
                                        }else if($act->before == 4){
                                            $valueB = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->before == 5){
                                            $valueB = "TOYOTA REACH TRUCK";
                                        }else if($act->before == 6){
                                            $valueB = "BT REACH TRUCK";
                                        }else if($act->before == 7){
                                            $valueB = "BT STACKER";
                                        }else if($act->before == 8){
                                            $valueB = "RAYMOND REACH TRUCK";
                                        }else if($act->before == 9){
                                            $valueB = "RAYMOND STACKER";
                                        }else if($act->before == 10){
                                            $valueB = "STACKER TAILIFT";
                                        }else if($act->before == 11){
                                            $valueB = "PPT";
                                        }else if($act->before == 12){
                                            $valueB = "OPH";
                                        }else{
                                            $valueB = "HPT";
                                        }
                                    }else if($act->field == "POUClassification"){
                                        if($act->before == 1){
                                            $valueB = "CLASS A";
                                        }else if($act->before == 2){
                                            $valueB = "CLASS B";
                                        }else if($act->before == 3){
                                            $valueB = "CLASS C";
                                        }else{
                                            $valueB = "CLASS D";
                                        }
                                    }else if($act->field == "POUwAttachment"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAttType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUAttModel"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUAttSerialNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUwAccesories"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccISite"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccLiftCam"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccRedLight"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccBlueLight"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccFireExt"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccStLight"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthers"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthersDetail"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUTechnician1"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "POUTechnician2"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "POUStatus"){
                                        if($act->before == null ||$act->before == 0 ){
                                            $valueB = "-";
                                        }else if($act->before == 1){
                                            $valueB = "WAITING FOR REPAIR UNIT";
                                        }else if($act->before == 2){
                                            $valueB = "UNDER REPAIR UNIT";
                                        }else if($act->before == 3){
                                            $valueB = "USED GOOD UNIT";
                                        }else if($act->before == 4){
                                            $valueB = "SERVICE UNIT";
                                        }else if($act->before == 5){
                                            $valueB = "FOR SCRAP UNIT";
                                        }else if($act->before == 6){
                                            $valueB = "FOR SALE UNIT";
                                        }else if($act->before == 7){
                                            $valueB = "WAITING PARTS";
                                        }else if($act->before == 8){
                                            $valueB = "WAITING BACK ORDER";
                                        }else if($act->before == 9){
                                            $valueB = "WAITING SPARE BATT";
                                        }else if($act->before == 10){
                                            $valueB = "STOCK UNIT";
                                        }else if($act->before == 11){
                                            $valueB = "WAITING FOR MCI";
                                        }else if($act->before == 12){
                                            $valueB = "WAITING FOR PDI";
                                        }else{
                                            $valueB = "DONE PDI (WFD)";
                                        }
                                    }else if($act->field == "POUTransferArea"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('sections')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "POUTransferBay"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                        }
                                    }else if($act->field == "POUTransferDate"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUTransferRemarks"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUwSpareBat1"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUSB1Brand"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1BatType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1SerialNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1Code"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1Amper"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1Volt"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1CCable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB1CTable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUwSpareBat2"){
                                        if($act->before == 0 || $act->before == null ){
                                            $valueB = "No";
                                        }else{
                                            $valueB = "Yes";
                                        }
                                    }else if($act->field == "POUSB2Brand"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2BatType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2SerialNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2Code"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2Amper"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2Volt"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2CCable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                    }else if($act->field == "POUSB2CTable"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = $act->before;
                                        }
                                // Unit - CONFIRM
                                    }else if($act->field == "CUTransferStatus"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            if($act->before == null ||$act->before == 0 ){
                                                $valueB = "-";
                                            }else if($act->before == 1){
                                                $valueB = "WAITING FOR REPAIR UNIT";
                                            }else if($act->before == 2){
                                                $valueB = "UNDER REPAIR UNIT";
                                            }else if($act->before == 3){
                                                $valueB = "USED GOOD UNIT";
                                            }else if($act->before == 4){
                                                $valueB = "SERVICE UNIT";
                                            }else if($act->before == 5){
                                                $valueB = "FOR SCRAP UNIT";
                                            }else if($act->before == 6){
                                                $valueB = "FOR SALE UNIT";
                                            }else if($act->before == 7){
                                                $valueB = "WAITING PARTS";
                                            }else if($act->before == 8){
                                                $valueB = "WAITING BACK ORDER";
                                            }else if($act->before == 9){
                                                $valueB = "WAITING SPARE BATT";
                                            }else if($act->before == 10){
                                                $valueB = "STOCK UNIT";
                                            }else if($act->before == 11){
                                                $valueB = "WAITING FOR MCI";
                                            }else if($act->before == 12){
                                                $valueB = "WAITING FOR PDI";
                                            }else{
                                                $valueB = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "CUTransferArea"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('sections')->where('id',$act->before)->first()->name;
                                        }
                                    }else if($act->field == "CUTransferBay"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                        }
                                    }else if($act->field == "CUDelTransfer"){
                                        if($act->before == 1 ){
                                            $valueB = "YES";
                                        }else{
                                            $valueB = "NO";
                                        }
                                // Unit - WORKSHOP
                                    }else if($act->field == "WSBayNum"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            $valueB = DB::table('bay_areas')->where('id',$act->before)->first()->area_name;
                                        }
                                    }else if($act->field == "WSToA"){
                                        if($act->before == 1){
                                            $valueB = "WAREHOUSE";
                                        }else if($act->before == 2){
                                            $valueB = "WORKSHOP";
                                        }else{
                                            $valueB = "PDI";
                                        }
                                    }else if($act->field == "WSStatus"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            if($act->before == null ||$act->before == 0 ){
                                                $valueB = "-";
                                            }else if($act->before == 1){
                                                $valueB = "WAITING FOR REPAIR UNIT";
                                            }else if($act->before == 2){
                                                $valueB = "UNDER REPAIR UNIT";
                                            }else if($act->before == 3){
                                                $valueB = "USED GOOD UNIT";
                                            }else if($act->before == 4){
                                                $valueB = "SERVICE UNIT";
                                            }else if($act->before == 5){
                                                $valueB = "FOR SCRAP UNIT";
                                            }else if($act->before == 6){
                                                $valueB = "FOR SALE UNIT";
                                            }else if($act->before == 7){
                                                $valueB = "WAITING PARTS";
                                            }else if($act->before == 8){
                                                $valueB = "WAITING BACK ORDER";
                                            }else if($act->before == 9){
                                                $valueB = "WAITING SPARE BATT";
                                            }else if($act->before == 10){
                                                $valueB = "STOCK UNIT";
                                            }else if($act->before == 11){
                                                $valueB = "WAITING FOR MCI";
                                            }else if($act->before == 12){
                                                $valueB = "WAITING FOR PDI";
                                            }else{
                                                $valueB = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "WSUnitType"){
                                        if($act->before == null ){
                                            $valueB = "-";
                                        }else{
                                            if($act->before == null ||$act->before == 0 ){
                                                $valueB = "-";
                                            }else if($act->before == 1){
                                                $valueB = "TOYOTA IC JAPAN";
                                            }else if($act->before == 2){
                                                $valueB = "TOYOTA ELECTRIC JAPAN";
                                            }else if($act->before == 3){
                                                $valueB = "TOYOTA IC CHINA";
                                            }else if($act->before == 4){
                                                $valueB = "TOYOTA ELECTRIC CHINA";
                                            }else if($act->before == 5){
                                                $valueB = "TOYOTA REACH TRUCK";
                                            }else if($act->before == 6){
                                                $valueB = "BT REACH TRUCK";
                                            }else if($act->before == 7){
                                                $valueB = "BT STACKER";
                                            }else if($act->before == 8){
                                                $valueB = "RAYMOND REACH TRUCK";
                                            }else if($act->before == 9){
                                                $valueB = "RAYMOND STACKER";
                                            }else if($act->before == 10){
                                                $valueB = "STACKER TAILIFT";
                                            }else if($act->before == 11){
                                                $valueB = "PPT";
                                            }else if($act->before == 12){
                                                $valueB = "OPC";
                                            }else{
                                                $valueB = "HPT";
                                            }
                                        }
                                    }else if($act->field == "WSDelTransfer"){
                                        if($act->before == 1 ){
                                            $valueB = "YES";
                                        }else{
                                            $valueB = "NO";
                                        }
                                // TECHNICIAN SCHEDULE
                                    }else if($act->field == "Techid"){
                                        $valueB = DB::table('wms_technicians')->where('id',$act->before)->first()->name;
                                    }else if($act->field == "Baynum"){
                                        $valueB = DB::table('wms_bay_areas')->where('id',$act->before)->first()->area_name;
                                    }else if($act->field == "Status"){
                                        if($act->before == 1){
                                            $valueB = "PENDING";
                                        }else if($act->before == 2){
                                            $valueB = "ONGOING";
                                        }else{
                                            $valueB = "DONE";
                                        }
                                // Unit - PARTS INFORMATION
                                    }else if($act->field == "PIReason"){
                                        if($act->before == 1){
                                            $valueB = "(B) - Back Order";
                                        }else if($act->before == 2){
                                            $valueB = "(M) - Machining";
                                        }else{
                                            $valueB = "(R) - Received";
                                        }
                                // Unit - DOWNTIME
                                    }else if($act->field == "DTReason"){
                                        if($act->before == 1){
                                            $valueB = "Lack of Space";
                                        }else if($act->before == 2){
                                            $valueB = "Lack of Technician";
                                        }else if($act->before == 3){
                                            $valueB = "No Work";
                                        }else if($act->before == 4){
                                            $valueB = "Waiting for Machining";
                                        }else if($act->before == 5){
                                            $valueB = "Waiting for Parts";
                                        }else{
                                            $valueB = "Waiting for PO";
                                        }
                                    }else{
                                        $valueB = $act->before;
                                    }
    
                            // AFTER
                                // USER
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
                                // Unit - PULLOUT
                                    }else if($act->field == "POUUnitType"){
                                        if($act->after == 1){
                                            $valueA = "DIESEL/GASOLINE/LPG";
                                        }else{
                                            $valueA = "BATTERY";
                                        }
                                    }else if($act->field == "POUBrand"){
                                        $valueA = DB::table('brands')->where('id',$act->after)->first()->name;
                                    }else if($act->field == "POUUnitType2"){
                                        if($act->after == 1){
                                            $valueA = "TOYOTA IC JAPAN";
                                        }else if($act->after == 2){
                                            $valueA = "TOYOTA ELECTRIC JAPAN";
                                        }else if($act->after == 3){
                                            $valueA = "TOYOTA IC CHINA";
                                        }else if($act->after == 4){
                                            $valueA = "TOYOTA ELECTRIC CHINA";
                                        }else if($act->after == 5){
                                            $valueA = "TOYOTA REACH TRUCK";
                                        }else if($act->after == 6){
                                            $valueA = "BT REACH TRUCK";
                                        }else if($act->after == 7){
                                            $valueA = "BT STACKER";
                                        }else if($act->after == 8){
                                            $valueA = "RAYMOND REACH TRUCK";
                                        }else if($act->after == 9){
                                            $valueA = "RAYMOND STACKER";
                                        }else if($act->after == 10){
                                            $valueA = "STACKER TAILIFT";
                                        }else if($act->after == 11){
                                            $valueA = "PPT";
                                        }else if($act->after == 12){
                                            $valueA = "OPH";
                                        }else{
                                            $valueA = "HPT";
                                        }
                                    }else if($act->field == "POUClassification"){
                                        if($act->after == 1){
                                            $valueA = "CLASS A";
                                        }else if($act->after == 2){
                                            $valueA = "CLASS B";
                                        }else if($act->after == 3){
                                            $valueA = "CLASS C";
                                        }else{
                                            $valueA = "CLASS D";
                                        }
                                    }else if($act->field == "POUwAttachment"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAttType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUAttModel"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUAttSerialNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUwAccesories"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccISite"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccLiftCam"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccRedLight"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccBlueLight"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccFireExt"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccStLight"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthers"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUAccOthersDetail"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUTechnician1"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "POUTechnician2"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "POUStatus"){
                                        if($act->after == null ||$act->after == 0 ){
                                            $valueA = "-";
                                        }else if($act->after == 1){
                                            $valueA = "WAITING FOR REPAIR UNIT";
                                        }else if($act->after == 2){
                                            $valueA = "UNDER REPAIR UNIT";
                                        }else if($act->after == 3){
                                            $valueA = "USED GOOD UNIT";
                                        }else if($act->after == 4){
                                            $valueA = "SERVICE UNIT";
                                        }else if($act->after == 5){
                                            $valueA = "FOR SCRAP UNIT";
                                        }else if($act->after == 6){
                                            $valueA = "FOR SALE UNIT";
                                        }else if($act->after == 7){
                                            $valueA = "WAITING PARTS";
                                        }else if($act->after == 8){
                                            $valueA = "WAITING BACK ORDER";
                                        }else if($act->after == 9){
                                            $valueA = "WAITING SPARE BATT";
                                        }else if($act->after == 10){
                                            $valueA = "STOCK UNIT";
                                        }else if($act->after == 11){
                                            $valueA = "WAITING FOR MCI";
                                        }else if($act->after == 12){
                                            $valueA = "WAITING FOR PDI";
                                        }else{
                                            $valueA = "DONE PDI (WFD)";
                                        }
                                    }else if($act->field == "POUTransferArea"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('sections')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "POUTransferBay"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                        }
                                    }else if($act->field == "POUTransferDate"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUTransferRemarks"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUwSpareBat1"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUSB1Brand"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1BatType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1SerialNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1Code"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1Amper"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1Volt"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1CCable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB1CTable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUwSpareBat2"){
                                        if($act->after == 0 || $act->after == null ){
                                            $valueA = "No";
                                        }else{
                                            $valueA = "Yes";
                                        }
                                    }else if($act->field == "POUSB2Brand"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2BatType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2SerialNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2Code"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2Amper"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2Volt"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2CCable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                    }else if($act->field == "POUSB2CTable"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = $act->after;
                                        }
                                // Unit - CONFIRM
                                    }else if($act->field == "CUTransferStatus"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            if($act->after == null ||$act->after == 0 ){
                                                $valueA = "-";
                                            }else if($act->after == 1){
                                                $valueA = "WAITING FOR REPAIR UNIT";
                                            }else if($act->after == 2){
                                                $valueA = "UNDER REPAIR UNIT";
                                            }else if($act->after == 3){
                                                $valueA = "USED GOOD UNIT";
                                            }else if($act->after == 4){
                                                $valueA = "SERVICE UNIT";
                                            }else if($act->after == 5){
                                                $valueA = "FOR SCRAP UNIT";
                                            }else if($act->after == 6){
                                                $valueA = "FOR SALE UNIT";
                                            }else if($act->after == 7){
                                                $valueA = "WAITING PARTS";
                                            }else if($act->after == 8){
                                                $valueA = "WAITING BACK ORDER";
                                            }else if($act->after == 9){
                                                $valueA = "WAITING SPARE BATT";
                                            }else if($act->after == 10){
                                                $valueA = "STOCK UNIT";
                                            }else if($act->after == 11){
                                                $valueA = "WAITING FOR MCI";
                                            }else if($act->after == 12){
                                                $valueA = "WAITING FOR PDI";
                                            }else{
                                                $valueA = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "CUTransferArea"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('sections')->where('id',$act->after)->first()->name;
                                        }
                                    }else if($act->field == "CUTransferBay"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                        }
                                    }else if($act->field == "CUDelTransfer"){
                                        if($act->after == 1 ){
                                            $valueA = "YES";
                                        }else{
                                            $valueA = "NO";
                                        }
                                // Unit - WORKSHOP
                                    }else if($act->field == "WSBayNum"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            $valueA = DB::table('bay_areas')->where('id',$act->after)->first()->area_name;
                                        }
                                    }else if($act->field == "WSToA"){
                                        if($act->after == 1){
                                            $valueA = "WAREHOUSE";
                                        }else if($act->after == 2){
                                            $valueA = "WORKSHOP";
                                        }else{
                                            $valueA = "PDI";
                                        }
                                    }else if($act->field == "WSStatus"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            if($act->after == null ||$act->after == 0 ){
                                                $valueA = "-";
                                            }else if($act->after == 1){
                                                $valueA = "WAITING FOR REPAIR UNIT";
                                            }else if($act->after == 2){
                                                $valueA = "UNDER REPAIR UNIT";
                                            }else if($act->after == 3){
                                                $valueA = "USED GOOD UNIT";
                                            }else if($act->after == 4){
                                                $valueA = "SERVICE UNIT";
                                            }else if($act->after == 5){
                                                $valueA = "FOR SCRAP UNIT";
                                            }else if($act->after == 6){
                                                $valueA = "FOR SALE UNIT";
                                            }else if($act->after == 7){
                                                $valueA = "WAITING PARTS";
                                            }else if($act->after == 8){
                                                $valueA = "WAITING BACK ORDER";
                                            }else if($act->after == 9){
                                                $valueA = "WAITING SPARE BATT";
                                            }else if($act->after == 10){
                                                $valueA = "STOCK UNIT";
                                            }else if($act->after == 11){
                                                $valueA = "WAITING FOR MCI";
                                            }else if($act->after == 12){
                                                $valueA = "WAITING FOR PDI";
                                            }else{
                                                $valueA = "DONE PDI (WFD)";
                                            }
                                        }
                                    }else if($act->field == "WSUnitType"){
                                        if($act->after == null ){
                                            $valueA = "-";
                                        }else{
                                            if($act->after == null ||$act->after == 0 ){
                                                $valueA = "-";
                                            }else if($act->after == 1){
                                                $valueA = "TOYOTA IC JAPAN";
                                            }else if($act->after == 2){
                                                $valueA = "TOYOTA ELECTRIC JAPAN";
                                            }else if($act->after == 3){
                                                $valueA = "TOYOTA IC CHINA";
                                            }else if($act->after == 4){
                                                $valueA = "TOYOTA ELECTRIC CHINA";
                                            }else if($act->after == 5){
                                                $valueA = "TOYOTA REACH TRUCK";
                                            }else if($act->after == 6){
                                                $valueA = "BT REACH TRUCK";
                                            }else if($act->after == 7){
                                                $valueA = "BT STACKER";
                                            }else if($act->after == 8){
                                                $valueA = "RAYMOND REACH TRUCK";
                                            }else if($act->after == 9){
                                                $valueA = "RAYMOND STACKER";
                                            }else if($act->after == 10){
                                                $valueA = "STACKER TAILIFT";
                                            }else if($act->after == 11){
                                                $valueA = "PPT";
                                            }else if($act->after == 12){
                                                $valueA = "OPC";
                                            }else{
                                                $valueA = "HPT";
                                            }
                                        }
                                    }else if($act->field == "WSDelTransfer"){
                                        if($act->after == 1 ){
                                            $valueA = "YES";
                                        }else{
                                            $valueA = "NO";
                                        }
                                // TECHNICIAN SCHEDULE
                                    }else if($act->field == "Techid"){
                                        $valueA = DB::table('wms_technicians')->where('id',$act->after)->first()->name;
                                    }else if($act->field == "Baynum"){
                                        $valueA = DB::table('wms_bay_areas')->where('id',$act->after)->first()->area_name;
                                    }else if($act->field == "Status"){
                                        if($act->after == 1){
                                            $valueA = "PENDING";
                                        }else if($act->after == 2){
                                            $valueA = "ONGOING";
                                        }else{
                                            $valueA = "DONE";
                                        }
                                // Unit - PARTS INFORMATION
                                    }else if($act->field == "PIReason"){
                                        if($act->after == 1){
                                            $valueA = "(B) - Back Order";
                                        }else if($act->after == 2){
                                            $valueA = "(M) - Machining";
                                        }else{
                                            $valueA = "(R) - Received";
                                        }
                                // Unit - DOWNTIME
                                    }else if($act->field == "DTReason"){
                                        if($act->after == 1){
                                            $valueA = "Lack of Space";
                                        }else if($act->after == 2){
                                            $valueA = "Lack of Technician";
                                        }else if($act->after == 3){
                                            $valueA = "No Work";
                                        }else if($act->after == 4){
                                            $valueA = "Waiting for Machining";
                                        }else if($act->after == 5){
                                            $valueA = "Waiting for Parts";
                                        }else{
                                            $valueA = "Waiting for PO";
                                        }
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
                }
                $cdate = $ndate;
            }
            $x++;
        }
        return response()->json($result);
    }
}
