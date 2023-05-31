<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Edit Bay/Area
                        </div>
                        {{-- <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('department.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD</a>
                        </div> --}}
                    </div>

                    {{-- Start Edit --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{ url('/system-management/area/update/'.$area->id ) }}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="aname" class="block mb-2 text-sm font-medium text-gray-900">Area Name</label>
                                        <input type="text" id="aname" name="aname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $area->area_name }}"required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="section" class="block mb-2 text-sm font-medium text-gray-900">Section</label>
                                        <select id="section" name="section" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="role" required >
                                            <option selected disabled>Choose a Section</option>
                                        @foreach ($sec as $secs)
                                            <option value="{{$secs->id}}" {{ ($secs->id == $area->section) ? 'selected' : ''; }}>{{$secs->name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-6">
                                        <label for="categ" class="block mb-2 text-sm font-medium text-gray-900">Legend</label>
                                        <select id="categ" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="categ" required >
                                        @foreach ($cat as $cats)
                                            {{-- <option value="{{$cats->id}}">{{$cats->cat_name}}</option> --}}
                                            <option value="{{$cats->id}}" {{ ($cats->id == $area->id) ? 'selected' : ''; }} >{{$cats->cat_name}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-6">
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                        <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="status" required >
                                            <option {{ ($area->status == 0) ? 'selected' : ''; }} value="0">Inactive</option>
                                            <option {{ ($area->status == 1) ? 'selected' : ''; }} value="1">Active</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-semibold focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                                <a href="{{route('area.index')}}" type="button" class="text-white bg-gray-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center">Cancel</a>
                            </form>
                        </div>
                    {{-- End Edit --}}

                </div>
            </div>
        </div>
    </div>

</x-app-layout>