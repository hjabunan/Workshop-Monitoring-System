@section('title','Workshop Monitoring System')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div style="height: calc(100vh - 205px);" class="relative">
                    <img src="{{ asset('images/ws - layout.jpg') }}" class="absolute h-full left-1/2 -translate-x-1/2 z-30" alt="">
                </div>
                <div class="grid grid-cols-2 border-t pt-4">
                    <div>
                        @if (Auth::user()->role === '1')
                            <button id="editorTab" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10">EDITOR</button>
                        @else
                            <button id="editorTab" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10" hidden>EDITOR</button>
                        @endif
                    </div>
                    <div class="justify-self-end">
                        <button class="hidden text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10"><span class="mr-1 text-xl"><i class="uil uil-save align-middle"></i></span>SAVE</button>
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

                        <button data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: {{ $rgbaColor }}; border: 2px solid #000000;" class="thisArea disabled:opacity-10" {{ (Auth::user()->role === '0') ? (in_array($area->areaid, explode(',', Auth::user()->area))) ? '' : 'disabled' : '' }}></button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Add Modal -->
            <div id="addModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
                <div class="relative w-full h-full max-w-2xl md:h-auto">
                    <!-- Modal content -->
                    <form action="{{ route('area.add') }}" method="POST" class="relative bg-white rounded-lg shadow">
                        @csrf
                        <!-- Modal body -->
                        <div class="p-6">
                            <div class="">
                                <label for="area" class="block text-sm font-medium text-gray-900">Area</label>
                                <input type="text" id="area" name="area" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
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
                        <div class="p-6">
                            <div class="">
                                <a href="" data-modal-hide="editModal" id="editBtn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Edit Location</a>
                                <a href="" data-modal-hide="editModal" id="deleteBtn" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Delete Location</a>
                                <button data-modal-hide="editModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <!-- Edit Location Modal End -->
    </div>
    <script>
        $(document).ready(function(){
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
            
            $("#editorTab").click(function(){
                window.location.href = "{{ url('/editor-area') }}";
            });
        });
    </script>
</x-app-layout>
