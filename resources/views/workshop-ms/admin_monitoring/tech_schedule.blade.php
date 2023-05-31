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
                            </ul>
                        </div>
                        <div id="divSchedule">
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
                                        <div class="col-span-3 text-xs gap-1 text-center">
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
                                                    <tbody id="techTable" class="overflow-y-auto">
                                                        @foreach ($techsched as $techscheds)
                                                        <tr class="techTable bg-white border-b hover:bg-gray-300">
                                                            <td scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
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
                                        </div>
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
                                    </div>
                            </div>
                    {{-- Summary Report --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="activityx" role="tabpanel" aria-labelledby="activityx-tab">
                                <div class="grid grid-cols-8 gap-1">
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
                                    <div class="">
                                        <select id="techname0" name="techname0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" required>
                                                <option value="" selected disabled></option>
                                            @foreach ($tech as $techs)
                                                <option value="{{$techs->id}}">{{$techs->initials}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="">
                                        <button type="button" id="searchSchedule" name="searchSchedule" class="text-white bg-blue-600 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full">SEARCH</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){

            jQuery(document).on( "click", "#saveSchedule", function(){
                
                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.saveSchedule') }}",
                    method:"POST",
                    dataType: 'json',
                    data: $('#formSched').serialize(),
                    success:function(result){
                        $('#techTable').html(result)
                        alert("Save Success!");
                        document.getElementById('formSched').reset();
                        $('#TSPOUID').val('');
                        $('#TSID').val('');
                    },
                    error: function(error){
                        alert("Save Failed!");
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
                var _token = $('input[name="_token"]').val();
                
                $.ajax({
                    url:"{{ route('admin_monitoring.tech_schedule.deleteSchedule') }}",
                    method:"POST",
                    dataType: 'json',
                    data:{ TSID: TSID, _token: _token, },
                    success:function(result){
                        $('#techTable').html(result)
                        alert("Delete Success!");
                        document.getElementById('formSched').reset();
                    },
                    error: function(error){
                        alert("Delete Failed!");
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


        } );
    </script>
</x-app-layout>
