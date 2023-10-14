@section('title','Workshop Monitoring System')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myDashboard" data-tabs-toggle="#myDashboardContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button data-area="W5A" class="btnArea inline-block p-2 border-b-2 rounded-t-lg" id="w5a-tab" data-tabs-target="#w5a" type="button" role="tab" aria-controls="w5a" aria-selected="false">Warehouse 5A</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button data-area="W6" class="btnArea inline-block p-2 border-b-2 rounded-t-lg" id="w6-tab" data-tabs-target="#w6" type="button" role="tab" aria-controls="w6" aria-selected="false">Warehouse 6</button>
                    </li>
                </ul>
                
                <div>
                    @if ((Auth::user()->role == '0') || (Auth::user()->role == '1'))
                        <button class="btnEditor text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10">EDITOR</button>
                    @endif
                </div>
            </div>
            <div id="myDashboardContent">
                <div class="hidden p-2 rounded-lg bg-gray-50 dark:bg-gray-800" id="w5a" role="tabpanel" aria-labelledby="w5a-tab">
                    <div style="height: calc(100vh - 180px);" class="relative">
                        <img src="{{ asset('images/ws - layout.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
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
                                    $alpha = 0.8;
        
                                    $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                                ?>
        
                                {{-- <button
                                    data-id="{{ $area->id }}"
                                    data-name="{{ $area->name }}"
                                    style="z-index:40;
                                            width: calc(({{ $area->width_ratio }} * (100vh - 205px)));
                                            height: calc(({{ $area->height_ratio }} * (100vh - 205px)));
                                            position: absolute;
                                            top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px);
                                            left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }}));
                                            background-color: {{ $rgbaColor }};


                                            border: 2px solid #000000;"
                                    class="thisArea disabled:opacity-1/2 font-bold tracking-widest"
                                    {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'disabled' : '' }}>
                                    {{ $area->name }}
                                </button> --}}
                                <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea disabled:opacity-3 font-bold tracking-widest" {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'disabled' : '' }}>{{$area->name}}</button>
                                {{-- <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index: 40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000; {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'opacity: 0.5;' : '' }}" class="thisArea {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'disabled' : '' }}"></button> --}}
                            @endif
                        @endforeach
                    </div>
                </div>
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
                                    $alpha = 0.8;
        
                                    $rgbaColor = "rgba($red, $green, $blue, $alpha)";
                                ?>
        
                                <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea disabled:opacity-20" {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'disabled' : '' }}></button>
                                {{-- <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index: 40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000; {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'opacity: 0.5;' : '' }}" class="thisArea {{ (Auth::user()->role === '2') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'disabled' : '' }}"></button> --}}
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var area_loc = 'W5A';

            $(".btnArea").click(function(){
                area_loc = $(this).data('area');
            });

            $(".thisArea").click(function(){
                var areaID = $(this).data('id');
                var areaName = $(this).data('name');
                var _token =  '{{ csrf_token() }}'

                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard.getSName') }}",
                    data: {areaName: areaName ,_token: _token},
                    success: function (result) {
                        window.location.href = result;
                    }
                });
            });
            
            $(".btnEditor").click(function(){
                // window.location.href = {{ url('/editor-area') }};
                window.location.href = `{{ url('/editor-area') }}/${area_loc}`;
            });
        });
    </script>
</x-app-layout>
