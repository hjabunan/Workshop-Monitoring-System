@section('title','Workshop Monitoring System')
<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Edit Technician
                        </div>
                    </div>

                    {{-- Start Edit --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{ url('/system-management/technician/update/'.$tech->id ) }}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-3 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="fname" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                                        @php
                                            $nameParts = explode(' ', trim($tech->name));
                                            $firstName = $nameParts[0];
                                            if (count($nameParts) >= 4) {
                                                $firstName .= ' ' . $nameParts[1];
                                            }
                                        @endphp
                                        <input type="text" id="fname" name="fname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $firstName }}" required>
                                    </div>
                                    {{-- <div class="mb-6 col-span-1">
                                        <label for="fname" class="block mb-2 text-sm font-medium text-gray-900">First Name</label>
                                        <input type="text" id="fname" name="fname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{explode(' ', trim($tech->name))[0];}}" required>
                                    </div> --}}
                                    <div class="mb-6 col-span-1">
                                        <label for="mname" class="block mb-2 text-sm font-medium text-gray-900">Middle Name</label>
                                        @php
                                            $middleName = (count($nameParts) >= 4) ? $nameParts[2] : $nameParts[1];
                                        @endphp
                                        <input type="text" id="mname" name="mname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $middleName }}">
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="lname" class="block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                                        @php
                                            $lastName = end($nameParts);
                                        @endphp
                                        <input type="text" id="lname" name="lname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $lastName }}" required>
                                    </div>
                                </div>
                                <div class="grid grid-flow-row-dense grid-cols-3 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="section" class="block mb-2 text-sm font-medium text-gray-900">Section</label>
                                        <select id="section" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="section" readonly>
                                            @foreach ($section as $sections)
                                                <option {{ ($tech->section == $sections->name) ? 'selected' : ''; }} value="{{$sections->name}}">{{$sections->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                        <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="status" required >
                                            <option {{ ($tech->status == 0) ? 'selected' : ''; }} value="0">Inactive</option>
                                            <option {{ ($tech->status == 1) ? 'selected' : ''; }} value="1">Active</option>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-semibold focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                                <a href="{{route('technician.index')}}" type="button" class="text-white bg-gray-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center">Cancel</a>
                            </form>
                        </div>
                    {{-- End Edit --}}

                </div>
            </div>
        </div>
    </div>

</x-app-layout>