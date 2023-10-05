<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\ActivityLog;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $users = DB::select('SELECT wms_users.id, wms_users.name, wms_users.email, wms_users.idnum, wms_users.email_verified_at, wms_users.dept, 
        departments.name AS deptname, wms_users.area, wms_users.password, wms_users.role, wms_users.status, wms_sections.name as sname
        FROM wms_users 
        LEFT JOIN wms_sections ON wms_sections.id = wms_users.area
        INNER JOIN departments ON wms_users.dept = departments.id');

        $sections = Section::pluck('name', 'id');   

        return view('system-management.user.index', compact('users', 'sections'));
    }

    public function create(){
        $depts = DB::select('select id, name from departments where status=1');
        $sections = Section::pluck('name', 'id');   
        return view('system-management.user.add',compact('depts','sections'));
    }

    public function store(Request $request){
        $user = $request->validate([
            'uname' => 'required',
            'email' => 'required',
            'idnum' => 'required',
            'dept' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        $user = new User;
        $user->name = $request->uname;
        $user->idnum = $request->idnum;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->dept = $request->dept;
        $user->area = implode(',', $request->input('area', []));
        $user->password = Hash::make($request->password);

        // $dirtyAttributes = $user->getDirty();
        //     foreach ($dirtyAttributes as $attribute => $newValue) {
        //         $field = ucwords(str_replace('_', ' ', $attribute));
        //         $newValue = $user->getAttribute($attribute);  // Use getAttribute to get the new value
        //             if($attribute == "password"){
        //                 $newValue = "";
        //             }
                
        //         $newLog = new ActivityLog();
        //         $newLog->table = 'UserTable';
        //         $newLog->table_key = $user->idnum;  // You need to set $id before this loop
        //         $newLog->action = 'ADD';   // Changed action to 'ADD'
        //         $newLog->description = $user->name;
        //         $newLog->field = $field;
        //         $newLog->before = null;    // No "before" value for add action
        //         $newLog->after = $newValue;
        //         $newLog->user_id = Auth::user()->id;
        //         $newLog->save();
        //     }
        $user->save();

        return redirect()->route('user.index');
    }

    public function edit($id){

        $user = User::find($id);
        $selectedAreas = explode(',', $user->area);

        $users = DB::table('wms_users')->where('id', $id)->first();

        $depts = DB::select('SELECT * FROM departments where status=1');
        $sects = DB::select('SELECT * FROM wms_sections');
        
        return view('system-management.user.edit',compact('selectedAreas','users','depts', 'sects'));
    }

    public function update(Request $request, $id){
        $user = $request->validate([
            'uname' => 'required',
            'email' => 'required',
            'idnum' => 'required',
            'dept' => 'required',
            'role' => 'required',
            'area' => 'required',
        ]);

        if($request->password ==""){
            $user = User::find($id);
            $user->name = $request->uname;
            $user->idnum = $request->idnum;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->dept = $request->dept;
            $user->status = $request->ustatus;
            $user->area = implode(',', $request->input('area', []));
        }else{
            $user = User::find($id);
            $user->name = $request->uname;
            $user->idnum = $request->idnum;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->dept = $request->dept;
            $user->status = $request->ustatus;
            $user->area = implode(',', $request->area);
            $user->password = Hash::make($request->password);
        }

            $dirtyAttributes = $user->getDirty();
        
            foreach($dirtyAttributes as $attribute => $newValue){
                $oldValue = $user->getOriginal($attribute);
    
                $field = ucwords(str_replace('_', ' ', $attribute));
            
                if($attribute == "password"){
                    $oldValue = "";
                    $newValue = "";
                }

                $newLog = new ActivityLog();
                $newLog->table = 'UserTable';
                $newLog->table_key = $id;
                $newLog->action = 'UPDATE';
                $newLog->description = $user->name;
                $newLog->field = $field;
                $newLog->before = $oldValue;
                $newLog->after = $newValue;
                $newLog->user_id = Auth::user()->id;
                $newLog->save();
            }

            $user->update();

        return redirect()->route('user.index');
    }
}
