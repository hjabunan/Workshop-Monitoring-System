@section('title','Workshop Monitoring System')
<x-app-layout>
        <div style="height: calc(100vh - 60px);" class="py-1 overflow-x-auto">
            <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
                <div class="overflow-hidden sm:rounded-lg h-full">
                    <div class="p-3 text-gray-900 h-full">
                        <div class="px-4 grid grid-cols-2 gap-x-3 ">
                            <div class="self-center font-black text-2xl text-red-500 leading-tight">
                                Edit User
                            </div>
                        </div>
    
                        {{-- Start Add{{route('user.store')}} --}}
                            <form action="{{ url('/system-management/user/update/'.$users->id ) }}" method="POST" class="px-40 ">
                                @csrf
                                <div class="grid grid-flow-row-dense grid-cols-2 gap-x-5">
                                    <div class="mb-3 col-span-1">
                                        <label for="uname" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                                        <input type="text" id="uname" name="uname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{$users->name}}" required>
                                    </div>
                                    <div class="mb-3 col-span-1">
                                        <label for="idnum" class="block mb-2 text-sm font-medium text-gray-900">ID Number</label>
                                        <input type="text" id="idnum" name="idnum" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{$users->idnum}}" required>
                                    </div>
                                    <div class="mb-3 col-span-1">
                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                        <input type="text" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="username@toyotaforklifts-philippines.com"  value="{{$users->email}}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                        <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required >
                                            <option {{ ($users->role == 0) ? 'selected' : ''; }} value="0">User</option>
                                            <option {{ ($users->role == 1) ? 'selected' : ''; }} value="1">Admin</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dept" class="block mb-2 text-sm font-medium text-gray-900">Department</label>
                                        <select id="dept" name="dept" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                            @foreach ($depts as $dept)
                                                <option {{ ($users->dept == $dept->id) ? 'selected' : '' }} value="{{ $dept->id }}">{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-span-1">
                                        <label class="block mb-2 text-sm font-medium text-gray-900">Area/Bay</label>
                                        <div class="grid grid-cols-3 gap-4">
                                            @foreach ($sects as $section)
                                                <label for="area_{{ $section->id }}" class="flex items-center space-x-2">
                                                    <input type="checkbox" id="area_{{ $section->id }}" name="area[]" value="{{ $section->id }}" class="form-checkbox h-4 w-4 text-blue-500" {{ (in_array($section->id, $selectedAreas)) ? 'checked' : '' }}>
                                                    <span class="text-gray-900 text-xs">{{ $section->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mb-3 col-span-1">
                                        <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                        <select id="ustatus" name="ustatus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required >
                                            <option {{ ($users->status == 0) ? 'selected' : ''; }} value="0">Inactive</option>
                                            <option {{ ($users->status == 1) ? 'selected' : ''; }} value="1">Active</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-span-1"></div>
                                    <div class="mb-3 col-span-2">
                                        <div class="flex items-center">
                                            <input id="checked-checkbox" name="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="checked-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Check to change password</label>
                                        </div>
                                    </div>
                                    <div class="mb-3 password" id="passwordbox">
                                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                        <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  value="">
                                    </div>
                                    <div class="mb-3" id="cpasswordbox">
                                        <label for="cpassword" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                        <input type="password" id="cpassword" name="cpassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="">
                                    </div>
                                </div>
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update User</button>
                                <a href="{{route('user.index')}}" type="button" class="text-white bg-gray-500 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Cancel</a>
                            </form>
                        {{-- End Add --}}
    
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                    $("#passwordbox").hide();
                    $("#cpasswordbox").hide();
                    
                $("#checked-checkbox").change(function() {
                if ($(this).is(":checked")) {
                    $("#passwordbox").show();
                    $("#cpasswordbox").show();
                } else {
                    $("#passwordbox").hide();
                    $("#cpasswordbox").hide();
                }
                });

                var checkboxes = $('input[name="area[]"]');

                $('#role').on('change', function() {
                    var role = $(this).val();
                    var checkboxes = $('input[name="area[]"]');

                    if (role === "" || role === "1") {
                        checkboxes.prop('checked', true);
                    } else {
                        checkboxes.prop('checked', false);
                    }
                });
            });
          </script>
    </x-app-layout>
