@section('title','Workshop Monitoring System')
<x-app-layout>

    <style>
        select[name="tdept_length"] {
            padding-right
            : 40px !important;
        }
    </style>

    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full grid grid-cols-7 gap-x-3">
                    <div class="col-span-3">
                        <div class="px-4 grid grid-cols-2 gap-x-3 ">
                            <div class="self-center font-black text-2xl text-red-500 leading-tight">
                                Activity Logs
                            </div>
                        </div>
    
                        {{-- Start Table --}}
                        {{-- <div class="">
                            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Mockups, Logos..." required>
                                <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                            </div>
                        </div> --}}
                        {{-- 196 --}}
                        <div class="bg-white mt-4 mb-2 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 196px);">
                            <table id="tableLogs" class="w-full text-xs text-left text-gray-500 row-border stripe">
                                <thead class="text-xs text-center text-gray-700 uppercase bg-white" style="position: sticky; top: 0;">
                                    <tr class="btBay bg-white">
                                        <th scope="col" class="px-6 py-3">
                                            Table
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            User
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-2 py-1 text-center">
                                                {{$log->table}}
                                            </td>
                                            <td class="px-2 py-1 text-center">
                                                {{$log->description}}
                                            </td>
                                            <td class="px-2 py-1 text-center">
                                                {{$log->userDetails->name}}
                                            </td>
                                            <td class="px-2 py-1 text-center">
                                                <button type="button" data-id="{{$log->table_key}}" data-table="{{$log->table}}" data-desc="{{$log->description}}" class="btnActView" id="btnActView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                            </td>
                                        </tr>
                                    @endforeach                             
                                </tbody>
                            </table>
                        </div>
                        {{$logs->links()}}
                        {{-- End Table --}}
                    </div>
                    <div class="col-span-4">
                        <div class="px-4 grid grid-cols-2 gap-x-3 ">
                            <div class="self-center font-black text-2xl text-red-500 leading-tight">
                                History
                            </div>
                        </div>
    
                        <div class="bg-white mt-4 p-4 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 150px);">
                            <ol id="viewActivity" class="relative border-l border-gray-200">
                                
                            </ol>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var previousClickedButton = null;

            $(".btnActView").click(function() {
                var tableKey = $(this).data("id");
                var tableX = $(this).data("table");
                var desc = $(this).data("desc");
                var _token = $('input[name="_token"]').val();

                if (previousClickedButton) {
                    // Remove the class from the previously clicked button
                    previousClickedButton.closest('tr').removeClass('bg-lime-400');
                }

                $(this).closest('tr').removeClass('bg-white');
                $(this).closest('tr').addClass('bg-lime-400');
                previousClickedButton = $(this);

                $("#viewActivity").empty();

                $.ajax({
                    type: "GET",
                    
                    url: "{{route('activity-logs.getLogs')}}",
                    data: {tableKey: tableKey, tableX: tableX, desc: desc, _token: _token,},
                    dataType: "json",
                    success: function (result) {
                        $("#viewActivity").append(result);
                    }
                });
            });
        });
    </script>
</x-app-layout>
