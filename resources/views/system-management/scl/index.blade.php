@section('title','Workshop Monitoring System')
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

        th, td {
            padding: 0.25rem;
        }
    </style>

    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Staging Controller Legend Management
                        </div>
                        <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('scl.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD CONTROLLER</a>
                        </div>
                    </div>

                    {{-- Start Table --}}
                        <div class="bg-white mt-4 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 155px);">
                            <table id="tableBay" class="w-full text-sm text-left text-gray-500 row-border stripe">
                                <thead class="text-xs text-gray-700 uppercase bg-white" style="position: sticky; top: 0;">
                                    <tr bg-white>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            ID
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            Name
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            Day In
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            Day Out
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            Color
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $x=1;
                                    @endphp
                                    @foreach ($stg as $scl)
                                    <tr class="bg-white border-b ">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{$x++}}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{$scl->stg_name}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$scl->stg_dayin}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$scl->stg_dayout}}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($scl->stg_color == "bg-green-500")
                                                GREEN
                                            @elseif ($scl->stg_color == "bg-blue-500")
                                                BLUE
                                            @elseif ($scl->stg_color == "bg-yellow-500")
                                                YELLOW
                                            @else
                                                RED
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ url('/system-management/scl/edit/'.$scl->id ) }}" class="font-medium text-blue-600  hover:underline ">Edit</a>
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
