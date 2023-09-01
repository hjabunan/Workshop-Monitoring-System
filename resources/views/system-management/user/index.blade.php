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
    </style>

    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            User Management
                        </div>
                        <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('user.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD USER</a>
                        </div>
                        <div class="col-span-2 self-center justify-self-end mt-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="search" id="default-search" class="block w-[] p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Mockups, Logos..." required>
                            </div>
                        </div>
                    </div>

                    {{-- Start Table --}}
                        <div class="bg-white mt-4 relative overflow-x-auto shadow-md sm:rounded-lg" style="height: calc(100vh - 200px);">
                            <table id="tuser" class="w-full h-full text-sm text-left text-gray-500 row-border stripe">
                                <thead class="text-xs text-gray-700 uppercase bg-white" style="position: sticky; top: 0;z-index: 1;">
                                    <tr class="btUser bg-white">
                                        <th scope="col" class="px-6 py-3">
                                            NAME
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            ID Number
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Department
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Role
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
                                    @foreach ($users as $users)
                                        <tr class="bg-white border-b ">
                                            <th scope="row" class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{$users->name}}
                                            </th>
                                            <td class="px-3 py-2">
                                                {{$users->idnum}}
                                            </td>
                                            <td class="px-3 py-2">
                                                {{$users->email}}
                                            </td>
                                            <td class="px-3 py-2">
                                                {{$users->deptname}}
                                            </td>
                                            <td class="px-3 py-2">
                                                @if ($users->role ==0)
                                                    User
                                                @else
                                                    Admin
                                                @endif 
                                            </td>
                                            <td class="px-3 py-2">
                                                @if ($users->status ==0)
                                                    <p class="text-red-500">Inactive</p>
                                                @else
                                                <p class="text-green-500">Active</p>
                                                @endif 
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="hidden" id="idnum" class="text-sm rounded-lg block w-full p-2.5" value="{{$users->id}}">
                                                <a href="{{ url('/system-management/user/edit/'.$users->id ) }}" class="font-medium text-blue-600  hover:underline ">Edit</a>
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

    <script>
        $(document).ready( function () {
             $('#tdept').DataTable({
                "ordering":false
                });
             });
        } );
    </script>
</x-app-layout>
