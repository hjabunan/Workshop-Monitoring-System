@section('title','Workshop Monitoring System')
<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Edit Controller
                        </div>
                    </div>

                    {{-- Start Edit --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{ url('/system-management/scl/update/'.$scl->id ) }}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="stg_name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                        <input type="text" id="stg_name" name="stg_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $scl->stg_name }}" uppercase required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="stg_color" class="block mb-2 text-sm font-medium text-gray-900">Color</label>
                                        <select id="stg_color" name="stg_color" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required >
                                            <option {{ ($scl->stg_color == 'bg-green-500') ? 'selected' : ''; }} value="bg-green-500">GREEN</option>
                                            <option {{ ($scl->stg_color == 'bg-blue-500') ? 'selected' : ''; }} value="bg-blue-500">BLUE</option>
                                            <option {{ ($scl->stg_color == 'bg-yellow-500') ? 'selected' : ''; }} value="bg-yellow-500">YELLOW</option>
                                            <option {{ ($scl->stg_color == 'bg-red-500') ? 'selected' : ''; }} value="bg-red-500">RED</option>
                                        </select>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="din" class="block mb-2 text-sm font-medium text-gray-900">Day In</label>
                                        <input type="text" id="din" name="din" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $scl->stg_dayin }}" uppercase required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="dout" class="block mb-2 text-sm font-medium text-gray-900">Day Out</label>
                                        <input type="text" id="dout" name="dout" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $scl->stg_dayout }}" uppercase required>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-semibold focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                                <a href="{{route('scl.index')}}" type="button" class="text-white bg-gray-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center">Cancel</a>
                            </form>
                        </div>
                    {{-- End Edit --}}

                </div>
            </div>
        </div>
    </div>

</x-app-layout>