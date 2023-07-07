@section('title','Workshop Monitoring System')
<x-app-layout>

    <style>
        .bg-gray-900{
            opacity: 50% !important;
        }
        .resizer{
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 5px;
            background-color: black;
            z-index: 49;
        }
        .resizer.nw{
            top: -1px;
            left: -1px;
            cursor: nw-resize;
        }
        .resizer.ne{
            top: -1px;
            right: -1px;
            cursor: ne-resize;
        }
        .resizer.sw{
            bottom: -1px;
            left: -1px;
            cursor: sw-resize;
        }
        .resizer.se{
            bottom: -1px;
            right: -1px;
            cursor: se-resize;
        }
    </style>

    {{-- Edit Location Modal --}}
        <div id="saveModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
            <div class="relative w-full h-full max-w-2xl md:h-auto">
                <!-- Modal content -->
                <form action="{{ url('/area/update/'.$id) }}" method="POST" class="relative bg-white rounded-lg shadow">
                    @csrf
                    <!-- Modal body -->
                    <div class="p-6">
                        <div class="">
                            <input type="hidden" id="areaID" name="areaID" value="{{ $id }}">
                            <input type="hidden" id="areaTop" name="areaTop" value="{{ $thisArea->top }}">
                            <input type="hidden" id="areaLeft" name="areaLeft" value="{{ $thisArea->left }}">
                            <input type="hidden" id="areaHeight" name="areaHeight" value="{{ $thisArea->height }}">
                            <input type="hidden" id="areaWidth" name="areaWidth" value="{{ $thisArea->width }}">
                            <input type="hidden" id="areaWidthRatio" name="areaWidthRatio" value="{{ $thisArea->width_ratio }}">
                            <input type="hidden" id="areaHeightRatio" name="areaHeightRatio" value="{{ $thisArea->height_ratio }}">
                            <input type="hidden" id="areaLeftRatio" name="areaLeftRatio" value="{{ $thisArea->left_ratio }}">
                            <p>Are you sure you want to save?</p>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                        <button data-modal-hide="saveModal" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Yes</button>
                        <button data-modal-hide="saveModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No</button>
                    </div>
                </form>
            </div>
        </div>
    {{-- Edit Location Modal End --}}

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div style="height: calc(100vh - 205px);" class="relative">
                        <img src="{{ asset('images/ws - layout.jpg') }}" class="absolute h-full m-auto left-1/2 -translate-x-1/2" alt="">
                    </div>
                    <div class="grid grid-cols-2 border-t pt-4">
                        <div>
                        </div>
                        <div class="justify-self-end">
                            <button data-modal-toggle="saveModal" data-modal-target="saveModal" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10"><span class="mr-1 text-xl"><i class="uil uil-save align-middle"></i></span>SAVE</button>
                            <button id="cancelEdit" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                        </div>
                    </div>
                    <div class="">
                        @foreach ($areas as $area)
                        <?php 
                            $hexColor = $area->hexcolor;
                            $rgbColor = sscanf($hexColor, "#%02x%02x%02x");
    
                            $red = $rgbColor[0];
                            $green = $rgbColor[1];
                            $blue = $rgbColor[2];
                            $alpha = 0.5;
    
                            $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                        ?>
                        @if ($area->id == $id)
                            <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000; cursor: move;" class="item">{{ $area->name }}
                                <div class="resizer ne"></div>
                                <div class="resizer nw"></div>
                                <div class="resizer sw"></div>
                                <div class="resizer se"></div>
                            </button>
                        @else
                            <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea">{{ $area->name }}
                            </button>
                        @endif
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
            $("#cancelEdit").click(function () { 
                window.location.href = "{{ url('/editor-area') }}";
            });
        });
    </script>

</x-app-layout>