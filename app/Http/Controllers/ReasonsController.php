<?php

namespace App\Http\Controllers;

use App\Models\Reasons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReasonsController extends Controller
{
    public function index(){
        $reason = DB::table('wms_reasons')
                    ->get();

        $search = '';
        return view('system-management.reason.index', compact('reason','search'));
    }

    public function create()
    {
        return view('system-management.reason.add');
    }

    public function store(Request $request)
    {
        $reasonData = $request->validate([
            'name' => 'required',
        ]);
    
        $reason = new Reasons();
        $reason->reason_name = strtoupper($reasonData['name']);
    
            $words = explode(' ', $reasonData['name']);
            $newreasonCode = '';
            $testreasonCode = '';
            foreach ($words as $word) {
                if($word != end($words)){
                    $newreasonCode .= strtoupper(substr($word, 0, 1));
                }
                $testreasonCode .= strtoupper(substr($word, 0, 1));
            }
            $testreasonCode2 = $testreasonCode;

            $x = 1;
            $lastWord = end($words);
            $lastCode = strtoupper(substr($lastWord, 0, $x));
            $testreasonCode2 = $newreasonCode.$lastCode;
            do{
                $x++;
                $existingReason = Reasons::where('reason_code', 'DT' .$testreasonCode2)->first();
                if ($existingReason) {
                    // Append the first 2 letters of the last word
                    $lastWord = end($words);
                    $lastCode = strtoupper(substr($lastWord, 0, $x));
                    $testreasonCode2 = $newreasonCode.$lastCode;
                }else{
                    break;
                }
            }while(true);

        $reason->reason_code = 'DT' .$newreasonCode.$lastCode;
        $reason->save();

        return redirect()->route('reason.index');
    }

    public function edit($id)
    {
        $reason = DB::table('wms_reasons')->where('id', $id)->first();
        return view('system-management.reason.edit',compact('reason'));
    }

    public function update(Request $request, $id)
    {
        $reasonData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        $reason = Reasons::find($id);
        $reason->reason_name = strtoupper($reasonData['name']);
    
            $words = explode(' ', $reasonData['name']);
            $newreasonCode = '';
            $testreasonCode = '';
            foreach ($words as $word) {
                if($word != end($words)){
                    $newreasonCode .= strtoupper(substr($word, 0, 1));
                }
                $testreasonCode .= strtoupper(substr($word, 0, 1));
            }

            $testreasonCode2 = $testreasonCode;
        
            $x = 1;
            $lastWord = end($words);
            $lastCode = strtoupper(substr($lastWord, 0, $x));
            $testreasonCode2 = $newreasonCode.$lastCode;
            do{
                $x++;
                $existingReason = Reasons::where('reason_code', 'DT' .$testreasonCode2)->first();
                if ($existingReason) {
                    $lastWord = end($words);
                    $lastCode = strtoupper(substr($lastWord, 0, $x));
                    $testreasonCode2 = $newreasonCode.$lastCode;
                }else{
                    break;
                }
            }while(true);

        $reason->reason_code = 'DT' .$newreasonCode.$lastCode;
        $reason->reason_status = $reasonData['status'];
        $reason->update();

        return redirect()->route('reason.index');
    }

    public function search($search){
        $reason = DB::table('wms_reasons')
                    ->whereRaw("CONCAT_WS(' ', reason_name, reason_status) LIKE '%{$search}%'")->get();
                    
        return view('system-management.reason.index', compact('search','reason'));
    }
}
