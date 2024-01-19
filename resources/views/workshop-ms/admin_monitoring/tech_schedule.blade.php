@section('title','Workshop Monitoring System')
<x-app-layout>
    <style>
        #table_id2_length, #table_id1_length, #table_id2_filter {
            display: none;
        }

        #table_id{
            overflow: scroll;
        }

        button:disabled,button[disabled]{
            cursor: not-allowed;
            pointer-events: none;
            background-color: white;
            color: white;
            outline: none;
            border: none;
        }

    </style>
    <div style="height: calc(100vh - 60px);" class="py-1">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="grid grid-cols-1 gap-1">
                        <div class="mb-1 border-b border-gray-200">
                            <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center overflow-x-auto" id="myTab" data-tabs-toggle="#divSchedule" role="tablist">
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="technician-tab" data-tabs-target="#technician" type="button" role="tab" aria-controls="technician" aria-selected="false">Technician Scheduling</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="activityx-tab" data-tabs-target="#activityx" type="button" role="tab" aria-controls="activityx" aria-selected="false">Summary Report</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="baydetails-tab" data-tabs-target="#baydetails" type="button" role="tab" aria-controls="baydetails" aria-selected="false">Bay Details</button>
                                </li>
                            </ul>
                        </div>
                        <div id="divSchedule">
                    {{-- Technician Scheduling --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="technician" role="tabpanel" aria-labelledby="technician-tab">
                                <form action="" id="formSched">
                                  @csrf
                                    <div class="grid grid-cols-4 gap-1">
                                        <div class="col-span-3 place-self-center">
                                            <div class="grid grid-cols-5 gap-1">
                                                <input type="hidden" id="TSID" name="TSID">
                                                <div class="place-self-center">
                                                    <label for="TSName" class="block mb-1 text-sm text-gray-900 text-center">Technician Name</label>
                                                </div>
                                                <div class="">
                                                    <select id="TSName" name="TSName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" required>
                                                            <option value="" selected disabled></option>
                                                        @foreach ($tech as $techs)
                                                            <option value="{{$techs->id}}">{{$techs->initials}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="place-self-center">
                                                    <label for="TSDate" class="block mb-1 text-sm text-gray-900 text-center">Date</label>
                                                </div>
                                                <div class="col-span-2">
                                                    <div class="relative max-w-sm mb-1">
                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                        </div>
                                                        <input datepicker datepicker-format="mm/dd/yyyy" type="text" value="{{ date('m/d/Y') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" placeholder="Select Date" id="TSDate" name="TSDate">
                                                    </div>
                                                </div>
                                                <div class="place-self-center">
                                                    <label for="TSBayNum" class="block mb-1 text-sm text-gray-900 text-center">Bay Number</label>
                                                </div>
                                                <div class="">
                                                    <select id="TSBayNum" name="TSBayNum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" required>
                                                            <option value=""></option>
                                                        @foreach ($bay as $bays)
                                                            <option value="{{$bays->BayID}}">{{$bays->area_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="place-self-center">
                                                    <label for="TSJONum" class="block mb-1 text-sm text-gray-900 text-center">J.O. Number</label>
                                                </div>
                                                <div class="col-span-2">
                                                    <input type="text" id="TSJONum" name="TSJONum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none" required>
                                                    <input type="hidden" id="TSPOUID" name="TSPOUID">
                                                </div>
                                                <div class="place-self-center">
                                                    <label for="TSSoW" class="block mb-1 text-sm text-gray-900 text-center">Scope of Work</label>
                                                </div>
                                                <div class="col-span-2">
                                                    <input type="text" id="TSSoW" name="TSSoW" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" required>
                                                </div>
                                                <div class="col-span-2"></div>
                                                <div class="place-self-center">
                                                    <label for="TSActivity" class="block mb-1 text-sm text-gray-900 text-center">Activity</label>
                                                </div>
                                                <div class="col-span-4">
                                                    <input type="text" id="TSActivity" name="TSActivity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content-start">
                                            <div class="">
                                                <button type="button" id="saveSchedule" name="saveSchedule" class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-1/2">SAVE</button>
                                                <button type="button" id="saveScheduleH" name="saveScheduleH" class="hidden text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-1/2">SAVE</button>
                                            </div>
                                            <div class="mt-1">
                                                <button type="button" id="deleteSchedule" name="deleteSchedule" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-1/2">DELETE</button>
                                            </div>
                                            <div class="mt-1">
                                                <button type="button" id="clearSchedule" name="clearSchedule" class="text-white bg-gray-600 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-1/2">CLEAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                    <hr class="mt-2 mb-2">
                                    <div style="height: calc(100vh - 25em);" class="gap-1 overflow-y-auto">
                                        <table class="w-full text-sm text-left text-gray-500">
                                            <thead class="text-xs text-gray-700 uppercase bg-white" style="position: sticky; top: 0;">
                                                <tr>
                                                    <th scope="col" class="px-6 py-2">
                                                        Technician
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Bay Number
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        JO Number
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Date
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Scope of Work
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Activity
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="techTable" class="">
                                                @foreach ($techsched as $techscheds)
                                                <tr class="techTable bg-white border-b hover:bg-gray-300">
                                                    <td class="px-6 py-1">
                                                        <span data-id="{{$techscheds->id}}">
                                                            {{$techscheds->techname}}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techscheds->bayname}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techscheds->JONumber}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techscheds->scheddate}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techscheds->scopeofwork}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techscheds->activity}}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr class="mt-2 mb-2">
                                    <div class="grid grid-cols-8 gap-1">
                                        <div class="place-self-center">
                                            <label for="" class="block mb-1 text-sm text-gray-900 text-center">From</label>
                                        </div>
                                        <div class="col-span-2 input-group input-daterange">
                                            <div class="relative max-w-sm mb-1">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input datepicker datepicker-format="mm/dd/yyyy" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" name="fromDate" id="fromDate" placeholder="From Date">
                                            </div>
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block mb-1 text-sm text-gray-900 text-center">To</label>
                                        </div>
                                        <div class="col-span-2 input-group input-daterange">
                                            <div class="relative max-w-sm mb-1">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input datepicker datepicker-format="mm/dd/yyyy" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="toDate" id="toDate" placeholder="To Date">
                                            </div>
                                        </div>
                                        <div class="self-center">
                                            <div class="">
                                                <button type="button" id="viewSchedule" name="viewSchedule" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full">VIEW</button>
                                            </div>
                                        </div>
                                        <div class="self-center">
                                            <div class="">
                                                <button type="button" id="resetSchedule" name="resetSchedule" class="text-white bg-yellow-600 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full">RESET</button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                    {{-- Summary Report --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="activityx" role="tabpanel" aria-labelledby="activityx-tab">
                                <div class="grid grid-cols-6 gap-1">
                                    <div class="col-span-5">
                                        <div class="grid grid-cols-2">
                                            <div class="grid grid-rows-2">
                                                <div class="grid grid-cols-3">
                                                    <div class="place-self-center">
                                                        <label for="" class="block mb-1 text-sm text-gray-900 text-center">From</label>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <div class="relative max-w-sm mb-2">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                            </div>
                                                            <input datepicker datepicker-format="mm/dd/yyyy" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" id="fromDateS" name="fromDateS" placeholder="From Date">
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="grid grid-cols-3">
                                                    <div class="place-self-center">
                                                        <label for="" class="block mb-1 text-sm text-gray-900 text-center">To</label>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <div class="relative max-w-sm mb-2">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                            </div>
                                                            <input datepicker datepicker-format="mm/dd/yyyy" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" id="toDateS" name="toDateS" placeholder="To Date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="grid grid-rows-2">
                                                <div class="grid grid-cols-3">
                                                    <div class="place-self-center">
                                                        <label for="" class="block mb-1 text-sm text-gray-900 text-center">Technician</label>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <select id="techname0" name="techname0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                                                                <option value="" selected disabled></option>
                                                            @foreach ($tech as $techs)
                                                                <option value="{{$techs->id}}">{{$techs->name}} / {{$techs->initials}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class=""></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <button type="button" id="searchSchedule" name="searchSchedule" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full mb-1">SEARCH</button>
                                        <button type="button" id="generateSReport" name="generateSReport" class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full mb-1">GENERATE</button>
                                        <button type="button" id="clearSearch" name="clearSearch" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full">RESET</button>
                                    </div>
                                </div>
                                <div style="height: calc(100vh - 15em);" class="gap-1 overflow-y-auto">
                                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        <table class="w-full text-sm text-left text-gray-500">
                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-2">
                                                        Technician
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Bay Number
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Date
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Scope of Work
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Activity
                                                    </th>
                                                    <th scope="col" class="px-6 py-2">
                                                        Status
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="techTable2" class="overflow-y-auto">
                                                @foreach ($techschedX as $techschedXS)
                                                <tr class="techTable2 bg-white border-b hover:bg-gray-300">
                                                    <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                                        <span data-id="{{$techschedXS->id}}">
                                                            {{$techschedXS->techname}}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techschedXS->bayname}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techschedXS->scheddate}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techschedXS->scopeofwork}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        {{$techschedXS->activity}}
                                                    </td>
                                                    <td class="px-6 py-1">
                                                        @if ($techschedXS->TSStatus == 1)
                                                            PENDING
                                                        @else
                                                            DONE 
                                                        @endif 
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    {{-- Bay Details --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="baydetails" role="tabpanel" aria-labelledby="baydetails-tab">
                                {{-- <div id='calendar-container' style="height: 80vh">
                                    <div id='calendar'></div>
                                    <div id="bay-names" class="bay-names"></div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- SUCCESS MODAL --}}
        <div id="success-modal" class="fixed items-center top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="bg-green-200 rounded-lg shadow-xl border border-gray-200 w-80 mx-auto p-4">
                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-12 w-12">
                        <circle cx="12" cy="12" r="11" fill="#4CAF50"/>
                        <path fill="#FFFFFF" d="M9.25 15.25L5.75 11.75L4.75 12.75L9.25 17.25L19.25 7.25L18.25 6.25L9.25 15.25Z"/>
                        </svg>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Success!</h3>
                    <p class="text-sm text-gray-500">Your changes have been saved.</p>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button id="SCloseButton" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">Close</button>
                </div>
            </div>
        </div>
    {{-- FAILED MODAL --}}
        <div id="failed-modal" class="fixed items-center top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="bg-red-200 rounded-lg shadow-lg w-80 mx-auto p-4">
              <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-12 w-12">
                    <circle cx="12" cy="12" r="10" fill="#f44336"/>
                    <path d="M8.46 8.46L15.54 15.54M8.46 15.54L15.54 8.46" stroke="#fff" stroke-width="2"/>
                </svg>
              </div>
              <div class="mt-4 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Failed!</h3>
                <p class="text-xs text-gray-900">Your changes could not be saved. Please try again.</p>
              </div>
              <div class="mt-5 sm:mt-6">
                <button id="FCloseButton" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">Close</button>
              </div>
            </div>
        </div>
    <script>
        $(document).ready(function(){
        // For Calendar
                // jQuery(document).on("click", "#baydetails-tab", function(){
                    //     var _token = $('input[name="_token"]').val();
                        
                    //     // Calendar
                    //     var calendarEl = document.getElementById('calendar');

                    //     var calendar = new FullCalendar.Calendar(calendarEl, {
                    //         initialView: 'dayGridWeek',
                    //         headerToolbar: {
                    //             left: '',
                    //             center: 'title',
                    //             right: 'today prev,next'
                    //         },
                    //         weekends: true,
                    //         // hiddenDays: [0], // hide Sundays
                    //         allDaySlot: true,
                    //         displayEventTime: false,
                    //         height: 635,
                    //         events: function(info, successCallback) {
                    //             $.ajax({
                    //                 url: '{{ route('admin_monitoring.tech_schedule.getEvents') }}',
                    //                 type: 'GET',
                    //                 // data: { bay: bay, _token: _token,},
                    //                 success: function(response) {
                    //                     var formattedEvents = response.map(function(event) {
                    //                         return {
                    //                             title: event.title,
                    //                             start: event.start,
                    //                             end: event.end,
                    //                             color: event.color
                    //                         };
                    //                     });
                    //                     successCallback(formattedEvents);
                    //                 },
                    //                 error: function(xhr) {
                    //                     console.log(xhr.responseText);
                    //                 }
                    //             });
                    //         }
                    //     });

                    //     calendar.render();
                // });
            jQuery(document).on("click", "#baydetails-tab", function(){
                    var _token = $('input[name="_token"]').val();
                // Calendar
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridWeek',
                            headerToolbar: {
                            left: '',
                            center: 'title',
                            right: 'today prev,next'
                            },
                        weekends: true,
                        hiddenDays: [0], // hide Sundays
                        allDaySlot: true,
                        displayEventTime: false,
                        height: 635,
                        events: function(info, successCallback) {
                            $.ajax({
                                url: '{{ route('admin_monitoring.tech_schedule.getEvents') }}',
                                type: 'GET',
                                success: function(response) {
                                    var formattedEvents = response.map(function(event) {
                                        return {
                                            title: event.area_name + ' (' + event.title + ')',
                                            start: event.start,
                                            end: event.end,
                                            color: event.color,
                                        };
                                    });

                                    successCallback(formattedEvents);
                                },
                                error: function(xhr) {
                                    console.log(xhr.responseText);
                                }
                            });
                        },
                    });

                calendar.render();
            });

        // 
            jQuery(document).on( "click", "#SCloseButton", function(){
                $("#success-modal").removeClass("flex");
                $("#success-modal").addClass("hidden");
            });
        // 
            jQuery(document).on( "click", "#FCloseButton", function(){
                $("#failed-modal").removeClass("flex");
                $("#failed-modal").addClass("hidden");
            });

        // Save Schedule 
            $('#saveSchedule').on( "click", function(){
                var dropdown1 = document.getElementById("TSName");
                var selectedValue1 = dropdown1.value;

                
                var dropdown2 = document.getElementById("TSBayNum");
                var selectedValue2 = dropdown2.value; 

                if (selectedValue1 === '' || selectedValue2 === '' || $('#TSJONum').val() === '' || $('#TSSoW').val() === '' || $('#TSActivity').val() === ''){
                    $("#failed-modal").removeClass("hidden");
                    $("#failed-modal").addClass("flex");
                    $('#saveScheduleH').prop("disabled", false);
                } else {
                    $('#saveScheduleH').click();
                }
            });

            // jQuery(document).on( "click", "#saveScheduleH", function(){
            $('#saveScheduleH').on( "click", function(){
                
                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.saveSchedule') }}",
                    method:"POST",
                    dataType: 'json',
                    data: $('#formSched').serialize(),
                    success:function(result){
                        $('#techTable').html(result)
                        document.getElementById('formSched').reset();
                        $('#TSPOUID').val('');
                        $('#TSID').val('');
                        $('#TSJONum').val('');

                        $("#success-modal").removeClass("hidden");
                        $("#success-modal").addClass("flex");
                    },
                    error: function(error){
                        $("#failed-modal").removeClass("hidden");
                        $("#failed-modal").addClass("flex");
                    }
                });
            });

            jQuery(document).on( "click", "#clearSchedule", function(){
                $('#TSJONum').val('');
                $('#TSPOUID').val('');
                $('#TSID').val('');
                document.getElementById('formSched').reset();
            });

            jQuery(document).on( "click", "#techTable tr", function(){
                var id = $(this).find('span').data('id');
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.getSchedule') }}",
                    method:"POST",
                    dataType: 'JSON',
                    data:{ id: id, _token: _token, },
                    success:function(result){
                        $('#TSID').val(result.TSID);
                        $('#TSName').val(result.TSName);
                        $('#TSBayNum').val(result.TSBayNum);
                        $('#TSJONum').val(result.TSJONum);
                        $('#TSPOUID').val(result.POUID);
                        $('#TSDate').val(result.TSDate);
                        $('#TSSoW').val(result.TSSoW);
                        $('#TSActivity').val(result.TSActivity);
                    }
                });
            })

            jQuery(document).on( "click", "#deleteSchedule", function(){
                var TSID = $('#TSID').val();
                var TSPOUID = $('#TSPOUID').val();
                var _token = $('input[name="_token"]').val();
                
                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.deleteSchedule') }}",
                    method:"POST",
                    dataType: 'json',
                    data:{ TSID: TSID, TSPOUID: TSPOUID, _token: _token, },
                    success:function(result){
                        $('#TSJONum').val('');
                        $('#TSPOUID').val('');
                        $('#TSID').val('');
                        $('#techTable').html(result)
                        document.getElementById('formSched').reset();

                        $("#success-modal").removeClass("hidden");
                        $("#success-modal").addClass("flex");
                    },
                    error: function(error){
                        $("#failed-modal").removeClass("hidden");
                        $("#failed-modal").addClass("flex");
                    }
                });
            });

            jQuery(document).on( "click", "#viewSchedule", function(){
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.filterSchedule') }}",
                    method:"POST",
                    data:{ fromDate: fromDate, toDate: toDate, _token: _token },
                    success:function(result,){
                        $('#techTable').html(result);
                    }
                });
            });

            jQuery(document).on( "click", "#resetSchedule", function(){
                var _token = $('input[name="_token"]').val();
                        $('#fromDate').val('');
                        $('#toDate').val('');

                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.resetSchedule') }}",
                    method:"POST",
                    data:{ _token: _token },
                    success:function(result,){
                        $('#techTable').html(result);
                    }
                });
            });

            jQuery(document).on( "click", "#searchSchedule", function(){
                var fromDateS = $('#fromDateS').val();
                var toDateS = $('#toDateS').val();
                var techname0 = $('#techname0').val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.filterScheduleX') }}",
                    method:"POST",
                    data:{ fromDateS: fromDateS, toDateS: toDateS, techname0: techname0, _token: _token },
                    success:function(result,){
                        $('#techTable2').html(result);
                    }
                });
            });

            jQuery(document).on( "change", "#TSBayNum", function(){
                var TSBayNum = $(this).val();
                var _token = $('input[name="_token"]').val();
                
                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.getJONum') }}",
                    method:"GET",
                    dataType: "JSON",
                    data:{ TSBayNum: TSBayNum, _token: _token },
                    success:function(result,){
                        $('#TSJONum').val(result.WSID);
                        $('#TSPOUID').val(result.POUID);
                    }
                });
            });

            jQuery(document).on( "click", "#clearSearch", function(){
                var _token = $('input[name="_token"]').val();
                    $('#fromDateS').val('');
                    $('#toDateS').val('');
                    $('#techname0').val('');

                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.clearSearch') }}",
                    method:"POST",
                    data:{ _token: _token },
                    success:function(result,){
                        $('#techTable2').html(result);
                    }
                });
            });

            jQuery(document).on( "click", "#generateSReport", function(){
                var fromDateS = $('#fromDateS').val();
                var toDateS = $('#toDateS').val();
                var techname0 = $('#techname0').val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.generateSReport') }}",
                    method:"POST",
                    data:{ fromDateS: fromDateS, toDateS: toDateS, techname0: techname0, _token: _token },
                    success: function(response) {
                        var link = document.createElement('a');
                        link.href = 'data:text/csv;charset=utf-8,' + encodeURI(response);
                        link.target = '_blank';
                        link.download = 'Schedule Report.csv';
                        link.click();
                    },
                });
            });

        } );
    </script>
</x-app-layout>
