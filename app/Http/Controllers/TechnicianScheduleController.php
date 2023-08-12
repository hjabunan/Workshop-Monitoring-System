<?php

namespace App\Http\Controllers;

use App\Models\TechnicianSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Test\Constraint\RequestAttributeValueSame;

class TechnicianScheduleController extends Controller
{
    public function index(){
        $techsched = DB::select('SELECT technician_schedules.id, technician_schedules.techid, technicians.id AS techid1, technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                bay_areas.id as bayid, bay_areas.area_name AS bayname, technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus
                                FROM technician_schedules
                                INNER JOIN technicians on technicians.id = technician_schedules.techid
                                INNER JOIN bay_areas on bay_areas.id = technician_schedules.baynum
                                WHERE technician_schedules.status=1 OR technician_schedules.status=2
                        ');
                        
        $techschedX = DB::select('SELECT technician_schedules.id, technician_schedules.techid, technicians.id AS techid1, technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                bay_areas.id as bayid, bay_areas.area_name AS bayname, technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus
                                FROM technician_schedules
                                INNER JOIN technicians on technicians.id = technician_schedules.techid
                                INNER JOIN bay_areas on bay_areas.id = technician_schedules.baynum
                                ');

        $tech = DB::SELECT('SELECT id, initials FROM technicians WHERE status="1"');
        // $bay = DB::SELECT('SELECT * FROM bay_areas WHERE status="1" AND category="2"');
        $bay = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSBayNum, bay_areas.id as BayID, bay_areas.area_name FROM unit_workshops INNER JOIN bay_areas on bay_areas.id = unit_workshops.WSBayNum WHERE WSStatus<=4' );

        return view('workshop-ms.admin_monitoring.tech_schedule',compact('techsched','tech','bay','techschedX'));
    }

    public function saveSchedule(Request $request){
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
            $techsched->save();
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
            $techsched->update();
        }

        $result = '';

        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    technicians.initials, bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN technicians on techid=technicians.id
                                    INNER JOIN bay_areas on baynum=bay_areas.id
                                    WHERE technician_schedules.status=1 OR technician_schedules.status=2
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
        $techsched = TechnicianSchedule::find($request->TSID);
        $techsched->delete();

        $result = '';

        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, technicians.initials AS techname, technician_schedules.baynum,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    technicians.initials, bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN technicians on techid=technicians.id
                                    INNER JOIN bay_areas on baynum=bay_areas.id
                                    WHERE technician_schedules.status="1"
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
        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    technicians.initials, bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN technicians on techid=technicians.id
                                    INNER JOIN bay_areas on baynum=bay_areas.id
                                    WHERE technician_schedules.status="1" AND technician_schedules.scheddate BETWEEN ? AND ?', [$request->fromDate, $request->toDate]
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
        $techschedule = DB::SELECT('SELECT technician_schedules.id, technician_schedules.techid, technicians.initials AS techname, technician_schedules.baynum, technician_schedules.JONumber,
                                    technician_schedules.scheddate, technician_schedules.scopeofwork, technician_schedules.activity, technician_schedules.status as TSStatus,
                                    technicians.initials, bay_areas.area_name
                                    FROM technician_schedules 
                                    INNER JOIN technicians on techid=technicians.id
                                    INNER JOIN bay_areas on baynum=bay_areas.id
                                    WHERE techid = ? AND scheddate BETWEEN ? AND ?', [$request->techname0, $request->fromDateS, $request->toDateS]
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

    public function getJONum(Request $request){
        $result = '';

        $WSID = DB::SELECT('SELECT unit_workshops.id as WSID, unit_workshops.WSPOUID FROM unit_workshops WHERE WSStatus<=4 AND WSBayNum=?', [$request->TSBayNum] );

        foreach ($WSID as $WS) {
            // $result .= '<option value="'.$bays->id.'">'.$bays->id.'</option>';
            $result = array(
                'WSID' => $WS->WSID,
                'POUID' => $WS->WSPOUID,
            );
            
            // $WS->WSID;

        }
        // echo $result;
        return response()->json($result);
    }

    public function getEvents(Request $request){
        $events = DB::table('technician_schedules')
                    ->select('baynum', 'area_name','activity', 'scheddate','technician_schedules.status')
                    // ->where('status', '=', 1)
                    // ->where('baynum', '=', $request->bay)
                    ->leftJoin('bay_areas','technician_schedules.baynum','=','bay_areas.id')
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
        $activeBayNames = DB::table('bay_areas')
            ->select('area_name')
            ->where('status', '=', 1)
            ->get()
            ->pluck('area_name');

        return response()->json($activeBayNames);
    }
}
