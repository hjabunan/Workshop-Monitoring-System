@section('title','Workshop Monitoring System')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-4 border-b border-gray-200 pb-2">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="DBoard" data-tabs-toggle="#DBoardContent" role="tablist">
                    @if ($tab === "W5A")
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-2 border-b-2 rounded-t-lg" id="whouse5a-tab" data-tabs-target="#whouse5a" type="button" role="tab" aria-controls="whouse5a" aria-selected="false">Warehouse 5A</button>
                    </li>
                    @elseif ($tab === "W6")
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-2 border-b-2 rounded-t-lg" id="whouse6-tab" data-tabs-target="#whouse6" type="button" role="tab" aria-controls="whouse6" aria-selected="false">Warehouse 6</button>
                        {{-- <button class="inline-block p-2 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="whouse6-tab" data-tabs-target="#whouse6" type="button" role="tab" aria-controls="whouse6" aria-selected="false">Warehouse 6</button> --}}
                    </li>
                    @endif
                </ul>
                
                <div class="flex gap-x-5">
                    <button data-modal-target="addModal" data-modal-toggle="addModal" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-6 focus:outline-none h-10">ADD</button>
                    <button id="cancelEdit" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2 hover:text-gray-900 focus:z-10">CANCEL</button>
                </div>
            </div>
            <div id="DBoardContent">
                @if ($tab === "W5A")
                <div class="hidden p-2 rounded-lg bg-gray-50" id="whouse5a" role="tabpanel" aria-labelledby="whouse5a-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws-layout.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
                    </div>
                    <div class="">
                        @if($areas->isEmpty())
                        @else
                            @foreach ($areas as $area)
                                @if ($area->area_location == 'W5A')
                                    <?php 
                                        $hexColor = $area->hexcolor;
                                        $rgbColor = sscanf($hexColor, "#%02x%02x%02x");
            
                                        $red = $rgbColor[0];
                                        $green = $rgbColor[1];
                                        $blue = $rgbColor[2];
                                        $alpha = 0.5;
            
                                        $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                                    ?>
                                        <button data-modal-target="editModal" data-modal-toggle="editModal" data-id="{{ $area->id }}" data-name="{{ $area->name }}" data-hexcolor="{{ $area->hexcolor }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea">{{ $area->name}}
                                        </button>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @elseif ($tab === "W6")
                <div class="hidden p-2 rounded-lg bg-gray-50" id="whouse6" role="tabpanel" aria-labelledby="whouse6-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws-layout1.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
                    </div>
                    <div class="">
                        @if($areas->isEmpty())
                        @else
                            @foreach ($areas as $area)
                                @if ($area->area_location == 'W6')
                                    <?php 
                                        $hexColor = $area->hexcolor;
                                        $rgbColor = sscanf($hexColor, "#%02x%02x%02x");
            
                                        $red = $rgbColor[0];
                                        $green = $rgbColor[1];
                                        $blue = $rgbColor[2];
                                        $alpha = 0.5;
            
                                        $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                                    ?>
                                        <button data-modal-target="editModal" data-modal-toggle="editModal" data-id="{{ $area->id }}" data-name="{{ $area->name }}" data-hexcolor="{{ $area->hexcolor }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea">{{ $area->name}}
                                        </button>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif
            </div>

        {{-- <div class="flex justify-between mb-4 border-b border-gray-200 pb-2">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="DashboardTab" data-tabs-toggle="#myDashboardContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="disabled:pointer-events-none inline-block p-2 border-b-2 rounded-t-lg" data-tabs-target="#w5a" type="button" role="tab" aria-controls="w5a" aria-selected="false">Warehouse 5A</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button  class="disabled:pointer-events-none inline-block p-2 border-b-2 rounded-t-lg" data-tabs-target="#w6" type="button" role="tab" aria-controls="w6" aria-selected="false">Warehouse 6</button>
                    </li>
                </ul>
                
                <div class="flex gap-x-5">
                    <button data-modal-target="addModal" data-modal-toggle="addModal" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-6 focus:outline-none h-10">ADD</button>
                    <button id="cancelEdit" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2 hover:text-gray-900 focus:z-10">CANCEL</button>
                </div>
            </div>
            <div id="myDashboardContent">
                <div class="hidden p-2 rounded-lg bg-gray-50" id="w5a" role="tabpanel" aria-labelledby="w5a-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws - layout.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
                    </div>
                    <div class="">
                        @if($areas->isEmpty())
                        @else
                            @foreach ($areas as $area)
                                @if ($area->area_location == 'W5A')
                                    <?php 
                                        $hexColor = $area->hexcolor;
                                        $rgbColor = sscanf($hexColor, "#%02x%02x%02x");
            
                                        $red = $rgbColor[0];
                                        $green = $rgbColor[1];
                                        $blue = $rgbColor[2];
                                        $alpha = 0.5;
            
                                        $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                                    ?>
                                        <button data-modal-target="editModal" data-modal-toggle="editModal" data-id="{{ $area->id }}" data-name="{{ $area->name }}" data-hexcolor="{{ $area->hexcolor }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea">{{ $area->name}}
                                        </button>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="hidden p-2 rounded-lg bg-gray-50" id="w6" role="tabpanel" aria-labelledby="w6-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws - layout1.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
                    </div>
                    <div class="">
                        @if($areas->isEmpty())
                        @else
                            @foreach ($areas as $area)
                                @if ($area->area_location == 'W6')
                                    <?php 
                                        $hexColor = $area->hexcolor;
                                        $rgbColor = sscanf($hexColor, "#%02x%02x%02x");
            
                                        $red = $rgbColor[0];
                                        $green = $rgbColor[1];
                                        $blue = $rgbColor[2];
                                        $alpha = 0.5;
            
                                        $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                                    ?>
                                        <button data-modal-target="editModal" data-modal-toggle="editModal" data-id="{{ $area->id }}" data-name="{{ $area->name }}" data-hexcolor="{{ $area->hexcolor }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea">{{ $area->name}}
                                        </button>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Add Modal -->
            <div id="addModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full h-full max-w-2xl md:h-auto">
                    <!-- Modal content -->
                    <form action="{{ route('area.add') }}" method="POST" class="relative bg-white rounded-lg shadow">
                        @csrf
                        <!-- Modal body -->
                        <input type="hidden" name="area_loc" value="{{ $tab }}">
                        <div class="p-4">
                            <div class="grid grid-cols-5">
                                <div class="col-span-1">
                                    <label for="area" class="block text-sm font-medium text-gray-900">Area</label>
                                </div>
                                <div class="col-span-4">
                                    <input type="hidden" id="selectedDataId" name="selectedDataId" value="">
                                    <select name="area" id="area" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-sm" onchange="updateDataId()">
                                        <option value="" selected disabled>SELECT SECTION</option>
                                        @foreach ($sections as $section)
                                            <option data-id="{{$section->id}}" value="{{$section->name}}">{{$section->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="display: flex; flex-wrap: wrap;" class="mt-2 mb-2 gap-2">
                                <?php
                                    $hexColors = ['#FF0000', '#FFA500', '#FFFF00', '#00FF00', '#00FFFF', '#0000FF', '#8A2BE2', '#FF00FF', '#FF1493', '#800080', '#808080', '#000000'];
                                ?>

                                @foreach ($hexColors as $hexColor)
                                    <button class="colorHex" style="background-color: {{ $hexColor }}; width: 100px; height: 50px; border: none;" value="{{ $hexColor}}"></button>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-5 mt-5">
                                <div class="col-span-1">
                                    <label for="area" class="block text-sm font-medium text-gray-900">Area Color</label>
                                </div>
                                <div class="col-span-4">
                                    <input type="color" id="colorpicker" name="colorpicker" class="w-full" value="#000000">
                                </div>
                            </div>
                            <div class="">
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                            <button data-modal-hide="addModal" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                            <button data-modal-hide="addModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                        </div>
                    </form>
                </div>
            </div>
        <!-- Add Modal End -->

        <!-- Edit Location Modal -->
            <div id="editModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-10 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                <div class="relative w-full h-full max-w-2xl md:h-auto">
                    <!-- Modal content -->
                    <form action="{{ route('area.add') }}" method="POST" class="relative bg-white rounded-lg shadow">
                        @csrf
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-6 border-b rounded-t h-8">
                            <h3 id="areaName" class="text-xl leading-8 font-semibold text-gray-900"></h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="editModal">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-2">
                            <div style="display: flex; flex-wrap: wrap;" class="mt-2 mb-2 gap-2">
                                <?php
                                    $hexColors = ['#FF0000', '#FFA500', '#FFFF00', '#00FF00', '#00FFFF', '#0000FF', '#8A2BE2', '#FF00FF', '#FF1493', '#800080', '#808080', '#000000'];
                                ?>

                                @foreach ($hexColors as $hexColor)
                                    <button class="colorHexE" style="background-color: {{ $hexColor }}; width: 100px; height: 50px; border: none;" value="{{ $hexColor}}"></button>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-5 mt-5">
                                <div class="col-span-1">
                                    <label for="area" class="block text-sm font-medium text-gray-900">Area Color</label>
                                </div>
                                <div class="col-span-4">
                                    <input type="color" id="colorpickerE" name="colorpickerE" class="w-full">
                                    <input type="hidden" id="areaID" name="areaID" class="">
                                </div>
                            </div>
                            <div class="mt-5">
                                @if($areas->isEmpty())
                                    <a href=""></a>
                                @else
                                    <a href="" data-modal-hide="editModal" id="updateBtn" data-area-id="{{ $area->id }}" data-areaname="{{ $area->name}}"  class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update Color</a>
                                    <a href="" data-modal-hide="editModal" id="editBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Edit Location</a>
                                    <a href="" data-modal-hide="editModal" id="deleteBtn" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Delete Location</a>
                                @endif
                                <button data-modal-hide="editModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <!-- Edit Location Modal End -->

        {{-- CONFIRMATION DELETE MODAL FOR Dashboard Area --}}
            <div id="modalDeleteArea" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
                <div class="relative w-full h-full max-w-md md:h-auto">
                    <div class="relative bg-white rounded-lg shadow">
                        <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalDeleteArea">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-6 text-center">
                            <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</h3>
                            <button type="button" id="deleteConfirmArea"  data-modal-hide="modalDeleteArea" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Yes, I'm sure.
                            </button>
                            <button data-modal-hide="modalDeleteArea" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel.</button>
                        </div>
                    </div>
                </div>
            </div>

        
        {{-- Hidden Button for Delete --}}
        <button type="button" id="btnAreaDeleteH" class="btnAreaDeleteH hidden" data-modal-target="modalDeleteArea" data-modal-toggle="modalDeleteArea"></button>

    </div>
    <script>
        $(document).ready(function(event){
            var tab = "{{ $tab }}";
            $("#DBoard").addClass("pointer-events-none");

            // if (tab === "W5A") {
            //     $("#DBoard li:nth-child(1) button").click(function(event){
            //         event.preventDefault();
            //         $("#whouse5a").removeClass("hidden");
            //         $("#whouse6").addClass("hidden");
            //     });
            //     // $("#DBoard").addClass("pointer-events-none");
            // } else if (tab === "W6") {
            //     $("#DBoard li:nth-child(2) button").click(function(event){
            //         event.preventDefault();
            //         $("#whouse5a").addClass("hidden");
            //         $("#whouse6").removeClass("hidden");
            //         $("#whouse6").addClass("active");
            //     });
            //     // $("#DBoard").addClass("pointer-events-none");
            // }

            $(".thisArea").click(function(){
                var areaID = $(this).data('id');
                var areaName = $(this).data('name');
                var hexcolor = $(this).data('hexcolor');
                $('#areaName').html(areaName);
                $('#colorpickerE').val(hexcolor);
                $('#areaID').val(areaID);
                $('#editBtn').prop('href', `{{ url('/area/edit/${tab}/${areaID}') }}`);

                $('#deleteBtn').data('id', areaID);
                $('#deleteBtn').data('areaname', areaName);
            });

            $("#deleteBtn").click(function (e) {
                e.preventDefault();

                var id = $(this).data('id');
                var name = $(this).data('areaname');

                $('#deleteConfirmArea').data('id', id);
                $('#deleteConfirmArea').data('name', name);
                $("#btnAreaDeleteH").click();
            });

            $("#deleteConfirmArea").click(function () {
                var areaID = $(this).data('id');
                var areaName = $(this).data('name');
                var _token = '{{ csrf_token() }}';

                // Send AJAX request to the controller
                $.ajax({
                    type: "POST",
                    url: "{{ route('area.delete') }}",
                    data: { areaID: areaID, areaName: areaName, _token: _token },
                    success: function (data) {
                        window.location.reload();
                    },
                });
            });

            $("#cancelEdit").click(function () { 
                window.location.href = "{{ url('/dashboard') }}";
            });

            $("#updateBtn").click(function (e) {
                e.preventDefault();

                var id = $('#areaID').val();
                var colorA = $('#colorpickerE').val();
                var _token = '{{ csrf_token() }}';

                $.ajax({
                    type: "POST",
                    url: "{{ route('area.updateC') }}",
                    data: { id: id, colorA: colorA, _token: _token },
                    success: function (data) {
                        window.location.reload();
                    },
                });
            });

            $(".colorHex").click(function (e) { 
                e.preventDefault();
                var colorHex = $(this).val();

                $('#colorpicker').val(colorHex);
            });

            $(".colorHexE").click(function (e) { 
                e.preventDefault();
                var colorHexE = $(this).val();

                $('#colorpickerE').val(colorHexE);
            });
        });

        function updateDataId() {
            var selectElement = document.getElementById("area");
            var selectedDataId = selectElement.options[selectElement.selectedIndex].getAttribute("data-id");
            document.getElementById("selectedDataId").value = selectedDataId;
        }
    </script>
</x-app-layout>
