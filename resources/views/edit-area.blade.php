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

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myDashboard" data-tabs-toggle="#myDashboardContent" role="tablist">
                    @if ($tab === "W5A")
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-2 border-b-2 rounded-t-lg" id="w5a-tab" data-tabs-target="#w5a" type="button" role="tab" aria-controls="w5a" aria-selected="false">Warehouse 5A</button>
                    </li>
                    @elseif ($tab === "W6")
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-2 border-b-2 rounded-t-lg" id="w6-tab" data-tabs-target="#w6" type="button" role="tab" aria-controls="w6" aria-selected="false">Warehouse 6</button>
                    </li>
                    @endif
                </ul>
                
                <div class="flex gap-x-5">
                    <button data-modal-toggle="saveModal" data-modal-target="saveModal" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10"><span class="mr-1 text-xl"><i class="uil uil-save align-middle"></i></span>SAVE</button>
                    <button id="cancelEdit" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2 hover:text-gray-900 focus:z-10">CANCEL</button>
                </div>
            </div>
            <div id="myDashboardContent">
                @if ($tab === "W5A")
                <div class="hidden p-2 rounded-lg bg-gray-50 dark:bg-gray-800" id="w5a" role="tabpanel" aria-labelledby="w5a-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws - layout.jpg') }}" class="absolute h-full m-auto left-1/2 -translate-x-1/2" alt="">
                    </div>
                    <div class="">
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
                            @endif
                        @endforeach
                    </div>
                </div>
                @elseif ($tab === "W6")
                <div class="hidden p-2 rounded-lg bg-gray-50 dark:bg-gray-800" id="w6" role="tabpanel" aria-labelledby="w6-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws - layout1.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
                    </div>
                    <div class="">
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
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            </div> --}}
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
            var tab = "{{ $tab }}";
            
            $("#cancelEdit").click(function () { 
                window.location.href = `{{ url('/editor-area') }}/{{$tab}}`;
            });


            
            const el = document.querySelector(".item");

            let isResizing = false;

            el.addEventListener("mousedown", mousedown);

            function mousedown(e) {
                window.addEventListener("mousemove", mousemove);
                window.addEventListener("mouseup", mouseup);

                let prevX = e.clientX;
                let prevY = e.clientY;

                function mousemove(e) {
                    if (!isResizing) {
                    let newX = prevX - e.clientX;
                    let newY = prevY - e.clientY;

                    const rect = el.getBoundingClientRect();

                    el.style.left = rect.left - newX + "px";
                    el.style.top = rect.top - newY + "px";

                    prevX = e.clientX;
                    prevY = e.clientY;
                    }
                }

                function mouseup() {
                    window.removeEventListener("mousemove", mousemove);
                    window.removeEventListener("mouseup", mouseup);

                    var itemTop = $(".item").offset().top - 160;
                    var itemLeft = $(".item").offset().left;
                    var itemHeight = $(".item").height();
                    var itemWidth = $(".item").width();

                    var vh = $(window).height() - 205;
                    var widthRatio = itemWidth / vh;
                    var heightRatio = itemHeight / vh;
                    var vw = $(window).width();
                    var areaTop = (itemTop / vh) * 100;

                    var areaLeft = ((vw / 2) - itemLeft) / vh;
                    var leftRatio = ((vw / 2) - itemLeft) / vh;

                    var areaHeight = (itemHeight / vh) * 100;
                    var areaWidth = (itemWidth / vw) * 100;

                    $('#areaTop').val(areaTop);
                    $('#areaLeft').val(areaLeft);
                    $('#areaHeight').val(itemHeight);
                    $('#areaWidth').val(itemWidth);
                    $('#areaWidthRatio').val(widthRatio);
                    $('#areaHeightRatio').val(heightRatio);
                    $('#areaLeftRatio').val(leftRatio);
                }
            }

            const resizers = document.querySelectorAll(".resizer");
            let currentResizer;

            for (let resizer of resizers) {
                resizer.addEventListener("mousedown", mousedown);

                function mousedown(e) {
                    currentResizer = e.target;
                    isResizing = true;

                    let prevX = e.clientX;
                    let prevY = e.clientY;

                    window.addEventListener("mousemove", mousemove);
                    window.addEventListener("mouseup", mouseup);

                    function mousemove(e) {
                        const rect = el.getBoundingClientRect();

                        if (currentResizer.classList.contains("se")) {
                            el.style.width = rect.width - (prevX - e.clientX) + "px";
                            el.style.height = rect.height - (prevY - e.clientY) + "px";
                        } else if (currentResizer.classList.contains("sw")) {
                            el.style.width = rect.width + (prevX - e.clientX) + "px";
                            el.style.height = rect.height - (prevY - e.clientY) + "px";
                            el.style.left = rect.left - (prevX - e.clientX) + "px";
                        } else if (currentResizer.classList.contains("ne")) {
                            el.style.width = rect.width - (prevX - e.clientX) + "px";
                            el.style.height = rect.height + (prevY - e.clientY) + "px";
                            el.style.top = rect.top - (prevY - e.clientY) + "px";
                        } else {
                            el.style.width = rect.width + (prevX - e.clientX) + "px";
                            el.style.height = rect.height + (prevY - e.clientY) + "px";
                            el.style.top = rect.top - (prevY - e.clientY) + "px";
                            el.style.left = rect.left - (prevX - e.clientX) + "px";
                        }

                        prevX = e.clientX;
                        prevY = e.clientY;
                    }

                    function mouseup() {
                        window.removeEventListener("mousemove", mousemove);
                        window.removeEventListener("mouseup", mouseup);
                        isResizing = false;

                        var itemTop = $(".item").offset().top - 160;
                        var itemLeft = $(".item").offset().left;
                        var itemHeight = $(".item").height();
                        var itemWidth = $(".item").width();

                        var vh = $(window).height() - 205;
                        var widthRatio = itemWidth / vh;
                        var heightRatio = itemHeight / vh;
                        var vw = $(window).width();
                        var areaTop = (itemTop / vh) * 100;
                        var areaLeft = (vw / 2) - itemLeft;
                        var areaHeight = (itemHeight / vh) * 100;
                        var areaWidth = (itemWidth / vw) * 100;

                        $('#areaTop').val(areaTop);
                        $('#areaLeft').val(areaLeft);
                        $('#areaHeight').val(itemHeight);
                        $('#areaWidth').val(itemWidth);
                        $('#areaWidthRatio').val(widthRatio);
                        $('#areaHeightRatio').val(heightRatio);
                    }
                }
            }
        });
    </script>

</x-app-layout>