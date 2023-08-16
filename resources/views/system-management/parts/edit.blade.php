@section('title','Workshop Monitoring System')
<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Edit Parts
                        </div>
                    </div>

                    {{-- Start Edit --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{ url('/system-management/parts/update/'.$parts->id ) }}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="partnum" class="block mb-2 text-sm font-medium text-gray-900">Part Number</label>
                                        <input type="text" id="partnum" name="partnum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $parts->partno }}" required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="partitemno" class="block mb-2 text-sm font-medium text-gray-900">Item Number</label>
                                        <input type="text" id="partitemno" name="partitemno" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $parts->itemno }}">
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="partname" class="block mb-2 text-sm font-medium text-gray-900">Part Name</label>
                                        <input type="text" id="partname" name="partname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $parts->partname }}" required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="partbrand" class="block mb-2 text-sm font-medium text-gray-900">Brand</label>
                                        <input type="text" id="partbrand" name="partbrand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $parts->brand }}" required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="partprice" class="block mb-2 text-sm font-medium text-gray-900">Price</label>
                                        <input type="text" id="partprice" name="partprice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $parts->price }}" required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                        <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="status" required >
                                            <option {{ ($parts->status == 'INACTIVE') ? 'selected' : ''; }} value="INACTIVE">Inactive</option>
                                            <option {{ ($parts->status == 'ACTIVE') ? 'selected' : ''; }} value="ACTIVE">Active</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-semibold focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                                <a href="{{route('parts.index')}}" type="button" class="text-white bg-gray-500 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center">Cancel</a>
                            </form>
                        </div>
                    {{-- End Edit --}}

                </div>
            </div>
        </div>
    </div>

</x-app-layout>