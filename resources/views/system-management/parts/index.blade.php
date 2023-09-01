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
        .center-search {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 15em;
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

                        @if($search != '')
                        {{-- <div class="flex items-center">
                            <div class="mr-3">
                                <a id="btn-add" href="{{route('parts.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-14 py-3 text-center">ADD PARTS</a>
                            </div>
    
                            <div id="searchContainer" class="relative mt-1">
                                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="search" id="PTableSearch" value="{{$search}}" class="block w-96 p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Parts, Part Number..." required>
                                    <button id="clearButton" type="button" class=" absolute right-20 bottom-3 mr-2">
                                        <i class="uil uil-times text-2xl"></i>
                                    </button>
                                    <button type="submit" id="BTableSearch" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                                </div>
                            </div>
                        </div> --}}<!-- Separate row for the button -->
                        <div class="flex items-center justify-self-end">
                            <div class="">
                                <a id="btn-add" href="{{route('parts.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-14 py-3 text-center">ADD PARTS</a>
                            </div>
                        </div>

                        <!-- Separate row for the input -->
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
                                    <input type="search" id="PTableSearch" value="{{$search}}" class="block w-96 p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Parts, Part Number..." required>
                                    <button id="clearButton" type="button" class=" absolute right-20 bottom-3 mr-2">
                                        <i class="uil uil-times text-2xl"></i>
                                    </button>
                                    <button type="submit" id="BTableSearch" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                                </div>
                            </div>
                        </div>
                        @else
                            <div class="mr-3 justify-self-end">
                                <a id="btn-add" href="{{route('parts.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-14 py-3 text-center">ADD PARTS</a>
                            </div>
                        @endif
                    </div>

                    @if($search != '')
                    {{-- Start Table --}}
                        <div class="bg-white mt-2 shadow-md rounded-lg overflow-y-auto" style="height: calc(100vh - 210px);">
                            <table id="tableBay" class="w-full text-sm text-left text-gray-500 row-border stripe">
                                <thead class="text-sm text-gray-700 uppercase bg-white" style="position: sticky; top: 0;">
                                    <tr>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            PART NUMBER
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            PART NAME
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            BRAND
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            PRICE
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            STATUS
                                        </th>
                                        <th scope="col" class="px-6 py-1 text-center">
                                            ACTION
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="PartsTable" name="PartsTable" class="PartsTable">
                                    @php
                                        $x=1;
                                    @endphp
                                    @foreach ($parts as $part)
                                    <tr class="bg-white border-b ">
                                        <td class="px-1 py-1 text-center text-sm">
                                            {{ $x++ }}
                                        </td>
                                        <td class="px-1 py-1 text-center text-sm">
                                            {{ $part->partno }}
                                        </td>
                                        <td class="px-1 py-1 text-center text-sm">
                                            {{ $part->partname }}
                                        </td>
                                        <td class="px-1 py-1 text-center text-sm">
                                            {{ $part->brand }}
                                        </td>
                                        <td class="px-1 py-1 text-center text-sm">
                                            {{ $part->price }}
                                        </td>
                                        <td class="px-1 py-1 text-center text-sm">
                                            {{ $part->status }}
                                        </td>
                                        <td class="px-1 py-1 text-center text-sm">
                                            <a href="{{ url('/system-management/parts/edit/'.$part->id) }}" class="font-medium text-blue-600 hover:underline">Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    {{-- End Table --}}
                    @else
                        <div class="mt-2 rounded-lg overflow-y-auto" style="height: calc(100vh - 210px);">
                            <div id="searchContainer" class="relative mt-1 w-full">
                                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                                <div class="relative w-5/6">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="search" id="PTableSearch" value="{{$search}}" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Parts, Part Number..." required>
                                    <button id="clearButton" type="button" class=" absolute right-20 bottom-3 mr-2">
                                        <i class="uil uil-times text-2xl"></i>
                                    </button>
                                    <button type="submit" id="BTableSearch" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            const searchQuery = "{{ $search }}";
            const inputElement = document.getElementById("PTableSearch");
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

            updateSearchBarPosition();

            $("#BTableSearch").on("click", function() {
                performSearch();
            });
            
            function performSearch() {
                const value = $("#PTableSearch").val().trim();
                if (value !== "") {
                    window.location.href = `/system-management/parts/${value}`;
                } else {
                    // If the search input is empty, reload the page to show all results
                    window.location.href = `/system-management/parts`;
                }
            }

            $("#PTableSearch").on("input", function() {
                updateSearchBarPosition();
            });
            
            inputElement.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    performSearch(); // Call the function to perform the search
                }
            });
            


        });
    </script>
</x-app-layout>
