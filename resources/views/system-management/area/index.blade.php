<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-black text-2xl text-blue-600 leading-tight">
            {{ __('Department Management') }}
        </h2>
    </x-slot> --}}

    <style>
        select[name="tdept_length"] {
            padding-right
            : 40px !important;
        }
    </style>

    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Bay/Area Management
                        </div>
                        <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('area.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD AREA</a>
                        </div>
                    </div>

                    {{-- Start Table --}}
                    <div class="bg-white mt-4 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 155px);">
                        <table id="tableBay" class="w-full text-sm text-left text-gray-500 row-border stripe">
                            <thead class="text-xs text-gray-700 uppercase bg-white" style="position: sticky; top: 0;">
                                <tr class="btBay bg-white">
                                    <th scope="col" class="px-6 py-3">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Bay/Area
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Section
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Legend
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
                                @foreach ($area as $areas)
                                <tr class="bg-white border-b">
                                    <td scope="row" class="px-6 py-1.5 whitespace-nowrap">
                                        {{$areas->id}}
                                    </td>
                                    <td class="px-6 py-1.5">
                                        {{$areas->area_name}}
                                    </td>
                                    <td class="px-6 py-1.5">
                                        {{$areas->name}}
                                    </td>
                                    <td class="px-6 py-1.5">
                                        {{$areas->cat_name}}
                                    </td>
                                    <td class="px-6 py-1.5">
                                        @if ($areas->status ==0)
                                            <p class="text-red-500">Inactive</p>
                                        @else
                                        <p class="text-green-500">Active</p>
                                        @endif 
                                    </td>
                                    <td class="px-6 py-1.5">
                                        <a href="{{ url('/system-management/area/edit/'.$areas->id ) }}" class="font-medium text-blue-600  hover:underline ">Edit</a>
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
