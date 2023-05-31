<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Add Company
                        </div>
                    </div>

                    {{-- Start Add --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{route('company.store')}}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Company Name</label>
                                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Company Address</label>
                                        <input type="text" id="address" name="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div class="mb-6 col-span-1">
                                        <label for="area" class="block mb-2 text-sm font-medium text-gray-900">Company Area</label>
                                        <select id="area" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="area" required >
                                            <option value="" selected disabled></option>
                                            @foreach ($custareaz as $careas)
                                                <option value="{{$careas->id}}">{{$careas->custarea}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">ADD</button>
                                <a href="{{route('company.index')}}" type="button" class="text-white bg-gray-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">CANCEL</a>
                            </form>
                        </div>
                    {{-- End Add --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
