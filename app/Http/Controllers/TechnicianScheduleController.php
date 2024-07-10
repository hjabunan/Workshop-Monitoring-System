<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\TechnicianSchedule;
use App\Models\UnitPullOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Test\Constraint\RequestAttributeValueSame;

class TechnicianScheduleController extends Controller
{
    public function index(){
        $techsched = DB::select('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.id AS techid1, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                wms_bay_areas.id as bayid, wms_bay_areas.area_name AS bayname, technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus
                                FROM technician_schedules
                                INNER JOIN wms_technicians on wms_technicians.id = technician_schedules.techid
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = technician_schedules.baynum
                                WHERE technician_schedules.is_deleted=0 AND technician_schedules.status=1 OR technician_schedules.status=2
                        ');
                        
        $techschedX = DB::select('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.id AS techid1, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                wms_bay_areas.id as bayid, wms_bay_areas.area_name AS bayname, technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus
                                FROM technician_schedules
                                INNER JOIN wms_technicians on wms_technicians.id = technician_schedules.techid
                                INNER JOIN wms_bay_areas on wms_bay_areas.id = technician_schedules.baynum
                                WHERE technician_schedules.is_deleted=0
                                ');

        $tech = DB::SELECT('SELECT id, name, initials FROM wms_technicians WHERE status="1"');

        $bay = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSBayNum, wms_bay_areas.id as BayID, wms_bay_areas.area_name 
                            FROM unit_workshops INNER JOIN wms_bay_areas on wms_bay_areas.id = unit_workshops.WSBayNum 
                            WHERE isBrandNew=0 AND WSDelTransfer = 0 AND unit_workshops.is_deleted=0 
                            ORDER BY wms_bay_areas.area_name' );

        return view('workshop-ms.admin_monitoring.tech_schedule',compact('techsched','tech','bay','techschedX'));
    }

    public function saveSchedule(Request $request){
        $POUB = UnitPullOut::where('id', $request->TSPOUID)->first();
        $tesc = TechnicianSchedule::where('id', $request->TSID)->first();
        if($request->TSID == ''){
            $techsched = new TechnicianSchedule();
            $techsched->techid = strtoupper($request->TSName);
            $techsched->baynum = strtoupper($request->TSBayNum);
            $techsched->JONumber = strtoupper($request->TSJONum);
            $techsched->POUID = strtoupper($request->TSPOUID);
            $techsched->scheddate = $request->TSDate;
            $techsched->scopeofwork = strtoupper($request->TSSoW);
            $techsched->activity = strtoupper($request->TSActivity);
            $techsched->remarks = '';
                $dirtyAttributes = $techsched->getDirty();
            $techsched->save();
                foreach($dirtyAttributes as $attribute => $newValue){
                    $oldValue = $techsched->getOriginal($attribute);

                    $field = ucwords(str_replace('_', ' ', $attribute));

                    $newLog = new ActivityLog();
                    $newLog->table = 'Tech. Schedule Table';
                    $newLog->table_key = $techsched->id;
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
            $techsched = TechnicianSchedule::find($request->TSID);
            $techsched->techid = strtoupper($request->TSName);
            $techsched->baynum = strtoupper($request->TSBayNum);
            $techsched->JONumber = strtoupper($request->TSJONum);
            $techsched->POUID = strtoupper($request->TSPOUID);
            $techsched->scheddate = $request->TSDate;
            $techsched->scopeofwork = strtoupper($request->TSSoW);
            $techsched->activity = strtoupper($request->TSActivity);
            $techsched->remarks = '';
                $dirtyAttributes = $techsched->getDirty();
                foreach($dirtyAttributes as $attribute => $newValue){
                    $oldValue = $techsched->getOriginal($attribute);

                    $field = ucwords(str_replace('_', ' ', $attribute));

                    $newLog = new ActivityLog();
                    $newLog->table = 'Tech. Schedule Table';
                    $newLog->table_key = $request->TSID;
                    $newLog->action = 'UPDATE';
                    $newLog->description = $POUB->POUSerialNum;
                    $newLog->field = $field;
                    $newLog->before = $oldValue;
                    $newLog->after = $newValue;
                    $newLog->user_id = Auth::user()->id;
                    $newLog->ipaddress =  request()->ip();
                    $newLog->save();
                }
            $techsched->update();
        }

        $result = '';

        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    wms_technicians.initials, wms_bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN wms_technicians on techid=wms_technicians.id
                                    INNER JOIN wms_bay_areas on baynum=wms_bay_areas.id
                                    WHERE technician_schedules.is_deleted=0 AND technician_schedules.status=1 OR technician_schedules.status=2
                                ');

        if(count($techschedule)>0){
            foreach ($techschedule as $TS) {
                $result .='
                            <tr class="techTable bg-white border-b hover:bg-gray-300">
                                <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                    <span data-id="'.$TS->id.'">
                                        '.$TS->initials.'
                                    </span>
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->area_name.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->JONumber.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scheddate.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scopeofwork.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->activity.'
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

    public function getSchedule(Request $request){
        $techsched = DB::table('technician_schedules')->where('id', $request->id)->first();

        $result = array(
            "TSID" => $techsched->id,
            "TSName" => $techsched->techid,
            "TSBayNum" => $techsched->baynum,
            "TSJONum" => $techsched->JONumber,
            "POUID" => $techsched->POUID,
            "TSDate" => $techsched->scheddate,
            "TSSoW" => $techsched->scopeofwork,
            "TSActivity" => $techsched->activity,
        );
        return response()->json($result);
    }

    public function deleteSchedule(Request $request){
        $POUB = UnitPullOut::where('id', $request->TSPOUID)->first();
        $tesc = TechnicianSchedule::where('id', $request->TSID)->first();
        $techsched = TechnicianSchedule::find($request->TSID);
        $techsched->is_deleted = 1;
            $dirtyAttributes = $techsched->getDirty();
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $techsched->getOriginal($attribute);

                $field = ucwords(str_replace('_', ' ', $attribute));

                $newLog = new ActivityLog();
                $newLog->table = 'Tech. Schedule Table';
                $newLog->table_key = $request->TSID;
                $newLog->action = 'DELETE';
                $newLog->description = $POUB->POUSerialNum;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->ipaddress =  request()->ip();
                $newLog->save();
            }
        $techsched->update();

        $result = '';

        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    wms_technicians.initials, wms_bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN wms_technicians on techid=wms_technicians.id
                                    INNER JOIN wms_bay_areas on baynum=wms_bay_areas.id
                                    WHERE technician_schedules.is_deleted=0 AND technician_schedules.status="1" OR technician_schedules.status="2"
                                ');

        if(count($techschedule)>0){
            foreach ($techschedule as $TS) {
                $result .='
                            <tr class="techTable bg-white border-b hover:bg-gray-300">
                                <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                    <span data-id="'.$TS->id.'">
                                        '.$TS->initials.'
                                    </span>
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->area_name.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->JONumber.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scheddate.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scopeofwork.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->activity.'
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

    public function filterSchedule(Request $request){
        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    wms_technicians.initials, wms_bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN wms_technicians on techid=wms_technicians.id
                                    INNER JOIN wms_bay_areas on baynum=wms_bay_areas.id
                                    WHERE technician_schedules.status="1" AND technician_schedules.is_deleted=0 AND technician_schedules.scheddate BETWEEN ? AND ?', [$request->fromDate, $request->toDate]
                            );
        $result = '';

        if(count($techschedule)>0){
            foreach ($techschedule as $TS) {
                $result .='
                            <tr class="techTable bg-white border-b hover:bg-gray-300">
                                <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                    <span data-id="'.$TS->id.'">
                                        '.$TS->initials.'
                                    </span>
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->area_name.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->JONumber.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scheddate.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scopeofwork.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->activity.'
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

    public function resetSchedule(Request $request){
        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    wms_technicians.initials, wms_bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN wms_technicians on techid=wms_technicians.id
                                    INNER JOIN wms_bay_areas on baynum=wms_bay_areas.id
                                    WHERE technician_schedules.status="1" AND is_deleted=0'
                            );
        $result = '';

        if(count($techschedule)>0){
            foreach ($techschedule as $TS) {
                $result .='
                            <tr class="techTable bg-white border-b hover:bg-gray-300">
                                <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                    <span data-id="'.$TS->id.'">
                                        '.$TS->initials.'
                                    </span>
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->area_name.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->JONumber.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scheddate.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scopeofwork.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->activity.'
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

    public function filterScheduleX(Request $request){
        if($request->techname0 != null || $request->techname0 != ''){
            $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                        technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                        wms_technicians.initials, wms_bay_areas.area_name
                                        FROM technician_schedules 
                                        INNER JOIN wms_technicians on technician_schedules.techid=wms_technicians.id
                                        INNER JOIN wms_bay_areas on technician_schedules.baynum=wms_bay_areas.id
                                        WHERE techid = ? AND scheddate BETWEEN ? AND ?', [$request->techname0, $request->fromDateS, $request->toDateS]
                            );
        }else{
            $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                        technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                        wms_technicians.initials, wms_bay_areas.area_name
                                        FROM technician_schedules 
                                        INNER JOIN wms_technicians on technician_schedules.techid=wms_technicians.id
                                        INNER JOIN wms_bay_areas on technician_schedules.baynum=wms_bay_areas.id
                                        WHERE scheddate BETWEEN ? AND ?', [$request->fromDateS, $request->toDateS]
                            );
            
        }

        $result = '';

        if(count($techschedule)>0){
            foreach ($techschedule as $TS) {
                if($TS->TSStatus == 1){
                    $TStatus = 'PENDING';
                }else{
                    $TStatus = 'DONE';
                }

                $result .='
                            <tr class="techTable bg-white border-b hover:bg-gray-300">
                                <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                    <span data-id="'.$TS->id.'">
                                        '.$TS->initials.'
                                    </span>
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->area_name.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->JONumber.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scheddate.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scopeofwork.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->activity.'
                                </td>
                                <td class="px-6 py-1">
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
        echo $result;
    }

    public function clearSearch(Request $request){
        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, wms_technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    wms_technicians.initials, wms_bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN wms_technicians on technician_schedules.techid=wms_technicians.id
                                    INNER JOIN wms_bay_areas on technician_schedules.baynum=wms_bay_areas.id'
                        );

        $result = '';

        if(count($techschedule)>0){
            foreach ($techschedule as $TS) {
                if($TS->TSStatus == 1){
                    $TStatus = 'PENDING';
                }else{
                    $TStatus = 'DONE';
                }

                $result .='
                            <tr class="techTable bg-white border-b hover:bg-gray-300">
                                <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                    <span data-id="'.$TS->id.'">
                                        '.$TS->initials.'
                                    </span>
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->area_name.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->JONumber.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scheddate.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->scopeofwork.'
                                </td>
                                <td class="px-6 py-1">
                                        '.$TS->activity.'
                                </td>
                                <td class="px-6 py-1">
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
        echo $result;
    }

    public function generateSReport(Request $request){
        $tech = DB::table('wms_technicians')
                ->select('name', 'initials')
                ->where('id', '=', $request->techname0)
                ->first();
        $title = "SCHEDULE REPORT";
            if($request->techname0 != null || $request->techname0 != ''){
                $data = DB::table('technician_schedules')
                            ->select('technician_schedules.id','baynum','area_name','JONumber','POUID','scheddate','scopeofwork','activity','technician_schedules.status','remarks')
                        ->leftJoin('wms_technicians','wms_technicians.id','=','technician_schedules.techid')
                        ->leftJoin('wms_bay_areas','wms_bay_areas.id','=','technician_schedules.baynum')
                        ->where('techid','=',$request->techname0)
                        ->whereBetween('scheddate',[$request->fromDateS, $request->toDateS])
                        ->get();
            
                        $csv = Writer::createFromString('');
                        $csv->insertOne(['']);
                        $csv->insertOne([$title]);
                        $csv->insertOne(['']);
                        $csv->insertOne(['FROM:', $request->fromDateS]);
                        $csv->insertOne(['TO:', $request->toDateS]);
                        $csv->insertOne(['']);
                        $csv->insertOne(['TECHNICIAN: ', $tech->name]);
                        $csv->insertOne(['']);
                        $csv->insertOne(['id', 'Bay Number', 'Date', 'Activity', 'Scope of Work', 'Status', 'Remarks']);
                
                        foreach($data as $row){
                            if($row->status == 1){
                                $status = "PENDING";
                            }else if($row->status == 2){
                                $status = "ONGOING";
                            }else{
                                $status = "DONE";
                            }
                            $csv->insertOne([$row->id, $row->area_name, $row->scheddate, $row->activity, $row->scopeofwork, $status, $row->remarks]);
                        }
                
                        $csvContent = $csv->getContent();
                    
                        return response($csvContent)
                            ->header('Content-Type', 'text/csv')
                            ->header('Content-Disposition', 'attachment; filename="data.csv"');
            }else{
                $data = DB::table('technician_schedules')
                        ->select('technician_schedules.id','baynum','area_name','wms_technicians.name as Tname','JONumber','POUID','scheddate','scopeofwork','activity','technician_schedules.status','remarks')
                        ->leftJoin('wms_technicians','wms_technicians.id','=','technician_schedules.techid')
                        ->leftJoin('wms_bay_areas','wms_bay_areas.id','=','technician_schedules.baynum')
                        ->whereBetween('scheddate',[$request->fromDateS, $request->toDateS])->get();
            
                        $csv = Writer::createFromString('');
                        $csv->insertOne(['']);
                        $csv->insertOne([$title]);
                        $csv->insertOne(['']);
                        $csv->insertOne(['FROM:', $request->fromDateS]);
                        $csv->insertOne(['TO:', $request->toDateS]);
                        $csv->insertOne(['']);
                        $csv->insertOne(['id', 'Bay Number', 'Technician Name', 'Date', 'Activity', 'Scope of Work', 'Status', 'Remarks']);
                
                        foreach($data as $row){
                            if($row->status == 1){
                                $status = "PENDING";
                            }else if($row->status == 2){
                                $status = "ONGOING";
                            }else{
                                $status = "DONE";
                            }
                            $csv->insertOne([$row->id, $row->area_name, $row->Tname, $row->scheddate, $row->activity, $row->scopeofwork, $status, $row->remarks]);
                        }
                
                        $csvContent = $csv->getContent();
                    
                        return response($csvContent)
                            ->header('Content-Type', 'text/csv')
                            ->header('Content-Disposition', 'attachment; filename="data.csv"');
            }

    }

    public function getJONum(Request $request){
        $result = '';

        $WSID = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID FROM unit_workshops WHERE WSBayNum=?', [$request->TSBayNum] );

        foreach ($WSID as $WS) {
            
            $result = array(
                'WSID' => $WS->WSID,
                'POUID' => $WS->WSPOUID,
            );

        }
        return response()->json($result);
    }

    public function getEvents(Request $request){
        $events = DB::table('technician_schedules')
                    ->select('baynum', 'area_name','activity', 'scheddate','technician_schedules.status')
                    // ->where('status', '=', 1)
                    // ->where('baynum', '=', $request->bay)
                    ->leftJoin('wms_bay_areas','technician_schedules.baynum','=','wms_bay_areas.id')
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
                'baynum' => $event->baynum,
                'area_name' => $event->area_name,
                'title' => $event->activity,
                'start' => \Carbon\Carbon::parse($event->scheddate)->toIso8601String(),
                'end' => \Carbon\Carbon::parse($event->scheddate)->toIso8601String(),
                'color' => $ecolor,
            ];
        }
    
        return response()->json($formattedEvents);
    }

    public function getActiveBayNames(Request $request) {
        $activeBayNames = DB::table('wms_bay_areas')
            ->select('area_name')
            ->where('status', '=', 1)
            ->get()
            ->pluck('area_name');

        return response()->json($activeBayNames);
    }
}
