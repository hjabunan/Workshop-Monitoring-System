<x-app-layout>
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

    <div style="height: calc(100vh - 90px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid gap-x-3">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            PDI Area
                        </div>
                    </div>
                    <div class="grid grid-cols-12 mt-2">
                        {{-- BAYS --}}
                        <div class="col-span-10">
                            <div class="">
                                <div class="grid grid-cols-4 content-start gap-2 mr-2">
                                    @foreach ($bays as $bay)
                                        @if ($bay->section == 21)
                                            @php
                                                $x = 0;
                                            @endphp
                                            @foreach ($workshop as $WS)
                                                @if ($WS->WSBayNum == $bay->id)
                                                    @php
                                                        $x = 1;
                                                        break;
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @if ($x == 1)
                                                @foreach ($workshop as $WS)
                                                    @if ($WS->WSBayNum == $bay->id)
                                                        {{-- CLASS AND STATUS --}}
                                                            @if($WS->POUClassification == 1)
                                                                @php
                                                                    $Classification = "CLASS A";
                                                                @endphp
                                                            @elseif($WS->POUClassification == 2)
                                                                @php
                                                                    $Classification = "CLASS B";
                                                                @endphp
                                                            @elseif($WS->POUClassification == 3)
                                                                @php
                                                                    $Classification = "CLASS C";
                                                                @endphp
                                                            @elseif($WS->POUClassification == 4)
                                                                @php
                                                                    $Classification = "CLASS D";
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $Classification = "";
                                                                @endphp
                                                            @endif
                            
                                                            @if($WS->WSStatus == 1)
                                                                @php
                                                                    $Status = "WAITING FOR REPAIR UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 2)
                                                                @php
                                                                    $Status = "UNDER REPAIR UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 3)
                                                                @php
                                                                    $Status = "USED GOOD UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 4)
                                                                @php
                                                                    $Status = "SERVICE UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 5)
                                                                @php
                                                                    $Status = "FOR SCRAP UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 6)
                                                                @php
                                                                    $Status = "FOR SALE UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 7)
                                                                @php
                                                                    $Status = "WAITING PARTS";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 8)
                                                                @php
                                                                    $Status = "WAITING BACK ORDER";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 9)
                                                                @php
                                                                    $Status = "WAITING SPARE BATT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 10)
                                                                @php
                                                                    $Status = "STOCK UNIT";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 11)
                                                                @php
                                                                    $Status = "WAITING FOR MCI";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 12)
                                                                @php
                                                                    $Status = "WAITING FOR PDI";
                                                                @endphp
                                                            @elseif($WS->WSStatus == 13)
                                                                @php
                                                                    $Status = "DONE PDI (WFD)";
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $Status = "VACANT";
                                                                @endphp
                                                            @endif
                                                        <div class="">
                                                            <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                <div class=""><label class="font-medium text-lg ">{{$bay->area_name}}</label></div>
                                                                <input type="hidden" id="hddnJONum" value="{{$WS->WSID}}">
                                                                <div class="grid grid-cols-7 text-xs">
                                                                    <div class="col-span-3 text-white text-left">
                                                                        <div class=""><label class="font-medium">Class:</label></div>
                                                                    </div>
                                                                    <div class="col-span-4 text-left font-bold">
                                                                        <div class=""><label class="">{{$Classification}}</label></div>
                                                                    </div>
                                                                    <div class="col-span-3 text-white text-left">
                                                                        <div class=""><label class="font-medium">Code:</label></div>
                                                                    </div>
                                                                    <div class="col-span-4 text-left font-bold">
                                                                        <div class=""><label class="">{{$WS->POUCode}}</label></div>
                                                                    </div>
                                                                    <div class="col-span-3 text-white text-left">
                                                                        <div class=""><label class="font-medium">Serial Number:</label></div>
                                                                    </div>
                                                                    <div class="col-span-4 text-left font-bold">
                                                                        <div class=""><label class="">{{$WS->POUSerialNum}}</label></div>
                                                                    </div>
                                                                    <div class="col-span-3 text-white text-left">
                                                                        <div class=""><label class="font-medium">Model:</label></div>
                                                                    </div>
                                                                    <div class="col-span-4 text-left font-bold">
                                                                        <div class=""><label class="">{{$WS->POUModel}}</label></div>
                                                                    </div>
                                                                    <div class="col-span-3 text-white text-left">
                                                                        <div class=""><label class="font-medium">Mast Height:</label></div>
                                                                    </div>
                                                                    <div class="col-span-4 text-left font-bold">
                                                                        <div class=""><label class="">{{$WS->POUMastHeight}}</label></div>
                                                                    </div>
                                                                    <div class="col-span-3 text-white text-left">
                                                                        <div class=""><label class="font-medium">Status:</label></div>
                                                                    </div>
                                                                    <div class="col-span-4 text-left font-bold">
                                                                        <div class="text-[10px]"><label class="">{{$Status}}</label></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="">
                                                    <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                        <div class=""><label class="font-medium text-lg">{{$bay->area_name}}</label></div>
                                                        <input type="hidden" id="hddnJONum" value="0">
                                                        <div class="grid grid-cols-7 text-xs">
                                                            <div class="col-span-3 text-gray-500 text-left">
                                                                <div class=""><label class="font-medium">Class:</label></div>
                                                                <div class=""><label class="font-medium">Code:</label></div>
                                                                <div class=""><label class="font-medium">Serial Number:</label></div>
                                                                <div class=""><label class="font-medium">Model:</label></div>
                                                                <div class=""><label class="font-medium">Mast Height:</label></div>
                                                                <div class=""><label class="font-medium">Status:</label></div>
                                                            </div>
                                                            <div class="col-span-4">
                                                                <div class=""><label class=""></label></div>
                                                                <div class=""><label class=""></label></div>
                                                                <div class=""><label class=""></label></div>
                                                                <div class=""><label class=""></label></div>
                                                                <div class=""><label class=""></label></div>
                                                                <div class=""><label class=""></label></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        {{-- LEGEND AND TOTAL CAPACITY --}}
                        <div class="col-span-2 grid grid-rows-4">
                            <div class="">
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
                            <div id="divTotalCap" class="row-span-3 mt-5">
                                <div class=""><label class="font-medium">TOTAL CAPACITY:</label></div>
                                <div class="grid grid-cols-8 items-center ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>TOYOTA IC JAPAN</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitTICJ" id="UnitTICJ" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>TOYOTA ELECTRIC JAPAN</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitTEJ" id="UnitTEJ" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>TOYOTA IC CHINA</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitTICC" id="UnitTICC" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>TOYOTA ELECTRIC CHINA</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitTEC" id="UnitTEC" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>TOYOTA REACH TRUCK</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitTRT" id="UnitTRT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>BT REACH TRUCK</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitBTRT" id="UnitBTRT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>BT STACKER</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitBTS" id="UnitBTS" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>RAYMOND REACH TRUCK</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitRRT" id="UnitRRT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>RAYMOND STACKER</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitRS" id="UnitRS" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>STACKER TAILIFT</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitST" id="UnitST" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>PPT</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitPPT" id="UnitPPT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>OPC</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitOPC" id="UnitOPC" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label>HPT</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitHPT" id="UnitHPT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                                <hr class="mt-0.5 mb-0.5">
                                <div class="grid grid-cols-8 items-center mt-0.5 ml-2">
                                    <div class="col-span-6 text-xs">
                                        <label style="float: right;" class="mr-5">TOTAL</label>
                                    </div>
                                    <div class="col-span-2">
                                        <input type="text" name="UnitTotal" id="UnitTotal" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-md text-center pointer-events-none" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MODALS --}}
        {{-- Small Modal - Unit Info --}}
            <div id="modalUR" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
                <div class="relative w-full h-full max-w-3xl md:h-auto">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow h-full">
                        <div style="height: 84vh;" class="grid px-3 pb-3 space-y-1 h-full overflow-y-auto">
                            <form action="" id="formUR">
                                @csrf
                                    <div class="grid ">
                                        <div class="grid grid-cols-4 place-items-center uppercase">
                                            <div class=""><label class="font-medium">Location</label></div>
                                            <div class=""><label class="font-medium">Status</label></div>
                                            <div class=""><label class="font-medium">Unit Count</label></div>
                                            <div class=""><label class="font-medium">Bay Number</label></div>
                                        </div>
                                        <div class="grid grid-cols-4 place-items-center gap-1">
                                            <div class="">
                                                <select id="UnitInfoToA" name="UnitInfoToA" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                                                    <option value="" selected disabled></option>
                                                    <option value="1">WAREHOUSE</option>
                                                    <option value="2">WORKSHOP</option>
                                                    <option value="3">PDI</option>
                                                </select>
                                            </div>
                                            <div class="">
                                                <select id="UnitInfoStatus" name="UnitInfoStatus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                                                    <option value="" selected disabled></option>
                                                    <option value="1">WAITING FOR REPAIR UNIT</option>
                                                    <option value="2">UNDER REPAIR UNIT</option>
                                                    <option value="3">USED GOOD UNIT</option>
                                                    <option value="4">SERVICE UNIT</option>
                                                    <option value="5">FOR SCRAP UNIT</option>
                                                    <option value="6">FOR SALE UNIT</option>
                                                    <option value="7">WAITING PARTS</option>
                                                    <option value="8">WAITING BACK ORDER</option>
                                                    <option value="9">WAITING SPARE BATT</option>
                                                    <option value="10">STOCK UNIT</option>
                                                    <option value="11">WAITING FOR MCI</option>
                                                    <option value="12">WAITING FOR PDI</option>
                                                    <option value="13">DONE PDI (WFD)</option>
                                                    <option value="14">VACANT</option>
                                                </select>
                                            </div>
                                            <div class="">
                                                <input type="text" id="UnitInfoJON" name="UnitInfoJON" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                                <input type="hidden" id="UnitInfoPOUID" name="UnitInfoPOUID">
                                            </div>
                                            <div class="">
                                                <input type="text" id="UnitInfoBayNum" name="UnitInfoBayNum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                                <input type="hidden" id="UnitBayNum" name="UnitBayNum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                            </div>
                                        </div>
                                        <hr class="mt-2 mb-2">
                                        <div class="">
                                            <div class="bg-red-500"><label class="ml-2 text-xl font-medium text-black">&nbsp;&nbsp;&nbsp;FIFO : First In First Out Process</label></div>
                                            <div class="grid grid-cols-7">
                                                <div class="col-span-3">
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHBrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHBrand" id="WHBrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHCode" class="block text-sm font-medium text-gray-900">Code:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHCode" id="WHCode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHModel" id="WHModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHSerialNum" id="WHSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHCompName" class="block text-xs font-medium text-gray-900">Company Name:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHCompName" id="WHCompName" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHCompAdd" class="block text-xs font-medium text-gray-900">Company Address:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHCompAdd" id="WHCompAdd" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHSalesman" class="block text-xs font-medium text-gray-900">Salesman:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHSalesman" id="WHSalesman" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHArrivalDate" class="block text-sm font-medium text-gray-900">Arrival Date:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <div class="relative max-w-sm">
                                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                </div>
                                                                <input datepicker type="text" id="WHArrivalDate" name="WHArrivalDate" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-2">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class=" mt-2"></div>
                                                <div class="col-span-3">
                                                    <div class="h-[38px]">
                                                        <div id="UClass" class="grid grid-cols-3 mt-2">
                                                            <div class="col-span-1 text-left flex items-center">
                                                                <label for="WHClassification" class="block text-sm font-medium text-gray-900">Classification:</label>
                                                            </div>
                                                            <div class="col-span-2">
                                                                <select name="WHClassification" id="WHClassification" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                                    <option value="" selected disabled></option>
                                                                    <option value="1">CLASS A</option>
                                                                    <option value="2">CLASS B</option>
                                                                    <option value="3">CLASS C</option>
                                                                    <option value="4">CLASS D</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHUnitType" class="block text-sm font-medium text-gray-900">Unit Type:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <select id="WHUnitType" name="WHUnitType" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                                                                <option value="" selected disabled></option>
                                                                <option value="1">TOYOTA IC JAPAN</option>
                                                                <option value="2">TOYOTA ELECTRIC JAPAN</option>
                                                                <option value="3">TOYOTA IC CHINA</option>
                                                                <option value="4">TOYOTA ELECTRIC CHINA</option>
                                                                <option value="5">TOYOTA REACH TRUCK</option>
                                                                <option value="6">BT REACH TRUCK</option>
                                                                <option value="7">BT STACKER</option>
                                                                <option value="8">RAYMOND REACH TRUCK</option>
                                                                <option value="9">RAYMOND STACKER</option>
                                                                <option value="10">STACKER TAILIFT</option>
                                                                <option value="11">PPT</option>
                                                                <option value="12">OPC</option>
                                                                <option value="13">HPT</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHAttachment" class="block text-sm font-medium text-gray-900">Attachment:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHAttachment" id="WHAttachment" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHMastHeight" class="block text-sm font-medium text-gray-900">Mast Height:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHMastHeight" id="WHMastHeight" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHPIC" class="block text-sm font-medium text-gray-900">P.I.C. :</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHPIC" id="WHPIC" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHVB" class="block text-sm font-medium text-gray-900">Verified By:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <input type="text" name="WHVB" id="WHVB" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3 mt-2">
                                                        <div class="col-span-1 text-left flex items-center">
                                                            <label for="WHVB" class="block text-sm font-medium text-gray-900">Remarks:</label>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <textarea id="WSRemarks" name="WSRemarks" rows="3" class="remarks block w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 py-1 uppercase" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="grid grid-cols-2 gap-1 place-items-center mt-4">
                                                <div class="flex items-center mr-4">
                                                    <input id="UnitNew" type="radio" value="1" name="Radio_Unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                    <label for="UnitNew" class="ml-2 text-sm font-medium text-gray-900">New Unit</label>
                                                </div>
                                                <div class="flex items-center mr-4">
                                                    <input id="UnitUsed" type="radio" value="2" name="Radio_Unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                    <label for="UnitUsed" class="ml-2 text-sm font-medium text-gray-900">Used Unit</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-3 mb-3">
                                        <div class="grid grid-cols-12">
                                            <div class="col-span-8">
                                                <div class="text-left gap-1">
                                                    <label for="legz" class="block mb-1 text-md font-medium text-red-500">LEGEND</label>
                                                </div>
                                                <div class="grid grid-cols-5 gap-1">
                                                    <div class="col-span-2 place-self-center">
                                                        <label for=green" class="block text-sm text-gray-900">Shorter than Target</label>
                                                    </div>
                                                    <div class="col-span-2 place-self-center">
                                                        <label for="green" class="block text-sm text-gray-900">(1-3 Months or 90 Days)</label>
                                                    </div>
                                                    <div style="float: right;" name="green" class="w-full h-6 bg-green-500 rounded ring-1 ring-inset ring-black ring-opacity-0">
                                                    </div>
                                                    <div class="col-span-2 place-self-center">
                                                        <label for="yellow" class="block text-sm text-gray-900">Caution</label>
                                                    </div>
                                                    <div class="col-span-2 place-self-center">
                                                        <label for="yellow" class="block text-sm text-gray-900">(4-6 Months or 180 days)</label>
                                                    </div>
                                                    <div style="float: right;" name="yellow" class="w-full h-6 bg-yellow-400 rounded ring-1 ring-inset ring-black ring-opacity-0">
                                                    </div>
                                                    <div class="col-span-2 place-self-center">
                                                        <label for="red" class="block text-sm text-gray-900">Danger</label>
                                                    </div>
                                                    <div class="col-span-2 place-self-center">
                                                        <label for="red" class="block text-sm text-gray-900">(7-9 Months or 270 days)</label>
                                                    </div>
                                                    <div style="float: right;" name="red" class="w-full h-6 bg-red-500 rounded ring-1 ring-inset ring-black ring-opacity-0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-span-1"></div>
                                            <div class="col-span-3 bg-blue-300 rounded">
                                                <br>
                                                <div class="grid grid-cols-2">
                                                    <div class="col-span-2 place-self-center">
                                                        <label for="WHRunningDays" class="block text-sm font-medium text-gray-900 uppercase">Running Days</label>
                                                    </div>
                                                    <div class="col-span-2 place-self-center ml-7 mr-7">
                                                        <input type="text" name="WHRunningDays" id="WHRunningDays" class="block w-full p-1.5 text-gray-900 text-2xl border border-gray-300 font-medium rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase pointer-events-none">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-2 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button id="saveUnit" name="saveUnit" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                            <button id="transferUnit" name="transferUnit" type="button" class="text-white bg-yellow-600 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">TRANSFER</button>
                            <button data-modal-hide="modalUR" id="closeBayMon" type="button" class="text-white bg-gray-600 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">EXIT</button>
                        </div>
                    </div>
                </div>
            </div>
        {{-- Transfer Modal for Units --}}
            <div id="modalTransferUnit" data-modal-backdrop="static" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full bg-gray-800 bg-opacity-50">
                <div class="relative w-full h-full max-w-2xl md:h-auto">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-4 border-b rounded-t">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                TRANSFER OF UNIT
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalTransferUnit">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-6">
                            <form action="" id="formWHTransfer">
                                @csrf
                                    <div class="">
                                        <div class="grid grid-cols-2 gap-1 place-items-center">
                                            <div class="flex items-center mr-4">
                                                <input id="TransferWH" type="radio" value="1" name="Radio_Transfer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                                <label for="TransferWH" class="ml-2 text-sm font-medium text-gray-900">Transfer Warehouse/Workshop</label>
                                            </div>
                                            <div class="flex items-center mr-4">
                                                <input id="TransferDeliver" type="radio" value="2" name="Radio_Transfer" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                <label for="TransferDeliver" class="ml-2 text-sm font-medium text-gray-900">Transfer for Deliver</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-5 mb-2">
                                        <input type="hidden" id="POUIDx" name="POUIDx">
                                    </div>
                                <div id="divWHTransfer" class="grid grid-cols-5 items-center">
                                    <div id="label" class="uppercase mb-2">
                                        <label for="UnitTransferDate" class="block text-sm font-medium text-gray-900">Transfer Date:</label>
                                    </div>
                                    <div class="col-span-2">
                                        <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="UnitTransferDate" id="UnitTransferDate">
                                        </div>
                                    </div>
                                    <div class="col-span-2"></div>
                                    <div id="label" class="uppercase mt-5">
                                        <label for="UnitStatus" class="block text-sm font-medium text-gray-900">Status:</label>
                                    </div>
                                    <div id="input" class="col-span-2 uppercase mt-5">
                                        <select name="UnitStatus" id="UnitStatus" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            <option value="" selected disabled></option>
                                            <option value="1">WAITING FOR REPAIR UNIT</option>
                                            <option value="2">UNDER REPAIR UNIT</option>
                                            <option value="3">USED GOOD UNIT</option>
                                            <option value="4">SERVICE UNIT</option>
                                            <option value="5">FOR SCRAP UNIT</option>
                                            <option value="6">FOR SALE UNIT</option>
                                            <option value="7">WAITING PARTS</option>
                                            <option value="8">WAITING BACK ORDER</option>
                                            <option value="9">WAITING SPARE BATT</option>
                                            <option value="10">STOCK UNIT</option>
                                            <option value="11">WAITING FOR MCI</option>
                                            <option value="12">WAITING FOR PDI</option>
                                            <option value="13">DONE PDI (WFD)</option>
                                        </select>
                                    </div>
                                    <div id="input" class="col-span-2">
                                    </div>
                                    <div id="label" class="uppercase mt-2">
                                        <label for="UnitArea" class="block text-sm font-medium text-gray-900">Area:</label>
                                    </div>
                                    <div id="input" class="col-span-2 uppercase mt-2">
                                        <select name="UnitArea" id="UnitArea" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            <option value="" selected></option>
                                            @foreach ($sectionT as $sectionsT)
                                            <option value="{{$sectionsT->id}}">{{$sectionsT->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="input" class="col-span-2"></div>
                                    <div id="label" class="uppercase mt-2">
                                        <label for="UnitBay" class="block text-sm font-medium text-gray-900">Bay:</label>
                                    </div>
                                    <div id="input" class="col-span-2 uppercase mt-2">
                                        <select name="UnitBay" id="UnitBay" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            <option value="" selected></option>
                                            @foreach ($baysT as $bayT)
                                            <option value="{{$bayT->id}}">{{$bayT->area_name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="BayID" name="BayID">
                                    </div>
                                    <div id="" class="col-span-2"></div>
                                    <div id="label" class="uppercase mt-5">
                                        <label for="UnitRemarksT" class="block text-sm font-medium text-gray-900">Transfer Remarks:</label>
                                    </div>
                                    <div id="input" class="uppercase mt-5 col-span-2">
                                        <textarea rows="3" name="UnitRemarksT" id="UnitRemarksT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                    </div>
                                </div>

                                <div id="divDelTransfer" class="grid grid-cols-5 items-center">
                                    <div id="label" class="uppercase mb-2">
                                        <label for="UnitDelDate" class="block text-sm font-medium text-gray-900">Delivery Date:</label>
                                    </div>
                                    <div class="col-span-2">
                                        <div class="relative max-w-sm">
                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                            </div>
                                            <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="UnitDelDate" id="UnitDelDate">
                                        </div>
                                    </div>
                                    <div class="col-span-2"></div>
                                    <div id="label" class="uppercase mt-5">
                                        <label for="UnitRemarksT" class="block text-sm font-medium text-gray-900">Delivery Remarks:</label>
                                    </div>
                                    <div id="input" class="uppercase mt-5 col-span-2">
                                        <textarea rows="3" name="UnitDelRemarksT" id="UnitDelRemarksT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                            <button type="button" id="saveTransferUnit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">TRANSFER</button>
                            <button data-modal-hide="modalTransferUnit" type="button" id="closeTransfer" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
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

    {{-- POU Hidden Button for Transfer --}}
        <button type="button" id="btnPOUTransferH" class="btnPOUTransferH hidden" data-modal-target="modalTransferUnit" data-modal-toggle="modalTransferUnit"></button>
    <script>
        $(document).ready(function(){
            // Get Capacity
                var CUnitTICJ = <?php echo $CUnitTICJ; ?>;
                    $('#UnitTICJ').val(CUnitTICJ);
                var CUnitTEJ = <?php echo $CUnitTEJ; ?>;
                    $('#UnitTEJ').val(CUnitTEJ);
                var CUnitTICC = <?php echo $CUnitTICC; ?>;
                    $('#UnitTICC').val(CUnitTICC);
                var CUnitTEC = <?php echo $CUnitTEC; ?>;
                    $('#UnitTEC').val(CUnitTEC);
                var CUnitTRT = <?php echo $CUnitTRT; ?>;
                    $('#UnitTRT').val(CUnitTRT);
                var CUnitBTRT = <?php echo $CUnitBTRT; ?>;
                    $('#UnitBTRT').val(CUnitBTRT);
                var CUnitBTS = <?php echo $CUnitBTS; ?>;
                    $('#UnitBTS').val(CUnitBTS);
                var CUnitRTR = <?php echo $CUnitRTR; ?>;
                    $('#UnitRTR').val(CUnitRTR);
                var CUnitRS = <?php echo $CUnitRS; ?>;
                    $('#UnitRS').val(CUnitRS);
                var CUnitST = <?php echo $CUnitST; ?>;
                    $('#UnitST').val(CUnitST);
                var CUnitPPT = <?php echo $CUnitPPT; ?>;
                    $('#UnitPPT').val(CUnitPPT);
                var CUnitOPC = <?php echo $CUnitOPC; ?>;
                    $('#UnitOPC').val(CUnitOPC);
                var CUnitHPT = <?php echo $CUnitHPT; ?>;
                    $('#UnitHPT').val(CUnitHPT);
                var CUnitTotal = <?php echo $CUnitTotal; ?>;
                    $('#UnitTotal').val(CUnitTotal);

            // Close Success
                jQuery(document).on( "click", "#SCloseButton", function(){
                    $("#success-modal").removeClass("flex");
                    $("#success-modal").addClass("hidden");
                });

            // Close Failed
                jQuery(document).on( "click", "#FCloseButton", function(){
                    $("#failed-modal").removeClass("flex");
                    $("#failed-modal").addClass("hidden");
                });

            // Close Modal
                jQuery(document).on( "click", "#modalClosePart", function(){
                    $("#modalDeleteParts").removeClass("flex");
                    $("#modalDeleteParts").addClass("hidden");
                });

            // Change Color
                $(".btnBay").each(function() {
                    var hddnJONum = $(this).find("#hddnJONum").val();
                    if (hddnJONum == 0) {
                        $(this).addClass("bg-gray-500");
                    } else {
                        $(this).addClass("bg-green-500");
                    }
                });

            // Get Data per Bay
                jQuery(document).on( "click", ".btnBay", function(){
                    var bay = $(this).attr("data-id");
                    
                    var bayname = $(this).attr("data-bayname");
                    $('#UnitInfoBayNum').val(bayname);
                    $('#UnitSBayNum').val(bayname);
                    var _token = $('input[name="_token"]').val();
                    
                    var d = new Date();

                    var month = d.getMonth()+1;
                    var day = d.getDate();

                    var output = ((''+month).length<2 ? '0' : '') + month + '/' +
                                ((''+day).length<2 ? '0' : '') + day + '/' +
                                d.getFullYear();

                    $.ajax({
                        url: "{{ route('w-storage8.getBayData') }}",
                        type: "GET",
                        dataType: "json",
                        data: {bay:bay, output: output, _token: _token,},
                        success: function(result) { 
                            $('#UnitInfoToA').val(result.WSToA);
                            $('#UnitInfoJON').val(result.WSID);
                                if( $('#UnitInfoJON').val() == ""){
                                    $('#UnitInfoStatus').val(14);
                                }else{
                                    $('#UnitInfoStatus').val(result.WSStatus);
                                }
                            $('#UnitInfoPOUID').val(result.WSPOUID);
                            $('#UnitBayNum').val(result.WSBayNum);
                            $('#WHBrand').val(result.POUBrand);
                            $('#WHCode').val(result.POUCode);
                            $('#WHModel').val(result.POUModel);
                            $('#WHSerialNum').val(result.POUSerialNum);
                            $('#WHAttachment').val(result.POUAttType);
                            $('#WHMastHeight').val(result.POUMastHeight);
                            $('#WHArrivalDate').val(result.POUArrivalDate);
                            $('#WHClassification').val(result.POUClassification);
                            $('#WHCompName').val(result.POUCustomer);
                            $('#WHCompAdd').val(result.POUCustAddress);
                            $('#WHSalesman').val(result.POUSalesman);
                            $('#WHUnitType').val(result.WSUnitType);
                            $('#WHPIC').val(result.initials);
                                // For Running Days
                                    var startDate = new Date(result.POUArrivalDate);
                                    var today = new Date();
                                    var todayDate = today; 
                                    var rdays = 0;
                                    while (startDate <= todayDate) {
                                        var dayOfWeek = startDate.getDay();
                                        if (dayOfWeek !== 0) {
                                        rdays++;
                                        }
                                        startDate.setDate(startDate.getDate() + 1);
                                    }
                                $('#WHRunningDays').val(rdays);
                            $('#WHVB').val(result.WSVerifiedBy);
                            $('#WSRemarks').val(result.WSRemarks);

                                    var uCondition = result.WSUnitCondition;
                                if (uCondition == 1) {
                                    $('#UnitNew').prop('checked', true);
                                    $('#UClass').addClass("hidden");
                                } else if (uCondition == 2) {
                                    $('#UnitUsed').prop('checked', true);
                                    $('#UClass').removeClass("hidden");
                                } else {
                                    $('input[name="Radio_Unit"]').prop('checked', false);
                                    $('#UClass').removeClass("hidden");
                                }
                            // $('#UnitInfoPOUID').val(result.WSUnitCondition);
                        }
                    });
                });
            
            // If Area Change, Data from Bay also changes
                jQuery(document).on( "change", "#UnitArea", function(){
                    var area = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('w-storage8.getBay') }}",
                        method:"GET",
                        data:{area: area, _token: _token,},
                        success:function(result){
                            $('#UnitBay').html(result);
                        }
                    });
                });

            // Get Transfer Data
                jQuery(document).on( "click", "#transferUnit", function(){
                    var WSJONum = $('#UnitInfoJON').val();
                    var WSPOUID = $('#UnitInfoPOUID').val();
                    var UnitBayNum = $('#UnitBayNum').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('w-storage8.getTransferData') }}",
                        type: "GET",
                        dataType: "JSON",
                        data: {WSJONum: WSJONum, WSPOUID: WSPOUID, UnitBayNum: UnitBayNum, _token: _token,},
                        success: function(result) {
                            $('#UnitStatus').val(result.TransferStatus);
                            $('#UnitArea').val(result.TransferArea);
                            $('#UnitBay').html(result.TransferBay);
                            $('#BayID').val($('#UnitBay').val());
                            $('#UnitRemarksT').val(result.TransferRemarks);
                            $('#POUIDx').val(WSPOUID);

                            $('#btnPOUTransferH').click();
                        },
                    });
                });

            // Save Transfer Data
                jQuery(document).on( "click", "#saveTransferUnit", function(){ 
                    // alert($('#BayID').val());
                    $.ajax({
                        url: "{{ route('w-storage8.saveTransferData') }}",
                        type: "POST",
                        // dataType: "JSON",
                        data: $('#formWHTransfer').serialize(),
                        success: function(result) {
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            location.reload();
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Save Unit Data
                jQuery(document).on( "click", "#saveUnit", function(){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({    
                        url: "{{ route('w-storage8.saveUnitData') }}",
                        type: "POST",
                        data: $('#formUR').serialize(),
                        success: function(result) {
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            // location.reload();
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
                
            // Radio for Transfer
                $('#divDelTransfer').hide();
                $('input[name="Radio_Transfer"]').change(function() {
                    var value = $(this).val();
                    if (value === '1') {
                        $('#divDelTransfer').hide();
                        $('#divWHTransfer').show();
                    } else if (value === '2') {
                        $('#divWHTransfer').hide();
                        $('#divDelTransfer').show();
                    }
                });
                
        });
    </script>
</x-app-layout>
