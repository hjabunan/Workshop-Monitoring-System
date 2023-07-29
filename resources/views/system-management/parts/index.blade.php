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
                            Parts Management
                        </div>
                        <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('parts.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD PARTS</a>
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
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-3 py-3">
                                            PART NUMBER
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            PART NAME
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            BRAND
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            PRICE
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            STATUS
                                        </th>
                                        <th style="box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);" scope="col" class="px-6 py-3">
                                            ACTION
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($part as $parts)
                                    <tr class="bg-white border-b ">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{$parts->id}}
                                        </th>
                                        <td class="px-3 py-4">
                                            {{$parts->partno}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$parts->partname}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$parts->brand}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$parts->price}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{$parts->status}}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ url('/system-management/parts/edit/'.$parts->id ) }}" class="font-medium text-blue-600  hover:underline ">Edit</a>
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
