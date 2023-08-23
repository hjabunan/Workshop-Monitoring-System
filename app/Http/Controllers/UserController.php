<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $users = DB::select('SELECT wms_users.id, wms_users.name, wms_users.email, wms_users.idnum, wms_users.email_verified_at, wms_users.dept, 
        departments.name AS deptname, wms_users.area, wms_users.password, wms_users.role, wms_users.status, sections.name as sname
        FROM wms_users 
        LEFT JOIN sections ON sections.id = wms_users.area
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
        $user->save();

        return redirect()->route('user.index');
    }

    public function edit($id){

        $user = User::find($id);
        $selectedAreas = explode(',', $user->area);

        $users = DB::table('wms_users')->where('id', $id)->first();

        $depts = DB::select('SELECT * FROM departments');
        $sects = DB::select('SELECT * FROM sections');
        
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
            $user->area = implode(',', $request->input('area', []));
            $user->update();
        }else{
            $user = User::find($id);
            $user->name = $request->uname;
            $user->idnum = $request->idnum;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->dept = $request->dept;
            $user->area = implode(',', $request->area);
            $user->password = Hash::make($request->password);
            $user->update();
        }

        return redirect()->route('user.index');
    }
}
