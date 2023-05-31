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
    </style>

    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Customer Area Management
                        </div>
                        <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('cust_area.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD AREA</a>
                        </div>
                    </div>

                    {{-- Start Table --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table id="tdept" class="w-full h-full text-sm text-left text-gray-500 row-border stripe">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Area
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($custarea as $custareas)
                                    <tr class="bg-white border-b ">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{$custareas->id}}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{$custareas->custarea}}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($custareas->status ==0)
                                                <p class="text-red-500">Inactive</p>
                                            @else
                                            <p class="text-green-500">Active</p>
                                            @endif 
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ url('/system-management/cust_area/edit/'.$custareas->id ) }}" class="font-medium text-blue-600  hover:underline ">Edit</a>
                                            
                                        </td>
                                    </tr>
                                    @endforeach                                
                                </tbody>
                            </table>
                        </div>
                    {{-- End Table --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
