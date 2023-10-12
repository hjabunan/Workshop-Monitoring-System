@section('title','Workshop Monitoring System')
<x-app-layout>
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
                        {{-- <div class=""></div> --}}
                        <div class="col-span-2 self-center justify-self-end mt-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                                <input type="search" id="searchUser" class="block !w-[100px] p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search User..." required>
                            </div>
                        </div>
                    </div>

                    {{-- Start Table --}}
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-2 overflow-y-auto" style="height: calc(100vh - 230px);">
                            <table id="tableParts" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50" style="position: sticky; top: 0;">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            NAME
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            ID Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            Department
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            Role
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tableBUser">
                                    @foreach ($users as $users)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-2 py-1">
                                                {{$users->name}}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                {{$users->idnum}}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                {{$users->email}}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                {{$users->deptname}}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($users->role ==0)
                                                    User
                                                @else
                                                    Admin
                                                @endif 
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($users->status ==0)
                                                    <p class="text-red-500">Inactive</p>
                                                @else
                                                <p class="text-green-500">Active</p>
                                                @endif 
                                            </td>
                                            <td class="px-3 py-2 text-center">
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
            $("#searchUser").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#tableBUser tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</x-app-layout>
