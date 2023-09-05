<style>
    .disabled-link {
    color: gray; /* Change the color to indicate it's disabled */
    pointer-events: none; /* Disable pointer events to prevent clicking */
    text-decoration: none; /* Optionally remove underline */
}
</style>
<nav class="px-1 bg-white border-gray-200 shadow z-50 relative w-full">
    <div class="container flex flex-wrap items-center justify-between mx-auto w-auto">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo1 class="block h-9 w-auto fill-current text-gray-100" />
            </a>
        </div>
        <div class="hidden md:block md:w-auto space-x-6 sm:-my-px sm:ml-10 sm:flex" id="navbar-dropdown">
            <ul class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 md:bg-white">
                <li>
                    <a href="{{route('dashboard')}}" class="block py-2 pl-3 pr-4 bg-blue-700 rounded md:bg-transparent text-gray-700 hover:text-blue-700 md:p-0" aria-current="page">Dashboard</a>
                </li>
{{-- Start Workshop Monitoring System --}}
                <li>
                    <button id="dropdownDividerButtona" data-dropdown-toggle="dropdownDividera" class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto" type="button">Workshop Monitoring System <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                    <!-- Dropdown menu -->
                    <div id="dropdownDividera" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-52">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDividerButtona">
                            @if (Auth::check() && in_array(2, explode(',', Auth::user()->area)))
                                <li>
                                    <button id="doubleDropdownButtona" data-dropdown-toggle="doubleDropdowna" data-dropdown-trigger="hover" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">
                                        BT Workshop
                                        <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <div id="doubleDropdowna" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="doubleDropdownButtona">
                                            <li>
                                                <a href="{{route('bt-workshop.index')}}" class="block px-4 py-2 hover:bg-gray-100">Monitoring</a>
                                            </li>
                                            <li>
                                                <a href="{{route('bt-workshop.report')}}" class="block px-4 py-2 hover:bg-gray-100">Report</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if (Auth::check() && in_array(1, explode(',', Auth::user()->area)))
                                <li>
                                    <button id="doubleDropdownButtonb" data-dropdown-toggle="doubleDropdownb" data-dropdown-trigger="hover" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100" >Toyota Workshop<svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></button>
                                    <div id="doubleDropdownb" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="doubleDropdownButtonb">
                                        <li>
                                            <a href="{{route('t-workshop.index')}}" class="block px-4 py-2 hover:bg-gray-100">Monitoring</a>
                                        </li>
                                        <li>
                                            <a href="{{route('t-workshop.report')}}" class="block px-4 py-2 hover:bg-gray-100">Report</a>
                                        </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            @if (Auth::check() && in_array(3, explode(',', Auth::user()->area)))
                                <li>
                                    <button id="doubleDropdownButtonc" data-dropdown-toggle="doubleDropdownc" data-dropdown-trigger="hover" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100" >Raymond Workshop<svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></button>
                                    <div id="doubleDropdownc" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="doubleDropdownButtonc">
                                        <li>
                                            <a href="{{route('r-workshop.index')}}" class="block px-4 py-2 hover:bg-gray-100">Monitoring</a>
                                        </li>
                                        <li>
                                            <a href="{{route('r-workshop.report')}}" class="block px-4 py-2 hover:bg-gray-100">Report</a>
                                        </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            <li>
                                <a href="{{route('ppt-workshop.index')}}" class="block px-4 py-2 hover:bg-gray-100">PPT Workshop</a>
                            </li>
                            <li>
                                <a href="{{route('ovhl-workshop.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Overhauling Workshop</a>
                            </li>
                            <li>
                                <a href="{{route('other-workshop.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Other Workshop</a>
                            </li>
                            <li>
                                <a href="{{route('w-storage1.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Warehouse 1 -  Monitoring</a>
                            </li>
                            <li>
                                <a href="{{route('w-storage5b.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Warehouse 5B -  Monitoring</a>
                            </li>
                            <li>
                                <a href="{{route('w-storage5c.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Warehouse 5C -  Monitoring</a>
                            </li>
                            <li>
                                <a href="{{route('w-storage6.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Warehouse 6 -  Monitoring</a>
                            </li>
                            <li>
                                <a href="{{route('w-storage7.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Warehouse 7 -  Monitoring</a>
                            </li>
                            <li>
                                <a href="{{route('w-storage8.index')}}" class="block px-4 py-2 hover:bg-gray-100 ">Warehouse 8 -  Monitoring</a>
                            </li>
                            @if (Auth::check() && Auth::user()->role == 1)
                                <li>
                                    <button id="doubleDropdownButtone" data-dropdown-toggle="doubleDropdowne" data-dropdown-trigger="hover" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full px-4 py-2 hover:bg-gray-100">Admin Monitoring<svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></button>
                                    <div id="doubleDropdowne" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-45">
                                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="doubleDropdownButtone">
                                        <li>
                                            <a href="{{route('admin_monitoring.index')}}" class="block px-4 py-2 hover:bg-gray-100">Administrator Monitoring</a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin_monitoring.report')}}" class="block px-4 py-2 hover:bg-gray-100">Report</a>
                                        </li>
                                        {{-- <li>
                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">File Maintenance</a>
                                        </li> --}}
                                        <li>
                                            <a href="{{route('admin_monitoring.tech_schedule')}}" class="block px-4 py-2 hover:bg-gray-100">Technician Schedule</a>
                                        </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
{{-- End Workshop Monitoring System --}}

{{-- Start PDI Monitoring System --}}
<li>
    <button id="dropdownDividerButtonb" data-dropdown-toggle="dropdownDividerb" class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto" type="button">PDI Monitoring System <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
    <!-- Dropdown menu -->
    <div id="dropdownDividerb" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
        <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDividerButtonb">
            <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Delivery</a>
            </li>
            <li>
                <a href="{{route('mci-monitoring.index')}}" class="block px-4 py-2 hover:bg-gray-100">MCI Monitoring</a>
            </li>
            <li>
                <a href="{{route('pdi-monitoring.index')}}" class="block px-4 py-2 hover:bg-gray-100">PDI Monitoring</a>
            </li>
            <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Schedule Monitoring</a>
            </li>
        </ul>
    </div>
</li>
{{-- End PDI Monitoring System --}}

{{-- Start System Management --}}
                @if (Auth::check() && Auth::user()->role == 1)
                    <li>
                        <button id="dropdownDividerButtonc" data-dropdown-toggle="dropdownDividerc" class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 md:w-auto" type="button">System Management <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                        <!-- Dropdown menu -->
                        <div id="dropdownDividerc" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDividerButtonc">
                                <li>
                                    <a href="{{route('user.index')}}" class="block px-4 py-2 hover:bg-gray-100">Users</a>
                                </li>
                                <li>
                                    <a href="{{route('department.index')}}" class="block px-4 py-2 hover:bg-gray-100">Department</a>
                                </li>
                            </ul>
                            <div class="py-2">
                                {{-- <a href="{{route('activity.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled-link">Activity</a> --}}
                                <a href="{{route('area.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Bay/Area</a>
                                <a href="{{route('brand.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Brand</a>
                                <a href="{{route('category.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Category</a>
                                {{-- <a href="{{route('company.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled-link">Customer</a> --}}
                                {{-- <a href="{{route('cust_area.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled-link">Customer Area</a> --}}
                                <a href="{{route('scl.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Staging Control</a>
                                {{-- <a href="{{route('mast.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled-link">Mast</a> --}}
                                {{-- <a href="{{route('model.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled-link">Model</a> --}}
                                <a href="{{route('parts.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Parts</a>
                                <a href="{{route('reason.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Reasons - Downtime</a>
                                <a href="{{route('section.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Section</a>
                                <a href="{{route('technician.index')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Technician</a>
                            </div>
                        </div>
                    </li>
                @endif
{{-- End System Management --}}
            </ul>
        </div>
        <!-- Settings Dropdown -->
        <div class="hidden sm:flex sm:items-center sm:ml-6">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
            
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
            
                    <x-slot name="content">
                        {{-- <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link> --}}
            
                        <!-- Authentication -->
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
            
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <script>
                    var form = document.createElement('form');
                    form.action = "{{ route('logout') }}";
                    form.method = 'POST';
                
                    var csrfTokenInput = document.createElement('input');
                    csrfTokenInput.type = 'hidden';
                    csrfTokenInput.name = '_token';
                    csrfTokenInput.value = '{{ csrf_token() }}';
                
                    form.appendChild(csrfTokenInput);
                
                    document.body.appendChild(form);
                    form.submit();
                </script>
            @endauth
        </div>
        
    </div>
  </nav>
  