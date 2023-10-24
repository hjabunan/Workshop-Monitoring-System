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
                <div class="p-3 text-gray-900 h-full grid grid-cols-5 gap-x-3">
                    <div class="col-span-2">
                        <div class="px-4 grid grid-cols-2 gap-x-3 ">
                            <div class="self-center font-black text-2xl text-red-500 leading-tight">
                                Activity Logs
                            </div>
                        </div>
    
                        {{-- Start Table --}}
                        <div class="bg-white mt-4 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 150px);">
                            <table id="tableLogs" class="w-full text-sm text-left text-gray-500 row-border stripe">
                                <thead class="text-sm text-center text-gray-700 uppercase bg-white" style="position: sticky; top: 0;">
                                    <tr class="btBay bg-white">
                                        <th scope="col" class="px-6 py-3">
                                            ID
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
                                    @php
                                        $x=1;   
                                    @endphp
                                    @foreach ($logs as $log)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-2 py-1 text-center">
                                                {{ $x++ }}
                                            </td>
                                            <td class="px-2 py-1 text-center">
                                                {{$log->name}}
                                            </td>
                                            <td class="px-2 py-1 text-center">
                                                <button type="button" data-id="{{$log->table_key}}" class="btnActView" id="btnActView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                            </td>
                                        </tr>
                                    @endforeach                             
                                </tbody>
                            </table>
                        </div>
                        {{-- End Table --}}
                    </div>
                    <div class="col-span-3">
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
            $(".btnActView").click(function() {
                var tableKey = $(this).data("id");
                var _token = $('input[name="_token"]').val();
                $("#viewActivity").empty();

                $.ajax({
                    type: "GET",
                    url: "{{route('activity-logs.getLogs')}}",
                    data: {tableKey: tableKey, _token: _token,},
                    dataType: "json",
                    success: function (result) {
                        $("#viewActivity").append(result);
                    }
                });
            });
        });
    </script>
</x-app-layout>
