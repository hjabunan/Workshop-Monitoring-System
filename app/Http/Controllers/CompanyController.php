<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(){
        $company = DB::select('SELECT companies.id, companies.name, companies.address, companies.status, customer_areas.custarea as custarea FROM companies INNER JOIN customer_areas on companies.area = customer_areas.id');
        // $custareaz = DB::select('SELECT * FROM customer_areas');

        return view('system-management.company.index', compact('company'));
    }

    public function create()
    {
        $custareaz = DB::select('SELECT * FROM customer_areas');

        return view('system-management.company.add',compact('custareaz'));
    }

    public function store(Request $request)
    {
        $company = $request->validate([
            'name'=>'required',
            'address'=>'required',
            'area'=>'required'
        ]);
        $company = new Company();
        $company->name = strtoupper($request->name);
        $company->address = strtoupper($request->address);
        $company->area = strtoupper($request->area);
        $company->save();
        return redirect()->route('company.index');
    }

    public function edit($id)
    {
        $company = DB::table('companies')->where('id', $id)->first();
        $custareaz = DB::select('SELECT * FROM customer_areas');
        return view('system-management.company.edit',compact('company','custareaz'));
    }

    public function update(Request $request, $id)
    {
        $company = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'area'=>'required',
            'status' => 'required'
        ]);

        $company = Company::find($id);
        $company->name = strtoupper($request->name);
        $company->address = strtoupper($request->address);
        $company->area = strtoupper($request->area);
        $company->status = $request->status;
        $company->update();

        return redirect()->route('company.index');
    }
}
