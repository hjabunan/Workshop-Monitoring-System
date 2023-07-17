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
        $users = DB::select('SELECT users.id, users.name, users.email, users.idnum, users.email_verified_at, users.dept, 
        departments.name AS deptname, users.area, users.password, users.role, users.status, sections.name as sname
        FROM users 
        LEFT JOIN sections ON sections.id = users.area
        INNER JOIN departments ON users.dept = departments.id');

        $sections = Section::pluck('name', 'id');   

        //$dept = DB::select('select * from departments where id=?,' [1]);
        //$depts =DB::table('departments')->select('id','name')->get();
        return view('system-management.user.index', compact('users', 'sections'));
    }

    public function create()
    {
        $depts = DB::select('select id, name from departments where status=1');
        $sections = Section::pluck('name', 'id');   
        return view('system-management.user.add',compact('depts','sections'));
    }

    public function store(Request $request)
    {
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
        $user->area = $request->area;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        $users = DB::table('users')->where('id', $id)->first();
        $depts = DB::select('SELECT * FROM departments');
        $sects = DB::select('SELECT * FROM sections');
        //$depts = DB::table('departments')->where('id', $id)->first();
        return view('system-management.user.edit',compact('users','depts', 'sects'));
    }

    public function update(Request $request, $id)
    {
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
        $user->area = $request->area;
        $user->password = Hash::make($request->password);
        $user->update();

        return redirect()->route('user.index');
    }
}
