@section('title','Workshop Monitoring System')
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
                        {{-- <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('area.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD AREA</a>
                        </div> --}}
                        <div class="flex items-center justify-self-end">
                            <div class="">
                                <a id="btn-add" href="{{route('area.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-14 py-3 text-center">ADD AREA</a>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-3 ">
                        <div class=""></div>
                        <div class="flex items-center mt-1 justify-self-end">
                            <div id="searchContainer" class="relative">
                                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="search" id="ATableSearch" value="{{$search}}" class="block w-96 p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Bay/Area, Section..." required>
                                    <button id="clearButton" type="button" class=" absolute right-20 bottom-3 mr-2">
                                        <i class="uil uil-times text-2xl"></i>
                                    </button>
                                    <button type="submit" id="BTableSearch" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Start Table --}}
                    <div class="bg-white mt-4 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 210px);">
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
                                @php
                                    $x=1;
                                @endphp
                                @foreach ($area as $areas)
                                <tr class="bg-white border-b">
                                    <td scope="row" class="px-6 py-1.5 whitespace-nowrap">
                                        {{$x++}}
                                    </td>
                                    <td data-id="{{$areas->id}}" class="px-6 py-1.5">
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
    <script>
        $(document).ready(function () {
            const inputElement = document.getElementById("ATableSearch");
            const clearButton = document.getElementById("clearButton");

            // Clear Button 
                if (inputElement.value === "") {
                    clearButton.style.display = "none";
                }

                inputElement.addEventListener("input", function() {
                    if (inputElement.value === "") {
                        clearButton.style.display = "none";
                    } else {
                        clearButton.style.display = "block";
                    }
                });

                clearButton.addEventListener("click", function() {
                    inputElement.value = ""; // Clear the input field
                    clearButton.style.display = "none"; // Hide the clear button
                });
                    
                function updateSearchBarPosition() {
                    if (searchQuery !== "") {
                        $("#searchContainer").removeClass("center-search");
                    } else {
                        $("#searchContainer").addClass("center-search");
                    }
                }
            
            // Searching
                function performSearch() {
                    const value = $("#ATableSearch").val().trim();
                    if (value !== "") {
                        window.location.href = `/system-management/area/${encodeURIComponent(value)}`;
                    } else {
                        // If the search input is empty, reload the page to show all results
                        window.location.href = `/system-management/area`;
                    }
                }

            // On Hit ENTER
                inputElement.addEventListener("keypress", function(event) {
                    if (event.key === "Enter") {
                        performSearch(); // Call the function to perform the search
                    }
                });
                $("#BTableSearch").on("click", function() {
                    performSearch();
                });
        });
    </script>
</x-app-layout>
