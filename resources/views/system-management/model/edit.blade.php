@section('title','Workshop Monitoring System')
<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Edit Model
                        </div>
                    </div>

                    {{-- Start Edit --}}
                        <div class="bg-white p-4 mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                            <form action="{{ url('/system-management/model/update/'.$model->id ) }}" method="POST" class="px-60 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                    <div class="mb-6 col-span-1">
                                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Model Name</label>
                                        <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ $model->name }}"required>
                                    </div>
                                    <div class="mb-6">
                                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                        <select id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="status" required >
                                            <option {{ ($model->status == 0) ? 'selected' : ''; }} value="0">Inactive</option>
                                            <option {{ ($model->status == 1) ? 'selected' : ''; }} value="1">Active</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-semibold focus:ring-blue-300 rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
                                <a href="{{route('model.index')}}" type="button" class="text-white bg-neutral-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center">Cancel</a>
                            </form>
                        </div>
                    {{-- End Edit --}}

                </div>
            </div>
        </div>
    </div>

</x-app-layout>