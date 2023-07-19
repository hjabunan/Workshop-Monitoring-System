<x-app-layout>
    <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="px-4 grid grid-cols-2 gap-x-3 ">
                        <div class="self-center font-black text-2xl text-red-500 leading-tight">
                            Add User
                        </div>
                        {{-- <div class="self-center justify-self-end">
                            <a id="btn-add" href="{{route('department.create')}}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-16 py-2.5 text-center">ADD</a>
                        </div> --}}
                    </div>

                    {{-- Start Add{{route('user.store')}} --}}
                        <form action="{{route('user.store')}}" method="POST" class="px-40 ">
                            @csrf
                            <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                <div class="mb-6 col-span-1">
                                    <label for="uname" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                    <input type="text" id="uname" name="uname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-6 col-span-1">
                                    <label for="idnum" class="block mb-2 text-sm font-medium text-gray-900">ID Number</label>
                                    <input type="text" id="idnum" name="idnum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-6 col-span-1">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                    <input type="text" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="username@toyotaforklifts-philippines.com" required>
                                </div>
                                <div class="mb-6">
                                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                    <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="role" required >
                                        <option selected disabled>Choose a Role</option>
                                        <option value="0">User</option>
                                        <option value="1">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label for="dept" class="block mb-2 text-sm font-medium text-gray-900">Department</label>
                                    <select id="dept" name="dept" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" name="role" required >
                                        <option selected disabled>Choose a Department</option>
                                    @foreach ($depts as $dept)
                                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="mb-6 col-span-1">
                                    <label for="area" class="block mb-2 text-sm font-medium text-gray-900">Area/Bay</label>
                                    <div class="grid grid-cols-3 gap-4">
                                        @foreach ($sections as $id => $name)
                                            <label for="area_{{ $id }}" class="flex items-center space-x-2">
                                                <input type="checkbox" id="area_{{ $id }}" name="area[]" value="{{ $id }}" class="form-checkbox h-4 w-4 text-blue-500">
                                                <span class="text-gray-900 text-sm">{{ $name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    {{-- <input type="text" id="area" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"> --}}
                                </div>
                                <div class="mb-6">
                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                                <div class="mb-6">
                                    <label for="cpassword" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                    <input type="password" id="cpassword" name="cpassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                </div>
                            </div>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">ADD</button>
                            <a href="{{route('user.index')}}" type="button" class="text-white bg-neutral-500 hover:bg-neutral-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">CANCEL</a>
                        </form>
                    {{-- End Add --}}

                </div>
            </div>
        </div>
    </div>
        <script>
            $(document).ready(function() {
                var checkboxes = $('input[name="area[]"]');
                checkboxes.prop('disabled', true).prop('checked', false);
                
                $('#role').on('change', function() {
                    var role = $(this).val();
                    var checkboxes = $('input[name="area[]"]');
                    
                    if (role === "" || role === "1") {
                        checkboxes.prop('disabled', true).prop('checked', false);
                    } else {
                        checkboxes.prop('disabled', false);
                    }
                });
            });
        </script>
</x-app-layout>
