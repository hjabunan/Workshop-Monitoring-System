<?php

namespace App\Http\Controllers;

use App\Models\SystemManagement;
use App\Http\Requests\StoreSystemManagementRequest;
use App\Http\Requests\UpdateSystemManagementRequest;
//use App\Http\Controllers\Request;

class SystemManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('system-management.user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSystemManagementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSystemManagementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemManagement  $systemManagement
     * @return \Illuminate\Http\Response
     */
    public function show(SystemManagement $systemManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemManagement  $systemManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemManagement $systemManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSystemManagementRequest  $request
     * @param  \App\Models\SystemManagement  $systemManagement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSystemManagementRequest $request, SystemManagement $systemManagement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemManagement  $systemManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemManagement $systemManagement)
    {
        //
    }
}
