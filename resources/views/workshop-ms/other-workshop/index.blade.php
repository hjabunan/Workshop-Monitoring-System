@section('title','Workshop Monitoring System')
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

        th {
            background: white;
            position: sticky;
            top: 0;
        }

        #modalDeleteParts.flex {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #calendar {
            max-width: 1100px;
            margin: 5px auto;
            height: 250px;
            font-size: 12px;
        }
    </style>

    <div style="height: calc(100vh - 90px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Other Workshop
                        </div>
                    </div>
                    <div class="grid grid-cols-12 mt-2">
                        {{-- BAYS --}}
                        <div class="col-span-10">
                            <div class="mb-4 border-b border-gray-200">
                                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                                    <li class="" role="presentation">
                                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="ChargingArea-tab" data-tabs-target="#ChargingArea" type="button" role="tab" aria-controls="ChargingArea" aria-selected="false">CHARGING AREA</button>
                                    </li>
                                    <li class="" role="presentation">
                                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="FabArea-tab" data-tabs-target="#FabArea" type="button" role="tab" aria-controls="FabArea" aria-selected="false">FABRICATION AREA</button>
                                    </li>
                                    <li class="" role="presentation">
                                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="PaintingArea-tab" data-tabs-target="#PaintingArea" type="button" role="tab" aria-controls="PaintingArea" aria-selected="false">PAINTING AREA</button>
                                    </li>
                                </ul>
                            </div>
                                
                            <div id="myTabContent">
                                <div class="hidden p-1.5 rounded-lg" id="ChargingArea" role="tabpanel" aria-labelledby="ChargingArea-tab">
                                    <div class="">
                                        <div class="grid grid-cols-4 content-start gap-1 mr-2">
                                            @foreach ($bays as $bay)
                                                @if ($bay->section == 8)
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
                                                                            $Status = "RESERVED UNIT";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 12)
                                                                        @php
                                                                            $Status = "WAITING FOR MCI";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 13)
                                                                        @php
                                                                            $Status = "WAITING FOR PDI";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 14)
                                                                        @php
                                                                            $Status = "DONE PDI (WFD)";
                                                                        @endphp
                                                                    @else
                                                                        @php
                                                                            $Status = "VACANT";
                                                                        @endphp
                                                                    @endif
                                                                    @php
                                                                        $TransDate = $WS->POUTransferDate;
                                                                        $dateToday = date('m/d/Y');
                                                                        
                                                                        $dateTimeTransDate = new DateTime($TransDate);
                                                                        $dateTimeToday = new DateTime($dateToday);
        
                                                                        
                                                                        $interval = $dateTimeTransDate->diff($dateTimeToday);
        
                                                                        
                                                                        $diffInDays = $interval->days;
        
                                                                        foreach ($scl as $legend) {
                                                                            if ($diffInDays >= $legend->stg_dayin && $diffInDays <= $legend->stg_dayout){
                                                                                $color = $legend->stg_color;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                <div class="">
                                                                    <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block {{$color}} focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                        <div class=""><label class="font-medium text-lg ">{{$bay->area_name}}</label></div>
                                                                        <input type="hidden" id="hddnJONum" value="{{$WS->WSID}}">
                                                                        <input type="hidden" id="hddnTransferDate" value="{{$WS->POUTransferDate}}">
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
                                                            <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block bg-gray-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                <div class=""><label class="font-medium text-lg">{{$bay->area_name}}</label></div>
                                                                <input type="hidden" id="hddnJONum" value="0">
                                                                <div class="grid grid-cols-7 text-xs">
                                                                    <div class="col-span-3 text-gray-500 text-left">
                                                                        <div class=""><label class="font-medium">Code:</label></div>
                                                                        <div class=""><label class="font-medium">Brand:</label></div>
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
                                <div class="hidden p-1.5 rounded-lg" id="FabArea" role="tabpanel" aria-labelledby="FabArea-tab">
                                    <div class="">
                                        <div class="grid grid-cols-4 content-start gap-1 mr-2">
                                            @foreach ($bays as $bay)
                                                @if ($bay->section == 10)
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
                                                                            $Status = "RESERVED UNIT";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 12)
                                                                        @php
                                                                            $Status = "WAITING FOR MCI";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 13)
                                                                        @php
                                                                            $Status = "WAITING FOR PDI";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 14)
                                                                        @php
                                                                            $Status = "DONE PDI (WFD)";
                                                                        @endphp
                                                                    @else
                                                                        @php
                                                                            $Status = "VACANT";
                                                                        @endphp
                                                                    @endif
                                                                    @php
                                                                        $TransDate = $WS->POUTransferDate;
                                                                        $dateToday = date('m/d/Y');
                                                                        
                                                                        $dateTimeTransDate = new DateTime($TransDate);
                                                                        $dateTimeToday = new DateTime($dateToday);
        
                                                                        
                                                                        $interval = $dateTimeTransDate->diff($dateTimeToday);
        
                                                                        
                                                                        $diffInDays = $interval->days;
        
                                                                        foreach ($scl as $legend) {
                                                                            if ($diffInDays >= $legend->stg_dayin && $diffInDays <= $legend->stg_dayout){
                                                                                $color = $legend->stg_color;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                <div class="">
                                                                    <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block {{$color}} focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                        <div class=""><label class="font-medium text-lg ">{{$bay->area_name}}</label></div>
                                                                        <input type="hidden" id="hddnJONum" value="{{$WS->WSID}}">
                                                                        <input type="hidden" id="hddnTransferDate" value="{{$WS->POUTransferDate}}">
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
                                                            <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block bg-gray-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                <div class=""><label class="font-medium text-lg">{{$bay->area_name}}</label></div>
                                                                <input type="hidden" id="hddnJONum" value="0">
                                                                <div class="grid grid-cols-7 text-xs">
                                                                    <div class="col-span-3 text-gray-500 text-left">
                                                                        <div class=""><label class="font-medium">Code:</label></div>
                                                                        <div class=""><label class="font-medium">Brand:</label></div>
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
                                <div class="hidden p-1.5 rounded-lg" id="PaintingArea" role="tabpanel" aria-labelledby="PaintingArea-tab">
                                    <div class="">
                                        <div class="grid grid-cols-4 content-start gap-1 mr-2">
                                            @foreach ($bays as $bay)
                                                @if ($bay->section == 11)
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
                                                                            $Status = "RESERVED UNIT";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 12)
                                                                        @php
                                                                            $Status = "WAITING FOR MCI";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 13)
                                                                        @php
                                                                            $Status = "WAITING FOR PDI";
                                                                        @endphp
                                                                    @elseif($WS->WSStatus == 14)
                                                                        @php
                                                                            $Status = "DONE PDI (WFD)";
                                                                        @endphp
                                                                    @else
                                                                        @php
                                                                            $Status = "VACANT";
                                                                        @endphp
                                                                    @endif
                                                                    @php
                                                                        $TransDate = $WS->POUTransferDate;
                                                                        $dateToday = date('m/d/Y');
                                                                        
                                                                        $dateTimeTransDate = new DateTime($TransDate);
                                                                        $dateTimeToday = new DateTime($dateToday);
        
                                                                        
                                                                        $interval = $dateTimeTransDate->diff($dateTimeToday);
        
                                                                        
                                                                        $diffInDays = $interval->days;
        
                                                                        foreach ($scl as $legend) {
                                                                            if ($diffInDays >= $legend->stg_dayin && $diffInDays <= $legend->stg_dayout){
                                                                                $color = $legend->stg_color;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                <div class="">
                                                                    <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block {{$color}} focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                        <div class=""><label class="font-medium text-lg ">{{$bay->area_name}}</label></div>
                                                                        <input type="hidden" id="hddnJONum" value="{{$WS->WSID}}">
                                                                        <input type="hidden" id="hddnTransferDate" value="{{$WS->POUTransferDate}}">
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
                                                            <div data-modal-target="modalUnitInfo" data-modal-toggle="modalUnitInfo" data-id="{{$bay->id}}" data-bayname="{{$bay->area_name}}" class="btnBay block bg-gray-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full" style="cursor: pointer;">
                                                                <div class=""><label class="font-medium text-lg">{{$bay->area_name}}</label></div>
                                                                <input type="hidden" id="hddnJONum" value="0">
                                                                <div class="grid grid-cols-7 text-xs">
                                                                    <div class="col-span-3 text-gray-500 text-left">
                                                                        <div class=""><label class="font-medium">Code:</label></div>
                                                                        <div class=""><label class="font-medium">Brand:</label></div>
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
                            </div>
                        </div>
                        {{-- LEGEND AND TOTAL CAPACITY --}}
                        <div class="col-span-2 grid grid-rows-4">
                            <div class="">
                                <label class="font-medium">LEGEND:</label>
                                <div class="grid grid-rows-5 gap-1 mt-2 ml-8">
                                    <div class="">
                                        <div style="float: left;" class="mr-2 w-12 h-6 bg-gray-500 rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>VACANT</label>
                                    </div>

                                    @foreach ($scl as $legend)
                                        <div class="">
                                            <div style="float: left;" class="mr-2 w-12 h-6 {{$legend->stg_color}} rounded ring-1 ring-inset ring-black ring-opacity-0"></div><label>{{$legend->stg_name}}</label>
                                        </div>
                                    @endforeach
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
    {{-- Hidden Button For Revert and Delete --}}
    <button type="button" id="btnPartDRH" class="btnPartDRH hidden" data-modal-target="modalPartDR" data-modal-toggle="modalPartDR"></button>

    
    {{-- MODALS --}}
    {{-- Large Modal - Unit Info --}}
        <div id="modalUnitInfo" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-7xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal body -->
                    <div class="space-y-6" style="height: 85vh;">
                        <form action="" id="FormMonitoring">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                {{-- A SIDE --}}
                                <div class="">
                                    <div class="grid grid-rows-2">
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
                                                    <option value="3">GOOD UNIT</option>
                                                    <option value="4">SERVICE UNIT</option>
                                                    <option value="5">FOR SCRAP UNIT</option>
                                                    <option value="6">FOR SALE UNIT</option>
                                                    <option value="7">WAITING PARTS</option>
                                                    <option value="8">WAITING BACK ORDER</option>
                                                    <option value="9">WAITING SPARE BATT</option>
                                                    <option value="10">STOCK UNIT</option>
                                                    <option value="11">RESERVED UNIT</option>
                                                    <option value="12">WAITING FOR MCI</option>
                                                    <option value="13">WAITING FOR PDI</option>
                                                    <option value="14">DONE PDI (WFD)</option>
                                                    <option value="15">VACANT</option>
                                                </select>
                                            </div>
                                            <div class="">
                                                <input type="hidden" id="UnitInfoPOUID" name="UnitInfoPOUID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                                <input type="text" id="UnitInfoJON" name="UnitInfoJON" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                            </div>
                                            <div class="">
                                                <input type="text" id="UnitInfoBayNum" name="UnitInfoBayNum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                                <input type="hidden" id="UnitBayNum" name="UnitBayNum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-1 mb-1">
                                    <div class="mt-1 bg-red-600">
                                        <label for="" class="block text-lg font-medium text-white">&nbsp;&nbsp;&nbsp; Information</label>
                                    </div>
                                    <div class="">
                                        <div class="mb-1 border-b border-gray-200">
                                            <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center overflow-x-auto" id="InfoTab" data-tabs-toggle="#InfoTabContent" role="tablist">
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
                                        <div id="InfoTabContent">
                                            {{-- LEGEND TAB --}}
                                            <div class="hidden p-2 rounded-lg" id="legend" role="tabpanel" aria-labelledby="legend-tab">
                                                <div class="grid grid-rows-2">
                                                    <div class="grid grid-cols-3 rounded-lg place-items-center">
                                                        <div class="col-span-2"><label for="green" class="text-sm">On Schedule - (Within the Target Date)</label></div>
                                                        <div class="">
                                                            <div class="block w-48 bg-green-500 rounded">&nbsp;</div>
                                                        </div>
                                                        <div class="col-span-2 mt-1"><label for="yellow" class="text-sm">Caution - (7 Days Before the Target Date)</label></div>
                                                        <div class="mt-1">
                                                            <div class="block w-48 bg-yellow-500 rounded">&nbsp;</div>
                                                        </div>
                                                        <div class="col-span-2 mt-1"><label for="red" class="text-sm">Critical - (Above the Target Date)</label></div>
                                                        <div class=" mt-1">
                                                            <div class="block w-48 bg-red-500 rounded">&nbsp;</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div class="mb-1 border-b border-gray-200">
                                                        <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center overflow-x-auto" id="TechActTab" data-tabs-toggle="#LegendTabContent" role="tablist">
                                                            <li class="mr-2" role="presentation">
                                                                <button class="inline-block p-2 border-b-2 rounded-t-lg" id="technician-tab" data-tabs-target="#technician" type="button" role="tab" aria-controls="technician" aria-selected="false">Technician</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div id="LegendTabContent">
                                                        <div class="hidden p-2 rounded-lg" id="technician" role="tabpanel" aria-labelledby="technician-tab">
                                                            <div class="grid grid-cols-2 place-items-center">
                                                                <div class=""><label for="red" class="text-sm">Technician In-Charge</label></div>
                                                                <div class=""><label for="red" class="text-sm">Technician In-Charge Schedule</label></div>
                                                                <div class="">
                                                                    <div class="justify-self-center">
                                                                        <ul id="UnitInfoTechnician" name="UnitInfoTechnician" class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg text-center">
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="grid">
                                                                    <div class="">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-gray-900 text-center text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" name="TechnicianStartDate" id="TechnicianStartDate">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-1">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-gray-900 text-center text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" name="TechnicianEndDate" id="TechnicianEndDate">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-1 justify-self-center">
                                                                        <button type="button" id="viewSchedule" name="viewSchedule" data-modal-target="modalSchedule" data-modal-toggle="modalSchedule" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 p-2 focus:z-10">View Schedule</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- TRUCK DETAIL TAB --}}
                                            <div class="hidden p-2 rounded-lg" id="tdetail" role="tabpanel" aria-labelledby="tdetail-tab">
                                                <div class="grid grid-cols-2">
                                                    <div class="place-self-center self-center"><label class="font-medium">Code:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoCode" name="UnitInfoCode" class="border border-gray-300 text-gray-900 text-xl text-center font-medium rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Company Name:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoCompName" name="UnitInfoCompName" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Company Address:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoCompAdd" name="UnitInfoCompAdd" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Salesman:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoSalesman" name="UnitInfoSalesman" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Brand:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoBrand" name="UnitInfoBrand" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Serial Number:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoSerialNum" name="UnitInfoSerialNum" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Model:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoModel" name="UnitInfoModel" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Mast Type:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoMastType" name="UnitInfoMastType" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Unit Type:</label></div>
                                                    <div class="">
                                                        <select id="UnitInfoUType" name="UnitInfoUType" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
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
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Scope of Work:</label></div>
                                                    <div class=""><input type="text" id="UnitInfoSoW" name="UnitInfoSoW" class="border border-gray-300 text-gray-900 text-xs text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pointer-events-none"></div>
                                                </div>
                                                <div class="grid grid-cols-2 mt-0.5">
                                                    <div class="place-self-center self-center"><label class="font-medium">Remarks:</label></div>
                                                    <div class=""><textarea id="WSRemarks" name="WSRemarks" rows="4" class="remarks block w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 py-1" required></textarea></div>
                                                </div>
                                            </div>

                                            {{-- PLAN TAB --}}
                                            <div class="hidden p-1 rounded-lg" id="plan" role="tabpanel" aria-labelledby="plan-tab">
                                                <div class="flex flex-col">
                                                    <div class="">
                                                        {{-- PLAN --}}
                                                        <div class="col-span-4 grid grid-cols-5">
                                                            <div class="col-span-4 items-left">
                                                                <label for="" class="block text-lg font-medium text-gray-900">TARGET</label>
                                                            </div>
                                                            <div class="col-span-1 justify-self-center">
                                                                <button id="TUpdate" name="TUpdate" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center">UPDATE</button>
                                                            </div>
                                                        </div>
                                                        {{-- Inspection --}}
                                                        <div class="col-span-4 grid grid-cols-2">
                                                            <div class="grid grid-cols-2 place-items-center">
                                                                <div class="col-span-2"><label for="" class="block text-md text-gray-900">Inspection</label></div>
                                                                <div class=""><label for="" class="block text-sm text-gray-900">Date Start</label></div>
                                                                <div class="">
                                                                    <div class="relative max-w-sm">
                                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                        </div>
                                                                        <input datepicker type="text" id="TInspectDStart" name="TInspectDStart" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date Start">
                                                                    </div>
                                                                </div>
                                                                <div class="mt-1"><label for="" class="block text-sm text-gray-900">Date End</label></div>
                                                                <div class="mt-1">
                                                                    <div class="relative max-w-sm">
                                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                        </div>
                                                                        <input datepicker type="text" id="TInspectDEnd" name="TInspectDEnd" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date End">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Repair --}}
                                                            <div class="grid grid-cols-2 place-items-center">
                                                                <div class="col-span-2"><label for="" class="block text-md text-gray-900">Repair</label></div>
                                                                <div class=""><label for="" class="block text-sm text-gray-900">Date Start</label></div>
                                                                <div class="">
                                                                    <div class="relative max-w-sm">
                                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                        </div>
                                                                        <input datepicker type="text" id="TRepairDStart" name="TRepairDStart" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date Start">
                                                                    </div>
                                                                </div>
                                                                <div class="mt-1"><label for="" class="block text-sm text-gray-900">Date End</label></div>
                                                                <div class="mt-1">
                                                                    <div class="relative max-w-sm">
                                                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                        </div>
                                                                        <input datepicker type="text" id="TRepairDEnd" name="TRepairDEnd" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date End">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="mt-3 mb-3">
                                                    <div class="">
                                                        <div class="grid grid-cols-4">
                                                            <div class="col-span-3 items-left">
                                                                <label for="" class="block text-lg font-medium text-gray-900">ACTUAL</label>
                                                            </div>
                                                            <div class="col-span-1 justify-self-center">
                                                                <button id="AReset" name="AReset" type="button" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center">RESET</button>
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-2">
                                                            <div class="">
                                                                {{-- Date Start --}}
                                                                <div id="IDS" class="grid grid-cols-4 place-items-center" readonly>
                                                                    <div class="col-span-4"><label for="" class="block text-sm text-gray-900">Date of Inspection</label></div>
                                                                    <div class=""><label for="" class="block text-sm text-gray-900">Date Start</label></div>
                                                                    <div class="col-span-2">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" id="ActualIDStart" name="ActualIDStart" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date Start">
                                                                        </div>
                                                                    </div>
                                                                    <div class="">
                                                                        <button id="updateIDS" name="updateIDS" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">UPDATE</button>
                                                                    </div>
                                                                </div>
                                                                {{-- Date End --}}
                                                                <div id="IDE" class="grid grid-cols-4 place-items-center">
                                                                    <div class="mt-0.5"><label for="" class="block text-sm text-gray-900">Date End</label></div>
                                                                    <div class="col-span-2 mt-0.5">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" id="ActualIDEnd" name="ActualIDEnd" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date End">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-0.5">
                                                                        <button id="updateIDE" name="updateIDE" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">UPDATE</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                {{-- Date Start --}}
                                                                <div id="RDS" class="grid grid-cols-4 place-items-center">
                                                                    <div class="col-span-4"><label for="" class="block text-sm text-gray-900">Date of Repair</label></div>
                                                                    <div class=""><label for="" class="block text-sm text-gray-900">Date Start</label></div>
                                                                    <div class="col-span-2">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" id="ActualRDStart" name="ActualRDStart" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date Start">
                                                                        </div>
                                                                    </div>
                                                                    <div class="">
                                                                        <button id="updateRDS" name="updateRDS" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">UPDATE</button>
                                                                    </div>
                                                                </div>
                                                                {{-- Date End --}}
                                                                <div id="RDE" class="grid grid-cols-4 place-items-center">
                                                                    <div class="mt-0.5"><label for="" class="block text-sm text-gray-900">Date End</label></div>
                                                                    <div class="col-span-2 mt-0.5">
                                                                        <div class="relative max-w-sm">
                                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                            </div>
                                                                            <input datepicker type="text" id="ActualRDEnd" name="ActualRDEnd" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1" placeholder="Date End">
                                                                        </div>
                                                                    </div>
                                                                    <div class="mt-0.5">
                                                                        <button id="updateRDE" name="updateRDE" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center">UPDATE</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="mt-3 mb-3">
                                                    <div class="grid grid-cols-3">
                                                        <div class="col-span-2 pointer-events-none mr-2"><canvas id="PlanvActualChart" width="80" height="40"></canvas></div>
                                                        <div class="grid grid-rows-2 ml-2">
                                                            <div class="mt-5">
                                                                <label for="" class="block text-sm font-medium text-gray-900">Work Efficiency Result</label>
                                                                <input type="text" id="WERPercentage" name="WERPercentage" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg block w-full text-center py-1 font-medium pointer-events-none" placeholder="0">
                                                            </div>
                                                            <div class="">
                                                                <label for="" class="block text-sm font-medium text-gray-900">Target Day Progress</label>
                                                                <input type="text" id="TDPPercentage" name="TDPPercentage" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg sm:text-lg rounded-lg block w-full text-center py-1 font-medium pointer-events-none" placeholder="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ACTIVITY TAB --}}
                                            <div class="hidden p-2 rounded-lg" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                                <div id='calendar-container'>
                                                    <div id='calendar'></div>
                                                </div>
                                                {{-- <hr class="mt-2 mb-2">
                                                <div class="grid grid-cols-2">
                                                    <input type="hidden" id="UnitTSID" name="UnitTSID">
                                                    <div class="">
                                                        <div class="ml-5 mr-5 grid place-items-center">
                                                            <label for="" class="text-sm text-center">Activity of the Day</label>
                                                        </div>
                                                        <div class="ml-5 mr-5 mt-3 justify-self-center">
                                                            <input type="text" name="UnitInfoAOTD" id="UnitInfoAOTD" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg text-sm text-center pointer-events-none font-medium">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-3">
                                                        <div class=""><label for="" class="text-sm">Status:</label></div>
                                                        <div class="col-span-2">
                                                            <select id="UnitActivityStatus" name="UnitActivityStatus" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full text-center">
                                                                <option value="" selected disabled></option>
                                                                <option value="1">PENDING</option>
                                                                <option value="2">ONGOING</option>
                                                                <option value="3">DONE</option>
                                                            </select>
                                                        </div>
                                                        <div class="mt-1"><label for="" class="text-sm">Remarks:</label></div>
                                                        <div class="col-span-2 mt-1">
                                                            <textarea id="UnitInfoRemarks" name="UnitInfoRemarks" rows="3" class="block p-2.5 w-full text-xs text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                                        </div>
                                                        <div class="col-span-3 mt-2 ml-5 mr-5">
                                                            <button type="button" id="saveActivity" name="saveActivity" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full">SAVE</button>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>

                                            {{-- PARTS INFO TAB --}}
                                            <div class="hidden p-4 rounded-lg" id="partinfo" role="tabpanel" aria-labelledby="partinfo-tab">
                                                <div class="grid grid-cols-5">
                                                    <div class="col-span-5 pointer-events-none">
                                                        <canvas id="MonPartsChart"></canvas>
                                                    </div>
                                                    <div class="col-span-5 grid grid-cols-5 mt-6">
                                                        <div class="place-self-center">
                                                            <label for="" class="block text-lg font-medium text-red-600">Completion</label>
                                                        </div>
                                                        <div class="">
                                                            <input type="text" id="PChartCompletion" name="PChartCompletion" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg block w-full text-center py-1 pointer-events-none">
                                                        </div>
                                                        <div class="col-span-3"></div>
                                                        <div class="place-self-center mt-1">
                                                            <label for="" class="block text-lg font-medium text-red-600">Pending Parts</label>
                                                        </div>
                                                        <div class="mt-1">
                                                            <input type="text" id="PChartPP" name="PChartPP" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg block w-full text-center py-1 pointer-events-none">
                                                        </div>
                                                        <div class="mt-1"></div>
                                                        <div class="place-self-center mt-1">
                                                            <label for="" class="block text-lg font-medium text-red-600">Installed Parts</label>
                                                        </div>
                                                        <div class="mt-1">
                                                            <input type="text" id="PChartIP" name="PChartIP" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg block w-full text-center py-1 pointer-events-none">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- DOWNTIME TAB --}}
                                            <div class="hidden p-2 rounded-lg" id="downtime" role="tabpanel" aria-labelledby="downtime-tab">
                                                <div class="grid grid-rows-3">
                                                    <div class="row-span-1 grid grid-cols-4">
                                                        <div class="col-span-4 grid grid-cols-7 items-center">
                                                            <input type="hidden" id="DTID" name="DTID">
                                                            <div id="label" class="">
                                                                <label for="DTSDate" class="block text-sm text-gray-900">Start Date</label>
                                                            </div>
                                                            <div id="input" class="col-span-2 uppercase mr-1">
                                                                <div class="relative max-w-sm">
                                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                    </div>
                                                                    <input datepicker type="text" id="DTSDate" name="DTSDate" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1.5" placeholder="Date Start">
                                                                </div>
                                                            </div>
                                                            <div class=""></div>
                                                            <div id="label" class="">
                                                                <label for="DTEDate" class="block text-sm text-gray-900">End Date</label>
                                                            </div>
                                                            <div id="input" class="col-span-2 uppercase mr-1">
                                                                <div class="relative max-w-sm">
                                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                                    </div>
                                                                    <input datepicker type="text" id="DTEDate" name="DTEDate" datepicker-format="mm/dd/yyyy" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1.5" placeholder="Date End">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-span-4 grid grid-cols-7 mt-3">
                                                            <div class="col-span-5 grid grid-cols-5 items-center">
                                                                <div id="label" class="col-span-2">
                                                                    <label for="DTReason" class="block text-sm text-gray-900">Reason for Pending</label>
                                                                </div>
                                                                <div id="input" class="col-span-3 uppercase mr-1">
                                                                    <select id="DTReason" name="DTReason" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full">
                                                                        <option value="" selected disabled></option>
                                                                        <option value="1">LACK OF SPACE</option>
                                                                        <option value="2">LACK OF TECHNICIAN</option>
                                                                        <option value="3">NO WORK</option>
                                                                        <option value="4">WAITING FOR MACHINING</option>
                                                                        <option value="5">WAITING FOR PARTS</option>
                                                                        <option value="6">WAITING FOR PO</option>
                                                                    </select>
                                                                </div>
                                                                <div id="label" class="col-span-2 mt-1">
                                                                    <label for="DTRemarks" class="block text-sm text-gray-900">Remarks</label>
                                                                </div>
                                                                <div id="input" class="col-span-3 uppercase mr-1 mt-1">
                                                                    <textarea id="DTRemarks" name="DTRemarks" rows="3" class="block p-2.5 w-full text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-span-2">
                                                                <div class="mt-1"><button id="saveDT" name="saveDT" type="button" class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">SAVE</button></div>
                                                                <div class="mt-1"><button id="deleteDT" name="deleteDT" type="button" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">DELETE</button></div>
                                                                <div class="mt-1"><button id="clearDT" name="clearDT" type="button" class="text-white bg-gray-600 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">CLEAR</button></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <hr class="mt-5"> --}}
                                                    <div class="row-span-2 mt-2 overflow-y-auto" style="height: calc(100vh - 535px);">
                                                        <table id="tableDT" class="w-full text-sm text-left text-gray-500">
                                                            <thead class="text-gray-700 uppercase bg-gray-50">
                                                                <tr class="place-items-center">
                                                                    <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                        DATE STARTED
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                        DATE END
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                        REASON FOR PENDING
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                        REMARKS
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                        TOTAL DAYS
                                                                    </th>
                                                                    <th scope="col" class="hidden">
                                                                        TOTAL DAYS
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="DTTable" name="DTTable" class="DTTable">
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- B SIDE --}}
                                <div class="bg-slate-700 rounded p-3" style="height: 85vh;">
                                    {{-- PLAN --}}
                                    <div class="grid grid-cols-5">
                                        <div class="col-span-1 mt-5"></div>
                                        <div class="col-span-3 place-self-center mt-5"><label for="" class="block text-sm text-white">Running Days</label></div>
                                        <div class="col-span-1 mt-5"></div>
                                        <div class="col-span-2 mt-2"></div>
                                        <div class="col-span-1 mt-2"><input type="text" id="BRunningDays" name="BRunningDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-3xl rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                        <div class="col-span-2 mt-2"></div>
                                        {{-- <div class="col-span-2"><label for="" class="block text-sm text-white">Days</label></div>
                                        <div class="col-span-3 place-self-center"><label for="" class="block text-xs text-white">Target Days</label></div>
                                        <div class="col-span-2 place-self-center"><input type="text" id="BTargetDays" name="BTargetDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                        <div class="col-span-3 place-self-center mt-0.5"><label for="" class="block text-xs text-white">Running Days</label></div>
                                        <div class="col-span-2 place-self-center mt-0.5"><input type="text" id="BRunningDays" name="BRunningDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div> --}}
                                    </div>
                                    <hr class=" mt-10 mb-5">
                                    {{-- ACTIVITY --}}
                                    <div class="grid grid-cols-5">
                                        <div class="col-span-5"><label for="" class="block text-md font-medium text-white">PLAN</label></div>
                                        {{-- Target --}}
                                        <div class="col-span-3"></div>
                                        <div class="col-span-1 place-self-center"><label for="" class="block text-sm text-white">Days</label></div>
                                        <div class="col-span-1 place-self-center ml-5"><label for="" class="block text-sm text-white">Total</label></div>
                                        <div class="col-span-1 grid grid-rows-2"><div class="row-span-2 place-self-center"><label for="" class="block text-md font-medium text-red-500">TARGET</label></div></div>
                                        <div class="col-span-2 grid grid-rows-2">
                                            <div class="place-self-center"><label for="" class="block text-xs text-white">Dismantle/Disassemble</label></div>
                                            <div class="place-self-center mt-0.5"><label for="" class="block text-xs text-white">Reassemble/Installation</label></div>
                                        </div>
                                        <div class="col-span-1 grid grid-rows-2">
                                            <div class=""><input type="text" id="TDismantleDays" name="TDismantleDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="TReassembleDays" name="TReassembleDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                        </div>
                                        <div class="col-span-1 grid grid-rows-2">
                                            <div class="row-span-2 place-self-center ml-5">
                                                <input type="text" id="TTotalDays" name="TTotalDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled>
                                            </div>
                                        </div>
                                        {{-- Actual --}}
                                        <div class="col-span-3 mt-2"></div>
                                        <div class="col-span-1 mt-2 place-self-center"><label for="" class="block text-sm text-white">Days</label></div>
                                        <div class="col-span-1 mt-2 place-self-center ml-5"><label for="" class="block text-sm text-white">Total</label></div>
                                        <div class="col-span-1 grid grid-rows-2"><div class="row-span-2 place-self-center"><label for="" class="block text-md font-medium text-red-500">ACTUAL</label></div></div>
                                        <div class="col-span-2 grid grid-rows-2">
                                            <div class="place-self-center"><label for="" class="block text-xs text-white">Dismantle/Disassemble</label></div>
                                            <div class="place-self-center mt-0.5"><label for="" class="block text-xs text-white">Reassemble/Installation</label></div>
                                        </div>
                                        <div class="col-span-1 grid grid-rows-2">
                                            <div class=""><input type="text" id="ADismantleDays" name="ADismantleDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="AReassembleDays" name="AReassembleDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                        </div>
                                        <div class="col-span-1 grid grid-rows-2">
                                            <div class="row-span-2 place-self-center ml-5">
                                                <input type="text" id="ATotalDays" name="ATotalDays" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="mt-5 mb-5">
                                    <div class="grid grid-cols-5">
                                        <div class="col-span-5"><label for="" class="block text-md font-medium text-white">DOWNTIME</label></div>
                                        <div class="col-span-3"></div>
                                        <div class="col-span-1 place-self-center"><label for="" class="block text-sm text-white">Days Pending</label></div>
                                        <div class=""></div>
                                        <div class="col-span-1"></div>
                                        <div class="col-span-2 grid grid-rows-6 items-center">
                                            <div class="place-self-left mt-0.5"><label for="" class="block text-xs text-white">LACK OF SPACE</label></div>
                                            <div class="place-self-left mt-0.5"><label for="" class="block text-xs text-white">LACK OF TECHNICIAN</label></div>
                                            <div class="place-self-left mt-0.5"><label for="" class="block text-xs text-white">NO WORK</label></div>
                                            <div class="place-self-left mt-0.5"><label for="" class="block text-xs text-white">WAITING FOR MACHINING</label></div>
                                            <div class="place-self-left mt-0.5"><label for="" class="block text-xs text-white">WAITING FOR PARTS</label></div>
                                            <div class="place-self-left mt-0.5"><label for="" class="block text-xs text-white">WAITING FOR PO</label></div>
                                        </div>
                                        <div class="col-span-1 grid grid-rows-6">
                                            <div class="mt-0.5"><input type="text" id="DTLOS" name="DTLOS" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="DTLOT" name="DTLOT" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="DTNW" name="DTNW" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="DTWFM" name="DTWFM" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="DTWFP" name="DTWFP" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            <div class="mt-0.5"><input type="text" id="DTWFPO" name="DTWFPO" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                        </div>
                                        <div class="col-span-1 mt-16">
                                            <div class="grid">
                                                <div class="ml-5 place-self-center"><label for="" class="block text-sm text-white">Total Downtime</label></div>
                                                <div class="ml-5"><input type="text" id="DTTotal" name="DTTotal" class="bg-gray-50 border border-gray-300 text-gray-900 text-2xl rounded-lg block w-full text-center py-1 font-medium" placeholder="0" disabled></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b">
                        <button id="saveBayMon" name="saveBayMon" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                        <button id="transferUnit" name="transferUnit" data-modal-target="modalTransferUnit" data-modal-toggle="modalTransferUnit" type="button" class="text-white bg-yellow-600 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">TRANSFER</button>
                        <button id="PIBayMon" name="PIBayMon" data-modal-target="modalPartInfo" data-modal-toggle="modalPartInfo" type="button" class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">PARTS INFO</button>
                        <button data-modal-hide="modalUnitInfo" id="closeBayMon" type="button" class="text-white bg-gray-600 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">EXIT</button>
                    </div>
                </div>
            </div>
        </div>
    {{-- Large Modal - Part Info --}}
        <div id="modalPartInfo" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-7xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-1 border-b rounded-t">
                        <h3 class="text-xl font-medium text-gray-900 ml-5">
                            Parts Information
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1 ml-auto inline-flex items-center" data-modal-hide="modalPartInfo">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="space-y-6 h-[calc(100vh-70px)]">
                        <form action="" id="PartsInfo" class="h-full">
                            @csrf
                            <div class="grid grid-cols-9 gap-x-4 h-full">
                                <div class="col-span-3 h-full p-4">
                                    <div class="grid grid-cols-3 place-items-center h-[59%] pb-8">
                                        <input type="hidden" id="PIID" name="PIID">
                                        <input type="hidden" id="PIJONum" name="PIJONum">
                                        <input type="hidden" id="PIPartIDx" name="PIPartIDx">

                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">MRI Number</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <input type="text" id="PIMRINum" name="PIMRINum" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1">
                                        </div>

                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Part Number</label>
                                        </div>
                                        <div class="col-span-2 relative optionDiv w-full">
                                            <input type="text" id="PIPartNum" name="PIPartNum" class="inputOption border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1" required autocomplete="off">
                                            <div class="listOption hidden absolute top-[62px] w-full rounded-lg border-x border-b border-gray-300 overflow-y-auto max-h-[30vh] text-gray-600 bg-white z-[99] shadow-xl">
                                                <ul id="PartNo">
                                                    
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Description</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <input type="text" id="PIDescription" name="PIDescription" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1">
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Quantity</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <input type="text" id="PIQuantity" name="PIQuantity" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1">
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Unit Price</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <input type="text" id="PIPrice" name="PIPrice" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1">
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Total Price</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <input type="text" id="PITPrice" name="PITPrice" class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full text-center py-1" readonly>
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Date Requested</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <div class="relative max-w-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input datepicker type="text" id="PIDateReq" name="PIDateReq" datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1.5" placeholder="">
                                            </div>
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Date Received</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <div class="relative max-w-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input datepicker type="text" id="PIDateRec" name="PIDateRec" datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1.5" placeholder="">
                                            </div>
                                        </div>
                                        <div class="place-self-center">
                                            <label for="" class="block text-sm text-gray-900 font-medium">Reason</label>
                                        </div>
                                        <div class="col-span-2 w-full">
                                            <select id="PIReason" name="PIReason" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-1">
                                                <option value="" selected disabled></option>
                                                <option value="3">(R) - Received</option>
                                                <option value="1">(B) - Back Order</option>
                                                <option value="2">(M) - Machining</option>
                                            </select>
                                        </div>
                                        <div class="col-span-3 grid grid-cols-4">
                                            <div class="ml-1 mr-1"><button id="savePI" name="savePI" type="button" class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">SAVE</button></div>
                                            <div class="ml-1 mr-1"><button data-modal-target="modalInstall" data-modal-toggle="modalInstall" id="installPI" name="installPI" type="button" class="text-white bg-yellow-600 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center w-full">INSTALL</button></div>
                                            <div class="ml-1 mr-1"><button id="deletePI" name="deletePI" type="button" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">DELETE</button></div>
                                            <div class="ml-1 mr-1"><button id="clearPI" name="clearPI" type="button" class="text-white bg-gray-600 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">CLEAR</button></div>
                                        </div>
                                    </div>
                                    <div class="pointer-events-none h-[41%]">
                                        <canvas id="PartsChart" class="!h-[200px]"></canvas>
                                        <div class="grid grid-cols-3">
                                            <div class="">
                                                <label for="" class="block text-lg font-medium text-red-600">Pending Parts</label>
                                                <input type="text" id="ChartPP" name="ChartPP" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg block w-full text-center py-1">
                                            </div>
                                            <div class=""></div>
                                            <div class="">
                                                <label for="" class="block text-lg font-medium text-red-600">Installed Parts</label>
                                                <input type="text" id="ChartIP" name="ChartIP" class="bg-gray-50 border border-gray-300 text-gray-900 text-lg rounded-lg block w-full text-center py-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-6 flex flex-col h-full gap-y-4">
                                    <div class="h-1/2">
                                        <label for="" class="block text-lg font-medium text-red-600">Pending Parts</label>
                                        <div class="overflow-y-auto" style="height: calc(100vh - 475px);">
                                            <table id="tablePParts" class="w-full text-sm text-left text-gray-500">
                                                <thead class="text-gray-700 uppercase bg-gray-50">
                                                    <tr class="PPI place-items-center">
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            PARTS NUMBER
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            DESCRIPTION
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            QUANTITY
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            MRI NUMBER
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            DATE REQUESTED
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            DATE RECEIVED
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                                            REASON
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="PParts" name="PParts" class="PParts">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="h-1/2 gap-y-5">
                                        <label for="" class="block text-lg font-medium text-red-600">Installed Parts</label>
                                        <div class="grid grid-cols-7">
                                            <div class="col-span-5 overflow-y-auto" style="height: calc(100vh - 535px);">
                                                <table id="tablePPartsI" class="w-full text-sm text-left text-gray-500">
                                                    <thead class="text-gray-700 uppercase bg-gray-50">
                                                        <tr class="PPII place-items-center">
                                                            <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                PARTS NUMBER
                                                            </th>
                                                            <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                DESCRIPTION
                                                            </th>
                                                            <th scope="col" class="px-3 py-1 text-xs text-center">
                                                                QUANTITY
                                                            </th>
                                                            <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                MRI NUMBER
                                                            </th>
                                                            <th scope="col" class="px-6 py-1 text-xs text-center">
                                                                DATE Installed
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="PPartsI" name="PPartsI" class="PPartsI">
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-span-2 p-2" style="height: calc(100vh - 535px);">
                                                <div class="grid grid-cols-2">
                                                    <div class="">
                                                        <label for="" class="block text-lg text-gray-900 font-medium">REMARKS</label>
                                                    </div>
                                                    <div class=""></div>
                                                    <div class="col-span-2">
                                                        <textarea id="PIRemarks" name="PIRemarks" rows="10" class="remarks block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 py-1" style="resize: none;" required></textarea>
                                                    </div>
                                                    <div class="ml-1 mr-1 mt-2"><button id="updateRemarks" name="updateRemarks" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">UPDATE</button></div>
                                                    <div class="ml-1 mr-1 mt-2"><button id="closePI" name="closePI" data-modal-hide="modalPartInfo" type="button" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center w-full">EXIT</button></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {{-- Small Modal - Install Part --}}
        <div id="modalInstall" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="bg-green-400 relative rounded-lg shadow">
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-3">
                            <div class="place-self-center">
                                <label for="" class="block text-sm text-gray-900 font-medium">Installation Date</label>
                            </div>
                            <div class="col-span-2">
                                <div class="relative max-w-sm">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <input datepicker type="text" id="PIDateInstalled" name="PIDateInstalled" datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 py-1.5" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                        <button id="saveInstall" name="saveInstall" data-modal-hide="modalInstall" type="button" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">INSTALL</button>
                        <button data-modal-hide="modalInstall" type="button" class="text-white bg-gray-600 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
    {{-- Small Modal - Revert and Delete --}}
        <div id="modalPartDR" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative rounded-lg shadow bg-red-300">
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-3 place-items-center">
                            <div class="">
                                <button data-modal-hide="modalPartDR" type="button" id="btnRevertPI" name="btnRevertPI" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">REVERT</button>
                            </div>
                            <div class="">
                                <button data-modal-hide="modalPartDR" type="button" id="btnDeletePI" name="btnDeletePI" class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">DELETE</button>
                            </div>
                            <div class="">
                                <button data-modal-hide="modalPartDR" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Large Modal - View Technician Schedule -->
        <div id="modalSchedule" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-800 bg-opacity-50">
            <div class="relative w-full max-w-4xl max-h-full">
                <!-- Modal content -->
                <div class="relative rounded-lg shadow bg-green-50">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-2 border-b rounded-t">
                        <input type="text" id="UnitSBayNum" name="UnitSBayNum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm text-center rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 pointer-events-none">
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalSchedule">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 space-y-4">
                        <div class="mt-1 overflow-y-auto rounded" style="height: calc(100vh - 580px);">
                            <table id="tableSchedule" class="w-full text-sm text-left text-gray-500">
                                <thead class="TSched text-gray-700 uppercase bg-green-200">
                                    <tr class="TSched place-items-center">
                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                            DATE
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                            Technician In-Charge
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                            Scope of Works
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                            Activity
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-xs text-center">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="BTableSchedule" name="BTableSchedule" class="WSTable">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- Delete Modal for Parts --}}
        <div id="modalDeleteParts" class="fixed items-center top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full bg-gray-800 bg-opacity-50">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <div class="relative bg-white rounded-lg shadow">
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</h3>
                        <button type="button" id="deleteConfirmParts" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure.
                        </button>
                        <button id="modalClosePart" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel.</button>
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
                        <form action="" id="formPOUT">
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
                                            <option value="11">RESERVED UNIT</option>
                                            <option value="12">WAITING FOR MCI</option>
                                            <option value="13">WAITING FOR PDI</option>
                                            <option value="14">DONE PDI (WFD)</option>
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
    <!-- TECHNICIAN ACTIVITY -->
        <div id="modalTechAct" class="fixed items-center top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-300 bg-opacity-50">
            <div class="rounded-lg shadow-lg w-full max-w-2xl mx-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Technician Activity
                        </h3>
                        <button id="closeTAa" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <form action="" id="formActivityInfo">
                            @csrf
                            <div class="grid gap-4 mb-4 md:grid-cols-2">
                                <input type="hidden" class="" id="TAID" name="TAID">
                                <input type="hidden" class="" id="TAJONumber" name="TAJONumber">
                                <input type="hidden" class="" id="TABayNum">
                                <div>
                                    <label for="TAStatus" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                    <select id="TAStatus" name="TAStatus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option value="1">PENDING</option>
                                        <option value="2">ONGOING</option>
                                        <option value="3">DONE</option>
                                    </select>
                                </div>
                                <div class=""></div>
                                <div>
                                    <label for="TATechnician" class="block mb-2 text-sm font-medium text-gray-900">Technician</label>
                                    <input type="text" id="TATechnician" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pointer-events-none">
                                </div>
                                <div>
                                    <label for="TASchedDate" class="block mb-2 text-sm font-medium text-gray-900">Schedule Date</label>
                                    {{-- <input type="date" id="TASchedDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required> --}}
                                    <div class="relative max-w-sm">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <input type="text" id="TASchedDate" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" class="bg-gray-50 border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 pointer-events-none">
                                    </div>
                                </div>
                                <div>
                                    <label for="TASTime" class="block mb-2 text-sm font-medium text-gray-900">Start Time</label>
                                    <div class="relative max-w-sm">
                                        <input type="time" id="TASTime" name="TASTime" class="bg-gray-50 border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                                    </div>
                                </div>
                                <div>
                                    <label for="TAETime" class="block mb-2 text-sm font-medium text-gray-900">End Time</label>
                                    <div class="relative max-w-sm">
                                        <input type="time" id="TAETime" name="TAETime" class="bg-gray-50 border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                                    </div>
                                </div>
                                <div>
                                    <label for="TASoW" class="block mb-2 text-sm font-medium text-gray-900">Scope of Work</label>
                                    <input type="text" id="TASoW" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pointer-events-none">
                                </div>
                                <div>
                                    <label for="TAActivity" class="block mb-2 text-sm font-medium text-gray-900">Activity</label>
                                    <input type="text" id="TAActivity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pointer-events-none">
                                </div>
                                <div class="col-span-2">
                                    <label for="TARemarks" class="block mb-2 text-sm font-medium text-gray-900">Remarks</label>
                                    <textarea rows="4" id="TARemarks" name="TARemarks" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" style="resize: none;" required></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-3 space-x-2 border-t border-gray-200 rounded-b">
                        <button id="saveTActivity" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">UPDATE</button>
                        <button id="closeTAb" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        $(document).ready(function(){
            
            $('#PIQuantity').on('keydown', function(event) {
                var keyCode = event.which ? event.which : event.keyCode;
                
                if (
                    (keyCode >= 48 && keyCode <= 57) ||
                    (keyCode >= 96 && keyCode <= 105) || 
                    keyCode === 8 || keyCode === 46
                ) {
                } else {
                    event.preventDefault();
                }
            });

            // Color Changing
                // $(".btnBay").each(function() {
                //     var hddnJONum = $(this).find("#hddnJONum").val();
                //     var hddnTransferDate = $(this).find("#hddnTransferDate").val();
                //     if (hddnJONum == 0) {
                //         $(this).addClass("bg-gray-500");
                //     } else {
                //         // For Running Days
                //         var startDate = new Date(hddnTransferDate);
                //             var today = new Date();
                //             var todayDate = today; 
                //             var rdays = 0;
                //             while (startDate <= todayDate) {
                //                 var dayOfWeek = startDate.getDay();
                //                 if (dayOfWeek !== 0) {
                //                 rdays++;
                //                 }
                //                 startDate.setDate(startDate.getDate() + 1);
                //             }

                //             if(rdays <= 90){
                //                 $(this).addClass("bg-green-500");
                //             } else if (rdays > 90 && rdays <= 180) {
                //                 $(this).addClass("bg-blue-500");
                //             } else if (rdays > 180 && rdays <= 270) {
                //                 $(this).addClass("bg-yellow-500");
                //             } else if (rdays >= 300) {
                //                 $(this).addClass("bg-red-500");
                //             }
                //     }
                // });

            // 
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
                // var CUnitTotal = CUnitTICJ + CUnitTEJ + CUnitTICC + CUnitTEC + CUnitTRT + CUnitBTRT + CUnitBTS + CUnitRTR + CUnitRS + CUnitST + CUnitPPT + CUnitOPC + CUnitHPT;
                var CUnitTotal = <?php echo $CUnitTotal; ?>;
                    $('#UnitTotal').val(CUnitTotal);

            // 
                jQuery(document).on("click", "#activity-tab", function(){
                    var bay = $('#UnitBayNum').val();
                    var JON = $('#UnitInfoJON').val();
                    var _token = $('input[name="_token"]').val();
                    
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        height: 450,
                        // initialView: 'dayGridWeek',
                        initialView: 'timeGridWeek',
                        headerToolbar: {
                            left: '',
                            center: 'title',
                            right: 'today prev,next'
                        },
                        weekends: true,
                        hiddenDays: [0], // hide Sundays
                        allDaySlot: false,
                        displayEventTime: false,
                        events: function(info, successCallback) {
                            $.ajax({
                            url: '{{ route('other-workshop.getEvents') }}',
                            type: 'GET',
                            data: { bay: bay, JON: JON, _token: _token,},
                            success: function(response) {
                                successCallback(response);
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                            });
                        },
                        eventClick: function(info) {
                            var today = new Date();
                            var eventDate = new Date(info.event.start);

                            var formattedToday = today.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
                            var formattedEventDate = eventDate.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });

                            if (formattedEventDate >= formattedToday) {
                                var eventId = info.event.id;
                                var jonum = info.event.extendedProps.jonum;
                                var baynum = info.event.extendedProps.baynum;
                                var technician = info.event.extendedProps.technician;
                                var scheddate = info.event.extendedProps.scheddate;
                                var stime = info.event.extendedProps.stime;
                                var etime = info.event.extendedProps.etime;
                                var sow = info.event.extendedProps.sow;
                                var activity = info.event.extendedProps.activity;
                                var status = info.event.extendedProps.status;
                                var remarks = info.event.extendedProps.remarks;

                                $('#modalTechAct').removeClass('hidden');
                                $('#TAID').val(eventId);
                                $('#TAJONumber').val(jonum);
                                $('#TABayNum').val(baynum);
                                $('#TATechnician').val(technician);
                                $('#TASchedDate').val(scheddate);
                                $('#TASTime').val(stime);
                                $('#TAETime').val(etime);
                                $('#TASoW').val(sow);
                                $('#TAActivity').val(activity);
                                $('#TAStatus').val(status);
                                $('#TARemarks').val(remarks);
                            }
                        }
                    });
                    calendar.render();
                });

            // 
                jQuery(document).on( "click", "#closeTAa, #closeTAb", function(){
                    $("#modalTechAct").removeClass("flex");
                    $("#modalTechAct").addClass("hidden");
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
            // 
                jQuery(document).on( "click", "#modalClosePart", function(){
                    $("#modalDeleteParts").removeClass("flex");
                    $("#modalDeleteParts").addClass("hidden");
                });

            // Get Data per Bay
                jQuery(document).on( "click", ".btnBay", function(){
                    $("#InfoTab li:first-child button").click();
                    $("#TechActTab li:first-child button").click();
                    document.getElementById('FormMonitoring').reset();
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
                    
                    $('#DTTable').html('No Result Found!');

                    $.ajax({
                        url: "{{ route('other-workshop.getBayData') }}",
                        type: "GET",
                        dataType: "json",
                        data: {bay:bay, output: output, _token: _token,},
                        success: function(result) {
                            $('#UnitInfoToA').val(result.WSToA);
                            $('#UnitInfoPOUID').val(result.WSPOUID);
                            $('#UnitInfoJON').val(result.WSID);
                                if($('#UnitInfoJON').val() != ""){
                                    $('#UnitInfoStatus').val(result.WSStatus);
                                }else{
                                    $('#UnitInfoStatus').val(15);
                                }
                            $('#UnitBayNum').val(result.WSBayNum);
                            $('#UnitInfoCode').val(result.POUCode);
                            $('#UnitInfoCompName').val(result.POUCustomer);
                            $('#UnitInfoCompAdd').val(result.POUCustAddress);
                            $('#UnitInfoSalesman').val(result.POUSalesman);
                            $('#UnitInfoBrand').val(result.POUBrand);
                            $('#UnitInfoSerialNum').val(result.POUSerialNum);
                            $('#UnitInfoModel').val(result.POUModel);
                            $('#UnitInfoMastType').val(result.POUMastType);
                            $('#UnitInfoUType').val(result.WSUnitType);
                                    // For Plan Total
                                        var startDate = new Date(result.WSATIDS);
                                        var endDate = new Date(result.WSATRDE);
                                        var sdays = 0;
                                        while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    sdays++;
                                                }
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                $('#BTargetDays').val(sdays);
                                    // For Running Days
                                        var startDate = new Date(result.TransferDate);
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
                                $('#BRunningDays').val(rdays);
                            $('#TInspectDStart').val(result.WSATIDS);
                            $('#TInspectDEnd').val(result.WSATIDE);
                                    // For Days Target Dismatle
                                        var startDate = new Date($('#TInspectDStart').val());
                                        var endDate = new Date($('#TInspectDEnd').val());
                                        var daysi = 0;
                                        while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    daysi++;
                                                }
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                $('#TDismantleDays').val(daysi);
                            $('#TRepairDStart').val(result.WSATRDS);
                            $('#TRepairDEnd').val(result.WSATRDE);
                                    // For Days Target Reassemble
                                        var startDate = new Date($('#TRepairDStart').val());
                                        var endDate = new Date($('#TRepairDEnd').val());
                                        var daysr = 0;
                                        while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    daysr++;
                                                }
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                $('#TReassembleDays').val(daysr);
                                    // For Total Days of Activity Target
                                        var startDate = new Date($('#TInspectDStart').val());
                                        var endDate = new Date($('#TRepairDEnd').val());
                                        var days = 0;
                                        while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    days++;
                                                }
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                $('#TTotalDays').val(days);
                            $('#ActualIDStart').val(result.WSAAIDS);
                                    // Hide Inspection DS Update Button
                                        if ($("#ActualIDStart").val() != '') {
                                            $("#ActualIDStart").addClass("pointer-events-none");
                                            $("#updateIDS").hide();
                                            $("#IDE").show();
                                        } else {
                                            $("#ActualIDStart").removeClass("pointer-events-none");
                                            $("#updateIDS").show();
                                            $("#IDE").hide();
                                        }
                            $('#ActualIDEnd').val(result.WSAAIDE);
                                    // Hide Inspection DE Update Button
                                        if ($("#ActualIDEnd").val() != '') {
                                            $("#ActualIDEnd").addClass("pointer-events-none");
                                            $("#updateIDE").hide();
                                            $("#RDS").show();
                                        } else {
                                            $("#ActualIDEnd").removeClass("pointer-events-none");
                                            $("#updateIDE").show();
                                            $("#RDS").hide();
                                        }

                                    // Inspection Actual Number of Days
                                        var startDate = new Date(result.WSAAIDS);
                                        var endDate = new Date(result.WSAAIDE);
                                        var aidays = 0;
                                        while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    aidays++;
                                                }
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                $('#ADismantleDays').val(aidays);
                            $('#ActualRDStart').val(result.WSAARDS);
                                    // Hide Repair DS Update Button
                                        if ($("#ActualRDStart").val() != '') {
                                            $("#ActualRDStart").addClass("pointer-events-none");
                                            $("#updateRDS").hide();
                                            $("#RDE").show();
                                        } else {
                                            $("#ActualRDStart").removeClass("pointer-events-none");
                                            $("#updateRDS").show();
                                            $("#RDE").hide();
                                        }
                            $('#ActualRDEnd').val(result.WSAARDE);
                                    // Hide Repair DE Update Button
                                        if ($("#ActualRDEnd").val() != '') {
                                            $("#updateRDE").hide();
                                            $("#ActualRDEnd").addClass("pointer-events-none");
                                        } else {
                                            $("#updateRDE").show();
                                            $("#ActualRDEnd").removeClass("pointer-events-none");
                                        }

                                    // Repair Actual Number of Days
                                        var startDate = new Date(result.WSAARDS);
                                        var endDate = new Date(result.WSAARDE);
                                        var ardays = 0;
                                        while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    ardays++;
                                                }
                                            startDate.setDate(startDate.getDate() + 1);
                                        }
                                $('#AReassembleDays').val(ardays);
                                    // Actual Total Days
                                        if ($("#ActualIDStart").val() == ''){
                                            $('#ATotalDays').val('0');
                                        }else if($("#ActualIDStart").val() != '' && $("#ActualRDEnd").val() == '' ) {
                                            var startDate = new Date(result.WSAAIDS);
                                            var endDate = new Date();
                                            var tadays = 0;
                                            while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    tadays++;
                                                }
                                                startDate.setDate(startDate.getDate() + 1);
                                            }
                                            var actualdowntime = (tadays - result.Total_DTTDays);
                                            $('#ATotalDays').val(actualdowntime);
                                        }else if($("#ActualIDStart").val() != '' && $("#ActualRDEnd").val() != '' ) {
                                            var startDate = new Date(result.WSAAIDS);
                                            var endDate = new Date(result.WSAARDE);
                                            var tadays = 0;
                                            while (startDate <= endDate) {
                                            var dayOfWeek = startDate.getDay();
                                                if (dayOfWeek !== 0) {
                                                    tadays++;
                                                }
                                                startDate.setDate(startDate.getDate() + 1);
                                            }
                                            var actualdowntime = (tadays - result.Total_DTTDays);
                                            $('#ATotalDays').val(actualdowntime);
                                        
                                        }else{
                                            $('#ATotalDays').val('Error');
                                        }
                            $('#DTTable').html(result.DTTable);
                            $('#DTLOS').val(result.DTTotalsLOS);
                            $('#DTLOT').val(result.DTTotalsLOT);
                            $('#DTNW').val(result.DTTotalsNW);
                            $('#DTWFM').val(result.DTTotalsWFM);
                            $('#DTWFP').val(result.DTTotalsWFP);
                            $('#DTWFPO').val(result.DTTotalsWFPO);
                            $('#DTTotal').val(result.Total_DTTDays);
                                // Create the Chart for PlanvActualChart
                                    var planTotalDays = $("#TTotalDays").val();
                                    var actualTotalDays = $("#ATotalDays").val();
                                    var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                    var PlanvActualChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: ["Target Total Days", "Actual Total Days"],
                                            datasets: [{
                                                data: [planTotalDays, actualTotalDays],
                                                backgroundColor: [
                                                'rgba(255, 99, 132, 5)',
                                                'rgba(54, 162, 235, 5)',
                                                ],
                                                borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero: true
                                                    }
                                                }]
                                            }
                                        }
                                    });

                                if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                    var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                    var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                    $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                    $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                    $('#WERPercentage').val(100+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(100+'%');
                                }else{
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }
                                // Create the Chart for Parts Information
                                    var count1 = (result.count1);
                                    var count2 = (result.count2);
                            
                                    var ctx = document.getElementById("MonPartsChart").getContext("2d");
                                    var myChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: ["Pending Parts", "Installed Parts"],
                                            datasets: [{
                                                data: [count1, count2],
                                                backgroundColor: [
                                                'rgba(255, 99, 132, 5)',
                                                'rgba(54, 162, 235, 5)',
                                                ],
                                                borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero: true
                                                    }
                                                }]
                                            }
                                        }
                                    });

                            $('#PChartPP').val(result.count1);
                            $('#PChartIP').val(result.count2);
                                // Calculate Percentage
                                    var count3 = (count1 + count2);
                                    var count4 = ((count2/count3)*100)
                                        if($('#PChartPP').val() == 0 && $('#PChartIP').val() == 0){
                                            $('#PChartCompletion').val(0);
                                        }else{
                                            $('#PChartCompletion').val(count4.toFixed(2)+'%');
                                        }
                        // $('#PIRemarks').val(result.MonRemarks);
                        $('#UnitInfoTechnician').html(result.TechIC);
                        $('#UnitTSID').val(result.UnitTSID);
                        $('#UnitInfoAOTD').val(result.Activity);
                        // $('#UnitInfoAOTD').html(result.AOTD);
                        $('#UnitActivityStatus').val(result.UnitActivityStatus);
                        $('#UnitInfoRemarks').val(result.UnitInfoRemarks);
                        $('#UnitInfoSoW').val(result.MonSoW);
                        $('#WSRemarks').val(result.WSRemarks);

                        }
                    });
                });
                
            // Get Total Date for PLAN
                $('.datepicker-picker').on("click", function() {
                    var startDate = new Date($('#PlanDStart').val());
                    var endDate = new Date($('#PlanDTarget').val());
                    var days = 0;
                    while (startDate <= endDate) {
                    var dayOfWeek = startDate.getDay();
                    if (dayOfWeek !== 0) {
                        days++;
                    }
                    startDate.setDate(startDate.getDate() + 1);
                    }
                    $('#PlanTotalDays').val(days);
                    $('#BTargetDays').val(days);
                });

            // Save Other Data of Unit
                jQuery(document).on( "click", "#saveBayMon", function(){
                    $(this).prop("disabled", true);
                    $.ajax({
                        url: "{{ route('other-workshop.saveBayData') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: $("#FormMonitoring").serialize(),
                        success: function(result) {
                            $("#saveBayMon").prop("disabled", false);
                            $('#divTotalCap').html(result.TotalCap);
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#saveBayMon").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Get ACTIVITY
                // Target
                $('.datepicker-picker').on("click", function() {
                    var startDate = new Date($('#TInspectDStart').val());
                    var endDate = new Date($('#TInspectDEnd').val());
                    var daysi = 0;
                    while (startDate <= endDate) {
                    var dayOfWeek = startDate.getDay();
                    if (dayOfWeek !== 0) {
                        daysi++;
                    }
                    startDate.setDate(startDate.getDate() + 1);
                    }
                    $('#TDismantleDays').val(daysi);
                });

                $('.datepicker-picker').on("click", function() {
                    var startDate = new Date($('#TRepairDStart').val());
                    var endDate = new Date($('#TRepairDEnd').val());
                    var daysr = 0;
                    while (startDate <= endDate) {
                    var dayOfWeek = startDate.getDay();
                    if (dayOfWeek !== 0) {
                        daysr++;
                    }
                    startDate.setDate(startDate.getDate() + 1);
                    }
                    $('#TReassembleDays').val(daysr);
                });

                $('.datepicker-picker').on("click", function() {
                    var startDate = new Date($('#TInspectDStart').val());
                    var endDate = new Date($('#TRepairDEnd').val());
                    var days = 0;
                    while (startDate <= endDate) {
                    var dayOfWeek = startDate.getDay();
                    if (dayOfWeek !== 0) {
                        days++;
                    }
                    startDate.setDate(startDate.getDate() + 1);
                    }
                    $('#TTotalDays').val(days);
                });

            // Actual Activity Buttons
                if($("#updateIDS").val() == "") {
                    $("#IDE, #RDS, #RDE").hide();
                    $("#IDS").show();
                }

            // Save Dates on Target
                jQuery(document).on( "click", "#TUpdate", function(){
                    $(this).prop("disabled", true);
                    var TIStart = $("#TInspectDStart").val();
                    var TIEnd = $("#TInspectDEnd").val();
                    var TRStart = $("#TRepairDStart").val();
                    var TREnd = $("#TRepairDEnd").val();
                    var JONum = $('#UnitInfoJON').val();
                    var PulloutID = $('#UnitInfoPOUID').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.saveTargetActivity') }}",
                        method: "POST",
                        data: {TIStart: TIStart, TIEnd: TIEnd, TRStart: TRStart, TREnd: TREnd, JONum: JONum, PulloutID: PulloutID, _token: _token,},
                        success: function(result) {
                            $("#TUpdate").prop("disabled", false);
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            
                                // Create the Chart for PlanvActualChart
                                    var planTotalDays = $("#TTotalDays").val();
                                    var actualTotalDays = $("#ATotalDays").val();
                                    var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                    var PlanvActualChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: ["Target Total Days", "Actual Total Days"],
                                            datasets: [{
                                                data: [planTotalDays, actualTotalDays],
                                                backgroundColor: [
                                                'rgba(255, 99, 132, 5)',
                                                'rgba(54, 162, 235, 5)',
                                                ],
                                                borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero: true
                                                    }
                                                }]
                                            }
                                        }
                                    });

                                    if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                        var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                        var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                        $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                        $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                    }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                        $('#WERPercentage').val(100+'%');
                                        $('#TDPPercentage').val(0+'%');
                                    }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                        $('#WERPercentage').val(0+'%');
                                        $('#TDPPercentage').val(100+'%');
                                    }else{
                                        $('#WERPercentage').val(0+'%');
                                        $('#TDPPercentage').val(0+'%');
                                    }
                        },
                        error: function(error){
                            $("#TUpdate").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            

            // Save Date of Inspection Start
                jQuery(document).on( "click", "#updateIDS", function(){
                    var AIDStart = $("#ActualIDStart").val();
                    var JONum = $('#UnitInfoJON').val();
                    var PulloutID = $('#UnitInfoPOUID').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.updateIDS') }}",
                        method: "POST",
                        data: {AIDStart: AIDStart, JONum: JONum, PulloutID: PulloutID, _token: _token,},
                        success: function(result) {
                            $('#ActualIDStart').addClass('pointer-events-none');
                            $("#updateIDS").hide();
                            $("#IDE").show();
                            $("#updateIDE").show();
                            $('#ActualIDEnd').removeClass('pointer-events-none');

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");

                            var startDate = new Date(AIDStart);
                            var endDate = new Date();;
                            var tadays = 0;
                            while (startDate <= endDate) {
                                var dayOfWeek = startDate.getDay();
                                    if (dayOfWeek !== 0) {
                                        tadays++;
                                    }
                                startDate.setDate(startDate.getDate() + 1);
                            }

                            var dt = $('#DTTotal').val();

                            var actualdowntime = (tadays - dt);
                            $('#ATotalDays').val(actualdowntime);

                            
                            // For Running Days
                                $('#BRunningDays').val(tadays);

                            var planTotalDays = $("#TTotalDays").val();
                            var actualTotalDays = $("#ATotalDays").val();

                            // Create the Chart for PlanvActualChart
                                var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                var PlanvActualChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ["Plan Total Days", "Actual Total Days"],
                                        datasets: [{
                                            data: [planTotalDays, actualTotalDays],
                                            backgroundColor: [
                                            'rgba(255, 99, 132, 5)',
                                            'rgba(54, 162, 235, 5)',
                                            ],
                                            borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                        }]
                                    }
                                    }
                                });

                                if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                    var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                    var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                    $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                    $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                    $('#WERPercentage').val(100+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(100+'%');
                                }else{
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }

                                $("#UnitInfoStatus").val(2);
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // Save Date of Inspection End
                jQuery(document).on( "click", "#updateIDE", function(){
                    var AIDEnd = $("#ActualIDEnd").val();
                    var JONum = $('#UnitInfoJON').val();
                    var PulloutID = $('#UnitInfoPOUID').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.updateIDE') }}",
                        method: "POST",
                        data: {AIDEnd: AIDEnd, JONum: JONum, PulloutID: PulloutID, _token: _token,},
                        success: function(result) {
                            $('#ActualIDEnd').addClass('pointer-events-none');
                            $("#updateIDE").hide();
                            $("#RDS").show();
                            $("#updateRDS").show();
                            $('#ActualRDStart').removeClass('pointer-events-none');

                            var startDate = new Date($("#ActualIDStart").val());
                            var endDate = new Date($("#ActualIDEnd").val());
                            var aidays = 0;
                            while (startDate <= endDate) {
                                var dayOfWeek = startDate.getDay();
                                    if (dayOfWeek !== 0) {
                                        aidays++;
                                    }
                                startDate.setDate(startDate.getDate() + 1);
                            }
                            $('#ADismantleDays').val(aidays);

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // Save Date of Repair Start
                jQuery(document).on( "click", "#updateRDS", function(){
                    var ARDStart = $("#ActualRDStart").val();
                    var JONum = $('#UnitInfoJON').val();
                    var PulloutID = $('#UnitInfoPOUID').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.updateRDS') }}",
                        method: "POST",
                        data: {ARDStart: ARDStart, JONum: JONum, PulloutID: PulloutID, _token: _token,},
                        success: function(result) {
                            $('#ActualRDStart').addClass('pointer-events-none');
                            $("#updateRDS").hide();
                            $("#RDE").show();
                            $("#updateRDE").show();
                            $('#ActualRDEnd').removeClass('pointer-events-none');

                            $("#UnitInfoStatus").val(3);

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // Save Date of Repair End
                jQuery(document).on( "click", "#updateRDE", function(){
                    var ARDEnd = $("#ActualRDEnd").val();
                    var JONum = $('#UnitInfoJON').val();
                    var PulloutID = $('#UnitInfoPOUID').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.updateRDE') }}",
                        method: "POST",
                        data: {ARDEnd: ARDEnd, JONum: JONum, PulloutID: PulloutID, _token: _token,},
                        success: function(result) {
                            $('#ActualRDEnd').addClass('pointer-events-none');
                            $("#updateRDE").hide();
                            
                            var startDate = new Date($("#ActualIDStart").val());
                            var endDate = new Date($("#ActualRDEnd").val());
                            var tadays = 0;
                            while (startDate <= endDate) {
                                var dayOfWeek = startDate.getDay();
                                    if (dayOfWeek !== 0) {
                                        tadays++;
                                    }
                                startDate.setDate(startDate.getDate() + 1);
                            }
                            
                            $('#ATotalDays').val(tadays);
                            $('#ActualTotalDays').val(tadays);

                            var startDate = new Date($("#ActualRDStart").val());
                            var endDate = new Date($("#ActualRDEnd").val());
                            var ardays = 0;
                            while (startDate <= endDate) {
                                var dayOfWeek = startDate.getDay();
                                    if (dayOfWeek !== 0) {
                                        ardays++;
                                    }
                                startDate.setDate(startDate.getDate() + 1);
                            }
                            $('#AReassembleDays').val(ardays);
                            

                            var planTotalDays = $("#TTotalDays").val();
                            var actualTotalDays = $("#ATotalDays").val();

                            // Create the Chart for PlanvActualChart
                                var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                var PlanvActualChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                    labels: ["Plan Total Days", "Actual Total Days"],
                                    datasets: [{
                                        data: [planTotalDays, actualTotalDays],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                    },
                                    options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                        }]
                                    }
                                    }
                                });

                                if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                    var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                    var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                    $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                    $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                    $('#WERPercentage').val(100+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(100+'%');
                                }else{
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }

                                $("#UnitInfoStatus").val(4);

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Reset Dates on Actual
                jQuery(document).on( "click", "#AReset", function(){
                    var JONum = $('#UnitInfoJON').val();
                    var PulloutID = $('#UnitInfoPOUID').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.resetActual') }}",
                        method: "POST",
                        data: {JONum: JONum, PulloutID: PulloutID, _token: _token,},
                        success: function(result) {
                            $("#ActualIDStart").removeClass("pointer-events-none");
                            $("#ActualIDEnd").removeClass("pointer-events-none");
                            $("#ActualRDStart").removeClass("pointer-events-none");
                            $("#ActualRDEnd").removeClass("pointer-events-none");
                            $("#updateIDS").show();
                            $("#ActualIDStart").val('');
                            $("#ActualIDEnd").val('');
                            $("#ActualRDStart").val('');
                            $("#ActualRDEnd").val('');
                            $("#IDE").hide();
                            $("#RDS").hide();
                            $("#RDE").hide();

                            $('#ATotalDays').val('0');
                            $('#BRunningDays').val('0');
                            $('#ADismantleDays').val('0');
                            $('#AReassembleDays').val('0');

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");

                            var planTotalDays = $("#TTotalDays").val();
                            var actualTotalDays = $("#ATotalDays").val();

                            $("#UnitInfoStatus").val(1);

                            // Create the Chart for PlanvActualChart
                                var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                var PlanvActualChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ["Plan Total Days", "Actual Total Days"],
                                        datasets: [{
                                            data: [planTotalDays, actualTotalDays],
                                            backgroundColor: [
                                            'rgba(255, 99, 132, 5)',
                                            'rgba(54, 162, 235, 5)',
                                            ],
                                            borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                        }]
                                    }
                                    }
                                });

                                if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                    var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                    var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                    $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                    $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                    $('#WERPercentage').val(100+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(100+'%');
                                }else{
                                    $('#WERPercentage').val(0+'%');
                                    $('#TDPPercentage').val(0+'%');
                                }
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // 

            // Save Downtime
                jQuery(document).on( "click", "#saveDT", function(){
                    $(this).prop("disabled", true);
                    var JONum = $('#UnitInfoJON').val();
                    var DTID = $('#DTID').val();
                    var DTSDate = $('#DTSDate').val();
                    var DTEDate = $('#DTEDate').val();
                    var DTReason = $('#DTReason').val();
                    var DTRemarks = $('#DTRemarks').val();
                        var startDate = new Date($('#DTSDate').val());
                        var endDate = new Date($('#DTEDate').val());
                        var dtdays = 0;
                        while (startDate <= endDate) {
                            var dayOfWeek = startDate.getDay();
                                if (dayOfWeek !== 0) {
                                    dtdays++;
                                }
                            startDate.setDate(startDate.getDate() + 1);
                        }
                    var DTTDays = dtdays;
                    var _token = $('input[name="_token"]').val();
                        var aIDStart = $('#ActualIDStart').val();
                        var aRDEnd = $('#ActualRDEnd').val();

                    $.ajax({
                        url:"{{ route('other-workshop.saveDowntime') }}",
                        method: "POST",
                        data: {DTID: DTID, DTSDate: DTSDate, DTEDate: DTEDate, DTReason: DTReason, DTRemarks: DTRemarks, DTTDays: DTTDays, JONum: JONum, _token: _token,},
                        success: function(result) {
                            $("#saveDT").prop("disabled", false);
                            $('#DTID').val('');
                            $('#DTSDate').val('');
                            $('#DTEDate').val('');
                            $('#DTReason').val(0);
                            $('#DTRemarks').val('');
                            $('#DTTable').html(result.DTTable);
                            $('#DTLOS').val(result.DTTotalsLOS);
                            $('#DTLOT').val(result.DTTotalsLOT);
                            $('#DTNW').val(result.DTTotalsNW);
                            $('#DTWFM').val(result.DTTotalsWFM);
                            $('#DTWFP').val(result.DTTotalsWFP);
                            $('#DTWFPO').val(result.DTTotalsWFPO);
                            $('#DTTotal').val(result.Total_DTTDays);
                                if ($("#ActualRDEnd").val() != '') {
                                    var startDate = new Date(aIDStart);
                                    var endDate = new Date(aRDEnd);
                                    var tadays = 0;
                                    while (startDate <= endDate) {
                                        var dayOfWeek = startDate.getDay();
                                            if (dayOfWeek !== 0) {
                                                tadays++;
                                            }
                                        startDate.setDate(startDate.getDate() + 1);
                                    }
                                    
                                    var actualdowntime = (tadays - result.Total_DTTDays);
                                    $('#ATotalDays').val(actualdowntime);
                                }else if ($("#ActualRDEnd").val() == '') {
                                    var startDate = new Date(aIDStart);
                                    var endDate = new Date();;
                                    var tadays = 0;
                                    while (startDate <= endDate) {
                                        var dayOfWeek = startDate.getDay();
                                            if (dayOfWeek !== 0) {
                                                tadays++;
                                            }
                                        startDate.setDate(startDate.getDate() + 1);
                                    }
                                    
                                    var actualdowntime = ($('#BRunningDays').val() - result.Total_DTTDays);
                                    $('#ATotalDays').val(actualdowntime);
                                }else{
                                    $('#ATotalDays').val('');
                                }

                                // Create the Chart for PlanvActualChart
                                var planTotalDays = $("#TTotalDays").val();
                                    var actualTotalDays = $("#ATotalDays").val();
                                    var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                    var PlanvActualChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: ["Target Total Days", "Actual Total Days"],
                                            datasets: [{
                                                data: [planTotalDays, actualTotalDays],
                                                backgroundColor: [
                                                'rgba(255, 99, 132, 5)',
                                                'rgba(54, 162, 235, 5)',
                                                ],
                                                borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero: true
                                                    }
                                                }]
                                            }
                                        }
                                    });

                                    if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                        var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                        var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                        $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                        $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                    }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                        $('#WERPercentage').val(100+'%');
                                        $('#TDPPercentage').val(0+'%');
                                    }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                        $('#WERPercentage').val(0+'%');
                                        $('#TDPPercentage').val(100+'%');
                                    }else{
                                        $('#WERPercentage').val(0+'%');
                                        $('#TDPPercentage').val(0+'%');
                                    }

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#saveDT").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Get details of Downtime
                jQuery(document).on( "click", "#DTTable tr", function(){
                    var DTID = $(this).find('span').data('id');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.getDowntime') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{DTID: DTID, _token: _token,},
                        success:function(result){
                            $('#DTID').val(result.DTID);
                            $('#DTSDate').val(result.DTSDate);
                            $('#DTEDate').val(result.DTEDate);
                            $('#DTReason').val(result.DTReason);
                            $('#DTRemarks').val(result.DTRemarks);
                        }
                    });
                });
            
            // Delete Downtime
                jQuery(document).on( "click", "#deleteDT", function(){
                    var JONum = $('#UnitInfoJON').val();
                    var DTID = $('#DTID').val();
                    var _token = $('input[name="_token"]').val();
                        var aIDStart = $('#ActualIDStart').val();
                        var aRDEnd = $('#ActualRDEnd').val();

                    $.ajax({
                        url:"{{ route('other-workshop.deleteDowntime') }}",
                        method: "POST",
                        dataType: 'json',
                        data: {JONum: JONum, DTID: DTID, _token: _token,},
                        success: function(result) {
                            $('#DTID').val('');
                            $('#DTSDate').val('');
                            $('#DTEDate').val('');
                            $('#DTReason').val(0);
                            $('#DTRemarks').val('');
                            $('#DTTable').html(result.DTTable);
                            $('#DTLOS').val(result.DTTotalsLOS);
                            $('#DTLOT').val(result.DTTotalsLOT);
                            $('#DTNW').val(result.DTTotalsNW);
                            $('#DTWFM').val(result.DTTotalsWFM);
                            $('#DTWFP').val(result.DTTotalsWFP);
                            $('#DTWFPO').val(result.DTTotalsWFPO);
                            $('#DTTotal').val(result.Total_DTTDays);

                            if ($("#ActualRDEnd").val() != '') {
                                var startDate = new Date(aIDStart);
                                var endDate = new Date(aRDEnd);
                                var tadays = 0;
                                while (startDate <= endDate) {
                                    var dayOfWeek = startDate.getDay();
                                        if (dayOfWeek !== 0) {
                                            tadays++;
                                        }
                                    startDate.setDate(startDate.getDate() + 1);
                                }
                                
                                var actualdowntime = (tadays - result.Total_DTTDays);
                                $('#ATotalDays').val(actualdowntime);
                                $('#ActualTotalDays').val(actualdowntime);
                            }else if ($("#ActualRDEnd").val() == '') {
                                var startDate = new Date(aIDStart);
                                var endDate = new Date();
                                var tadays = 0;
                                while (startDate <= endDate) {
                                    var dayOfWeek = startDate.getDay();
                                        if (dayOfWeek !== 0) {
                                            tadays++;
                                        }
                                    startDate.setDate(startDate.getDate() + 1);
                                }
                                
                                var actualdowntime = (tadays - result.Total_DTTDays);
                                $('#ATotalDays').val(actualdowntime);
                                $('#ActualTotalDays').val(actualdowntime);
                            }else if(result.Total_DTTDays == 0){
                                $('#ATotalDays').val($('#ADismantleDays').val() + $('#AReassembleDays').val());
                            }else{
                                $('#ATotalDays').val('');
                            }

                                // Create the Chart for PlanvActualChart
                                var planTotalDays = $("#TTotalDays").val();
                                    var actualTotalDays = $("#ATotalDays").val();
                                    var ctx = document.getElementById("PlanvActualChart").getContext("2d");
                                    var PlanvActualChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: ["Target Total Days", "Actual Total Days"],
                                            datasets: [{
                                                data: [planTotalDays, actualTotalDays],
                                                backgroundColor: [
                                                'rgba(255, 99, 132, 5)',
                                                'rgba(54, 162, 235, 5)',
                                                ],
                                                borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                ],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            legend: {
                                                display: false
                                            },
                                            scales: {
                                                yAxes: [{
                                                    ticks: {
                                                        beginAtZero: true
                                                    }
                                                }]
                                            }
                                        }
                                    });

                                    if ($('#TTotalDays').val() != 0 && $('#ATotalDays').val() != 0){
                                        var percent1 = ((planTotalDays/actualTotalDays) * 100);
                                        var percent2 = ((actualTotalDays/planTotalDays) * 100);

                                        $('#WERPercentage').val(percent1.toFixed(2)+'%');
                                        $('#TDPPercentage').val(percent2.toFixed(2)+'%');
                                    }else if($('#TTotalDays').val() != 0 && $('#ATotalDays').val() == 0){
                                        $('#WERPercentage').val(100+'%');
                                        $('#TDPPercentage').val(0+'%');
                                    }else if($('#TTotalDays').val() == 0 && $('#ATotalDays').val() != 0){
                                        $('#WERPercentage').val(0+'%');
                                        $('#TDPPercentage').val(100+'%');
                                    }else{
                                        $('#WERPercentage').val(0+'%');
                                        $('#TDPPercentage').val(0+'%');
                                    }

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Clear Downtime
                jQuery(document).on( "click", "#clearDT", function(){
                    $('#DTID').val('');
                    $('#DTSDate').val('');
                    $('#DTEDate').val('');
                    $('#DTReason').val(0);
                    $('#DTRemarks').val('');
                });

            $('#installPI').hide();

            // Click Parts Info
                jQuery(document).on("click","#PIBayMon", function(){
                    $('#installPI').hide();
                    $('#PIID').val('');
                    $('#PIMRINum').val('');
                    $('#PIPartIDx').val('');
                    $('#PIPartNum').val('');
                    $('#PIDescription').val('');
                    $('#PIQuantity').val('');
                    $('#PIPrice').val('');
                    $('#PITPrice').val('');
                        var currentDate = new Date();
                        var month = currentDate.getMonth() + 1;
                        var day = currentDate.getDate();
                        var year = currentDate.getFullYear();

                        if (month < 10) {
                            month = "0" + month;
                        }
                        if (day < 10) {
                            day = "0" + day;
                        }

                        var formattedDate = month + "/" + day + "/" + year;
                    $('#PIDateReq').val(formattedDate);
                    $('#PIDateRec').val(formattedDate);
                    $('#PIReason').val(0);

                    var JONum = $('#UnitInfoJON').val();
                    $('#PIJONum').val(JONum);
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.getPI') }}",
                        method: "POST",
                        dataType: 'json',
                        data: {JONum: JONum, _token: _token,},
                        success: function(result) {
                            $('#PIRemarks').val(result.remarks);
                            $('#PParts').html(result.result1);
                            $('#PPartsI').html(result.result2);

                            var count1 = (result.count1);
                            var count2 = (result.count2);

                            $('#ChartPP').val(count1);
                            $('#ChartIP').val(count2);

                            // Create the Chart inside Part Information Modal
                            var ctx = document.getElementById("PartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                        },
                    });
                });

            // Price Computation
                var quantityInput = $('#PIQuantity');
                var priceInput = $('#PIPrice');
                var totalPriceInput = $('#PITPrice');

                function updateTotalPrice() {
                    var priceValue = priceInput.val().replace(',', '');
                    var quantity = parseFloat(quantityInput.val()) || 0;
                    var price = parseFloat(priceValue) || 0;
                    var formattedTotalPrice = quantity * price;
                    var totalPrice = formattedTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    totalPriceInput.val(totalPrice);
                }

                quantityInput.on('keyup', updateTotalPrice);
                priceInput.on('change', updateTotalPrice);

            // Save Parts Information
                jQuery(document).on( "click", "#savePI", function(){
                    $(this).prop("disabled", true);
                    var JONum = $('#PIJONum').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.savePI') }}",
                        method: "POST",
                        dataType: 'json',
                        data: $('#PartsInfo').serialize(),
                        success: function(result) {
                            $("#savePI").prop("disabled", false);
                            $('#installPI').hide();
                            $('#PIID').val('');
                            $('#PIMRINum').val('');
                            $('#PIPartIDx').val('');
                            $('#PIPartNum').val('');
                            $('#PIDescription').val('');
                            $('#PIQuantity').val('');
                            $('#PIPrice').val('');
                            $('#PITPrice').val('');
                                var currentDate = new Date();
                                var month = currentDate.getMonth() + 1;
                                var day = currentDate.getDate();
                                var year = currentDate.getFullYear();

                                if (month < 10) {
                                    month = "0" + month;
                                }
                                if (day < 10) {
                                    day = "0" + day;
                                }

                                var formattedDate = month + "/" + day + "/" + year;
                            $('#PIDateReq').val(formattedDate);
                            $('#PIDateRec').val(formattedDate);
                            $('#PIReason').val(0);
                            $('#PParts').html(result.result1);
                            $('#PPartsI').html(result.result2);

                            var count1 = (result.count1);
                            var count2 = (result.count2);

                            $('#ChartPP').val(count1);
                            $('#ChartIP').val(count2);

                            $('#PChartPP').val(result.count1);
                            $('#PChartIP').val(result.count2);

                            var count3 = (count1 + count2);
                            var count4 = ((count2/count3)*100)
                            

                            $('#PChartCompletion').val(count4.toFixed(2)+'%');

                            // Create the Chart for PendingvInstalled
                            var ctx = document.getElementById("PartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                labels: ["Pending Parts", "Installed Parts"],
                                datasets: [{
                                    data: [count1, count2],
                                    backgroundColor: [
                                    'rgba(255, 99, 132, 5)',
                                    'rgba(54, 162, 235, 5)',
                                    ],
                                    borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                                },
                                options: {
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                    }]
                                }
                                }
                            });
                            
                            var ctx = document.getElementById("MonPartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });

                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#savePI").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Get details of Parts
                jQuery(document).on( "click", "#PParts tr", function(){
                    var PIID = $(this).find('span').data('id');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('ovhl-workshop.getPInfo') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{PIID: PIID, _token: _token,},
                        success:function(result){
                            $('#installPI').show();
                            $('#PIID').val(result.PIID);
                            $('#PIMRINum').val(result.PIMRINum);
                            $('#PIPartNum').val(result.PIPartNum);
                            $('#PIDescription').val(result.PIDescription);
                            $('#PIQuantity').val(result.PIQuantity);
                            $('#PIPrice').val(result.PIPrice);
                            $('#PIDateReq').val(result.PIDateReq);
                            $('#PIDateRec').val(result.PIDateRec);
                            $('#PIReason').val(result.PIReason);

                            var priceValue = $('#PIPrice').val().replace(',', '');
                            var quantity = parseFloat(result.PIQuantity) || 0;
                            var price = parseFloat(priceValue) || 0;
                            var formattedTotalPrice = quantity * price;
                            var totalPrice = formattedTotalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                            $('#PITPrice').val(totalPrice);
                        }
                    });
                });

            // Clear Parts
                jQuery(document).on( "click", "#clearPI", function(){
                    $('#installPI').hide();
                    $('#PIID').val('');
                    $('#PIMRINum').val('');
                    $('#PIPartIDx').val('');
                    $('#PIPartNum').val('');
                    $('#PIDescription').val('');
                    $('#PIQuantity').val('');
                    $('#PIPrice').val('');
                    $('#PITPrice').val('');
                        var currentDate = new Date();
                        var month = currentDate.getMonth() + 1;
                        var day = currentDate.getDate();
                        var year = currentDate.getFullYear();

                        if (month < 10) {
                            month = "0" + month;
                        }
                        if (day < 10) {
                            day = "0" + day;
                        }

                        var formattedDate = month + "/" + day + "/" + year;
                    $('#PIDateReq').val(formattedDate);
                    $('#PIDateRec').val(formattedDate);
                    $('#PIReason').val(0);
                });

            // Delete Parts
                jQuery(document).on( "click", "#deletePI", function(){
                    $("#modalDeleteParts").removeClass("hidden");
                    $("#modalDeleteParts").addClass("flex");
                });

                jQuery(document).on( "click", "#deleteConfirmParts", function(){
                    $("#modalDeleteParts").removeClass("flex");
                    $("#modalDeleteParts").addClass("hidden");
                    var PIID = $('#PIID').val();
                    var JONum = $('#PIJONum').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.deletePI') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{PIID: PIID, JONum: JONum, _token: _token,},
                        success:function(result){
                            $('#installPI').hide();
                            $('#PIID').val('');
                            $('#PIMRINum').val('');
                            $('#PIPartIDx').val('');
                            $('#PIPartNum').val('');
                            $('#PIDescription').val('');
                            $('#PIQuantity').val('');
                            $('#PIPrice').val('');
                            $('#PITPrice').val('');
                                var currentDate = new Date();
                                var month = currentDate.getMonth() + 1;
                                var day = currentDate.getDate();
                                var year = currentDate.getFullYear();
                                
                                if (month < 10) {
                                    month = "0" + month;
                                }
                                if (day < 10) {
                                    day = "0" + day;
                                }

                                var formattedDate = month + "/" + day + "/" + year;
                            $('#PIDateReq').val(formattedDate);
                            $('#PIDateRec').val(formattedDate);
                            $('#PIReason').val(0);
                            $('#PParts').html(result.result1);
                            $('#PPartsI').html(result.result2);
                            
                            var count1 = (result.count1);
                            var count2 = (result.count2);
                            
                            $('#ChartPP').val(count1);
                            $('#ChartIP').val(count2);

                            $('#PChartPP').val(count1);
                            $('#PChartIP').val(count2);

                            var count3 = (count1 + count2);
                            var count4 = ((count2/count3)*100)
                            
                            $('#PChartCompletion').val(count4.toFixed(2)+'%');

                            // Create the Chart for PendingvInstalled
                            var ctx = document.getElementById("PartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            
                            var ctx = document.getElementById("MonPartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Install Parts
                jQuery(document).on( "click", "#saveInstall", function(){
                    var PIID = $('#PIID').val();
                    var JONum = $('#PIJONum').val();
                    var PartNum = $('#PIPartNum').val();
                    var Description = $('#PIDescription').val();
                    var Price = $('#PIPrice').val();
                    var PIDateInstalled = $('#PIDateInstalled').val();
                    var _token = $('input[name="_token"]').val();
                    
                    $.ajax({
                        url:"{{ route('other-workshop.installPI') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{PIID: PIID, JONum: JONum, PartNum: PartNum, Description: Description, Price: Price, PIDateInstalled: PIDateInstalled, _token: _token,},
                        success:function(result){
                            $('#installPI').hide();
                            $('#PIID').val('');
                            $('#PIMRINum').val('');
                            $('#PIPartIDx').val('');
                            $('#PIPartNum').val('');
                            $('#PIDescription').val('');
                            $('#PIQuantity').val('');
                            $('#PIPrice').val('');
                            $('#PITPrice').val('');
                                var currentDate = new Date();
                                var month = currentDate.getMonth() + 1;
                                var day = currentDate.getDate();
                                var year = currentDate.getFullYear();

                                if (month < 10) {
                                    month = "0" + month;
                                }
                                if (day < 10) {
                                    day = "0" + day;
                                }

                                var formattedDate = month + "/" + day + "/" + year;
                            $('#PIDateReq').val(formattedDate);
                            $('#PIDateRec').val(formattedDate);
                            $('#PIReason').val(0);
                            $('#PParts').html(result.result1);
                            $('#PPartsI').html(result.result2);

                            var count1 = (result.count1);
                            var count2 = (result.count2);

                            $('#ChartPP').val(count1);
                            $('#ChartIP').val(count2);

                            $('#PChartPP').val(count1);
                            $('#PChartIP').val(count2);

                            var count3 = (count1 + count2);
                            var count4 = ((count2/count3)*100)
                            

                            $('#PChartCompletion').val(count4.toFixed(2)+'%');

                            // Create the Chart for PendingvInstalled
                            var ctx = document.getElementById("PartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            
                            var ctx = document.getElementById("MonPartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                                }
                            });
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // For Delete and Revert of Installed Parts
                jQuery(document).on( "click", "#PPartsI tr", function(){
                    var id = $(this).attr('data-id');

                    $('#btnPartDRH').click();
                    
                    $('#btnRevertPI').data('id', id);
                    $('#btnDeletePI').data('id', id);
                });

                jQuery(document).on( "click", "#btnRevertPI", function(){
                    var id = $(this).data('id');
                    var JONum = $('#PIJONum').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.revertParts') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{id: id, JONum: JONum, _token: _token,},
                        success:function(result){
                            $('#PParts').html(result.result1);
                            $('#PPartsI').html(result.result2);

                            var count1 = (result.count1);
                            var count2 = (result.count2);

                            $('#ChartPP').val(count1);
                            $('#ChartIP').val(count2);

                            $('#PChartPP').val(count1);
                            $('#PChartIP').val(count2);

                            var count3 = (count1 + count2);
                            var count4 = ((count2/count3)*100)
                            

                            $('#PChartCompletion').val(count4.toFixed(2)+'%');

                            // Create the Chart for PendingvInstalled
                            var ctx = document.getElementById("PartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            
                            var ctx = document.getElementById("MonPartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

                jQuery(document).on( "click", "#btnDeletePI", function(){
                    var id = $(this).data('id');
                    var JONum = $('#PIJONum').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.deleteIParts') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{id: id, JONum: JONum, _token: _token,},
                        success:function(result){
                            $('#PParts').html(result.result1);
                            $('#PPartsI').html(result.result2);

                            var count1 = (result.count1);
                            var count2 = (result.count2);

                            $('#ChartPP').val(count1);
                            $('#ChartIP').val(count2);

                            $('#PChartPP').val(count1);
                            $('#PChartIP').val(count2);

                            var count3 = (count1 + count2);
                            var count4 = ((count2/count3)*100)
                            
                            if($('#PChartPP').val() == 0 && $('#PChartIP').val() == 0){
                                $('#PChartCompletion').val(0);
                            }else{
                                $('#PChartCompletion').val(count4.toFixed(2)+'%');
                            }

                            // Create the Chart for PendingvInstalled
                            var ctx = document.getElementById("PartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            
                            var ctx = document.getElementById("MonPartsChart").getContext("2d");
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ["Pending Parts", "Installed Parts"],
                                    datasets: [{
                                        data: [count1, count2],
                                        backgroundColor: [
                                        'rgba(255, 99, 132, 5)',
                                        'rgba(54, 162, 235, 5)',
                                        ],
                                        borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                beginAtZero: true
                                            }
                                        }]
                                    }
                                }
                            });
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });


            // View Technician Schedule
                jQuery(document).on( "click", "#viewSchedule", function(){
                    var bay = $('#UnitBayNum').val();
                    var TechSDate = $('#TechnicianStartDate').val();
                    var TechEDate = $('#TechnicianEndDate').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('other-workshop.viewSchedule') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {bay: bay, TechSDate: TechSDate, TechEDate: TechEDate, _token: _token,},
                        success: function(result) {
                            $('#BTableSchedule').html(result);
                        },
                    });
                });

            // Update Technician Schedule
                jQuery(document).on( "click", "#saveTActivity", function(){
                    $(this).prop("disabled", true);
                    $.ajax({
                        url:"{{ route('other-workshop.saveTActivity') }}",
                        method: "POST",
                        data: $('#formActivityInfo').serialize(),
                        success: function(result) {
                            $("#saveTActivity").prop("disabled", false);
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            $("#modalTechAct").addClass("hidden");

                            var bay = $('#TABayNum').val();
                            var JON = $('#UnitInfoJON').val();
                            var _token = $('input[name="_token"]').val();

                            var calendarEl = document.getElementById('calendar');

                            var calendar = new FullCalendar.Calendar(calendarEl, {
                                height: 450,
                                // initialView: 'dayGridWeek',
                                initialView: 'timeGridWeek',
                                headerToolbar: {
                                    left: '',
                                    center: 'title',
                                    right: 'today prev,next'
                                },
                                weekends: true,
                                hiddenDays: [0], // hide Sundays
                                allDaySlot: false,
                                displayEventTime: false,
                                events: function(info, successCallback) {
                                    $.ajax({
                                    url: '{{ route('bt-workshop.getEvents') }}',
                                    type: 'GET',
                                    data: { bay: bay, JON: JON, _token: _token,},
                                    success: function(response) {
                                        successCallback(response);
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText);
                                    }
                                    });
                                },
                                eventClick: function(info) {
                                    var today = new Date();
                                    var eventDate = new Date(info.event.start);
                                    
                                    if (eventDate.toDateString() <= today.toDateString()) {
                                        var eventId = info.event.id;
                                        var jonum = info.event.extendedProps.jonum;
                                        var baynum = info.event.extendedProps.baynum;
                                        var technician = info.event.extendedProps.technician;
                                        var scheddate = info.event.extendedProps.scheddate;
                                        var stime = info.event.extendedProps.stime;
                                        var etime = info.event.extendedProps.etime;
                                        var sow = info.event.extendedProps.sow;
                                        var activity = info.event.extendedProps.activity;
                                        var status = info.event.extendedProps.status;
                                        var remarks = info.event.extendedProps.remarks;

                                        $('#modalTechAct').removeClass('hidden');
                                        $('#TAID').val(eventId);
                                        $('#TAJONumber').val(jonum);
                                        $('#TABayNum').val(baynum);
                                        $('#TATechnician').val(technician);
                                        $('#TASchedDate').val(scheddate);
                                        $('#TASTime').val(stime);
                                        $('#TAETime').val(etime);
                                        $('#TASoW').val(sow);
                                        $('#TAActivity').val(activity);
                                        $('#TAStatus').val(status);
                                        $('#TARemarks').val(remarks);
                                    }
                                }
                            });

                            calendar.render();
                        },
                        error: function(error){
                            $("#saveTActivity").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                            $("#modalTechAct").addClass("hidden");
                        }
                    });

                });

            // Save Technician Activity
                jQuery(document).on( "click", "#saveActivity", function(){
                    $(this).prop("disabled", true);
                    var bay = $('#UnitBayNum').val();
                    var UnitTSID = $('#UnitTSID').val();
                    var UnitActivityStatus = $('#UnitActivityStatus').val();
                    var UnitInfoRemarks = $('#UnitInfoRemarks').val();
                    var _token = $('input[name="_token"]').val();
                    
                    var d = new Date();

                    var month = d.getMonth()+1;
                    var day = d.getDate();

                    var output = ((''+month).length<2 ? '0' : '') + month + '/' +
                                ((''+day).length<2 ? '0' : '') + day + '/' +
                                d.getFullYear();

                    $.ajax({
                        url: "{{ route('other-workshop.saveActivity') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {bay: bay, UnitTSID: UnitTSID, UnitActivityStatus: UnitActivityStatus, UnitInfoRemarks: UnitInfoRemarks, _token: _token, output: output,},
                        success: function(result) {
                            $("#saveActivity").prop("disabled", false);
                            $('#UnitTSID').val(result.UnitTSID);
                            $('#UnitInfoAOTD').val(result.Activity);
                            $('#UnitActivityStatus').val(result.UnitActivityStatus);
                            $('#UnitInfoRemarks').val(result.UnitInfoRemarks);
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");

                            var bay = $('#UnitBayNum').val();
                            var _token = $('input[name="_token"]').val();
                            
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
                                events: function(info, successCallback) {
                                    $.ajax({
                                    url: '{{ route('t-workshop.getEvents') }}',
                                    type: 'GET',
                                    data: { bay: bay, _token: _token,},
                                    success: function(response) {
                                        successCallback(response);
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText);
                                    }
                                    });
                                }
                            });

                            calendar.render();
                        },
                        error: function(error){
                            $("#saveActivity").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Save Remarks
                jQuery(document).on( "click", "#updateRemarks", function(){
                    $(this).prop("disabled", true);
                    var WSJONum = $('#UnitInfoJON').val();
                    var URemarks = $('#PIRemarks').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('other-workshop.saveRemarks') }}",
                        type: "POST",
                        data: {WSJONum: WSJONum, URemarks: URemarks, _token: _token,},
                        success: function(result) {
                            $("#updateRemarks").prop("disabled", false);
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#updateRemarks").prop("disabled", false);
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // If Area Change, Data from Bay also changes
                jQuery(document).on( "change", "#UnitArea", function(){
                    var area = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('other-workshop.report.getBay') }}",
                        method:"GET",
                        data:{area: area, _token: _token,},
                        success:function(result){
                            $('#UnitBay').html(result);
                        }
                    });
                });

            // Get Data of Transfer Unit
                jQuery(document).on( "click", "#transferUnit", function(){
                    var WSJONum = $('#UnitInfoJON').val();
                    var WSPOUID = $('#UnitInfoPOUID').val();
                    var UnitBayNum = $('#UnitBayNum').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('other-workshop.getTransferData') }}",
                        type: "GET",
                        dataType: "JSON",
                        data: {WSJONum: WSJONum, WSPOUID: WSPOUID, UnitBayNum: UnitBayNum, _token: _token,},
                        success: function(result) {
                            $('#POUIDx').val(WSPOUID);
                            $('#BayID').val(UnitBayNum);
                            $('#UnitTransferDate').val(result.TransferDate);
                            $('#UnitStatus').val(result.TransferStatus);
                            $('#UnitArea').val(result.TransferArea);
                            $('#UnitBay').html(result.TransferBay);
                            $('#UnitRemarksT').val(result.TransferRemarks);
                        },
                    });
                });

            // Save Transfer
                jQuery(document).on( "click", "#saveTransferUnit", function(){
                    $(this).prop("disabled", true);

                    $.ajax({
                        url: "{{ route('other-workshop.saveTransferUnit') }}",
                        type: "POST",
                        data: $('#formPOUT').serialize(),
                        // data: {WSPOUID: WSPOUID, UnitInfoJON: UnitInfoJON, UnitBayNum:UnitBayNum, UnitStatus: UnitStatus, UnitArea: UnitArea, UnitBay: UnitBay, UnitRemarks: UnitRemarks, _token: _token,},
                        success: function(result) {
                            $("#saveTransferUnit").prop("disabled", false);
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            $("#closeTransfer").click();
                            $("#closeBayMon").click();
                            location.reload();
                        },
                        error: function(error){
                            $("#saveTransferUnit").prop("disabled", false);
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

            // For Part Searching
                jQuery(document).on( "click", ".inputOption", function(e){
                    $('.content').not($(this).closest('.optionDiv').find('.listOption')).addClass('hidden');
                    $(this).closest('.optionDiv').find('.listOption').toggleClass('hidden');
                    var value = $(this).val().toLowerCase();
                    searchFilter(value);
                    e.stopPropagation();
                    
                    $('.listOption').addClass('hidden');
                });

                function searchFilter(searchInput){
                    $(".listOption li").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchInput) > -1)
                    });
                }

                jQuery(document).on( "keyup", ".inputOption", function(e){
                    var value = $(this).val();
                    var length = value.length;
                    var _token = $('input[name="_token"]').val();

                    if (value === "") {
                        $('.listOption').addClass('hidden');
                        if (ajaxRequest) {
                        ajaxRequest.abort();
                        }
                        return;
                    }

                    
                    if(length < 3){
                        $('.listOption').addClass('hidden');
                        if (ajaxRequest) {
                        ajaxRequest.abort();
                        }
                        return;
                    }

                    if (length = 3){
                        $.ajax({
                            url:"{{ route('other-workshop.search') }}",
                            method:"GET",
                            dataType: 'json',
                            data:{
                                value: value,
                                _token: _token
                            },
                            success:function(result){
                                $('#PartNo').html(result.partno);
                                
                                $('.listOption').removeClass('hidden');

                                
                                $('.content').not($(this).closest('.optionDiv').find('.listOption')).addClass('hidden');
                                $(this).closest('.optionDiv').find('.listOption').toggleClass('hidden');
                                var value = $(this).val().toLowerCase();
                                searchFilter(value);
                                e.stopPropagation();
                            }
                        });
                    }else if(length > 3){
                        searchFilter(length);
                    }else{
                        $('.listOption').addClass('hidden');
                    }

                });

                jQuery(document).on( "click", ".listOption li", function(){
                    var name = $(this).html();
                    var partid = $(this).data('id');
                    var _token = $('input[name="_token"]').val();
                    $('#PIPartIDx').val(partid);


                    $.ajax({
                        url:"{{ route('other-workshop.getPartsInfox') }}",
                        method:"POST",
                        dataType: 'json',
                        data:{
                            id: partid,
                            _token: _token
                        },
                        success:function(result){
                            $('#PIPartNum').val(result.partno);
                            $('#PIDescription').val(result.partname);
                            $('#PIPrice').val(result.price);

                            $(".listOption li").closest('.optionDiv').find('input').val(name);
                            $('.listOption').addClass('hidden');
                        }
                    })

                });
    
                
        });
    </script>
</x-app-layout>
