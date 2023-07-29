<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Add Technician
                        </div>
                    </div>

                    {{-- Start Add --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{route('technician.store')}}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-3 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="fname" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                                        <input type="text" id="fname" name="fname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="mname" class="block mb-2 text-sm font-medium text-gray-900">Middle Name</label>
                                        <input type="text" id="mname" name="mname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="lname" class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                                        <input type="text" id="lname" name="lname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                </div>
                                <div class="grid grid-flow-row-dense grid-cols-3 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="section" class="block mb-2 text-sm font-medium text-gray-900">Section</label>
                                        <select id="section" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" name="section" readonly>
                                            <option value="" selected disabled></option>
                                            @foreach ($section as $sections)
                                                <option value="{{$sections->name}}">{{$sections->name}}</option>
                                            @endforeach
                                            {{-- <option value="MCI SECTION">MCI SECTION</option>
                                            <option value="PDI SECTION">PDI SECTION</option>
                                            <option value="PPT SECTION">PPT SECTION</option>
                                            <option value="RAYMOND SECTION">RAYMOND SECTION</option>
                                            <option value="TOYOTA/OVERHAULING SECTION">TOYOTA/OVERHAULING SECTION</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">ADD</button>
                                <a href="{{route('technician.index')}}" type="button" class="text-white bg-gray-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">CANCEL</a>
                            </form>
                        </div>
                    {{-- End Add --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
