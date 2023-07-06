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
                    <div >
                        <button data-modal-target="addModal" data-modal-toggle="addModal" class="text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-6 focus:outline-none h-10">ADD</button>
                        <button id="cancelEdit" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                    </div>
                    <div class="justify-self-end">
                        <button class="hidden text-white font-semibold bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 focus:outline-none h-10"><span class="mr-1 text-xl"><i class="uil uil-save align-middle"></i></span>SAVE</button>
                    </div>
                </div>
                <div class="">
                    @foreach ($areas as $area)
                        <button data-modal-target="editModal" data-modal-toggle="editModal" data-id="{{ $area->id }}" data-name="{{ $area->name }}" style="z-index:40; width: calc(({{ $area->width_ratio }} * (100vh - 205px))); height: calc(({{ $area->height_ratio }} * (100vh - 205px))); position: absolute; top: calc(((100vh - 205px) * ({{ $area->top }} / 100)) + 160px); left: calc((100vw / 2) - ((100vh - 205px) * {{ $area->left_ratio }})); background-color: rgba(173, 216, 230, 0.5); border: 2px solid #000000;" class="thisArea">{{ $area->name}}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Add Modal -->
            <div id="addModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full h-full max-w-2xl md:h-auto">
                    <!-- Modal content -->
                    <form action="{{ route('area.add') }}" method="POST" class="relative bg-white rounded-lg shadow">
                        @csrf
                        <!-- Modal body -->
                        <div class="p-6">
                            <div class="grid grid-cols-5">
                                <div class="col-span-1">
                                    <label for="area" class="block text-sm font-medium text-gray-900">Area</label>
                                </div>
                                <div class="col-span-4">
                                    <select name="area" id="area" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-sm">
                                        @foreach ($sections as $section)
                                            <option value="{{$section->name}}">{{$section->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-5">
                                <div class="col-span-1">
                                    <label for="area" class="block text-sm font-medium text-gray-900">Area Color</label>
                                </div>
                                <div class="col-span-4">
                                    <select name="area" id="area" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-sm">
                                        @foreach ($sections as $section)
                                            <option value="{{$section->name}}">{{$section->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                <input type="text" id="color-picker" name="color" value="#000000">
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
                                <a href="" data-modal-hide="editModal" id="deleteBtn" data-area-id="{{ $area->id }}" data-areaname="{{ $area->name}}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Delete Location</a>
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

        
        {{-- D.R. Monitoring Hidden Button for Delete --}}
        <button type="button" id="btnAreaDeleteH" class="btnAreaDeleteH hidden" data-modal-target="modalDeleteArea" data-modal-toggle="modalDeleteArea"></button>

    </div>
    <script>
        $(document).ready(function(){
            $(".thisArea").click(function(){
                var areaID = $(this).data('id');
                var areaName = $(this).data('name');
                $('#areaName').html(areaName);
                $('#editBtn').prop('href', `{{ url('/area/edit/${areaID}') }}`);
            });

            $("#deleteBtn").click(function (e) {
                e.preventDefault();

                var id = $(this).data('area-id');
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

            $('#color-picker').spectrum({
                preferredFormat: 'hex',
                showInput: true,
                showInitial: true,
                showPalette: true,
                palette: [
                    ['#000000', '#ffffff', '#ff0000', '#00ff00', '#0000ff']
                ],
            });
        });
    </script>
</x-app-layout>
