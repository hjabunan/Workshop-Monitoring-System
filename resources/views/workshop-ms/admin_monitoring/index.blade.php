<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-black text-2xl text-blue-600 leading-tight">
            {{ __('Department Management') }}
        </h2>
    </x-slot> --}}

    <style>
        select[name="tdept_length"] {
            padding-right: 40px !important;
        }
        
        .bg-gray-900{
            opacity: 40% !important;
        }

        /* width */
        ::-webkit-scrollbar {
          width: 10px;
          height: 10px;
        }
        
        /* Track */
        ::-webkit-scrollbar-track {
          box-shadow: inset 0 0 2px grey; 
          border-radius: 10px;
        }
         
        /* Handle */
        ::-webkit-scrollbar-thumb {
          background: #4B5563; 
          border-radius: 10px;
        }
        
        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
          background: rgb(95, 95, 110); 
        }

        #table_id_length {
            display: ;
        }
    </style>

    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            BT Workshop
                        </div>
                    </div>

{{-- Start Workshop --}}

                <!-- Modal toggle -->
                    <div class="grid grid-cols-8 mt-5">
                    <div class="">
                        <button data-modal-target="extralarge-modal2" data-modal-toggle="extralarge-modal2" class="block text-white bg-gray-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            Bay 11
                        </button>
                    </div>
                    <div class="">
                        <button data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="block text-white bg-gray-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            CA1
                        </button>
                    </div>
                    <div class="">
                        <button data-modal-target="extralarge-modal3" data-modal-toggle="extralarge-modal3" class="block text-white bg-gray-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            D1
                        </button>
                    </div>
                    <div class="">
                        <button data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="block text-white bg-gray-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            FA
                        </button>
                    </div>
                    <div class="">
                        <button data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="block text-white bg-gray-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            PA
                        </button>
                    </div>
                    <div class="">
                        <button data-modal-target="defaultModalsm" data-modal-toggle="defaultModalsm" class="block text-white bg-gray-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                            U1
                        </button>
                    </div>
                    <div class="col-span-2">
                        <label class="font-medium">LEGEND:</label>
                        <div class="grid grid-rows-5 gap-1 mt-2 ml-8">
                            <div class="">
                                <div style="float: left;" class="mr-2 w-12 h-6 bg-gray-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>Vacant</label>
                            </div>
                            <div class="">
                                <div style="float: left;" class="mr-2 w-12 h-6 bg-green-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>On-Schedule</label>
                            </div>
                            <div class="">
                                <div style="float: left;" class="mr-2 w-12 h-6 bg-yellow-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>Caution</label>
                            </div>
                            <div class="">
                                <div style="float: left;" class="mr-2 w-12 h-6 bg-red-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>Critical</label>
                            </div>
                            <div class="">
                                <div style="float: left;" class="mr-2 w-12 h-6 bg-blue-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>Delivery Bay</label>
                            </div>
                        </div>
                    </div>
                </div>
                
<!-- Start First Modal -->
                        <div id="defaultModalsm" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-2 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                            <div class="relative w-full h-full max-w-2xl md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow h-full">
                                    <!-- Modal header -->
                                    <div class="grid items-start justify-between px-4 pt-2 rounded-t">
                                        <div class="grid grid-cols-4">
                                            <div class="mb-2">
                                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Activity Type</label>
                                                <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg sm:text-xs focus:ring-blue-500 focus:border-blue-500 block w-3/4 p-2.5" name="role" >
                                                    <option value="0">Parking</option>
                                                    <option value="1">Workshop</option>
                                                </select>
                                            </div>
                                            <div class="mb-2 col-span-1">
                                                <label for="idnum" class="block mb-2 text-sm font-medium text-gray-900">Job Order Number</label>
                                                <input type="text" id="idnum" name="idnum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-3/4 p-2.5" value="" readonly>
                                            </div>
                                            <div class="mb-2">
                                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                                <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg sm:text-xs focus:ring-blue-500 focus:border-blue-500 block w-3/4 p-2.5" name="role">
                                                    <option value="0">Active</option>
                                                    <option value="1">Done</option>
                                                    <option value="2">Vacant</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Bay Number</label>
                                                <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg sm:text-xs focus:ring-blue-500 focus:border-blue-500 block w-3/4 p-2.5" name="role">
                                                    <option value="0">Bay 0</option>
                                                    <option value="1">Bay 1</option>
                                                    <option value="2">Bay 2</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal body -->
                                    <hr>
                                    <div style="max-height: calc(100vh - 200px);" class="grid px-3 pb-3 space-y-1 h-full overflow-y-auto">
                                        <div class="text-left gap-1">
                                            <label for="fifo" class="block mb-1 text-md font-medium text-gray-900">FIFO : First In First Out Process</label>
                                        </div>
                                        <div class="grid grid-cols-4 gap-1">
                                            <div class="place-self-center">
                                                <label for="unitm" class="block text-sm text-gray-900">Unit</label>
                                            </div>
                                            <div class="">
                                                <input type="text" id="unitm" name="unitm" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                            </div>
                                            <div class="place-self-center">
                                                <label for="utype" class="block text-sm text-gray-900">Type</label>
                                            </div>
                                            <div class="">
                                                <select id="utype" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="utype">
                                                    <option value="0">F</option>
                                                    <option value="1">V</option>
                                                    <option value="2">M</option>
                                                </select>
                                            </div>
                                            <div class="place-self-center">
                                                <label for="serialnum" class="block text-sm text-gray-900">Serial No.</label>
                                            </div>
                                            <div class="place-self-center">
                                                <input type="text" id="serialnum" name="serialnum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                            </div>
                                            <div class="place-self-center">
                                                <label for="codez" class="block text-sm text-gray-900">Code</label>
                                            </div>
                                            <div class="">
                                                <input type="text" id="codez" name="codez" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                            </div>
                                            <div class="place-self-center">
                                                <label for="file_input" class="block text-sm text-gray-900">Attachment</label>
                                            </div>
                                            <div class="col-span-2">
                                                <input class="block w-full text-sm sm:text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="file_input" type="file">
                                            </div>
                                            <div class="place-self-center">
                                            </div>
                                            <div class="place-self-center">
                                                <label for="mastt" class="block text-sm text-gray-900">Mast Type</label>
                                            </div>
                                            <div class="">
                                                <select id="mastt" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="mastt">
                                                    <option value="0">F</option>
                                                    <option value="1">V</option>
                                                    <option value="2">M</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2 row-span-4 place-self-center">
                                                <label for="" class="block text-sm text-gray-900 text-center">Running Days</label>
                                                <input type="text" id="" name="" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1 font-medium" value="30" readonly>
                                            </div>
                                            <div class="place-self-center">
                                                <label for="" class="block text-sm text-gray-900">Arrival Date</label>
                                            </div>
                                            <div class="">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                      <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Arrival Date">
                                                </div>
                                            </div>
                                            <div class="place-self-center">
                                                <label for="pic" class="block text-sm text-gray-900">P.I.C.</label>
                                            </div>
                                            <div class="place-self-center">
                                                <input type="text" id="pic" name="pic" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                            </div>
                                            <div class="place-self-center">
                                                <label for="verby" class="block text-sm text-gray-900">Verified By</label>
                                            </div>
                                            <div class="">
                                                <select id="veryby" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="verby">
                                                    <option value="0">Tech 1</option>
                                                    <option value="1">Tech 2</option>
                                                    <option value="2">Tech 3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-3 gap-1 place-items-center">
                                            <div class="flex items-center mb-1">
                                                <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                <label for="default-radio" class="ml-2 text-sm font-medium text-gray-900">New Unit</label>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                <label for="default-radio" class="ml-2 text-sm font-medium text-gray-900">Old Unit</label>
                                            </div>
                                            <div class="flex items-center mb-1">
                                                <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                <label for="default-radio" class="ml-2 text-sm font-medium text-gray-900">Repair Unit</label>
                                            </div>
                                        </div>
                                        <hr class="mt-3 mb-3">
                                        <div class="">
                                            <div class="text-left gap-1">
                                                <label for="legz" class="block mb-1 text-md font-medium text-red-500">LEGEND</label>
                                            </div>
                                            <div class="grid grid-cols-5 gap-1">
                                                <div class="col-span-2 place-self-center">
                                                    <label for=green" class="block text-sm text-gray-900">Shorter than Target</label>
                                                </div>
                                                <div class="col-span-2 place-self-center">
                                                    <label for="green" class="block text-sm text-gray-900">1-3 Months or 90 Days</label>
                                                </div>
                                                <div style="float: right;" name="green" class="w-full h-6 bg-green-500 rounded ring-1 ring-inset ring-black ring-opacity-0">
                                                </div>
                                                <div class="col-span-2 place-self-center">
                                                    <label for="yellow" class="block text-sm text-gray-900">Caution</label>
                                                </div>
                                                <div class="col-span-2 place-self-center">
                                                    <label for="yellow" class="block text-sm text-gray-900">(4-6 MOnths or 180 days)</label>
                                                </div>
                                                <div style="float: right;" name="yellow" class="w-full h-6 bg-yellow-400 rounded ring-1 ring-inset ring-black ring-opacity-0">
                                                </div>
                                                <div class="col-span-2 place-self-center">
                                                    <label for="red" class="block text-sm text-gray-900">Danger</label>
                                                </div>
                                                <div class="col-span-2 place-self-center">
                                                    <label for="red" class="block text-sm text-gray-900">7-9 Months or 270/days</label>
                                                </div>
                                                <div style="float: right;" name="red" class="w-full h-6 bg-red-500 rounded ring-1 ring-inset ring-black ring-opacity-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex items-center p-2 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button data-modal-hide="defaultModalsm" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Save</button>
                                        <button data-modal-hide="defaultModalsm" type="button" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Transfer</button>
                                        <button data-modal-hide="defaultModalsm" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Exit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
{{-- End First Modal --}}
<!-- Start Second Modal -->
                        <div id="extralarge-modal2" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                            <div class="relative w-full h-full max-w-7xl md:h-auto">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    {{-- <div class="flex items-center justify-between border-b rounded-t"> </div> --}}
                                    <!-- Modal body -->
                                    <div class="p-2 space-y-6">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="text-center">
                                                {{-- Top Body --}}
                                                <div class="grid grid-cols-4 gap-1">
                                                    <div class="mb-1 gap">
                                                        <label for="role" class="block mb-1 text-sm font-medium text-gray-900">Activity Type</label>
                                                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg sm:text-xs focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="role">
                                                            <option value="0">Parking</option>
                                                            <option value="1">Workshop</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-1">
                                                        <label for="idnum" class="block mb-1 text-sm font-medium text-gray-900">Job Order Number</label>
                                                        <input type="text" id="idnum" name="idnum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="" readonly>
                                                    </div>
                                                    <div class="mb-1">
                                                        <label for="role" class="block mb-1 text-sm font-medium text-gray-900">Status</label>
                                                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg sm:text-xs focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="role">
                                                            <option value="0">Active</option>
                                                            <option value="1">Done</option>
                                                            <option value="2">Vacant</option>
                                                        </select>
                                                    </div>
                                                    {{--For Edit--}}
                                                    <div class="mb-1">
                                                        <label for="role" class="block mb-1 text-sm font-medium text-gray-900">Bay Number</label>
                                                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg sm:text-xs focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="role">
                                                            <option value="0">Bay 0</option>
                                                            <option value="1">Bay 1</option>
                                                            <option value="2">Bay 2</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr>
                                                {{-- Information Body --}}
                                                <div style="max-height: calc(100vh - 300px);" class="grid grid-cols-1 gap-1">
                                                    <div class="text-left gap-1">
                                                        <label for="information" class="block mb-1 text-md font-medium text-gray-900">Information</label>
                                                    </div>
                                                    <div class="gap-1">
                                                        <div class="mb-1 border-b border-gray-200">
                                                            <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center overflow-x-auto" id="myTab" data-tabs-toggle="#myTabContent1" role="tablist">
                                                                <li class="mr-2" role="presentation">
                                                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="legend-tab" data-tabs-target="#legend" type="button" role="tab" aria-controls="legend" aria-selected="false">Legend</button>
                                                                </li>
                                                                <li class="mr-2" role="presentation">
                                                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="tdetail-tab" data-tabs-target="#tdetail" type="button" role="tab" aria-controls="tdetail" aria-selected="false">Truck Detail</button>
                                                                </li>
                                                                <li class="mr-2" role="presentation">
                                                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="plan-tab" data-tabs-target="#plan" type="button" role="tab" aria-controls="plan" aria-selected="false">Plan</button>
                                                                </li>
                                                                <li class="mr-2" role="presentation">
                                                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="activity-tab" data-tabs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">Activity</button>
                                                                </li>
                                                                <li class="mr-2" role="presentation">
                                                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="partinfo-tab" data-tabs-target="#partinfo" type="button" role="tab" aria-controls="partinfo" aria-selected="false">Parts Info.</button>
                                                                </li>
                                                                <li class="mr-2" role="presentation">
                                                                    <button class="inline-block p-4 border-b-2 rounded-t-lg" id="downtime-tab" data-tabs-target="#downtime" type="button" role="tab" aria-controls="downtime" aria-selected="false">Downtime</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div id="myTabContent1">
                                                            {{-- LEGEND TAB --}}
                                                            <div class="hidden p-2 rounded-lg bg-gray-50" id="legend" role="tabpanel" aria-labelledby="legend-tab">
                                                                {{-- Info Top --}}
                                                                <div class="rounded-lg bg-gray-50 content-center">
                                                                    <div class="pb-1">
                                                                        <label for="green" class="text-sm">On Schedule - (Within the Target Date)</label><div style="float: right;" name="green" class="w-1/4 h-6 bg-green-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div>
                                                                    </div>
                                                                    <div class="pb-1">
                                                                        <label for="yellow" class="text-sm">Caution - (7 Days Before the Target Date)</label><div style="float: right;" name="yellow" class="w-1/4 h-6 bg-yellow-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div>
                                                                    </div>
                                                                    <div class="pb-1">
                                                                        <label for="red" class="text-sm">Critical - (Above the Target Date)</label><div style="float: right;" name="red" class="w-1/4 h-6 bg-red-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div>
                                                                    </div>
                                                                </div>
                                                                <hr class="mt-5 mb-5">
                                                                {{-- Technician Body --}}
                                                                <div class="grid grid-cols-1 gap-1">
                                                                    <div class="mb-1 border-b border-gray-200">
                                                                        <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center overflow-x-auto" id="myTab" data-tabs-toggle="#myTabContent2" role="tablist">
                                                                            <li class="mr-2" role="presentation">
                                                                                <button class="inline-block p-2 border-b-2 rounded-t-lg" id="technician-tab" data-tabs-target="#technician" type="button" role="tab" aria-controls="technician" aria-selected="false">Technician</button>
                                                                            </li>
                                                                            <li class="mr-2" role="presentation">
                                                                                <button class="inline-block p-2 border-b-2 rounded-t-lg" id="activityx-tab" data-tabs-target="#activityx" type="button" role="tab" aria-controls="activityx" aria-selected="false">Activity</button>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div id="myTabContent2">
                                                                        <div class="hidden p-2 rounded-lg bg-gray-50" id="technician" role="tabpanel" aria-labelledby="technician-tab">
                                                                            <div class="grid grid-cols-2 gap-2">
                                                                                <div class="">
                                                                                    <div class="text-left">
                                                                                        <label for="tic" class="block mb-1 text-sm text-gray-900 text-center">Technician In-Charge</label>
                                                                                        <div class="mb-2">
                                                                                            <select id="tic" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full gap-2 p-2" name="tic">
                                                                                                <option value="0">Tech Name</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="flex justify-center">
                                                                                            <button type="button" class="text-black bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-2 hover:text-gray-900 focus:z-10">View Schedule</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="">
                                                                                    <div class="text-left gap-1">
                                                                                        <label for="tics" class="block mb-1 text-sm text-gray-900 text-center">Technician In-Charge Schedule</label>
                                                                                        <div class="relative max-w-sm mb-2">
                                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                            </div>
                                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Start Date">
                                                                                        </div>
                                                                                        <div class="relative max-w-sm">
                                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                            </div>
                                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Start Date">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="hidden p-4 rounded-lg bg-gray-50" id="activityx" role="tabpanel" aria-labelledby="activityx-tab">
                                                                            <div class="grid grid-cols-2 gap-4">
                                                                                <div>
                                                                                    <label for="aotd" class="block mb-1 text-sm text-gray-900 text-center">Activity of the Day</label>
                                                                                    <input type="text" id="aotd" name="aotd" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="" disabled>
                                                                                </div>
                                                                                <div class="grid grid-cols-3 gap-2">
                                                                                    <div class="place-self-center">
                                                                                        <label for="statusa" class="block mb-1 text-sm text-gray-900 text-center">Status</label>
                                                                                    </div>
                                                                                    <div class="col-span-2 place-self-center">
                                                                                        <select id="statusa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full gap-2 p-2" name="statusa">
                                                                                            <option value="0">Done</option>
                                                                                            <option value="1">Pending</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="place-self-center">
                                                                                        <label for="remarksa" class="block mb-1 text-sm text-gray-900 text-center">Remarks</label>
                                                                                    </div>
                                                                                    <div class="col-span-2 place-self-center">
                                                                                        <input type="text" id="remarksa" name="remarksa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- TRUCK DETAIL TAB --}}
                                                            <div class="hidden p-4 rounded-lg bg-gray-50" id="tdetail" role="tabpanel" aria-labelledby="tdetail-tab">
                                                                <div class="grid grid-cols-2 text-xs gap-1 text-center">
                                                                    <div class="place-self-center">
                                                                        <label for="codeb" class="block text-sm font-medium text-gray-900">Code</label>
                                                                    </div>
                                                                    <div class="">
                                                                        <input type="text" id="codeb" name="codeb" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center font-medium py-1">
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="companynamea" class="block text-sm font-medium text-gray-900">Company Name</label>
                                                                    </div>
                                                                    <div class="">
                                                                        <select id="companynamea" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="companynamea">
                                                                            <option value="0">HII</option>
                                                                            <option value="1">DBShenker</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="place-self-center"><label for="branda" class="block text-sm font-medium text-gray-900">Brand</label></div>
                                                                    <div class="">
                                                                        <select id="branda" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="branda">
                                                                            <option value="0">Toyota</option>
                                                                            <option value="1">BT</option>
                                                                            <option value="2">Raymond</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="serialno" class="block text-sm font-medium text-gray-900">Serial Number</label>
                                                                    </div>
                                                                    <div class="">
                                                                        <input type="text" id="serialno" name="serialno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                                                    </div>
                                                                    <div class="place-self-center"><label for="modelb" class="block text-sm font-medium text-gray-900">Model</label></div>
                                                                    <div class="">
                                                                        <input type="text" id="modelb" name="modelb" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                                                    </div>
                                                                    <div class="place-self-center"><label for="masttype" class="block text-sm font-medium text-gray-900">Mast Type</label></div>
                                                                    <div class="">
                                                                        <select id="masttype" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="masttype">
                                                                            <option value="0">F</option>
                                                                            <option value="1">V</option>
                                                                            <option value="2">M</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="place-self-center"><label for="unittype" class="block text-sm font-medium text-gray-900">Unit Type</label></div>
                                                                    <div class="">
                                                                        <select id="unittype" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="unittype">
                                                                            <option value="0">BT ReachTruck</option>
                                                                            <option value="1">Toyota ReachTruck</option>
                                                                            <option value="2">Toyota Forklift</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="place-self-center"><label for="scopew" class="block text-sm font-medium text-gray-900">Scope of Work</label></div>
                                                                    <div class="">
                                                                        <input type="text" id="scopew" name="scopew" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- PLAN TAB --}}
                                                            <div class="hidden p-1 rounded-lg bg-gray-50" id="plan" role="tabpanel" aria-labelledby="plan-tab">
                                                                <div class="grid grid-cols-4 text-xs gap-1 text-center">
                                                                    <div class="col-span-2 place-self-center">
                                                                        <label for="" class="block text-sm font-medium text-gray-900">PLAN</label>
                                                                    </div>
                                                                    <div class="col-span-2 row-span-3 place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">TOTAL</label>
                                                                        <input type="text" id="" name="" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1 font-medium" value="30" readonly>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Date Started</label>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Start Date">
                                                                        </div>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Target Date</label>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Target Date">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-span-2 place-self-center">
                                                                        <label for="" class="block text-sm font-medium text-gray-900">ACTUAL</label>
                                                                    </div>
                                                                    <div class="col-span-2 row-span-3 place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">TOTAL</label>
                                                                        <input type="text" id="" name="" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1 font-medium" value="365" readonly>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Actual Date</label>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Actual Date">
                                                                        </div>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Date End</label>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="End Date">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="grid grid-cols-1 text-xs gap-1 text-center">
                                                                    B1
                                                                </div>
                                                            </div>
                                                            {{-- ACTIVITY TAB --}}
                                                            <div class="hidden p-2 rounded-lg bg-gray-50" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                                                <div class="grid grid-cols-1 text-sm gap-1 text-center">
                                                                    <div class="place-self-start">
                                                                        <label for="" class="block text-lg font-medium text-gray-900">TARGET</label>
                                                                    </div>
                                                                    <div class="grid grid-cols-4 text-sm gap-1 text-center">
                                                                        <div class="col-span-2 place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Inpection</label>
                                                                        </div>
                                                                        <div class="col-span-2 place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Repair</label>
                                                                        </div>
                                                                        <div class="place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Date Start</label>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="relative max-w-sm">
                                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                </div>
                                                                                <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Start Date">
                                                                            </div>
                                                                        </div>
                                                                        <div class="place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Date End</label>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="relative max-w-sm">
                                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                </div>
                                                                                <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="End Date">
                                                                            </div>
                                                                        </div>
                                                                        <div class="place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Date Start</label>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="relative max-w-sm">
                                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                </div>
                                                                                <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Start Date">
                                                                            </div>
                                                                        </div>
                                                                        <div class="place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Date End</label>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="relative max-w-sm">
                                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                </div>
                                                                                <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="End Date">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <hr class="mt-3 mb-3">
                                                                <div class="grid grid-cols-1 text-sm gap-1 text-center">
                                                                    <div class="grid grid-cols-2 place-content-between">
                                                                        <div class="place-self-start">
                                                                            <label for="" class="block text-lg font-medium text-gray-900">ACTUAL</label>
                                                                        </div>
                                                                        <div class="place-self-end">
                                                                            <button type="button" class="text-black bg-gray-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1.5 text-center">RESET</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="grid grid-cols-4 text-sm gap-1 text-center">
                                                                        <div class="place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">No. of Days</label>
                                                                        </div>
                                                                        <div class="col-span-3 place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Date of Inspection</label>
                                                                        </div>
                                                                        <div class="row-span-2 place-self-center">
                                                                            <input type="text" id="" name="" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1 font-medium" value="30" readonly>
                                                                        </div>
                                                                        <div class="place-self-center">
                                                                            <label for="" class="block text-sm text-gray-900">Date Start</label>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="relative max-w-sm">
                                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                                  <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                                </div>
                                                                                <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Start Date">
                                                                            </div>
                                                                        </div>
                                                                        <div class="place-self-center">
                                                                            <button type="button" class="text-black bg-green-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">UPDATE</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- PARTS INFO TAB --}}
                                                            <div class="hidden p-4 rounded-lg bg-gray-50" id="partinfo" role="tabpanel" aria-labelledby="partinfo-tab">
                                                                E
                                                            </div>
                                                            {{-- DOWNTIME TAB --}}
                                                            <div class="hidden p-2 rounded-lg bg-gray-50" id="downtime" role="tabpanel" aria-labelledby="downtime-tab">
                                                                <div class="grid grid-cols-4 text-xs gap-1 text-center">
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Date Start</label>
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Start Date">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row-span-3 place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900"> </label>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Date End</label>
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                              <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="End Date">
                                                                        </div>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Reason of Pending</label>
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <select id="pendingreason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="pendingreason">
                                                                            <option value="0">F</option>
                                                                            <option value="1">V</option>
                                                                            <option value="2">M</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="row-span-2 place-self-center">
                                                                        <label for="" class="block text-sm text-gray-900">Remarks</label>
                                                                    </div>
                                                                    <div class="row-span-2 col-span-2">
                                                                        <textarea id="remarks" rows="2" class="block p-1 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <button type="button" class="text-black bg-blue-400 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">ADD</button>
                                                                    </div>
                                                                    <div class="place-self-center">
                                                                        <button type="button" class="text-black bg-green-400 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">UPDATE</button>
                                                                    </div>
                                                                </div>
                                                                <hr class="mt-2 mb-2">
                                                                <div class="grid text-xs gap-1 text-center">
                                                                    <table id="table_id0" class="display">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Date Started</th>
                                                                                <th>Date End</th>
                                                                                <th>Reason of Pending</th>
                                                                                <th>Total Days</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>1/1/2023</td>
                                                                                <td>1/2/2023</td>
                                                                                <td>No Work</td>
                                                                                <td>2</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>1/12/2023</td>
                                                                                <td>1/16/2023</td>
                                                                                <td>No Work</td>
                                                                                <td>5</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>2/1/2023</td>
                                                                                <td>2/14/2023</td>
                                                                                <td>No Work</td>
                                                                                <td>14</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="max-height: calc(100vh - 150px);" class="grid grid-cols-1 gap-1 grid-nowrap overflow-x-auto">
                                                <div class="grid grid-cols-1 gap-1">
                                                    <div class="text-left gap-0">
                                                        <label for="plana" class="block text-sm font-medium text-gray-900">Plan</label>
                                                    </div>
                                                    <div class="grid grid-cols-2 text-xs gap-1">
                                                        <div class="place-self-center"></div>
                                                        <div class="place-self-center">Days</div>
                                                        <div class="place-self-center">Target Days</div>
                                                        <div class="place-self-center border-black">
                                                            <input type="text" id="targetd" name="targetd" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center">Running Days</div>
                                                        <div class="place-self-center">
                                                            <input type="text" id="runningd" name="runningd" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="grid grid-cols-1 gap-1">
                                                    <div class="text-left gap-1">
                                                        <label for="activitya" class="block text-sm font-medium text-gray-900">Activity</label>
                                                    </div>
                                                    <div class="grid grid-cols-5 text-xs gap-1 text-center">
                                                        <div class="col-span-3 place-self-center"></div>
                                                        <div class="place-self-center">Days</div>
                                                        <div class="place-self-center">Total</div>
                                                        <div class="place-self-center row-span-2 text-red-600 font-medium">Actual</div>
                                                        <div class="place-self-center col-span-2">Dismantle/Disassemble</div>
                                                        <div class="place-self-center">
                                                            <input type="text" id="adisday" name="adisday" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center row-span-2">2</div>
                                                        <div class="place-self-center col-span-2">Reassemble/Installation</div>
                                                        <div class="place-self-center"><input type="text" id="aasday" name="aasday" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                    </div>
                                                    <div class="gap-1"></div>
                                                    <div class="grid grid-cols-5 text-xs gap-1 text-center">
                                                        <div class="col-span-3 place-self-center"></div>
                                                        <div class="place-self-center">Days</div>
                                                        <div class="place-self-center">Total</div>
                                                        <div class="place-self-center row-span-2 text-red-600 font-medium">Target</div>
                                                        <div class="place-self-center col-span-2">Dismantle/Disassemble</div>
                                                        <div class="place-self-center">
                                                            <input type="text" id="tdisday" name="tdisday" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center row-span-2">2</div>
                                                        <div class="place-self-center col-span-2">Reassemble/Installation</div>
                                                        <div class="place-self-center"><input type="text" id="tasday" name="tasday" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="grid grid-cols-1 gap-1">
                                                    <div class="text-left gap-0">
                                                        <label for="downtimea" class="block text-sm font-medium text-gray-900">Downtime</label>
                                                    </div>
                                                    <div class="grid grid-cols-3 text-xs gap-1 text-center">
                                                        <div class="col-span-3 place-self-center">Days Pending</div>
                                                        <div class="place-self-center">Waiting for Parts</div>
                                                        <div class="flex justify-center"><input type="text" id="wp" name="wp" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 py-0.5 text-center" readonly></div>
                                                        <div class="row-span-6 place-self-center">TOTAL DOWNTIME<input type="text" id="totaldays" name="totaldays" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center">Waiting for Machining</div>
                                                        <div class="flex justify-center"><input type="text" id="wm" name="wm" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center">Lack of Space</div>
                                                        <div class="flex justify-center"><input type="text" id="ls" name="ls" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center">Lack of Techinician</div>
                                                        <div class="flex justify-center"><input type="text" id="lt" name="lt" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center">No Work</div>
                                                        <div class="flex justify-center"><input type="text" id="nw" name="nw" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 py-0.5 text-center" readonly></div>
                                                        <div class="place-self-center">Waiting of PO</div>
                                                        <div class="flex justify-center"><input type="text" id="wp" name="wp" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs sm:text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/2 py-0.5 text-center" readonly></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex items-center p-2 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button data-modal-hide="extralarge-modal" type="button" class="text-black bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                                        <button data-modal-hide="extralarge-modal" type="button" class="text-black bg-yellow-500 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">TRANSFER</button>
                                        <button data-modal-target="extralarge-modalPART" data-modal-toggle="extralarge-modalPART" type="button" class="text-black bg-green-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">PARTS INFO</button>
                                        <button data-modal-hide="extralarge-modal" type="button" class="text-black bg-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">EXIT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
{{-- End Second Modal --}}
<!-- Start Third Modal PART-->
<div id="extralarge-modalPART" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-2 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-7xl md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            {{-- <div class="flex items-center justify-between border-b rounded-t"> </div> --}}
            <!-- Modal body -->
            <div class="p-2 space-y-6">
                <div class="grid grid-cols-5 text-xs gap-1 text-center">
                    <div class="col-span-2 place-self-center">
                        <div class="grid grid-cols-3 place-self-center gap-1">
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">MRI Number</label>
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="mrino" name="mrino" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                            </div>
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">Part Number</label>
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="partno" name="partno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                            </div>
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">Description</label>
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="descrip" name="descrip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                            </div>
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">Quantity</label>
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="quantity" name="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                            </div>
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">Date Requested</label>
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="daterequested" name="daterequested" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                            </div>
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">Date Received</label>
                            </div>
                            <div class="col-span-2">
                                <input type="text" id="datereceived" name="datereceived" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1">
                            </div>
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900">Reason</label>
                            </div>
                            <div class="col-span-2">
                                <select id="pendingreason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center py-1" name="pendingreason">
                                    <option value="B">Back Order</option>
                                    <option value="M">Machining</option>
                                </select>
                            </div>
                            <div class="place-self-center mt-2">
                                <button type="button" class="text-black bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-2 hover:text-gray-900 focus:z-10">ADD</button>
                            </div>
                            <div class="place-self-center mt-2">
                                <button type="button" class="text-black bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-2 hover:text-gray-900 focus:z-10">UPDATE</button>
                            </div>
                            <div class="place-self-center mt-2">
                                <button type="button" class="text-black bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-2 hover:text-gray-900 focus:z-10">CLEAR</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <div class="grid grid-cols-2">
                            <div class="place-self-start">
                                <label for="" class="block text-lg font-medium text-red-500">Pending Parts</label>
                            </div>
                            <div class="place-self-end">
                                <label for="" class="block text-lg font-medium text-red-500">Date Installed</label>
                            </div>
                        </div>
                        <div class="grid text-xs gap-1 text-center">
                            <table id="table_id1" class="display">
                                <thead>
                                    <tr>
                                        <th>Parts Number</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>MRI Number</th>
                                        <th>Date Requested</th>
                                        <th>Date Received</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>201332R</td>
                                        <td>*DRIVE WHEEL(RELINE)</td>
                                        <td>1</td>
                                        <td>3096334</td>
                                        <td>1/1/2023</td>
                                        <td>1/2/2023</td>
                                        <td>Machining</td>
                                    </tr>
                                    <tr>
                                        <td>201332R</td>
                                        <td>*DRIVE WHEEL(RELINE)</td>
                                        <td>1</td>
                                        <td>3096334</td>
                                        <td>1/1/2023</td>
                                        <td>1/2/2023</td>
                                        <td>Machining</td>
                                    </tr>
                                    <tr>
                                        <td>201332R</td>
                                        <td>*DRIVE WHEEL(RELINE)</td>
                                        <td>1</td>
                                        <td>3096334</td>
                                        <td>1/1/2023</td>
                                        <td>1/2/2023</td>
                                        <td>Machining</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr class="">
                <div class="grid grid-cols-5 text-xs gap-1 text-center mt-0">
                    <div class="col-span-2 place-self-center">
                        A
                    </div>
                    <div class="col-span-3">
                        <div class="grid grid-cols-4 gap-1">
                            <div class="col-span-3 grid grid-cols-3">
                                <div class="col-span-3 place-self-start">
                                    <label for="table_id2" class="block text-lg font-medium text-red-500">Total Installed Parts</label>
                                </div>
                                <div class="col-span-3 text-xs gap-1 text-center">
                                    <table id="table_id2" class="display">
                                        <thead>
                                            <tr>
                                                <th>Parts Number</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>MRI Number</th>
                                                <th>Date Installed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>201332R</td>
                                                <td>*DRIVE WHEEL(RELINE)</td>
                                                <td>1</td>
                                                <td>3096334</td>
                                                <td>1/1/2023</td>
                                            </tr>
                                            <tr>
                                                <td>201332R</td>
                                                <td>*DRIVE WHEEL(RELINE)</td>
                                                <td>1</td>
                                                <td>3096334</td>
                                                <td>1/1/2023</td>
                                            </tr>
                                            <tr>
                                                <td>201332R</td>
                                                <td>*DRIVE WHEEL(RELINE)</td>
                                                <td>1</td>
                                                <td>3096334</td>
                                                <td>1/1/2023</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="col-span-2 place-self-start">
                                    <label for="" class="block text-lg font-medium text-red-500">Remarks</label>
                                </div>
                                <div class="col-span-2 grid grid-cols-2 gap-1">
                                    <div class="col-span-2">
                                        <textarea id="remarks" rows="3" class="block p-1 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    <div class="place-self-center">
                                        <button type="button" class="text-black bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-1 hover:text-gray-900 focus:z-10">UPDATE</button>
                                    </div>
                                    <div class="place-self-center">
                                        <button data-modal-hide="extralarge-modalPART" type="button" class="text-black bg-red-600 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-1 hover:text-gray-900 focus:z-10">EXIT</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            {{-- <div class="flex items-center p-2 space-x-2 border-t border-gray-200 rounded-b">
                <button data-modal-hide="extralarge-modalPART" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">I accept</button>
                <button data-modal-hide="extralarge-modalPART" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Decline</button>
            </div> --}}
        </div>
    </div>
</div>
{{-- End Third Modal Part --}}
<!-- Start Fourth Modal -->
<div id="extralarge-modal3" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-0.5 overflow-x-hidden overflow-y-auto md:inset-0 h-full">
    <div class="relative w-full h-full max-w-7xl">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow h-5/6 top-1/2 -translate-y-1/2">
            <!-- Modal header -->
            {{-- <div class="flex items-center justify-between gap-1 border-b rounded-t">
            </div> --}}
            <!-- Modal body -->
            <div class="relative p-0.5 space-y-3 h-full">
                <div class="grid grid-cols-8">
                    <div class="place-self-center">
                        <label for="" class="block text-lg font-medium text-red-500">BAY NUMBER</label>
                    </div>
                    <div class="col-span-6 place-self-center grid grid-cols-6">
                        <div class=""></div>
                        <div class="col-span-4">
                            <div style="float:right;" class="place-self-center">
                                LEGEND:<button data-popover-target="popover-description" data-popover-placement="bottom-end" type="button"><svg fill="none" height="25" viewBox="0 0 512 512" width="25" xmlns="http://www.w3.org/2000/svg" class="place-item-center"><path d="M256 20C125.66 20 20 125.66 20 256C20 386.34 125.66 492 256 492C386.34 492 492 386.34 492 256C492 125.66 386.34 20 256 20ZM277.76 406.09C271.467 412.25 263.107 415.33 252.68 415.33C242.253 415.33 233.82 412.25 227.38 406.09C221.093 399.93 217.947 392.127 217.94 382.68C217.94 373.08 221.157 365.203 227.59 359.05C234.17 352.75 242.533 349.597 252.68 349.59C262.827 349.583 271.117 352.737 277.55 359.05C284.117 365.197 287.403 373.073 287.41 382.68C287.41 392.133 284.193 399.937 277.76 406.09ZM344.44 214.48C339.013 225.22 329.51 237.177 315.93 250.35L298.56 266.89C287.7 277.35 281.483 289.593 279.91 303.62C279.683 307.177 278.109 310.513 275.508 312.949C272.906 315.385 269.474 316.737 265.91 316.73H252.5C236.85 316.73 223.67 303.94 226.18 288.49C227.19 281.756 228.958 275.157 231.45 268.82C236.31 256.94 245.173 245.273 258.04 233.82C271.04 222.22 279.687 212.84 283.98 205.68C288.167 198.737 290.39 190.788 290.41 182.68C290.41 158.48 279.26 146.38 256.96 146.38C246.387 146.38 237.883 149.673 231.45 156.26C220.06 167.93 208.58 183.11 192.28 183.11H191.05C173.63 183.11 158.76 168.51 164.21 151.96C168.329 139.211 175.775 127.791 185.78 118.88C203.22 103.42 226.947 95.6867 256.96 95.68C287.273 95.68 310.787 103.057 327.5 117.81C344.233 132.417 352.597 153.11 352.59 179.89C352.626 191.9 349.834 203.75 344.44 214.48V214.48Z" fill="black"/></svg></button>
                                <div data-popover id="popover-description" role="tooltip" class="absolute z-10 invisible inline-block text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 place-content-center">
                                    <div class="text-sm"><label>On Schedule</label><div style="float: left;" name="green" class="mr-2 w-7 h-4 bg-green-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div></div>
                                    <div class="text-sm"><label>Caution (Over 1 Day)</label><div style="float: left;" name="yellow" class="mr-2 w-7 h-4 bg-yellow-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div></div>
                                    <div class="text-sm"><label>Critical (Over 2 Days)</label><div style="float: left;" name="red" class="mr-2 w-7 h-4 bg-red-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div></div>
                                </div>
                            </div>
                        </div>
                        <div class=""></div>
                    </div>
                    <div class="place-self-end">
                        <button data-modal-hide="extralarge-modal3" type="button" class="text-black bg-red-600 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-1 hover:text-gray-900 focus:z-10">EXIT</button>
                    </div>
                </div>
                <div style="height: calc(100% - 50px);" class="col-span-8 text-xs overflow-y-auto">
                    <table id="table_id" class="display">
                        <thead>
                            <tr>
                                <th>Date Schedule</th>
                                <th>Time Schedule</th>
                                <th>Truck Plate Number</th>
                                <th>Company Name</th>
                                <th>Unit / Accessories</th>
                                <th>Sales Person</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                            <tr>
                                <td>01/01/2023</td>
                                <td>2 AM</td>
                                <td>AAV3565</td>
                                <td>DKSSI</td>
                                <td>FDN25-27225</td>
                                <td>Dennis Chung</td>
                                <td>SWAP MAST to S#26563 FDZN25</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- End Fourth Modal --}}
                    {{-- End Workshop --}}
                </div>
            </div>
        </div>
    </div>
    <script>$(document).ready( function () {
        $('#table_id').DataTable();
    } );</script>
</x-app-layout>
