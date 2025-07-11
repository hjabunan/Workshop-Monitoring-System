<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityLogsController;
use App\Http\Controllers\AdminMonitor;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BayAreaController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BTReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerAreaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MastController;
use App\Http\Controllers\MCIController;
use App\Http\Controllers\OtherReportController;
use App\Http\Controllers\OVHLReportController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\PDIController;
use App\Http\Controllers\PPTReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReasonsController;
use App\Http\Controllers\RReportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StagingController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TechnicianScheduleController;
use App\Http\Controllers\TReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WStorage1Controller;
use App\Http\Controllers\WStorage5BController;
use App\Http\Controllers\WStorage5CController;
use App\Http\Controllers\WStorage6Controller;
use App\Http\Controllers\WStorage7Controller;
use App\Http\Controllers\WStorage8Controller;
use App\Http\Controllers\XModelController;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
->name('logout');

Route::redirect(uri:'/', destination:'login');

Route::GET('/layouts/navigation/getUserData', [UserController::class, 'getUserData'])->name('layouts.navigation.getUserData');

Route::get('/navigation', function () {
    $sections = DB::table('wms_sections')->get();
    $areas = DB::table('area_tables')->get();

    return view('/navigation', compact('areas', 'sections'));
});

Route::get('/dashboard', function () {
    $areas = DB::table('area_tables')->get();
    $sections = DB::table('wms_sections')->get();

    
    $user = User::find(auth()->id());
    $areax = $user->area;

    return view('dashboard', compact('areas','areax'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::GET('/dashboard/getSName', [BTReportController::class, 'getEvents'])->name('bt-workshop.getEvents');

Route::get('/editor-area/{tab}', function ($tab) {
    $sections = DB::table('wms_sections')->get();
    $areas = DB::table('area_tables')->get();

    return view('editor-area', compact('areas', 'sections', 'tab'));
});

// Route::GET('/system-management/user/searchUser', [UserController::class, 'searchUser'])->name('system-management.user.searchUser');

Route::GET('/get-sname', [DashboardController::class, 'getSName'])->name('dashboard.getSName');


    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::POST('/area/add', [AreaController::class, 'add'])->name('area.add');
        Route::GET('/area/edit/{tab}/{id}', [AreaController::class, 'edit']);
        Route::POST('/area/delete', [AreaController::class, 'delete'])->name('area.delete');
        Route::POST('/area/updateC', [AreaController::class, 'updateC'])->name('area.updateC');
        Route::POST('/area/update/{id}', [AreaController::class, 'update']);
    });

// START OF WORKSHOP MONITORING SYSTEM
    // START OF BT WORKSHOP > MONITORING
        Route::GET('/workshop-ms/bt-workshop', [BTReportController::class, 'index'])->name('bt-workshop.index');
        // Unit Info
            Route::GET('/workshop-ms/bt-workshop/getEvents', [BTReportController::class, 'getEvents'])->name('bt-workshop.getEvents');
            Route::GET('/workshop-ms/bt-workshop/getBayData', [BTReportController::class, 'getBayData'])->name('bt-workshop.getBayData');
            Route::POST('/workshop-ms/bt-workshop/saveBayData', [BTReportController::class, 'saveBayData'])->name('bt-workshop.saveBayData');
            Route::POST('/workshop-ms/bt-workshop/saveTargetActivity', [BTReportController::class, 'saveTargetActivity'])->name('bt-workshop.saveTargetActivity');
            Route::POST('/workshop-ms/bt-workshop/resetActual', [BTReportController::class, 'resetActual'])->name('bt-workshop.resetActual');
            Route::POST('/workshop-ms/bt-workshop/updateIDS', [BTReportController::class, 'updateIDS'])->name('bt-workshop.updateIDS');
            Route::POST('/workshop-ms/bt-workshop/updateIDE', [BTReportController::class, 'updateIDE'])->name('bt-workshop.updateIDE');
            Route::POST('/workshop-ms/bt-workshop/updateRDS', [BTReportController::class, 'updateRDS'])->name('bt-workshop.updateRDS');
            Route::POST('/workshop-ms/bt-workshop/updateRDE', [BTReportController::class, 'updateRDE'])->name('bt-workshop.updateRDE');
            Route::GET('/workshop-ms/bt-workshop/getTransferData', [BTReportController::class, 'getTransferData'])->name('bt-workshop.getTransferData');
            Route::POST('/workshop-ms/bt-workshop/saveTransferUnit', [BTReportController::class, 'saveTransferUnit'])->name('bt-workshop.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/bt-workshop/saveDowntime', [BTReportController::class, 'saveDowntime'])->name('bt-workshop.saveDowntime');
            Route::POST('/workshop-ms/bt-workshop/getDowntime', [BTReportController::class, 'getDowntime'])->name('bt-workshop.getDowntime');
            Route::POST('/workshop-ms/bt-workshop/deleteDowntime', [BTReportController::class, 'deleteDowntime'])->name('bt-workshop.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/bt-workshop/getPI', [BTReportController::class, 'getPI'])->name('bt-workshop.getPI');
            Route::POST('/workshop-ms/bt-workshop/savePI', [BTReportController::class, 'savePI'])->name('bt-workshop.savePI');
            Route::POST('/workshop-ms/bt-workshop/getPInfo', [BTReportController::class, 'getPInfo'])->name('bt-workshop.getPInfo');
            Route::POST('/workshop-ms/bt-workshop/deletePI', [BTReportController::class, 'deletePI'])->name('bt-workshop.deletePI');
            Route::POST('/workshop-ms/bt-workshop/installPI', [BTReportController::class, 'installPI'])->name('bt-workshop.installPI');
            Route::POST('/workshop-ms/bt-workshop/revertParts', [BTReportController::class, 'revertParts'])->name('bt-workshop.revertParts');
            Route::POST('/workshop-ms/bt-workshop/deleteIParts', [BTReportController::class, 'deleteIParts'])->name('bt-workshop.deleteIParts');
            Route::POST('/workshop-ms/bt-workshop/saveRemarks', [BTReportController::class, 'saveRemarks'])->name('bt-workshop.saveRemarks');
            Route::GET('/workshop-ms/bt-workshop/search', [BTReportController::class, 'search'])->name('bt-workshop.search');
            Route::POST('/workshop-ms/bt-workshop/getPartsInfox', [BTReportController::class, 'getPartsInfox'])->name('bt-workshop.getPartsInfox');

        // Technician Schedule
            Route::POST('/workshop-ms/bt-workshop/viewSchedule', [BTReportController::class, 'viewSchedule'])->name('bt-workshop.viewSchedule');
            Route::POST('/workshop-ms/bt-workshop/saveActivity', [BTReportController::class, 'saveActivity'])->name('bt-workshop.saveActivity');
            Route::POST('/workshop-ms/bt-workshop/saveTActivity', [BTReportController::class, 'saveTActivity'])->name('bt-workshop.saveTActivity');

            Route::GET('/workshop-ms/bt-workshop/report', [BTReportController::class, 'indexR'])->name('bt-workshop.report');

        // Workshop
            Route::GET('/workshop-ms/bt-workshop/report/sortBrand', [BTReportController::class, 'sortBrand'])->name('bt-workshop.report.sortBrand');

        // Report
            Route::GET('/workshop-ms/bt-workshop/report/getBayR', [BTReportController::class, 'getBayR'])->name('bt-workshop.report.getBayR');
            Route::POST('/workshop-ms/bt-workshop/report/generateBrandReport', [BTReportController::class, 'generateBrandReport'])->name('bt-workshop.report.generateBrandReport');
            Route::POST('/workshop-ms/bt-workshop/report/generateBayReport', [BTReportController::class, 'generateBayReport'])->name('bt-workshop.report.generateBayReport');
            Route::POST('/workshop-ms/bt-workshop/report/searchRPU', [BTReportController::class, 'searchRPU'])->name('bt-workshop.report.searchRPU');
            Route::POST('/workshop-ms/bt-workshop/report/generateUnitReport', [BTReportController::class, 'generateUnitReport'])->name('bt-workshop.report.generateUnitReport');
            Route::POST('/workshop-ms/bt-workshop/report/generateUnitRecord', [BTReportController::class, 'generateUnitRecord'])->name('bt-workshop.report.generateUnitRecord');
            Route::POST('/workshop-ms/bt-workshop/report/generatePOUReport', [BTReportController::class, 'generatePOUReport'])->name('bt-workshop.report.generatePOUReport');
            Route::POST('/workshop-ms/bt-workshop/report/generateDUReport', [BTReportController::class, 'generateDUReport'])->name('bt-workshop.report.generateDUReport');
            Route::POST('/workshop-ms/bt-workshop/report/generateCanUnitReport', [BTReportController::class, 'generateCanUnitReport'])->name('bt-workshop.report.generateCanUnitReport');
            Route::POST('/workshop-ms/bt-workshop/report/generateDRMonReport', [BTReportController::class, 'generateDRMonReport'])->name('bt-workshop.report.generateDRMonReport');

        // Brand New Unit
            Route::POST('/workshop-ms/bt-workshop/report/saveBrandNew', [BTReportController::class, 'saveBrandNew'])->name('bt-workshop.report.saveBrandNew');
            Route::GET('/workshop-ms/bt-workshop/report/getBNUData', [BTReportController::class, 'getBNUData'])->name('bt-workshop.report.getBNUData');
            Route::POST('/workshop-ms/bt-workshop/report/deleteBNU', [BTReportController::class, 'deleteBNU'])->name('bt-workshop.report.deleteBNU');
            Route::POST('/workshop-ms/bt-workshop/report/transferNewUnit', [BTReportController::class, 'transferNewUnit'])->name('bt-workshop.report.transferNewUnit');

        // Pull Out Unit
            Route::POST('/workshop-ms/bt-workshop/report/savePullOut', [BTReportController::class, 'savePullOut'])->name('bt-workshop.report.savePullOut');
            Route::GET('/workshop-ms/bt-workshop/report/getPOUData', [BTReportController::class, 'getPOUData'])->name('bt-workshop.report.getPOUData');
            Route::POST('/workshop-ms/bt-workshop/report/deletePOU', [BTReportController::class, 'deletePOU'])->name('bt-workshop.report.deletePOU');
            Route::GET('/workshop-ms/bt-workshop/report/getBay', [BTReportController::class, 'getBay'])->name('bt-workshop.report.getBay');
            Route::POST('/workshop-ms/bt-workshop/report/transferPullOut', [BTReportController::class, 'transferPullOut'])->name('bt-workshop.report.transferPullOut');
            Route::GET('/workshop-ms/bt-workshop/report/getUnitStatus', [BTReportController::class, 'getUnitStatus'])->name('bt-workshop.report.getUnitStatus');
            Route::GET('/workshop-ms/bt-workshop/report/sortPullOut', [BTReportController::class, 'sortPullOut'])->name('bt-workshop.report.sortPullOut');
    
        // Confirm Unit
            Route::POST('/workshop-ms/bt-workshop/report/deleteCU', [BTReportController::class, 'deleteCU'])->name('bt-workshop.report.deleteCU');
            Route::GET('/workshop-ms/bt-workshop/report/sortConfirm', [BTReportController::class, 'sortConfirm'])->name('bt-workshop.report.sortConfirm');

        // Delivered Unit
            Route::POST('/workshop-ms/bt-workshop/report/deleteDU', [BTReportController::class, 'deleteDU'])->name('bt-workshop.report.deleteDU');
    
    // START OF T WORKSHOP > MONITORING
        Route::GET('/workshop-ms/t-workshop', [TReportController::class, 'index'])->name('t-workshop.index');
        // Unit Info
            Route::GET('/workshop-ms/t-workshop/getEvents', [TReportController::class, 'getEvents'])->name('t-workshop.getEvents');
            Route::GET('/workshop-ms/t-workshop/getBayData', [TReportController::class, 'getBayData'])->name('t-workshop.getBayData');
            Route::POST('/workshop-ms/t-workshop/saveBayData', [TReportController::class, 'saveBayData'])->name('t-workshop.saveBayData');
            Route::POST('/workshop-ms/t-workshop/saveTargetActivity', [TReportController::class, 'saveTargetActivity'])->name('t-workshop.saveTargetActivity');
            Route::POST('/workshop-ms/t-workshop/resetActual', [TReportController::class, 'resetActual'])->name('t-workshop.resetActual');
            Route::POST('/workshop-ms/t-workshop/updateIDS', [TReportController::class, 'updateIDS'])->name('t-workshop.updateIDS');
            Route::POST('/workshop-ms/t-workshop/updateIDE', [TReportController::class, 'updateIDE'])->name('t-workshop.updateIDE');
            Route::POST('/workshop-ms/t-workshop/updateRDS', [TReportController::class, 'updateRDS'])->name('t-workshop.updateRDS');
            Route::POST('/workshop-ms/t-workshop/updateRDE', [TReportController::class, 'updateRDE'])->name('t-workshop.updateRDE');
            Route::GET('/workshop-ms/t-workshop/getTransferData', [TReportController::class, 'getTransferData'])->name('t-workshop.getTransferData');
            Route::POST('/workshop-ms/t-workshop/saveTransferUnit', [TReportController::class, 'saveTransferUnit'])->name('t-workshop.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/t-workshop/saveDowntime', [TReportController::class, 'saveDowntime'])->name('t-workshop.saveDowntime');
            Route::POST('/workshop-ms/t-workshop/getDowntime', [TReportController::class, 'getDowntime'])->name('t-workshop.getDowntime');
            Route::POST('/workshop-ms/t-workshop/deleteDowntime', [TReportController::class, 'deleteDowntime'])->name('t-workshop.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/t-workshop/getPI', [TReportController::class, 'getPI'])->name('t-workshop.getPI');
            Route::POST('/workshop-ms/t-workshop/savePI', [TReportController::class, 'savePI'])->name('t-workshop.savePI');
            Route::POST('/workshop-ms/t-workshop/getPInfo', [TReportController::class, 'getPInfo'])->name('t-workshop.getPInfo');
            Route::POST('/workshop-ms/t-workshop/deletePI', [TReportController::class, 'deletePI'])->name('t-workshop.deletePI');
            Route::POST('/workshop-ms/t-workshop/installPI', [TReportController::class, 'installPI'])->name('t-workshop.installPI');
            Route::POST('/workshop-ms/t-workshop/revertParts', [TReportController::class, 'revertParts'])->name('t-workshop.revertParts');
            Route::POST('/workshop-ms/t-workshop/deleteIParts', [TReportController::class, 'deleteIParts'])->name('t-workshop.deleteIParts');
            Route::POST('/workshop-ms/t-workshop/saveRemarks', [TReportController::class, 'saveRemarks'])->name('t-workshop.saveRemarks');
            Route::GET('/workshop-ms/t-workshop/search', [TReportController::class, 'search'])->name('t-workshop.search');
            Route::POST('/workshop-ms/t-workshop/getPartsInfox', [TReportController::class, 'getPartsInfox'])->name('t-workshop.getPartsInfox');

        // Technician Schedule
            Route::POST('/workshop-ms/t-workshop/viewSchedule', [TReportController::class, 'viewSchedule'])->name('t-workshop.viewSchedule');
            Route::POST('/workshop-ms/t-workshop/saveActivity', [TReportController::class, 'saveActivity'])->name('t-workshop.saveActivity');
            Route::POST('/workshop-ms/t-workshop/saveTActivity', [TReportController::class, 'saveTActivity'])->name('t-workshop.saveTActivity');

            Route::GET('/workshop-ms/t-workshop/report', [TReportController::class, 'indexR'])->name('t-workshop.report');

        // Workshop
            Route::GET('/workshop-ms/t-workshop/report/sortBrand', [TReportController::class, 'sortBrand'])->name('t-workshop.report.sortBrand');

        // Report
            Route::GET('/workshop-ms/t-workshop/report/getBayR', [TReportController::class, 'getBayR'])->name('t-workshop.report.getBayR');
            Route::POST('/workshop-ms/t-workshop/report/generateBrandReport', [TReportController::class, 'generateBrandReport'])->name('t-workshop.report.generateBrandReport');
            Route::POST('/workshop-ms/t-workshop/report/generateBayReport', [TReportController::class, 'generateBayReport'])->name('t-workshop.report.generateBayReport');
            Route::POST('/workshop-ms/t-workshop/report/searchRPU', [TReportController::class, 'searchRPU'])->name('t-workshop.report.searchRPU');
            Route::POST('/workshop-ms/t-workshop/report/generateUnitReport', [TReportController::class, 'generateUnitReport'])->name('t-workshop.report.generateUnitReport');
            Route::POST('/workshop-ms/t-workshop/report/generateUnitRecord', [TReportController::class, 'generateUnitRecord'])->name('t-workshop.report.generateUnitRecord');
            Route::POST('/workshop-ms/t-workshop/report/generatePOUReport', [TReportController::class, 'generatePOUReport'])->name('t-workshop.report.generatePOUReport');
            Route::POST('/workshop-ms/t-workshop/report/generateDUReport', [TReportController::class, 'generateDUReport'])->name('t-workshop.report.generateDUReport');
            Route::POST('/workshop-ms/t-workshop/report/generateCanUnitReport', [TReportController::class, 'generateCanUnitReport'])->name('t-workshop.report.generateCanUnitReport');
            Route::POST('/workshop-ms/t-workshop/report/generateDRMonReport', [TReportController::class, 'generateDRMonReport'])->name('t-workshop.report.generateDRMonReport');

        // Brand New Unit
            Route::POST('/workshop-ms/t-workshop/report/saveBrandNew', [TReportController::class, 'saveBrandNew'])->name('t-workshop.report.saveBrandNew');
            Route::GET('/workshop-ms/t-workshop/report/getBNUData', [TReportController::class, 'getBNUData'])->name('t-workshop.report.getBNUData');
            Route::POST('/workshop-ms/t-workshop/report/deleteBNU', [TReportController::class, 'deleteBNU'])->name('t-workshop.report.deleteBNU');
            Route::POST('/workshop-ms/t-workshop/report/transferNewUnit', [TReportController::class, 'transferNewUnit'])->name('t-workshop.report.transferNewUnit');

        // Pull Out Unit
            Route::POST('/workshop-ms/t-workshop/report/savePullOut', [TReportController::class, 'savePullOut'])->name('t-workshop.report.savePullOut');
            Route::GET('/workshop-ms/t-workshop/report/getPOUData', [TReportController::class, 'getPOUData'])->name('t-workshop.report.getPOUData');
            Route::POST('/workshop-ms/t-workshop/report/deletePOU', [TReportController::class, 'deletePOU'])->name('t-workshop.report.deletePOU');
            Route::GET('/workshop-ms/t-workshop/report/getBay', [TReportController::class, 'getBay'])->name('t-workshop.report.getBay');
            Route::POST('/workshop-ms/t-workshop/report/transferPullOut', [TReportController::class, 'transferPullOut'])->name('t-workshop.report.transferPullOut');
            Route::GET('/workshop-ms/t-workshop/report/sortPullOut', [TReportController::class, 'sortPullOut'])->name('t-workshop.report.sortPullOut');
        
        // Confirm Unit
            Route::POST('/workshop-ms/t-workshop/report/deleteCU', [TReportController::class, 'deleteCU'])->name('t-workshop.report.deleteCU');
            Route::GET('/workshop-ms/t-workshop/report/sortConfirm', [TReportController::class, 'sortConfirm'])->name('t-workshop.report.sortConfirm');

        // Delivered Unit
            Route::POST('/workshop-ms/t-workshop/report/deleteDU', [TReportController::class, 'deleteDU'])->name('t-workshop.report.deleteDU');


    // START OF R WORKSHOP > MONITORING
        Route::GET('/workshop-ms/r-workshop', [RReportController::class, 'index'])->name('r-workshop.index');
        // Unit Info
            Route::GET('/workshop-ms/r-workshop/getEvents', [RReportController::class, 'getEvents'])->name('r-workshop.getEvents');
            Route::GET('/workshop-ms/r-workshop/getBayData', [RReportController::class, 'getBayData'])->name('r-workshop.getBayData');
            Route::POST('/workshop-ms/r-workshop/saveBayData', [RReportController::class, 'saveBayData'])->name('r-workshop.saveBayData');
            Route::POST('/workshop-ms/r-workshop/saveTargetActivity', [RReportController::class, 'saveTargetActivity'])->name('r-workshop.saveTargetActivity');
            Route::POST('/workshop-ms/r-workshop/resetActual', [RReportController::class, 'resetActual'])->name('r-workshop.resetActual');
            Route::POST('/workshop-ms/r-workshop/updateIDS', [RReportController::class, 'updateIDS'])->name('r-workshop.updateIDS');
            Route::POST('/workshop-ms/r-workshop/updateIDE', [RReportController::class, 'updateIDE'])->name('r-workshop.updateIDE');
            Route::POST('/workshop-ms/r-workshop/updateRDS', [RReportController::class, 'updateRDS'])->name('r-workshop.updateRDS');
            Route::POST('/workshop-ms/r-workshop/updateRDE', [RReportController::class, 'updateRDE'])->name('r-workshop.updateRDE');
            Route::GET('/workshop-ms/r-workshop/getTransferData', [RReportController::class, 'getTransferData'])->name('r-workshop.getTransferData');
            Route::POST('/workshop-ms/r-workshop/saveTransferUnit', [RReportController::class, 'saveTransferUnit'])->name('r-workshop.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/r-workshop/saveDowntime', [RReportController::class, 'saveDowntime'])->name('r-workshop.saveDowntime');
            Route::POST('/workshop-ms/r-workshop/getDowntime', [RReportController::class, 'getDowntime'])->name('r-workshop.getDowntime');
            Route::POST('/workshop-ms/r-workshop/deleteDowntime', [RReportController::class, 'deleteDowntime'])->name('r-workshop.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/r-workshop/getPI', [RReportController::class, 'getPI'])->name('r-workshop.getPI');
            Route::POST('/workshop-ms/r-workshop/savePI', [RReportController::class, 'savePI'])->name('r-workshop.savePI');
            Route::POST('/workshop-ms/r-workshop/getPInfo', [RReportController::class, 'getPInfo'])->name('r-workshop.getPInfo');
            Route::POST('/workshop-ms/r-workshop/deletePI', [RReportController::class, 'deletePI'])->name('r-workshop.deletePI');
            Route::POST('/workshop-ms/r-workshop/installPI', [RReportController::class, 'installPI'])->name('r-workshop.installPI');
            Route::POST('/workshop-ms/r-workshop/revertParts', [RReportController::class, 'revertParts'])->name('r-workshop.revertParts');
            Route::POST('/workshop-ms/r-workshop/deleteIParts', [RReportController::class, 'deleteIParts'])->name('r-workshop.deleteIParts');
            Route::POST('/workshop-ms/r-workshop/saveRemarks', [RReportController::class, 'saveRemarks'])->name('r-workshop.saveRemarks');
            Route::GET('/workshop-ms/r-workshop/search', [RReportController::class, 'search'])->name('r-workshop.search');
            Route::POST('/workshop-ms/r-workshop/getPartsInfox', [RReportController::class, 'getPartsInfox'])->name('r-workshop.getPartsInfox');
        // Technician Schedule
            Route::POST('/workshop-ms/r-workshop/viewSchedule', [RReportController::class, 'viewSchedule'])->name('r-workshop.viewSchedule');
            Route::POST('/workshop-ms/r-workshop/saveActivity', [RReportController::class, 'saveActivity'])->name('r-workshop.saveActivity');
            Route::POST('/workshop-ms/r-workshop/saveTActivity', [RReportController::class, 'saveTActivity'])->name('r-workshop.saveTActivity');

            Route::GET('/workshop-ms/r-workshop/report', [RReportController::class, 'indexR'])->name('r-workshop.report');

        // Workshop
            Route::GET('/workshop-ms/r-workshop/report/sortBrand', [RReportController::class, 'sortBrand'])->name('r-workshop.report.sortBrand');

        // Report
            Route::GET('/workshop-ms/r-workshop/report/getBayR', [RReportController::class, 'getBayR'])->name('r-workshop.report.getBayR');
            Route::POST('/workshop-ms/r-workshop/report/generateBrandReport', [RReportController::class, 'generateBrandReport'])->name('r-workshop.report.generateBrandReport');
            Route::POST('/workshop-ms/r-workshop/report/generateBayReport', [RReportController::class, 'generateBayReport'])->name('r-workshop.report.generateBayReport');
            Route::POST('/workshop-ms/r-workshop/report/searchRPU', [RReportController::class, 'searchRPU'])->name('r-workshop.report.searchRPU');
            Route::POST('/workshop-ms/r-workshop/report/generateUnitReport', [RReportController::class, 'generateUnitReport'])->name('r-workshop.report.generateUnitReport');
            Route::POST('/workshop-ms/r-workshop/report/generateUnitRecord', [RReportController::class, 'generateUnitRecord'])->name('r-workshop.report.generateUnitRecord');
            Route::POST('/workshop-ms/r-workshop/report/generatePOUReport', [RReportController::class, 'generatePOUReport'])->name('r-workshop.report.generatePOUReport');
            Route::POST('/workshop-ms/r-workshop/report/generateDUReport', [RReportController::class, 'generateDUReport'])->name('r-workshop.report.generateDUReport');
            Route::POST('/workshop-ms/r-workshop/report/generateCanUnitReport', [RReportController::class, 'generateCanUnitReport'])->name('r-workshop.report.generateCanUnitReport');
            Route::POST('/workshop-ms/r-workshop/report/generateDRMonReport', [RReportController::class, 'generateDRMonReport'])->name('r-workshop.report.generateDRMonReport');


        // Brand New Unit
            Route::POST('/workshop-ms/r-workshop/report/saveBrandNew', [RReportController::class, 'saveBrandNew'])->name('r-workshop.report.saveBrandNew');
            Route::GET('/workshop-ms/r-workshop/report/getBNUData', [RReportController::class, 'getBNUData'])->name('r-workshop.report.getBNUData');
            Route::POST('/workshop-ms/r-workshop/report/deleteBNU', [RReportController::class, 'deleteBNU'])->name('r-workshop.report.deleteBNU');
            Route::POST('/workshop-ms/r-workshop/report/transferNewUnit', [RReportController::class, 'transferNewUnit'])->name('r-workshop.report.transferNewUnit');

        // Pull Out Unit
            Route::POST('/workshop-ms/r-workshop/report/savePullOut', [RReportController::class, 'savePullOut'])->name('r-workshop.report.savePullOut');
            Route::GET('/workshop-ms/r-workshop/report/getPOUData', [RReportController::class, 'getPOUData'])->name('r-workshop.report.getPOUData');
            Route::POST('/workshop-ms/r-workshop/report/deletePOU', [RReportController::class, 'deletePOU'])->name('r-workshop.report.deletePOU');
            Route::GET('/workshop-ms/r-workshop/report/getBay', [RReportController::class, 'getBay'])->name('r-workshop.report.getBay');
            Route::POST('/workshop-ms/r-workshop/report/transferPullOut', [RReportController::class, 'transferPullOut'])->name('r-workshop.report.transferPullOut');
            Route::GET('/workshop-ms/r-workshop/report/getUnitStatus', [RReportController::class, 'getUnitStatus'])->name('r-workshop.report.getUnitStatus');
            Route::GET('/workshop-ms/r-workshop/report/sortPullOut', [RReportController::class, 'sortPullOut'])->name('r-workshop.report.sortPullOut');
        
        // Confirm Unit
            Route::POST('/workshop-ms/r-workshop/report/deleteCU', [RReportController::class, 'deleteCU'])->name('r-workshop.report.deleteCU');
            Route::GET('/workshop-ms/r-workshop/report/sortConfirm', [RReportController::class, 'sortConfirm'])->name('r-workshop.report.sortConfirm');

        // Delivered Unit
            Route::POST('/workshop-ms/r-workshop/report/deleteDU', [RReportController::class, 'deleteDU'])->name('r-workshop.report.deleteDU');

        // Cannibalized Unit
            Route::GET('/workshop-ms/r-workshop/report/getCanUnitStatus', [RReportController::class, 'getCanUnitStatus'])->name('r-workshop.report.getCanUnitStatus');
            Route::POST('/workshop-ms/r-workshop/report/saveCanUnit', [RReportController::class, 'saveCanUnit'])->name('r-workshop.report.saveCanUnit');
            Route::POST('/workshop-ms/r-workshop/report/getCanParts', [RReportController::class, 'getCanParts'])->name('r-workshop.report.getCanParts');
            Route::POST('/workshop-ms/r-workshop/report/deleteCanUnit', [RReportController::class, 'deleteCanUnit'])->name('r-workshop.report.deleteCanUnit');

        // DR Monitoring
            Route::GET('/workshop-ms/r-workshop/report/getDRMonStatus', [RReportController::class, 'getDRMonStatus'])->name('r-workshop.report.getDRMonStatus');
            Route::POST('/workshop-ms/r-workshop/report/saveDRMon', [RReportController::class, 'saveDRMon'])->name('r-workshop.report.saveDRMon');
            Route::POST('/workshop-ms/r-workshop/report/getDRParts', [RReportController::class, 'getDRParts'])->name('r-workshop.report.getDRParts');
            Route::POST('/workshop-ms/r-workshop/report/deleteDRMon', [RReportController::class, 'deleteDRMon'])->name('r-workshop.report.deleteDRMon');

    
    // START OF PPT WORKSHOP > MONITORING
        Route::GET('/workshop-ms/ppt-workshop', [PPTReportController::class, 'index'])->name('ppt-workshop.index');

        // Unit Info
            Route::GET('/workshop-ms/ppt-workshop/getEvents', [PPTReportController::class, 'getEvents'])->name('ppt-workshop.getEvents');
            Route::GET('/workshop-ms/ppt-workshop/getBayData', [PPTReportController::class, 'getBayData'])->name('ppt-workshop.getBayData');
            Route::POST('/workshop-ms/ppt-workshop/saveBayData', [PPTReportController::class, 'saveBayData'])->name('ppt-workshop.saveBayData');
            Route::POST('/workshop-ms/ppt-workshop/saveTargetActivity', [PPTReportController::class, 'saveTargetActivity'])->name('ppt-workshop.saveTargetActivity');
            Route::POST('/workshop-ms/ppt-workshop/resetActual', [PPTReportController::class, 'resetActual'])->name('ppt-workshop.resetActual');
            Route::POST('/workshop-ms/ppt-workshop/updateIDS', [PPTReportController::class, 'updateIDS'])->name('ppt-workshop.updateIDS');
            Route::POST('/workshop-ms/ppt-workshop/updateIDE', [PPTReportController::class, 'updateIDE'])->name('ppt-workshop.updateIDE');
            Route::POST('/workshop-ms/ppt-workshop/updateRDS', [PPTReportController::class, 'updateRDS'])->name('ppt-workshop.updateRDS');
            Route::POST('/workshop-ms/ppt-workshop/updateRDE', [PPTReportController::class, 'updateRDE'])->name('ppt-workshop.updateRDE');
            Route::GET('/workshop-ms/ppt-workshop/getTransferData', [PPTReportController::class, 'getTransferData'])->name('ppt-workshop.getTransferData');
            Route::POST('/workshop-ms/ppt-workshop/saveTransferUnit', [PPTReportController::class, 'saveTransferUnit'])->name('ppt-workshop.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/ppt-workshop/saveDowntime', [PPTReportController::class, 'saveDowntime'])->name('ppt-workshop.saveDowntime');
            Route::POST('/workshop-ms/ppt-workshop/getDowntime', [PPTReportController::class, 'getDowntime'])->name('ppt-workshop.getDowntime');
            Route::POST('/workshop-ms/ppt-workshop/deleteDowntime', [PPTReportController::class, 'deleteDowntime'])->name('ppt-workshop.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/ppt-workshop/getPI', [PPTReportController::class, 'getPI'])->name('ppt-workshop.getPI');
            Route::POST('/workshop-ms/ppt-workshop/savePI', [PPTReportController::class, 'savePI'])->name('ppt-workshop.savePI');
            Route::POST('/workshop-ms/ppt-workshop/getPInfo', [PPTReportController::class, 'getPInfo'])->name('ppt-workshop.getPInfo');
            Route::POST('/workshop-ms/ppt-workshop/deletePI', [PPTReportController::class, 'deletePI'])->name('ppt-workshop.deletePI');
            Route::POST('/workshop-ms/ppt-workshop/installPI', [PPTReportController::class, 'installPI'])->name('ppt-workshop.installPI');
            Route::POST('/workshop-ms/ppt-workshop/revertParts', [PPTReportController::class, 'revertParts'])->name('ppt-workshop.revertParts');
            Route::POST('/workshop-ms/ppt-workshop/deleteIParts', [PPTReportController::class, 'deleteIParts'])->name('ppt-workshop.deleteIParts');
            Route::POST('/workshop-ms/ppt-workshop/saveRemarks', [PPTReportController::class, 'saveRemarks'])->name('ppt-workshop.saveRemarks');
            Route::GET('/workshop-ms/ppt-workshop/search', [PPTReportController::class, 'search'])->name('ppt-workshop.search');
            Route::POST('/workshop-ms/ppt-workshop/getPartsInfox', [PPTReportController::class, 'getPartsInfox'])->name('ppt-workshop.getPartsInfox');

        // Technician Schedule
            Route::POST('/workshop-ms/ppt-workshop/viewSchedule', [PPTReportController::class, 'viewSchedule'])->name('ppt-workshop.viewSchedule');
            Route::POST('/workshop-ms/ppt-workshop/saveActivity', [PPTReportController::class, 'saveActivity'])->name('ppt-workshop.saveActivity');
            Route::POST('/workshop-ms/ppt-workshop/saveTActivity', [PPTReportController::class, 'saveTActivity'])->name('ppt-workshop.saveTActivity');

            Route::GET('/workshop-ms/ppt-workshop/report', [PPTReportController::class, 'indexR'])->name('ppt-workshop.report');

        // Workshop
            Route::GET('/workshop-ms/ppt-workshop/report/sortBrand', [PPTReportController::class, 'sortBrand'])->name('ppt-workshop.report.sortBrand');

        // Report
            Route::GET('/workshop-ms/ppt-workshop/report/getBayR', [PPTReportController::class, 'getBayR'])->name('ppt-workshop.report.getBayR');
            Route::POST('/workshop-ms/ppt-workshop/report/generateBrandReport', [PPTReportController::class, 'generateBrandReport'])->name('ppt-workshop.report.generateBrandReport');
            Route::POST('/workshop-ms/ppt-workshop/report/generateBayReport', [PPTReportController::class, 'generateBayReport'])->name('ppt-workshop.report.generateBayReport');
            Route::POST('/workshop-ms/ppt-workshop/report/searchRPU', [PPTReportController::class, 'searchRPU'])->name('ppt-workshop.report.searchRPU');
            Route::POST('/workshop-ms/ppt-workshop/report/generateUnitReport', [PPTReportController::class, 'generateUnitReport'])->name('ppt-workshop.report.generateUnitReport');
            Route::POST('/workshop-ms/ppt-workshop/report/generateUnitRecord', [PPTReportController::class, 'generateUnitRecord'])->name('ppt-workshop.report.generateUnitRecord');
            Route::POST('/workshop-ms/v-workshop/report/generatePOUReport', [PPTReportController::class, 'generatePOUReport'])->name('ppt-workshop.report.generatePOUReport');
            Route::POST('/workshop-ms/ppt-workshop/report/generateDUReport', [PPTReportController::class, 'generateDUReport'])->name('ppt-workshop.report.generateDUReport');
            Route::POST('/workshop-ms/ppt-workshop/report/generateCanUnitReport', [PPTReportController::class, 'generateCanUnitReport'])->name('ppt-workshop.report.generateCanUnitReport');
            Route::POST('/workshop-ms/ppt-workshop/report/generateDRMonReport', [PPTReportController::class, 'generateDRMonReport'])->name('ppt-workshop.report.generateDRMonReport');


        // Brand New Unit
            Route::POST('/workshop-ms/ppt-workshop/report/saveBrandNew', [PPTReportController::class, 'saveBrandNew'])->name('ppt-workshop.report.saveBrandNew');
            Route::GET('/workshop-ms/ppt-workshop/report/getBNUData', [PPTReportController::class, 'getBNUData'])->name('ppt-workshop.report.getBNUData');
            Route::POST('/workshop-ms/ppt-workshop/report/deleteBNU', [PPTReportController::class, 'deleteBNU'])->name('ppt-workshop.report.deleteBNU');
            Route::POST('/workshop-ms/ppt-workshop/report/transferNewUnit', [PPTReportController::class, 'transferNewUnit'])->name('ppt-workshop.report.transferNewUnit');

        // Pull Out Unit
            Route::POST('/workshop-ms/ppt-workshop/report/savePullOut', [PPTReportController::class, 'savePullOut'])->name('ppt-workshop.report.savePullOut');
            Route::GET('/workshop-ms/ppt-workshop/report/getPOUData', [PPTReportController::class, 'getPOUData'])->name('ppt-workshop.report.getPOUData');
            Route::POST('/workshop-ms/ppt-workshop/report/deletePOU', [PPTReportController::class, 'deletePOU'])->name('ppt-workshop.report.deletePOU');
            Route::GET('/workshop-ms/ppt-workshop/report/getBay', [PPTReportController::class, 'getBay'])->name('ppt-workshop.report.getBay');
            Route::POST('/workshop-ms/ppt-workshop/report/transferPullOut', [PPTReportController::class, 'transferPullOut'])->name('ppt-workshop.report.transferPullOut');
            Route::GET('/workshop-ms/ppt-workshop/report/getUnitStatus', [PPTReportController::class, 'getUnitStatus'])->name('ppt-workshop.report.getUnitStatus');
            Route::GET('/workshop-ms/ppt-workshop/report/sortPullOut', [PPTReportController::class, 'sortPullOut'])->name('ppt-workshop.report.sortPullOut');
        
        // Confirm Unit
            Route::POST('/workshop-ms/ppt-workshop/report/deleteCU', [PPTReportController::class, 'deleteCU'])->name('ppt-workshop.report.deleteCU');
            Route::GET('/workshop-ms/ppt-workshop/report/sortConfirm', [PPTReportController::class, 'sortConfirm'])->name('ppt-workshop.report.sortConfirm');

        // Delivered Unit
            Route::POST('/workshop-ms/ppt-workshop/report/deleteDU', [PPTReportController::class, 'deleteDU'])->name('ppt-workshop.report.deleteDU');

        // Cannibalized Unit
            Route::GET('/workshop-ms/ppt-workshop/report/getCanUnitStatus', [PPTReportController::class, 'getCanUnitStatus'])->name('ppt-workshop.report.getCanUnitStatus');
            Route::POST('/workshop-ms/ppt-workshop/report/saveCanUnit', [PPTReportController::class, 'saveCanUnit'])->name('ppt-workshop.report.saveCanUnit');
            Route::POST('/workshop-ms/ppt-workshop/report/getCanParts', [PPTReportController::class, 'getCanParts'])->name('ppt-workshop.report.getCanParts');
            Route::POST('/workshop-ms/ppt-workshop/report/deleteCanUnit', [PPTReportController::class, 'deleteCanUnit'])->name('ppt-workshop.report.deleteCanUnit');

        // DR Monitoring
            Route::GET('/workshop-ms/ppt-workshop/report/getDRMonStatus', [PPTReportController::class, 'getDRMonStatus'])->name('ppt-workshop.report.getDRMonStatus');
            Route::POST('/workshop-ms/ppt-workshop/report/saveDRMon', [PPTReportController::class, 'saveDRMon'])->name('ppt-workshop.report.saveDRMon');
            Route::POST('/workshop-ms/ppt-workshop/report/getDRParts', [PPTReportController::class, 'getDRParts'])->name('ppt-workshop.report.getDRParts');
            Route::POST('/workshop-ms/ppt-workshop/report/deleteDRMon', [PPTReportController::class, 'deleteDRMon'])->name('ppt-workshop.report.deleteDRMon');
    

            Route::GET('/workshop-ms/ppt-workshop/report/getBay', [PPTReportController::class, 'getBay'])->name('ppt-workshop.report.getBay');

    // START OF OTHER WORKSHOP > MONITORING
        Route::GET('/workshop-ms/other-workshop', [OtherReportController::class, 'index'])->name('other-workshop.index');

        // Unit Info
            Route::GET('/workshop-ms/other-workshop/getEvents', [OtherReportController::class, 'getEvents'])->name('other-workshop.getEvents');
            Route::GET('/workshop-ms/other-workshop/getBayData', [OtherReportController::class, 'getBayData'])->name('other-workshop.getBayData');
            Route::POST('/workshop-ms/other-workshop/saveBayData', [OtherReportController::class, 'saveBayData'])->name('other-workshop.saveBayData');
            Route::POST('/workshop-ms/other-workshop/saveTargetActivity', [OtherReportController::class, 'saveTargetActivity'])->name('other-workshop.saveTargetActivity');
            Route::POST('/workshop-ms/other-workshop/resetActual', [OtherReportController::class, 'resetActual'])->name('other-workshop.resetActual');
            Route::POST('/workshop-ms/other-workshop/updateIDS', [OtherReportController::class, 'updateIDS'])->name('other-workshop.updateIDS');
            Route::POST('/workshop-ms/other-workshop/updateIDE', [OtherReportController::class, 'updateIDE'])->name('other-workshop.updateIDE');
            Route::POST('/workshop-ms/other-workshop/updateRDS', [OtherReportController::class, 'updateRDS'])->name('other-workshop.updateRDS');
            Route::POST('/workshop-ms/other-workshop/updateRDE', [OtherReportController::class, 'updateRDE'])->name('other-workshop.updateRDE');
            Route::GET('/workshop-ms/other-workshop/getTransferData', [OtherReportController::class, 'getTransferData'])->name('other-workshop.getTransferData');
            Route::POST('/workshop-ms/other-workshop/saveTransferUnit', [OtherReportController::class, 'saveTransferUnit'])->name('other-workshop.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/other-workshop/saveDowntime', [OtherReportController::class, 'saveDowntime'])->name('other-workshop.saveDowntime');
            Route::POST('/workshop-ms/other-workshop/getDowntime', [OtherReportController::class, 'getDowntime'])->name('other-workshop.getDowntime');
            Route::POST('/workshop-ms/other-workshop/deleteDowntime', [OtherReportController::class, 'deleteDowntime'])->name('other-workshop.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/other-workshop/getPI', [OtherReportController::class, 'getPI'])->name('other-workshop.getPI');
            Route::POST('/workshop-ms/other-workshop/savePI', [OtherReportController::class, 'savePI'])->name('other-workshop.savePI');
            Route::POST('/workshop-ms/other-workshop/getPInfo', [OtherReportController::class, 'getPInfo'])->name('other-workshop.getPInfo');
            Route::POST('/workshop-ms/other-workshop/deletePI', [OtherReportController::class, 'deletePI'])->name('other-workshop.deletePI');
            Route::POST('/workshop-ms/other-workshop/installPI', [OtherReportController::class, 'installPI'])->name('other-workshop.installPI');
            Route::POST('/workshop-ms/other-workshop/revertParts', [OtherReportController::class, 'revertParts'])->name('other-workshop.revertParts');
            Route::POST('/workshop-ms/other-workshop/deleteIParts', [OtherReportController::class, 'deleteIParts'])->name('other-workshop.deleteIParts');
            Route::POST('/workshop-ms/other-workshop/saveRemarks', [OtherReportController::class, 'saveRemarks'])->name('other-workshop.saveRemarks');
            Route::GET('/workshop-ms/other-workshop/search', [OtherReportController::class, 'search'])->name('other-workshop.search');
            Route::POST('/workshop-ms/other-workshop/getPartsInfox', [OtherReportController::class, 'getPartsInfox'])->name('other-workshop.getPartsInfox');

        // Technician Schedule
            Route::POST('/workshop-ms/other-workshop/viewSchedule', [OtherReportController::class, 'viewSchedule'])->name('other-workshop.viewSchedule');
            Route::POST('/workshop-ms/other-workshop/saveActivity', [OtherReportController::class, 'saveActivity'])->name('other-workshop.saveActivity');
            Route::POST('/workshop-ms/other-workshop/saveTActivity', [OtherReportController::class, 'saveTActivity'])->name('other-workshop.saveTActivity');

            Route::GET('/workshop-ms/other-workshop/report/getBay', [OtherReportController::class, 'getBay'])->name('other-workshop.report.getBay');


    // START OF OVERHAULING WORKSHOP > MONITORING
        Route::GET('/workshop-ms/ovhl-workshop', [OVHLReportController::class, 'index'])->name('ovhl-workshop.index');

        // Unit Info
            Route::GET('/workshop-ms/ovhl-workshop/getEvents', [OVHLReportController::class, 'getEvents'])->name('ovhl-workshop.getEvents');
            Route::GET('/workshop-ms/ovhl-workshop/getBayData', [OVHLReportController::class, 'getBayData'])->name('ovhl-workshop.getBayData');
            Route::POST('/workshop-ms/ovhl-workshop/saveBayData', [OVHLReportController::class, 'saveBayData'])->name('ovhl-workshop.saveBayData');
            Route::POST('/workshop-ms/ovhl-workshop/saveTargetActivity', [OVHLReportController::class, 'saveTargetActivity'])->name('ovhl-workshop.saveTargetActivity');
            Route::POST('/workshop-ms/ovhl-workshop/resetActual', [OVHLReportController::class, 'resetActual'])->name('ovhl-workshop.resetActual');
            Route::POST('/workshop-ms/ovhl-workshop/updateIDS', [OVHLReportController::class, 'updateIDS'])->name('ovhl-workshop.updateIDS');
            Route::POST('/workshop-ms/ovhl-workshop/updateIDE', [OVHLReportController::class, 'updateIDE'])->name('ovhl-workshop.updateIDE');
            Route::POST('/workshop-ms/ovhl-workshop/updateRDS', [OVHLReportController::class, 'updateRDS'])->name('ovhl-workshop.updateRDS');
            Route::POST('/workshop-ms/ovhl-workshop/updateRDE', [OVHLReportController::class, 'updateRDE'])->name('ovhl-workshop.updateRDE');
            Route::GET('/workshop-ms/ovhl-workshop/getTransferData', [OVHLReportController::class, 'getTransferData'])->name('ovhl-workshop.getTransferData');
            Route::POST('/workshop-ms/ovhl-workshop/saveTransferUnit', [OVHLReportController::class, 'saveTransferUnit'])->name('ovhl-workshop.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/ovhl-workshop/saveDowntime', [OVHLReportController::class, 'saveDowntime'])->name('ovhl-workshop.saveDowntime');
            Route::POST('/workshop-ms/ovhl-workshop/getDowntime', [OVHLReportController::class, 'getDowntime'])->name('ovhl-workshop.getDowntime');
            Route::POST('/workshop-ms/ovhl-workshop/deleteDowntime', [OVHLReportController::class, 'deleteDowntime'])->name('ovhl-workshop.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/ovhl-workshop/getPI', [OVHLReportController::class, 'getPI'])->name('ovhl-workshop.getPI');
            Route::POST('/workshop-ms/ovhl-workshop/savePI', [OVHLReportController::class, 'savePI'])->name('ovhl-workshop.savePI');
            Route::POST('/workshop-ms/ovhl-workshop/getPInfo', [OVHLReportController::class, 'getPInfo'])->name('ovhl-workshop.getPInfo');
            Route::POST('/workshop-ms/ovhl-workshop/deletePI', [OVHLReportController::class, 'deletePI'])->name('ovhl-workshop.deletePI');
            Route::POST('/workshop-ms/ovhl-workshop/installPI', [OVHLReportController::class, 'installPI'])->name('ovhl-workshop.installPI');
            Route::POST('/workshop-ms/ovhl-workshop/revertParts', [OVHLReportController::class, 'revertParts'])->name('ovhl-workshop.revertParts');
            Route::POST('/workshop-ms/ovhl-workshop/deleteIParts', [OVHLReportController::class, 'deleteIParts'])->name('ovhl-workshop.deleteIParts');
            Route::POST('/workshop-ms/ovhl-workshop/saveRemarks', [OVHLReportController::class, 'saveRemarks'])->name('ovhl-workshop.saveRemarks');
            Route::GET('/workshop-ms/ovhl-workshop/search', [OVHLReportController::class, 'search'])->name('ovhl-workshop.search');
            Route::POST('/workshop-ms/ovhl-workshop/getPartsInfox', [OVHLReportController::class, 'getPartsInfox'])->name('ovhl-workshop.getPartsInfox');

        // Technician Schedule
            Route::POST('/workshop-ms/ovhl-workshop/viewSchedule', [OVHLReportController::class, 'viewSchedule'])->name('ovhl-workshop.viewSchedule');
            Route::POST('/workshop-ms/ovhl-workshop/saveActivity', [OVHLReportController::class, 'saveActivity'])->name('ovhl-workshop.saveActivity');
            Route::POST('/workshop-ms/ovhl-workshop/saveTActivity', [OVHLReportController::class, 'saveTActivity'])->name('ovhl-workshop.saveTActivity');
    

            Route::GET('/workshop-ms/ovhl-workshop/report/getBay', [OVHLReportController::class, 'getBay'])->name('ovhl-workshop.report.getBay');
        


    // START OF ADMIN MONITORING > TECHNICIAN SCHEDULE
        // Workshop
        Route::GET('/workshop-ms/admin-monitoring', [AdminMonitor::class, 'index'])->name('admin_monitoring.index');

        // Unit Info
            Route::GET('/workshop-ms/admin-monitoring/getEvents', [AdminMonitor::class, 'getEvents'])->name('admin-monitoring.getEvents');
            Route::GET('/workshop-ms/admin-monitoring/getBayData', [AdminMonitor::class, 'getBayData'])->name('admin-monitoring.getBayData');
            Route::POST('/workshop-ms/admin-monitoring/saveBayData', [AdminMonitor::class, 'saveBayData'])->name('admin-monitoring.saveBayData');
            Route::POST('/workshop-ms/admin-monitoring/saveTargetActivity', [AdminMonitor::class, 'saveTargetActivity'])->name('admin-monitoring.saveTargetActivity');
            Route::POST('/workshop-ms/admin-monitoring/resetActual', [AdminMonitor::class, 'resetActual'])->name('admin-monitoring.resetActual');
            Route::POST('/workshop-ms/admin-monitoring/updateIDS', [AdminMonitor::class, 'updateIDS'])->name('admin-monitoring.updateIDS');
            Route::POST('/workshop-ms/admin-monitoring/updateIDE', [AdminMonitor::class, 'updateIDE'])->name('admin-monitoring.updateIDE');
            Route::POST('/workshop-ms/admin-monitoring/updateRDS', [AdminMonitor::class, 'updateRDS'])->name('admin-monitoring.updateRDS');
            Route::POST('/workshop-ms/admin-monitoring/updateRDE', [AdminMonitor::class, 'updateRDE'])->name('admin-monitoring.updateRDE');
            Route::GET('/workshop-ms/admin-monitoring/getTransferData', [AdminMonitor::class, 'getTransferData'])->name('admin-monitoring.getTransferData');
            Route::POST('/workshop-ms/admin-monitoring/saveTransferUnit', [AdminMonitor::class, 'saveTransferUnit'])->name('admin-monitoring.saveTransferUnit');
        // Downtime
            Route::POST('/workshop-ms/admin-monitoring/saveDowntime', [AdminMonitor::class, 'saveDowntime'])->name('admin-monitoring.saveDowntime');
            Route::POST('/workshop-ms/admin-monitoring/getDowntime', [AdminMonitor::class, 'getDowntime'])->name('admin-monitoring.getDowntime');
            Route::POST('/workshop-ms/admin-monitoring/deleteDowntime', [AdminMonitor::class, 'deleteDowntime'])->name('admin-monitoring.deleteDowntime');
        // Parts
            Route::POST('/workshop-ms/admin-monitoring/getPI', [AdminMonitor::class, 'getPI'])->name('admin-monitoring.getPI');
            Route::POST('/workshop-ms/admin-monitoring/savePI', [AdminMonitor::class, 'savePI'])->name('admin-monitoring.savePI');
            Route::POST('/workshop-ms/admin-monitoring/getPInfo', [AdminMonitor::class, 'getPInfo'])->name('admin-monitoring.getPInfo');
            Route::POST('/workshop-ms/admin-monitoring/deletePI', [AdminMonitor::class, 'deletePI'])->name('admin-monitoring.deletePI');
            Route::POST('/workshop-ms/admin-monitoring/installPI', [AdminMonitor::class, 'installPI'])->name('admin-monitoring.installPI');
            Route::POST('/workshop-ms/admin-monitoring/revertParts', [AdminMonitor::class, 'revertParts'])->name('admin-monitoring.revertParts');
            Route::POST('/workshop-ms/admin-monitoring/deleteIParts', [AdminMonitor::class, 'deleteIParts'])->name('admin-monitoring.deleteIParts');
            Route::POST('/workshop-ms/admin-monitoring/saveRemarks', [AdminMonitor::class, 'saveRemarks'])->name('admin-monitoring.saveRemarks');
            Route::GET('/workshop-ms/admin-monitoring/search', [AdminMonitor::class, 'search'])->name('admin-monitoring.search');
            Route::POST('/workshop-ms/admin-monitoring/getPartsInfox', [AdminMonitor::class, 'getPartsInfox'])->name('admin-monitoring.getPartsInfox');

        // Technician Schedule
            Route::POST('/workshop-ms/admin-monitoring/viewSchedule', [AdminMonitor::class, 'viewSchedule'])->name('admin-monitoring.viewSchedule');
            Route::POST('/workshop-ms/admin-monitoring/saveActivity', [AdminMonitor::class, 'saveActivity'])->name('admin-monitoring.saveActivity');
            Route::POST('/workshop-ms/admin-monitoring/saveTActivity', [AdminMonitor::class, 'saveTActivity'])->name('admin-monitoring.saveTActivity');

        // Report
        Route::GET('/workshop-ms/admin_monitoring/report', [AdminMonitor::class, 'indexR'])->name('admin_monitoring.report');
            // Workshop
            Route::GET('/workshop-ms/admin_monitoring/report/sortBrand', [AdminMonitor::class, 'sortBrand'])->name('admin_monitoring.report.sortBrand');

            // Report
                Route::GET('/workshop-ms/admin_monitoring/report/getBayR', [AdminMonitor::class, 'getBayR'])->name('admin_monitoring.report.getBayR');
                Route::POST('/workshop-ms/admin_monitoring/report/generateBrandReport', [AdminMonitor::class, 'generateBrandReport'])->name('admin_monitoring.report.generateBrandReport');
                Route::POST('/workshop-ms/admin_monitoring/report/generateBayReport', [AdminMonitor::class, 'generateBayReport'])->name('admin_monitoring.report.generateBayReport');
                Route::POST('/workshop-ms/admin_monitoring/report/generatePOUReport', [AdminMonitor::class, 'generatePOUReport'])->name('admin_monitoring.report.generatePOUReport');
                Route::POST('/workshop-ms/admin_monitoring/report/generateDUReport', [AdminMonitor::class, 'generateDUReport'])->name('admin_monitoring.report.generateDUReport');
                Route::POST('/workshop-ms/admin_monitoring/report/generateCanUnitReport', [AdminMonitor::class, 'generateCanUnitReport'])->name('admin_monitoring.report.generateCanUnitReport');
                Route::POST('/workshop-ms/admin_monitoring/report/generateDRMonReport', [AdminMonitor::class, 'generateDRMonReport'])->name('admin_monitoring.report.generateDRMonReport');

            // Brand New Unit
                Route::POST('/workshop-ms/admin_monitoring/report/saveBrandNew', [AdminMonitor::class, 'saveBrandNew'])->name('admin_monitoring.report.saveBrandNew');
                Route::GET('/workshop-ms/admin_monitoring/report/getBNUData', [AdminMonitor::class, 'getBNUData'])->name('admin_monitoring.report.getBNUData');
                Route::POST('/workshop-ms/admin_monitoring/report/deleteBNU', [AdminMonitor::class, 'deleteBNU'])->name('admin_monitoring.report.deleteBNU');
                Route::POST('/workshop-ms/admin_monitoring/report/transferNewUnit', [AdminMonitor::class, 'transferNewUnit'])->name('admin_monitoring.report.transferNewUnit');

            // Pull Out Unit
                Route::POST('/workshop-ms/admin_monitoring/report/savePullOut', [AdminMonitor::class, 'savePullOut'])->name('admin_monitoring.report.savePullOut');
                Route::GET('/workshop-ms/admin_monitoring/report/getPOUData', [AdminMonitor::class, 'getPOUData'])->name('admin_monitoring.report.getPOUData');
                Route::GET('/workshop-ms/admin_monitoring/report/deletePOU', [AdminMonitor::class, 'deletePOU'])->name('admin_monitoring.report.deletePOU');
                Route::GET('/workshop-ms/admin_monitoring/report/getBay', [AdminMonitor::class, 'getBay'])->name('admin_monitoring.report.getBay');
                Route::POST('/workshop-ms/admin_monitoring/report/transferPullOut', [AdminMonitor::class, 'transferPullOut'])->name('admin_monitoring.report.transferPullOut');
                Route::GET('/workshop-ms/admin_monitoring/report/getUnitStatus', [AdminMonitor::class, 'getUnitStatus'])->name('admin_monitoring.report.getUnitStatus');
                Route::GET('/workshop-ms/admin_monitoring/report/sortPullOut', [AdminMonitor::class, 'sortPullOut'])->name('admin_monitoring.report.sortPullOut');
    
            // Confirm Unit
                Route::POST('/workshop-ms/admin_monitoring/report/deleteCU', [AdminMonitor::class, 'deleteCU'])->name('admin_monitoring.report.deleteCU');
                Route::GET('/workshop-ms/admin_monitoring/report/sortConfirm', [AdminMonitor::class, 'sortConfirm'])->name('admin_monitoring.report.sortConfirm');
    
            // Delivered Unit
                Route::POST('/workshop-ms/admin_monitoring/report/deleteDU', [AdminMonitor::class, 'deleteDU'])->name('admin_monitoring.report.deleteDU');

            // Cannibalized Unit
                Route::GET('/workshop-ms/admin_monitoring/report/getCanUnitStatus', [AdminMonitor::class, 'getCanUnitStatus'])->name('admin_monitoring.report.getCanUnitStatus');
                Route::POST('/workshop-ms/admin_monitoring/report/saveCanUnit', [AdminMonitor::class, 'saveCanUnit'])->name('admin_monitoring.report.saveCanUnit');
                Route::POST('/workshop-ms/admin_monitoring/report/getCanParts', [AdminMonitor::class, 'getCanParts'])->name('admin_monitoring.report.getCanParts');
                Route::POST('/workshop-ms/admin_monitoring/report/deleteCanUnit', [AdminMonitor::class, 'deleteCanUnit'])->name('admin_monitoring.report.deleteCanUnit');
    
            // DR Monitoring
                Route::GET('/workshop-ms/admin_monitoring/report/getDRMonStatus', [AdminMonitor::class, 'getDRMonStatus'])->name('admin_monitoring.report.getDRMonStatus');
                Route::POST('/workshop-ms/admin_monitoring/report/saveDRMon', [AdminMonitor::class, 'saveDRMon'])->name('admin_monitoring.report.saveDRMon');
                Route::POST('/workshop-ms/admin_monitoring/report/getDRParts', [AdminMonitor::class, 'getDRParts'])->name('admin_monitoring.report.getDRParts');
                Route::POST('/workshop-ms/admin_monitoring/report/deleteDRMon', [AdminMonitor::class, 'deleteDRMon'])->name('admin_monitoring.report.deleteDRMon');

        // Technician Schedule
        Route::GET('/workshop-ms/admin_monitoring', [TechnicianScheduleController::class, 'index'])->name('admin_monitoring.tech_schedule');
        Route::POST('/workshop-ms/admin_monitoring/saveSchedule', [TechnicianScheduleController::class, 'saveSchedule'])->name('admin_monitoring.tech_schedule.saveSchedule');
        Route::POST('/workshop-ms/admin_monitoring/getSchedule', [TechnicianScheduleController::class, 'getSchedule'])->name('admin_monitoring.tech_schedule.getSchedule');
        Route::POST('/workshop-ms/admin_monitoring/deleteSchedule', [TechnicianScheduleController::class, 'deleteSchedule'])->name('admin_monitoring.tech_schedule.deleteSchedule');
        Route::POST('/workshop-ms/admin_monitoring/filterSchedule', [TechnicianScheduleController::class, 'filterSchedule'])->name('admin_monitoring.tech_schedule.filterSchedule');
        Route::POST('/workshop-ms/admin_monitoring/resetSchedule', [TechnicianScheduleController::class, 'resetSchedule'])->name('admin_monitoring.tech_schedule.resetSchedule');
        Route::POST('/workshop-ms/admin_monitoring/filterScheduleX', [TechnicianScheduleController::class, 'filterScheduleX'])->name('admin_monitoring.tech_schedule.filterScheduleX');
        Route::POST('/workshop-ms/admin_monitoring/clearSearch', [TechnicianScheduleController::class, 'clearSearch'])->name('admin_monitoring.tech_schedule.clearSearch');
        Route::POST('/workshop-ms/admin_monitoring/generateSReport', [TechnicianScheduleController::class, 'generateSReport'])->name('admin_monitoring.tech_schedule.generateSReport');
        Route::GET('/workshop-ms/admin_monitoring/getJONum', [TechnicianScheduleController::class, 'getJONum'])->name('admin_monitoring.tech_schedule.getJONum');
        Route::GET('/workshop-ms/admin_monitoring/getActiveBayNames', [TechnicianScheduleController::class],'getActiveBayNames')->name('admin_monitoring.tech_schedule.getActiveBayNames');
        Route::GET('/workshop-ms/admin_monitoring/getEvents', [TechnicianScheduleController::class, 'getEvents'])->name('admin_monitoring.tech_schedule.getEvents');

    // START OF ADMIN MONITORING > WAREHOUSE
        // WAREHOUSE 1
            Route::GET('/workshop-ms/w-storage1', [WStorage1Controller::class, 'index'])->name('w-storage1.index');
            Route::GET('/workshop-ms/w-storage1/getBayData', [WStorage1Controller::class, 'getBayData'])->name('w-storage1.getBayData');
            Route::GET('/workshop-ms/w-storage1/getBay', [WStorage1Controller::class, 'getBay'])->name('w-storage1.getBay');
            Route::GET('/workshop-ms/w-storage1/getTransferData', [WStorage1Controller::class, 'getTransferData'])->name('w-storage1.getTransferData');
            Route::POST('/workshop-ms/w-storage1/saveTransferData', [WStorage1Controller::class, 'saveTransferData'])->name('w-storage1.saveTransferData');
            Route::POST('/workshop-ms/w-storage1/saveUnitData', [WStorage1Controller::class, 'saveUnitData'])->name('w-storage1.saveUnitData');

        // WAREHOUSE 5B
            Route::GET('/workshop-ms/w-storage5b', [WStorage5BController::class, 'index'])->name('w-storage5b.index');
            Route::GET('/workshop-ms/w-storage5b/getBayData', [WStorage5BController::class, 'getBayData'])->name('w-storage5b.getBayData');
            Route::GET('/workshop-ms/w-storage5b/getBay', [WStorage5BController::class, 'getBay'])->name('w-storage5b.getBay');
            Route::GET('/workshop-ms/w-storage5b/getTransferData', [WStorage5BController::class, 'getTransferData'])->name('w-storage5b.getTransferData');
            Route::POST('/workshop-ms/w-storage5b/saveTransferData', [WStorage5BController::class, 'saveTransferData'])->name('w-storage5b.saveTransferData');
            Route::POST('/workshop-ms/w-storage5b/saveUnitData', [WStorage5BController::class, 'saveUnitData'])->name('w-storage5b.saveUnitData');
    
        // WAREHOUSE 5C
            Route::GET('/workshop-ms/w-storage5c', [WStorage5CController::class, 'index'])->name('w-storage5c.index');
            Route::GET('/workshop-ms/w-storage5c/getBayData', [WStorage5CController::class, 'getBayData'])->name('w-storage5c.getBayData');
            Route::GET('/workshop-ms/w-storage5c/getBay', [WStorage5CController::class, 'getBay'])->name('w-storage5c.getBay');
            Route::GET('/workshop-ms/w-storage5c/getTransferData', [WStorage5CController::class, 'getTransferData'])->name('w-storage5c.getTransferData');
            Route::POST('/workshop-ms/w-storage5c/saveTransferData', [WStorage5CController::class, 'saveTransferData'])->name('w-storage5c.saveTransferData');
            Route::POST('/workshop-ms/w-storage5c/saveUnitData', [WStorage5CController::class, 'saveUnitData'])->name('w-storage5c.saveUnitData');

        // WAREHOUSE 6
            Route::GET('/workshop-ms/w-storage6', [WStorage6Controller::class, 'index'])->name('w-storage6.index');
            Route::GET('/workshop-ms/w-storage6/getBayData', [WStorage6Controller::class, 'getBayData'])->name('w-storage6.getBayData');
            Route::GET('/workshop-ms/w-storage6/getBay', [WStorage6Controller::class, 'getBay'])->name('w-storage6.getBay');
            Route::GET('/workshop-ms/w-storage6/getTransferData', [WStorage6Controller::class, 'getTransferData'])->name('w-storage6.getTransferData');
            Route::POST('/workshop-ms/w-storage6/saveTransferData', [WStorage6Controller::class, 'saveTransferData'])->name('w-storage6.saveTransferData');
            Route::POST('/workshop-ms/w-storage6/saveUnitData', [WStorage6Controller::class, 'saveUnitData'])->name('w-storage6.saveUnitData');

        // WAREHOUSE 7
            Route::GET('/workshop-ms/w-storage7', [WStorage7Controller::class, 'index'])->name('w-storage7.index');
            Route::GET('/workshop-ms/w-storage7/getBayData', [WStorage7Controller::class, 'getBayData'])->name('w-storage7.getBayData');
            Route::GET('/workshop-ms/w-storage7/getBay', [WStorage7Controller::class, 'getBay'])->name('w-storage7.getBay');
            Route::GET('/workshop-ms/w-storage7/getTransferData', [WStorage7Controller::class, 'getTransferData'])->name('w-storage7.getTransferData');
            Route::POST('/workshop-ms/w-storage7/saveTransferData', [WStorage7Controller::class, 'saveTransferData'])->name('w-storage7.saveTransferData');
            Route::POST('/workshop-ms/w-storage7/saveUnitData', [WStorage7Controller::class, 'saveUnitData'])->name('w-storage7.saveUnitData');

        // WAREHOUSE 7
            Route::GET('/workshop-ms/w-storage8', [WStorage8Controller::class, 'index'])->name('w-storage8.index');
            Route::GET('/workshop-ms/w-storage8/getBayData', [WStorage8Controller::class, 'getBayData'])->name('w-storage8.getBayData');
            Route::GET('/workshop-ms/w-storage8/getBay', [WStorage8Controller::class, 'getBay'])->name('w-storage8.getBay');
            Route::GET('/workshop-ms/w-storage8/getTransferData', [WStorage8Controller::class, 'getTransferData'])->name('w-storage8.getTransferData');
            Route::POST('/workshop-ms/w-storage8/saveTransferData', [WStorage8Controller::class, 'saveTransferData'])->name('w-storage8.saveTransferData');
            Route::POST('/workshop-ms/w-storage8/saveUnitData', [WStorage8Controller::class, 'saveUnitData'])->name('w-storage8.saveUnitData');

// END OF WORKSHOP MONITORING SYSTEM

// START OF PDI MONITORING SYSTEM
    // MCI Monitoring
        Route::GET('/workshop-ms/mci-monitoring', [MCIController::class, 'index'])->name('mci-monitoring.index');

    // PDI Monitoring
        Route::GET('/workshop-ms/pdi-monitoring', [PDIController::class, 'index'])->name('pdi-monitoring.index');
// END OF PDI MONITORING SYSTEM


// START OF SYSTEM MANAGEMENT
    //Upper Bracket
        // Logs
            Route::get('/system-management/activity-logs', [ActivityLogsController::class, 'index'])->name('activity-logs.index');
            Route::GET('/system-management/activity-logs/getLogs', [ActivityLogsController::class, 'getLogs'])->name('activity-logs.getLogs');
            Route::get('/system-management/activity-logs/{search}', [ActivityLogsController::class, 'search']);

        // User Management
            Route::get('/system-management/user', [UserController::class, 'index'])->name('user.index');
            Route::get('/system-management/user/add', [UserController::class, 'create'])->name('user.create');
            Route::post('/system-management/user/store', [UserController::class, 'store'])->name('user.store');
            Route::get('/system-management/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::post('/system-management/user/update/{id}', [UserController::class, 'update'])->name('user.update');

        // Department Management
            Route::get('/system-management/department', [DepartmentController::class, 'index'])->name('department.index');
            Route::get('/system-management/department/add', [DepartmentController::class, 'create'])->name('department.create');
            Route::post('/system-management/department/store', [DepartmentController::class, 'store'])->name('department.store');
            Route::get('/system-management/department/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
            Route::post('/system-management/department/update/{id}', [DepartmentController::class, 'update'])->name('department.update');

    // Lower Bracket
        // Activity Management
            Route::get('/system-management/activity', [ActivityController::class, 'index'])->name('activity.index');
            Route::get('/system-management/activity/add', [ActivityController::class, 'create'])->name('activity.create');
            Route::post('/system-management/activity/store', [ActivityController::class, 'store'])->name('activity.store');
            Route::get('/system-management/activity/edit/{id}', [ActivityController::class, 'edit'])->name('activity.edit');
            Route::post('/system-management/activity/update/{id}', [ActivityController::class, 'update'])->name('activity.update');

        // Bay/Area Management
            Route::get('/system-management/area', [BayAreaController::class, 'index'])->name('area.index');
            Route::get('/system-management/area/add', [BayAreaController::class, 'create'])->name('area.create');
            Route::post('/system-management/area/store', [BayAreaController::class, 'store'])->name('area.store');
            Route::get('/system-management/area/edit/{id}', [BayAreaController::class, 'edit'])->name('area.edit');
            Route::post('/system-management/area/update/{id}', [BayAreaController::class, 'update'])->name('area.update');
            Route::get('/system-management/area/{search}', [BayAreaController::class, 'search']);

        // Brand Management
            Route::get('/system-management/brand', [BrandController::class, 'index'])->name('brand.index');
            Route::get('/system-management/brand/add', [BrandController::class, 'create'])->name('brand.create');
            Route::post('/system-management/brand/store', [BrandController::class, 'store'])->name('brand.store');
            Route::get('/system-management/brand/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
            Route::post('/system-management/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');

        // Category Management
            Route::get('/system-management/category', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/system-management/category/add', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/system-management/category/store', [CategoryController::class, 'store'])->name('category.store');
            Route::get('/system-management/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::post('/system-management/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        
        // Customer Management
            Route::get('/system-management/company', [CompanyController::class, 'index'])->name('company.index');
            Route::get('/system-management/company/add', [CompanyController::class, 'create'])->name('company.create');
            Route::post('/system-management/company/store', [CompanyController::class, 'store'])->name('company.store');
            Route::get('/system-management/company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
            Route::post('/system-management/company/update/{id}', [CompanyController::class, 'update'])->name('company.update');

        // Customer Area Management
            Route::get('/system-management/cust_area', [CustomerAreaController::class, 'index'])->name('cust_area.index');
            Route::get('/system-management/cust_area/add', [CustomerAreaController::class, 'create'])->name('cust_area.create');
            Route::post('/system-management/cust_area/store', [CustomerAreaController::class, 'store'])->name('cust_area.store');
            Route::get('/system-management/cust_area/edit/{id}', [CustomerAreaController::class, 'edit'])->name('cust_area.edit');
            Route::post('/system-management/cust_area/update/{id}', [CustomerAreaController::class, 'update'])->name('cust_area.update');

        // Staging Controller Management
            Route::get('/system-management/scl', [StagingController::class, 'index'])->name('scl.index');
            Route::get('/system-management/scl/add', [StagingController::class, 'create'])->name('scl.create');
            Route::post('/system-management/scl/store', [StagingController::class, 'store'])->name('scl.store');
            Route::get('/system-management/scl/edit/{id}', [StagingController::class, 'edit'])->name('scl.edit');
            Route::post('/system-management/scl/update/{id}', [StagingController::class, 'update'])->name('scl.update');

        // Mast Management
            Route::get('/system-management/mast', [MastController::class, 'index'])->name('mast.index');
            Route::get('/system-management/mast/add', [MastController::class, 'create'])->name('mast.create');
            Route::post('/system-management/mast/store', [MastController::class, 'store'])->name('mast.store');
            Route::get('/system-management/mast/edit/{id}', [MastController::class, 'edit'])->name('mast.edit');
            Route::post('/system-management/mast/update/{id}', [MastController::class, 'update'])->name('mast.update');

        // Model Management
            Route::get('/system-management/model', [XModelController::class, 'index'])->name('model.index');
            Route::get('/system-management/model/add', [XModelController::class, 'create'])->name('model.create');
            Route::post('/system-management/model/store', [XModelController::class, 'store'])->name('model.store');
            Route::get('/system-management/model/edit/{id}', [XModelController::class, 'edit'])->name('model.edit');
            Route::post('/system-management/model/update/{id}', [XModelController::class, 'update'])->name('model.update');
        
        // Parts Management
            Route::get('/system-management/parts', [PartsController::class, 'index'])->name('parts.index');
            Route::get('/system-management/parts/add', [PartsController::class, 'create'])->name('parts.create');
            Route::post('/system-management/parts/store', [PartsController::class, 'store'])->name('parts.store');
            Route::get('/system-management/parts/edit/{id}', [PartsController::class, 'edit'])->name('parts.edit');
            Route::post('/system-management/parts/update/{id}', [PartsController::class, 'update'])->name('parts.update');
            Route::get('/system-management/parts/{search}', [PartsController::class, 'search']);
             
        // Reason for Downtime Management
            Route::get('/system-management/reason', [ReasonsController::class, 'index'])->name('reason.index');
            Route::get('/system-management/reason/add', [ReasonsController::class, 'create'])->name('reason.create');
            Route::post('/system-management/reason/store', [ReasonsController::class, 'store'])->name('reason.store');
            Route::get('/system-management/reason/edit/{id}', [ReasonsController::class, 'edit'])->name('reason.edit');
            Route::post('/system-management/reason/update/{id}', [ReasonsController::class, 'update'])->name('reason.update');
            Route::get('/system-management/reason/{search}', [ReasonsController::class, 'search']);

        // Section Management
            Route::get('/system-management/section', [SectionController::class, 'index'])->name('section.index');
            Route::get('/system-management/section/add', [SectionController::class, 'create'])->name('section.create');
            Route::post('/system-management/section/store', [SectionController::class, 'store'])->name('section.store');
            Route::get('/system-management/section/edit/{id}', [SectionController::class, 'edit'])->name('section.edit');
            Route::post('/system-management/section/update/{id}', [SectionController::class, 'update'])->name('section.update');
            Route::get('/system-management/section/{search}', [SectionController::class, 'search']);

        // Technician Management
            Route::get('/system-management/technician', [TechnicianController::class, 'index'])->name('technician.index');
            Route::get('/system-management/technician/add', [TechnicianController::class, 'create'])->name('technician.create');
            Route::post('/system-management/technician/store', [TechnicianController::class, 'store'])->name('technician.store');
            Route::get('/system-management/technician/edit/{id}', [TechnicianController::class, 'edit'])->name('technician.edit');
            Route::post('/system-management/technician/update/{id}', [TechnicianController::class, 'update'])->name('technician.update');
// END OF SYSTEM MANAGEMENT
       
        //Route::get('/system-management/{category}',SystemManagementController@index);

require __DIR__.'/auth.php';
