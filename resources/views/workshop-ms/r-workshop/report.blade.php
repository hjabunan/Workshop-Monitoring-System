<x-app-layout>
    <style>
        button:disabled,button[disabled]{
            cursor: not-allowed;
            pointer-events: none;
            background-color: white;
            color: white;
            outline: none;
            border: none;
        }

        #example_length{
            display: none;
        }

        /* Zebra Striped Table */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr.POU th {
            background: white;
            color: black;
        }

        th {
            background: white;
            position: sticky;
            top: 0;
        }

        .disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        .custom-br {
            line-height: 2; /* Adjusts the line height to 1.5 times the font size */
        }
    </style>
    <div style="height: calc(100vh - 75px);" class="py-1 overflow-y-hidden">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5 h-full">
            <div class="sm:rounded-lg h-full">
                <div class="p-3 text-gray-900 h-full">
                    <div class="grid grid-cols-1 gap-1">
                        <div class="mb-1 border-b border-gray-200">
                            <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center overflow-x-auto" id="myTab" data-tabs-toggle="#myTabContentReport" role="tablist">
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="workshop-tab" data-tabs-target="#workshop" type="button" role="tab" aria-controls="workshop" aria-selected="false">WORKSHOP</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="report-tab" data-tabs-target="#report" type="button" role="tab" aria-controls="report" aria-selected="false">REPORT</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="newunit-tab" data-tabs-target="#newunit" type="button" role="tab" aria-controls="newunit" aria-selected="false">BN SHIPMENT</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="pounit-tab" data-tabs-target="#pounit" type="button" role="tab" aria-controls="pounit" aria-selected="false">PULL OUT UNIT</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="counit-tab" data-tabs-target="#counit" type="button" role="tab" aria-controls="counit" aria-selected="false">CONFIRM UNIT</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="dunit-tab" data-tabs-target="#dunit" type="button" role="tab" aria-controls="dunit" aria-selected="false">DELIVERED UNIT</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="caunit-tab" data-tabs-target="#caunit" type="button" role="tab" aria-controls="caunit" aria-selected="false">CANNIBALIZED UNIT</button>
                                </li>
                                <li class="mr-2" role="presentation">
                                    <button class="inline-block p-2 border-b-2 rounded-t-lg" id="drunit-tab" data-tabs-target="#drunit" type="button" role="tab" aria-controls="drunit" aria-selected="false">D.R. MONITORING</button>
                                </li>
                            </ul>
                        </div>
                        <div id="myTabContentReport">
                            {{-- WORKSHOP --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="workshop" role="tabpanel" aria-labelledby="workshop-tab">
                                <div id="headDIV" class="grid mb-3">
                                    <div id="div1" class="grid grid-cols-4">
                                        <div class="col-span-2">
                                            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="BrandALL" type="radio" value="BrandALL" name="RadioBrand" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                                        <label for="BrandALL" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">ALL</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="BrandToyota" type="radio" value="BrandToyota" name="RadioBrand" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="BrandToyota" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">TOYOTA</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="BrandBT" type="radio" value="BrandBT" name="RadioBrand" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="BrandBT" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">BT</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="BrandRaymond" type="radio" value="BrandRaymond" name="RadioBrand" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="BrandRaymond" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">RAYMOND</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="grid justify-items-start">
                                        </div>
                                        <div class="grid justify-items-end">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text" id="WSTableSearch" name="WSTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="bodyDIV" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                    <table id="tableWS" class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="WS place-items-center">
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    BAY NUMBER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    COMPANY NAME
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    BRAND
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MODEL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CODE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    SERIAL NUMBER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MAST TYPE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DATE STARTED
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    TARGET DATE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    PERSON IN CHARGE
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBWS" name="tableBWS" class="WSTable">
                                            @foreach ($workshop as $WS)
                                            <tr class="bg-white border-b hover:bg-gray-200">
                                                <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                                    {{$WS->area_name}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->POUCustomer}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->name}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->POUModel}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->POUCode}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->POUSerialNum}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->POUMastType}}
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    
                                                </td>
                                                <td class="px-1 py-0.5 text-center">
                                                    {{$WS->initials}}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="report" role="tabpanel" aria-labelledby="report-tab">
                                <div class="grid grid-cols-4">
                                    {{-- Brand --}}
                                    <div class="m-4 bg-gray-100 rounded-lg" style="height: 29vh; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="inline-flex items-center justify-center w-full">
                                            <label class="text-xl font-extrabold text-gray-900"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">REPORT PER BRAND</span></label>
                                        </div>
                                        <div class="grid grid-cols-6 items-center">
                                            <div class="col-span-2"><label for="" class="block text-sm font-medium text-gray-900">BRAND:</label></div>
                                            <div class="col-span-4">
                                                <select name="RBrand" id="RBrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-sm">
                                                    <option value="1">TOYOTA</option>
                                                    <option value="2">BT</option>
                                                    <option value="3">RAYMOND</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">FROM:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="RBrandFromDate" id="RBrandFromDate">
                                                </div>
                                            </div>
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">TO:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="RBrandToDate" id="RBrandToDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center justify-center w-full mt-2">
                                            <button type="button" id="RGenBrand" name="RGenBrand" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none w-1/2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">GENERATE</button>
                                            {{-- <a href="{{ route('r-workshop.report.generateBrandReport') }}" target="_blank" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none w-1/2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">GENERATE</a> --}}
                                        </div>
                                    </div>
                                    {{-- Pull Out Unit --}}
                                    <div class="m-4 bg-gray-100 rounded-lg" style="height: 29vh; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="inline-flex items-center justify-center w-full">
                                            <label class="text-xl font-extrabold text-gray-900"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">PULL OUT UNIT</span></label>
                                        </div>
                                        <div class="grid grid-cols-6 items-center">
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">FROM:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="RPOUFromDate" id="RPOUFromDate">
                                                </div>
                                            </div>
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">TO:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="RPOUToDate" id="RPOUToDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center justify-center w-full mt-2">
                                            <button type="button" id="RGenPOU" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none w-1/2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">GENERATE</button>
                                        </div>
                                    </div>
                                    {{-- Per Bay --}}
                                    <div class="m-4 bg-gray-100 rounded-lg" style="height: 29vh; display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="inline-flex items-center justify-center w-full">
                                            <label class="text-xl font-extrabold text-gray-900"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">REPORT PER BAY</span></label>
                                        </div>
                                        <div class="grid grid-cols-6 items-center">
                                            <div class="col-span-2"><label for="" class="block text-sm font-medium text-gray-900">AREA:</label></div>
                                            <div class="col-span-4">
                                                <select name="RBayArea" id="RBayArea" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-xs">
                                                    @foreach ($section as $sections)
                                                        <option value=""></option>
                                                        <option value="{{$sections->id}}">{{$sections->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">BAY:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <select name="RBayNum" id="RBayNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-xs">
                                                    @foreach ($bayR as $bayR1)
                                                        <option value=""></option>
                                                        <option value="{{$bayR1->id}}">{{$bayR1->area_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">FROM:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="RBayFromDate" id="RBayFromDate">
                                                </div>
                                            </div>
                                            <div class="col-span-2 mt-1"><label for="" class="block text-sm font-medium text-gray-900">TO:</label></div>
                                            <div class="col-span-4 mt-1">
                                                <div class="relative max-w-sm">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="RBayToDate" id="RBayToDate">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inline-flex items-center justify-center w-full mt-2">
                                            <button type="button" id="RGenPOU" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none w-1/2 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">GENERATE</button>
                                        </div>
                                    </div>
                                    <div class="">B</div>
                                    <div class="">C</div>
                                    <div class="">D</div>
                                </div>
                            </div>
                            {{-- NEW UNIT --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="newunit" role="tabpanel" aria-labelledby="newunit-tab">
                                <div id="headDIV" class="grid grid-rows-2">
                                    <div id="div1" class="justify-self-end">
                                        <button type="button" id="addNewUnit" name="addNewUnit" data-modal-target="modalNewUnit" data-modal-toggle="modalNewUnit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-16 py-2.5 text-center mr-2 mb-2 ">ADD</button>
                                    </div>
                                    <div id="div2" class="grid grid-cols-4">
                                        <div class="grid justify-items-start">
                                            
                                        </div>
                                        <div class="col-span-2">
                                            
                                        </div>
                                        <div class="grid justify-items-end">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text" id="NTableSearch" name="NTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="bodyDIV" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                    <table id="tableNewUnit" class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="NewUnit place-items-center">
                                                <th scope="col" class="p-2 text-center">
                                                    ACTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    ARRIVAL DATE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CODE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MODEL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    SERIAL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MAST HEIGHT
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER ADDRESS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    REMARKS
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBNewUnit" name="tableBNewUnit" class="text-xs">
                                            @foreach ($bnunit as $BNU)
                                                <tr class="bg-white border-b hover:bg-gray-200">
                                                    <td class="w-3.5 p-1 whitespace-nowrap">
                                                        <button type="button" data-id="{{$BNU->id}}" data-unittype="{{$BNU->POUUnitType}}" class="btnBNUView" id="btnBNUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                                        <button type="button" data-id="{{$BNU->id}}" data-unittype="{{$BNU->POUUnitType}}" class="btnBNUEdit" id="btnBNUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                                        <button type="button" data-id="{{$BNU->id}}" data-unittype="{{$BNU->POUUnitType}}" class="btnBNUDelete" id="btnBNUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                                        <button type="button" data-id="{{$BNU->id}}" data-unittype="{{$BNU->POUUnitType}}" data-uremarks="{{$BNU->POURemarks}}" class="btnBNUTransfer" id="btnBNUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                                                    </td>
                                                    <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                                        {{$BNU->POUArrivalDate}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POUCode}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POUModel}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POUSerialNum}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POUMastHeight}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POUCustomer}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POUCustAddress}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$BNU->POURemarks}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- PULL OUT UNIT --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="pounit" role="tabpanel" aria-labelledby="pounit-tab">
                                <div id="headDIV" class="grid grid-rows-2">
                                    <div id="div1" class="justify-self-end">
                                        <button type="button" id="addPOUnit" name="addPOUnit" data-modal-target="modalPOU" data-modal-toggle="modalPOU" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-16 py-2.5 text-center mr-2 mb-2 ">ADD</button>
                                    </div>
                                    <div id="div2" class="grid grid-cols-4">
                                        <div class="grid justify-items-start">
                                            <select id="PUnitClassification" name="PUnitClassification" class="bg-gray-50 text-center border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 font-medium block w-3/4 h-10 p-2.5">
                                                <option selected value="">CLASSIFICATION</option>
                                                <option value="CLASS A">CLASS A</option>
                                                <option value="CLASS B">CLASS B</option>
                                                <option value="CLASS C">CLASS C</option>
                                                <option value="CLASS D">CLASS D</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="pouRadioPOU" type="radio" value="pouRadioPOU" name="pou-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                                        <label for="pouRadioPOU" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">Pull Out Unit</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="pouRadioCU" type="radio" value="pouRadioCU" name="pou-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="pouRadioCU" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">Confirmed Unit</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="pouRadioDU" type="radio" value="pouRadioDU" name="pou-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="pouRadioDU" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">Delivered Unit</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="pouRadioAllUnit" type="radio" value="pouRadioAllUnit" name="pou-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                        <label for="pouRadioAllUnit" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">All Unit</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="grid justify-items-end">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text" id="PTableSearch" name="PTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="bodyDIV" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                    <table id="tablePOU" class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="POU place-items-center">
                                                <th scope="col" class="p-2 text-center">
                                                    ACTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    ARRIVAL DATE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CODE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MODEL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    SERIAL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MAST HEIGHT
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER ADDRESS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    REMARKS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CLASSIFICATION
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBPOU" name="tableBPOU" class="text-xs">
                                            @foreach ($pounit as $POU)
                                                <tr class="bg-white border-b hover:bg-gray-200">
                                                    <td class="w-3.5 p-1 whitespace-nowrap">
                                                        <button type="button" data-id="{{$POU->id}}" data-unittype="{{$POU->POUUnitType}}" class="btnPOUView" id="btnPOUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                                        <button type="button" data-id="{{$POU->id}}" data-unittype="{{$POU->POUUnitType}}" class="btnPOUEdit" id="btnPOUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                                        <button type="button" data-id="{{$POU->id}}" data-unittype="{{$POU->POUUnitType}}" class="btnPOUDelete" id="btnPOUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                                        <button type="button" data-id="{{$POU->id}}" data-unittype="{{$POU->POUUnitType}}" data-poremarks="{{$POU->POURemarks}}" class="btnPOUTransfer" id="btnPOUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                                                    </td>
                                                    <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                                        {{$POU->POUArrivalDate}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POUCode}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POUModel}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POUSerialNum}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POUMastHeight}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POUCustomer}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POUCustAddress}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$POU->POURemarks}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        @if ($POU->POUClassification == '1')
                                                            CLASS A
                                                        @elseif ($POU->POUClassification == '2')
                                                            CLASS B
                                                        @elseif ($POU->POUClassification == '3')
                                                            CLASS C
                                                        @else
                                                            CLASS D
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- CONFIRM UNIT --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="counit" role="tabpanel" aria-labelledby="counit-tab">
                                <div class="grid grid-cols-5 gap-1 text-sm">
                                    <div id="divSMALL" class="col-span-1">
                                        <div class="flex items-center mb-4">
                                            <input id="cuAllStatus" type="radio" value="cuAllStatus" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                            <label for="cuAllStatus" class="ml-2 text-sm font-medium text-gray-900">ALL CONFIRM UNIT</label>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="cuWFRU" type="radio" value="cuWFRU" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                            <label for="cuWFRU" class="ml-2 text-sm font-medium text-gray-900">WAITING FOR REPAIR UNIT</label>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="cuURU" type="radio" value="cuURU" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                            <label for="cuURU" class="ml-2 text-sm font-medium text-gray-900">UNDER REPAIR UNIT</label>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="cuGU" type="radio" value="cuGU" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                            <label for="cuGU" class="ml-2 text-sm font-medium text-gray-900">GOOD UNIT</label>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="cuSU" type="radio" value="cuSU" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                            <label for="cuSU" class="ml-2 text-sm font-medium text-gray-900">SERVICE UNIT</label>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="cuFSU" type="radio" value="cuFSU" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                            <label for="cuFSU" class="ml-2 text-sm font-medium text-gray-900">FOR SCRAP UNIT</label>
                                        </div>
                                        <div class="flex items-center mb-4">
                                            <input id="cuDU" type="radio" value="cuDU" name="cuRadioStatus" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                            <label for="cuDU" class="ml-2 text-sm font-medium text-gray-900">DELIVERED UNIT</label>
                                        </div>
                                    </div>
                                    <div id="divBIG" class="col-span-4">
                                        <div id="cuHeading" class="cuHeading">
                                            <label class="mb-4 text-3xl font-extrabold text-gray-900"><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">ALL STATUS</span></label>
                                        </div>
                                        <div id="divBodyUp" class="grid grid-cols-4">
                                            <div class="grid justify-items-start">
                                                <select id="CUnitClassification" name="CUnitClassification" class="bg-gray-50 text-center border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 font-medium block w-3/4 h-10 p-2.5">
                                                    <option selected value="">CLASSIFICATION</option>
                                                    <option value="CLASS A">CLASS A</option>
                                                    <option value="CLASS B">CLASS B</option>
                                                    <option value="CLASS C">CLASS C</option>
                                                    <option value="CLASS D">CLASS D</option>
                                                </select>
                                            </div>
                                            <div class="col-span-2"> </div>
                                            <div class="grid justify-items-end">
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                        <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                    </div>
                                                    <input type="text" id="CTableSearch" name="CTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-50 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="divBodyMiddle" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                            <table id="tableCU" class="CU w-full text-sm text-left text-gray-500">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                                    <tr class="place-items-center">
                                                        <th scope="col" class="p-2 text-center">
                                                            ACTION
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            CODE
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            MODEL
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            SERIAL
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            MAST HEIGHT
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            REMARKS
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            CLASSIFICATION
                                                        </th>
                                                        <th scope="col" class="px-6 py-1 text-center">
                                                            STATUS
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableBCU" name="tableBCU" class="text-xs">
                                                    @foreach ($cunit as $CU)
                                                        <tr class="bg-white border-b hover:bg-gray-200">
                                                            <td class="w-3.5 p-1 whitespace-nowrap">
                                                                <button type="button" data-id="{{$CU->POUID}}" data-unittype="{{$CU->POUUnitType}}" class="btnCUView" id="btnCUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                                                <button type="button" data-id="{{$CU->POUID}}" data-unittype="{{$CU->POUUnitType}}" class="btnCUEdit" id="btnCUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                                                <button type="button" data-id="{{$CU->POUID}}" data-cubay="{{$CU->CUTransferBay}}" class="btnCUDelete" id="btnCUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                                                <button type="button" data-id="{{$CU->POUID}}" class="btnCUTransfer" id="btnCUTransfer"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M811.3 938.7H217.5c-71.5 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h296.9c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7H217.5c-24.5 0-44.4 19.9-44.4 44.4v593.8c0 24.5 19.9 44.4 44.4 44.4h593.8c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7S941 488.4 941 512v296.9c0 71.6-58.2 129.8-129.7 129.8z" fill="#0dd954"/><path d="M898.4 405.3c-23.6 0-42.7-19.1-42.7-42.7V212.9c0-23.3-19-42.3-42.3-42.3H663.7c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h149.7c70.4 0 127.6 57.2 127.6 127.6v149.7c0 23.7-19.1 42.8-42.6 42.8z" fill="#5F6379"/><path d="M373.6 712.6c-10.9 0-21.8-4.2-30.2-12.5-16.7-16.7-16.7-43.7 0-60.3L851.2 132c16.7-16.7 43.7-16.7 60.3 0 16.7 16.7 16.7 43.7 0 60.3L403.8 700.1c-8.4 8.3-19.3 12.5-30.2 12.5z" fill="#5F6379"/></svg></button>
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                {{$CU->POUCode}}
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                {{$CU->POUModel}}
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                {{$CU->POUSerialNum}}
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                {{$CU->POUMastHeight}}
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                {{$CU->CUTransferRemarks}}
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                @if ($CU->POUClassification == '1')
                                                                    CLASS A
                                                                @elseif ($CU->POUClassification == '2')
                                                                    CLASS B
                                                                @elseif ($CU->POUClassification == '3')
                                                                    CLASS C
                                                                @else
                                                                    CLASS D
                                                                @endif
                                                            </td>
                                                            <td class="px-1 py-0.5 text-center">
                                                                @if ($CU->CUTransferStatus == '1')
                                                                    WAITING FOR REPAIR UNIT
                                                                @elseif ($CU->CUTransferStatus == '2')
                                                                    UNDER REPAIR UNIT
                                                                @elseif ($CU->CUTransferStatus == '3')
                                                                    GOOD UNIT
                                                                @elseif ($CU->CUTransferStatus == '4')
                                                                    SERVICE UNIT
                                                                @else
                                                                    FOR SCRAP UNIT
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- DELIVERED UNIT --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="dunit" role="tabpanel" aria-labelledby="dunit-tab">
                                <div id="headDIV" class="grid grid-rows-2">
                                    <div id="div1" class="justify-self-end">
                                        
                                    </div>
                                    <div id="div2" class="grid grid-cols-4">
                                        <div class="grid justify-items-start">
                                            {{-- <select id="duSelectStatus" name="duSelectStatus" class="bg-gray-50 text-center border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 font-medium block w-3/4 h-10 p-2.5">
                                                <option selected value="">CLASSIFICATION</option>
                                                <option value="CLASS A">CLASS A</option>
                                                <option value="CLASS B">CLASS B</option>
                                                <option value="CLASS C">CLASS C</option>
                                                <option value="CLASS D">CLASS D</option>
                                            </select> --}}
                                        </div>
                                        <div class="col-span-2">
                                            {{-- <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="duRadioNDU" type="radio" value="duRadioNDU" name="du-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                                        <label for="duRadioNDU" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">Not Delivered Unit</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="duRadioDU" type="radio" value="duRadioDU" name="du-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="duRadioDU" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">Delivered Unit</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="duRadioAllUnit" type="radio" value="duRadioAllUnit" name="du-radio-unit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                        <label for="duRadioAllUnit" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">All Unit</label>
                                                    </div>
                                                </li>
                                            </ul> --}}
                                        </div>
                                        <div class="grid justify-items-end">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text" id="DTableSearch" name="DTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="bodyDIV" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                    <table id="tableDU" class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="place-items-center">
                                                <th scope="col" class="p-2 text-center">
                                                    ACTION
                                                </th>
                                                <th scope="col" class="px-5 py-1 text-center">
                                                    TRANSFER DATE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CODE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MODEL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    SERIAL
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    MAST HEIGHT
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER ADDRESS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    REMARKS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CLASSIFICATION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DELIVERED DATE
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBDU" name="tableBDU" class="text-xs">
                                            @foreach ($dunit as $DU)
                                                <tr class="bg-white border-b hover:bg-gray-200">
                                                    <td class="w-3.5 p-1 whitespace-nowrap">
                                                        <button type="button" data-id="{{$DU->POUID}}" data-unittype="{{$DU->POUUnitType}}" class="btnDUView" id="btnDUView"><svg fill="#000000" viewBox="-3.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"> <path d="M12.406 13.844c1.188 0 2.156 0.969 2.156 2.156s-0.969 2.125-2.156 2.125-2.125-0.938-2.125-2.125 0.938-2.156 2.125-2.156zM12.406 8.531c7.063 0 12.156 6.625 12.156 6.625 0.344 0.438 0.344 1.219 0 1.656 0 0-5.094 6.625-12.156 6.625s-12.156-6.625-12.156-6.625c-0.344-0.438-0.344-1.219 0-1.656 0 0 5.094-6.625 12.156-6.625zM12.406 21.344c2.938 0 5.344-2.406 5.344-5.344s-2.406-5.344-5.344-5.344-5.344 2.406-5.344 5.344 2.406 5.344 5.344 5.344z"></path></svg></button>
                                                        <button type="button" data-id="{{$DU->POUID}}" data-unittype="{{$DU->POUUnitType}}" class="btnDUEdit" id="btnDUEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M823.3 938.8H229.4c-71.6 0-129.8-58.2-129.8-129.8V215.1c0-71.6 58.2-129.8 129.8-129.8h297c23.6 0 42.7 19.1 42.7 42.7s-19.1 42.7-42.7 42.7h-297c-24.5 0-44.4 19.9-44.4 44.4V809c0 24.5 19.9 44.4 44.4 44.4h593.9c24.5 0 44.4-19.9 44.4-44.4V512c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v297c0 71.6-58.2 129.8-129.8 129.8z" fill="#3688FF"/><path d="M483 756.5c-1.8 0-3.5-0.1-5.3-0.3l-134.5-16.8c-19.4-2.4-34.6-17.7-37-37l-16.8-134.5c-1.6-13.1 2.9-26.2 12.2-35.5l374.6-374.6c51.1-51.1 134.2-51.1 185.3 0l26.3 26.3c24.8 24.7 38.4 57.6 38.4 92.7 0 35-13.6 67.9-38.4 92.7L513.2 744c-8.1 8.1-19 12.5-30.2 12.5z m-96.3-97.7l80.8 10.1 359.8-359.8c8.6-8.6 13.4-20.1 13.4-32.3 0-12.2-4.8-23.7-13.4-32.3L801 218.2c-17.9-17.8-46.8-17.8-64.6 0L376.6 578l10.1 80.8z" fill="#5F6379"/></svg></button>
                                                        <button type="button" data-id="{{$DU->POUID}}" data-unittype="{{$DU->POUUnitType}}" data-duid="{{$DU->id}}" data-cubay="{{$DU->CUTransferBay}}" class="btnDUDelete" id="btnDUDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M779.5 1002.7h-535c-64.3 0-116.5-52.3-116.5-116.5V170.7h768v715.5c0 64.2-52.3 116.5-116.5 116.5zM213.3 256v630.1c0 17.2 14 31.2 31.2 31.2h534.9c17.2 0 31.2-14 31.2-31.2V256H213.3z" fill="#ff3838"/><path d="M917.3 256H106.7C83.1 256 64 236.9 64 213.3s19.1-42.7 42.7-42.7h810.7c23.6 0 42.7 19.1 42.7 42.7S940.9 256 917.3 256zM618.7 128H405.3c-23.6 0-42.7-19.1-42.7-42.7s19.1-42.7 42.7-42.7h213.3c23.6 0 42.7 19.1 42.7 42.7S642.2 128 618.7 128zM405.3 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7S448 403 448 426.6v256c0 23.6-19.1 42.7-42.7 42.7zM618.7 725.3c-23.6 0-42.7-19.1-42.7-42.7v-256c0-23.6 19.1-42.7 42.7-42.7s42.7 19.1 42.7 42.7v256c-0.1 23.6-19.2 42.7-42.7 42.7z" fill="#5F6379"/></svg></button>
                                                    </td>
                                                    <td scope="row" class="px-1 py-0.5 whitespace-nowrap text-center">
                                                        {{$DU->DUTransferDate}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->POUCode}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->POUModel}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->POUSerialNum}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->POUMastHeight}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->POUCustomer}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->POUCustAddress}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->DURemarks}}
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        @if ($DU->POUClassification == '1')
                                                            CLASS A
                                                        @elseif ($DU->POUClassification == '2')
                                                            CLASS B
                                                        @elseif ($DU->POUClassification == '3')
                                                            CLASS C
                                                        @else
                                                            CLASS D
                                                        @endif
                                                    </td>
                                                    <td class="px-1 py-0.5 text-center">
                                                        {{$DU->DUDelDate}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- CANNIBALIZED UNIT --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="caunit" role="tabpanel" aria-labelledby="caunit-tab">
                                <div id="headDIV" class="grid grid-rows-2">
                                    <div id="div1" class="justify-self-end">
                                        <button type="button" id="addCanUnit" data-modal-target="modalCanUnit" data-modal-toggle="modalCanUnit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-16 py-2.5 text-center mr-2 mb-2 ">ADD</button>
                                    </div>
                                    <div id="div2" class="grid grid-cols-4">
                                        <div class="grid justify-items-start">
                                            <select id="CanUnitSection" name="CanUnitSection" class="bg-gray-50 text-center border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 font-medium block w-3/4 h-10 p-2.5">
                                                <option selected value="">SECTION</option>
                                                <option value="TOYOTA">TOYOTA</option>
                                                <option value="OVERHAULING">OVERHAULING</option>
                                                <option value="BT SECTION">BT SECTION</option>
                                                <option value="RAYMOND SECTION">RAYMOND SECTION</option>
                                                <option value="PPT SECTION">PPT SECTION</option>
                                                <option value="G SECTION">G SECTION</option>
                                                <option value="PDI SECTION">PDI SECTION</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="CanUnitALL" type="radio" value="CanUnitALL" name="RadioCanUnit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                                        <label for="CanUnitALL" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">ALL</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="CanUnitPending" type="radio" value="CanUnitPending" name="RadioCanUnit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="CanUnitPending" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">PENDING</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="CanUnitClosed" type="radio" value="CanUnitClosed" name="RadioCanUnit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="CanUnitClosed" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">CLOSED</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="CanUnitNFR" type="radio" value="CanUnitNFR" name="RadioCanUnit" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                        <label for="CanUnitNFR" class="w-full py-3 ml-2 text-xs font-medium text-gray-900">NOT FOR RETURN</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="grid justify-items-end">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text" id="CanUnitTableSearch" name="CanUnitTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="bodyDIV" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                    <table id="tableCanUnit" class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="CanUnit place-items-center">
                                                <th scope="col" class="p-2 text-center">
                                                    ACTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DATE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    C/O NUMBER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    PART NUMBER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DESCRIPTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    SECTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER ADDRESS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    PREPARED BY
                                                </th>
                                                <th class="hidden">
                                                    STATUS
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="CanUnitTable" name="CanUnitTable" class="CanUnitTable">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- D.R. MONITORING --}}
                            <div class="hidden p-2 rounded-lg bg-gray-50" id="drunit" role="tabpanel" aria-labelledby="drunit-tab">
                                <div id="headDIV" class="grid grid-rows-2">
                                    <div id="div1" class="justify-self-end">
                                        <button type="button" id="addDRMon" data-modal-target="modalDRMon" data-modal-toggle="modalDRMon" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-16 py-2.5 text-center mr-2 mb-2 ">ADD</button>
                                    </div>
                                    <div id="div2" class="grid grid-cols-4">
                                        <div class="grid justify-items-start">
                                        </div>
                                        <div class="col-span-2">
                                            <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="DRMonALL" type="radio" value="DRMonALL" name="RadioDRMon" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" checked>
                                                        <label for="DRMonALL" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">ALL</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="DRMonOnGoing" type="radio" value="DRMonOnGoing" name="RadioDRMon" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="DRMonOnGoing" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">ON GOING</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="DRMonPending" type="radio" value="DRMonPending" name="RadioDRMon" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="DRMonPending" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">PENDING</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="DRMonCancelled" type="radio" value="DRMonCancelled" name="RadioDRMon" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                        <label for="DRMonCancelled" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">CANCELLED</label>
                                                    </div>
                                                </li>
                                                <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                                    <div class="flex items-center pl-3">
                                                        <input id="DRMonDone" type="radio" value="DRMonDone" name="RadioDRMon" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
                                                        <label for="DRMonDone" class="w-full py-3 ml-2 text-sm font-medium text-gray-900">DONE</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="grid justify-items-end">
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-7 h-7 text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text" id="WorkshopTableSearch" name="WorkshopTableSearch" class="block p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg h-10 w-60 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for details">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="bodyDIV" style="height: calc(100vh - 265px);" class="overflow-y-auto">
                                    <table id="tableDRMon" class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr class="DRMon place-items-center">
                                                <th scope="col" class="p-2 text-center">
                                                    ACTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DATE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CODE
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    PART NUMBER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DESCRIPTION
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    CUSTOMER ADDRESS
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    SUPPLIER
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    DELIVERY RECEIPT NO.
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    PURCHASE REQUEST NO.
                                                </th>
                                                <th scope="col" class="px-6 py-1 text-center">
                                                    STATUS
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="DRMonTable" name="DRMonTable" class="DRMonTable">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- HIDDEN --}}
            {{-- Brand New Hidden Button for View --}}
            <button type="button" id="btnBNUViewH" class="btnBNUViewH hidden" data-modal-target="modalNewUnit" data-modal-toggle="modalNewUnit"></button>
            {{-- Brand New Hidden Button for Edit --}}
            <button type="button" id="btnBNUEditH" class="btnBNUEditH hidden" data-modal-target="modalNewUnit" data-modal-toggle="modalNewUnit"></button>
            {{-- Brand New Hidden Button for Delete --}}
            <button type="button" id="btnBNUDeleteH" class="btnBNUDeleteH hidden" data-modal-target="modalDeleteBNU" data-modal-toggle="modalDeleteBNU"></button>
            {{-- Brand New Hidden Button for Transfer --}}
            <button type="button" id="btnBNUTransferH" class="btnBNUTransferH hidden" data-modal-target="modalTransferBNU" data-modal-toggle="modalTransferBNU"></button>
            {{-- POU Hidden Button for Edit --}}
            <button type="button" id="btnPOUEditH" class="btnPOUEditH hidden" data-modal-target="modalPOU" data-modal-toggle="modalPOU"></button>
            {{-- POU Hidden Button for View --}}
            <button type="button" id="btnPOUViewH" class="btnPOUViewH hidden" data-modal-target="modalPOU" data-modal-toggle="modalPOU"></button>
            {{-- POU Hidden Button for Delete --}}
            <button type="button" id="btnPOUDeleteH" class="btnPOUDeleteH hidden" data-modal-target="modalDeletePOU" data-modal-toggle="modalDeletePOU"></button>
            {{-- POU Hidden Button for Transfer --}}
            <button type="button" id="btnPOUTransferH" class="btnPOUTransferH hidden" data-modal-target="modalTransferPOU" data-modal-toggle="modalTransferPOU"></button>
            {{-- CU Hidden Button for Edit --}}
            {{-- <button type="button" id="btnCUEditH" class="btnCUEditH hidden" data-modal-target="modalPOU" data-modal-toggle="modalPOU"></button> --}}
            {{-- CU Hidden Button for Delete --}}
            <button type="button" id="btnCUDeleteH" class="btnCUDeleteH hidden" data-modal-target="modalDeleteCU" data-modal-toggle="modalDeleteCU"></button>
            {{-- CU Hidden Button for Transfer --}}
            {{-- <button type="button" id="btnCUTransferH" class="btnCUTransferH hidden" data-modal-target="modalTransferCU" data-modal-toggle="modalTransferCU"></button> --}}
            {{-- DU Hidden Button for Edit --}}
            {{-- <button type="button" id="btnDUEditH" class="btnDUEditH hidden" data-modal-target="modalDU" data-modal-toggle="modalDU"></button> --}}
            {{-- DU Hidden Button for Delete --}}
            <button type="button" id="btnDUDeleteH" class="btnDUDeleteH hidden" data-modal-target="modalDeleteDU" data-modal-toggle="modalDeleteDU"></button>
            {{-- DU Hidden Button for Transfer --}}
            {{-- <button type="button" id="btnDUTransferH" class="btnDUTransferH hidden" data-modal-target="modalTransferDU" data-modal-toggle="modalTransferDU"></button> --}}
            {{-- Cannibalized Unit Hidden Button for Edit --}}
            {{-- <button type="button" id="btnCanUnitEditH" class="btnCanUnitEditH hidden" data-modal-target="modalCanUnit" data-modal-toggle="modalCanUnit"></button> --}}
            {{-- Cannibalized Unit Hidden Button for Delete --}}
            {{-- <button type="button" id="btnCanUnitDeleteH" class="btnCanUnitDeleteH hidden" data-modal-target="modalDeleteCanUnit" data-modal-toggle="modalDeleteCanUnit"></button> --}}
            {{-- D.R. Monitoring Hidden Button for Edit --}}
            {{-- <button type="button" id="btnDRMonEditH" class="btnDRMonEditH hidden" data-modal-target="modalDRMon" data-modal-toggle="modalDRMon"></button> --}}
            {{-- D.R. Monitoring Hidden Button for Delete --}}
            {{-- <button type="button" id="btnDRMonDeleteH" class="btnDRMonDeleteH hidden" data-modal-target="modalDeleteDRMon" data-modal-toggle="modalDeleteDRMon"></button> --}}
            {{-- D.R. Monitoring Hidden Button for Add DIV --}}
            {{-- <button type="button" id="btnDRMonAddDIVH" class="btnDRMonAddDIVH hidden"></button> --}}
            
            {{-- Hidden Button for Success --}}
            <button type="button" id="btnSuccessH" class="btnSuccessH hidden" data-modal-target="success-modal" data-modal-toggle="success-modal"></button>
            {{-- Hidden Button for Failed --}}
            <button type="button" id="btnFailedH" class="btnFailedH hidden" data-modal-target="failed-modal" data-modal-toggle="failed-modal"></button>
    </div>
        
    {{-- MODALS --}}
        {{-- ADD AND EDIT MODAL FOR NEW UNITS --}}
        <div id="modalNewUnit" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-4xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-medium text-gray-900">
                            NEW UNIT
                        </h3>
                        <button type="button" id="buttonCloseN" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalNewUnit">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-2 space-y-6">
                        <form action="" id="formNewUnit">
                            @csrf
                            <input type="hidden" id="BNUIDe" name="BNUIDe">
                            <div id="NewUnitbhead" class="">
                                <div class="grid grid-cols-3">
                                    <div class="grid grid-cols-3 items-center">
                                        <div id="label" class="">
                                            <label for="BNUnitType" class="block text-sm font-medium text-gray-900">Unit Type:</label>
                                        </div>
                                        <div id="input" class="col-span-2 uppercase mr-1">
                                            <select name="BNUnitType" id="BNUnitType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-sm">
                                                <option value="1">DIESEL/GASOLINE/LPG</option>
                                                <option value="2">BATTERY</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class=""></div>
                                    <div class="grid grid-cols-3 items-center">
                                        <div id="label" class="">
                                            <label for="BNUArrivalDate" class="block text-sm font-medium text-gray-900">Arrival Date:</label>
                                        </div>
                                        <div id="input" class="col-span-2">
                                            <div class="relative max-w-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text"  datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="BNUArrivalDate" id="BNUArrivalDate">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="dataNewUnit" style="height: calc(100vh - 350px);" class="mt-2">
                                <div class="mb-4 border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="NewUnit" data-tabs-toggle="#NewUnitContent" role="tablist">
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="NUnitDetails-tab" data-tabs-target="#NUnitDetails" type="button" role="tab" aria-controls="NUnitDetails" aria-selected="false">UNIT DETAILS</button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="NOtherDetails-tab" data-tabs-target="#NOtherDetails" type="button" role="tab" aria-controls="NOtherDetails" aria-selected="false">OTHER DETAILS</button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="NBatteryDetails-tab" data-tabs-target="#NBatteryDetails" type="button" role="tab" aria-controls="NBatteryDetails" aria-selected="false">BATTERY DETAILS</button>
                                        </li>
                                        <li role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="NChargerDetails-tab" data-tabs-target="#NChargerDetails" type="button" role="tab" aria-controls="NChargerDetails" aria-selected="false">CHARGER DETAILS</button>
                                        </li>
                                    </ul>
                                </div>
                                <div id="NewUnitContent">
                                    {{-- UNIT DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="NUnitDetails" role="tabpanel" aria-labelledby="NUnitDetails-tab">
                                        <div class="grid grid-cols-7 items-center">
                                            <div id="label" class="">
                                                <label for="BNUBrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <select name="BNUBrand" id="BNUBrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected disabled></option>
                                                    @foreach ($brand as $brands)
                                                    <option value="{{$brands->id}}">{{$brands->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="">
                                                {{-- <label for="BNUClassification" class="block text-sm font-medium text-gray-900">Classification:</label> --}}
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                {{-- <select name="BNUClassification" id="BNUClassification" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected disabled></option>
                                                    <option value="1">CLASS A</option>
                                                    <option value="2">CLASS B</option>
                                                    <option value="3">CLASS C</option>
                                                    <option value="4">CLASS D</option>
                                                </select> --}}
                                            </div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="BNUModel" id="BNUModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="BNUSerialNum" id="BNUSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCode" class="block text-sm font-medium text-gray-900">Code:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="BNUCode" id="BNUCode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUMastType" class="block text-sm font-medium text-gray-900">Mast Type:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="BNUMastType" id="BNUMastType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUForkSize" class="block text-sm font-medium text-gray-900">Fork Size:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="BNUForkSize" id="BNUForkSize" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUMastHeight" class="block text-sm font-medium text-gray-900">Mast Height:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="BNUMastHeight" id="BNUMastHeight" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-3 mt-2">
                                                <input type="checkbox" id="BNUwAttachment" name="BNUwAttachment" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="BNUwAttachment" class="ml-2 text-sm font-medium text-gray-900">Unit with Attachment</label>
                                            </div>
                                            <div class=""></div>
                                            <div class="col-span-3 mt-2">
                                                <input type="checkbox" id="BNUwAccesories" name="BNUwAccesories" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="BNUwAccesories" class="ml-2 text-sm font-medium text-gray-900">Unit with Accesories</label>
                                            </div>
                                            <div id="BAttachment" class="col-span-3 grid grid-cols-3 mt-2 items-center justify-self-center">
                                                <div class="col-span-3">
                                                    <label for="BNUAttachment" class="block text-sm font-medium text-gray-900">Attachment:</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <label for="BNUAttType" class="block text-sm font-medium text-gray-900">Type:</label>
                                                </div>
                                                <div class="">
                                                    <input type="text" name="BNUAttType" id="BNUAttType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div class="mt-1"></div>
                                                <div class="mt-1">
                                                    <label for="BNUAttModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                                </div>
                                                <div class="mt-1">
                                                    <input type="text" name="BNUAttModel" id="BNUAttModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div class="mt-1"></div>
                                                <div class="mt-1">
                                                    <label for="BNUAttSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div class="mt-1">
                                                    <input type="text" name="BNUAttSerialNum" id="BNUAttSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                            <div class=""></div>
                                            <div id="BAccesories" class="col-span-3 grid grid-cols-3 mt-2 items-start">
                                                <div class="col-span-3">
                                                    <label for="BNUAccesories" class="block text-sm font-medium text-gray-900">Accesories:</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <input type="checkbox" id="BNUAccISite" name="BNUAccISite" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccISite" class="ml-2 text-sm font-medium text-gray-900">I-Site</label>
                                                </div>
                                                <div class="">
                                                    <input type="checkbox" id="BNUAccLiftCam" name="BNUAccLiftCam" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccLiftCam" class="ml-2 text-sm font-medium text-gray-900">Lift Cam</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <input type="checkbox" id="BNUAccRedLight" name="BNUAccRedLight" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccRedLight" class="ml-2 text-sm font-medium text-gray-900">Red Light</label>
                                                </div>
                                                <div class="">
                                                    <input type="checkbox" id="BNUAccBlueLight" name="BNUAccBlueLight" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccBlueLight" class="ml-2 text-sm font-medium text-gray-900">Blue Light</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <input type="checkbox" id="BNUAccFireExt" name="BNUAccFireExt" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccFireExt" class="ml-2 text-xs font-medium text-gray-900">Fire Extinguisher</label>
                                                </div>
                                                <div class="">
                                                    <input type="checkbox" id="BNUAccStLight" name="BNUAccStLight" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccStLight" class="ml-2 text-xs font-medium text-gray-900">Strobe Light</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="col-span-2">
                                                    <input type="checkbox" id="BNUAccOthers" name="BNUAccOthers" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="BNUAccOthers" class="ml-2 text-sm font-medium text-gray-900">Others</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="col-span-2 mt-1 relative">
                                                    <input type="text" id="BNUAccOthersDetail" name="BNUAccOthersDetail" class="block rounded-t-lg w-full text-sm text-gray-900 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Other Accesories" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- OTHER DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="NOtherDetails" role="tabpanel" aria-labelledby="NOtherDetails-tab">
                                        <div class="grid grid-cols-7 items-center">
                                            <div id="label" class="">
                                                <label for="BNUTechnician1" class="block text-sm font-medium text-gray-900">Technician 1:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <select name="BNUTechnician1" id="BNUTechnician1" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected disabled></option>
                                                    @foreach ($technician as $technicians)
                                                    <option value="{{$technicians->id}}">{{$technicians->initials}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUTechnician2" class="block text-sm font-medium text-gray-900">Technician 2:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <select name="BNUTechnician2" id="BNUTechnician2" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected></option>
                                                    @foreach ($technician as $technicians)
                                                    <option value="{{$technicians->id}}">{{$technicians->initials}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-5">
                                                <label for="BNUSalesman" class="block text-sm font-medium text-gray-900">Salesman:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-5">
                                                <input type="text" name="BNUSalesman" id="BNUSalesman" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-5">
                                                <label for="BNUCustomer" class="block text-sm font-medium text-gray-900">Customer:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-5">
                                                <input type="text" name="BNUCustomer" id="BNUCustomer" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCustAddress" class="block text-sm font-medium text-gray-900">Customer Address:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCustAddress" id="BNUCustAddress" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                        </div>
                                    </div>
                                    {{-- BATTERY DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="NBatteryDetails" role="tabpanel" aria-labelledby="NBatteryDetails-tab">
                                        <div class="grid grid-cols-3 items-center">
                                            <div class="col-span-1 mt-1">
                                                <label for="BNUBatAttached" class="ml-2 text-sm font-medium text-gray-900">Battery Attached</label>
                                            </div>
                                            <div class="col-span-1 mt-1">
                                                <input type="checkbox" id="BNUwBatSpare1" name="BNUwBatSpare1" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="BNUwBatSpare1" class="ml-2 text-sm font-medium text-gray-900">Spare Battery 1</label>
                                            </div>
                                            <div class="col-span-1 mt-1">
                                                <input type="checkbox" id="BNUwBatSpare2" name="BNUwBatSpare2" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="BNUwBatSpare2" class="ml-2 text-sm font-medium text-gray-900">Spare Battery 2</label>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-3">
                                            <div id="BatAttached" class="grid grid-cols-3 mr-2 mt-1 items-center">
                                                <div id="label" class="">
                                                    <label for="BNUBABrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                </div>
                                                <div id="input" class="col-span-2">
                                                    <input type="text" name="BNUBABrand" id="BNUBABrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBABatType" class="block text-xs font-medium text-gray-900">Battery Type:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBABatType" id="BNUBABatType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBASerialNum" class="block text-xs font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBASerialNum" id="BNUBASerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBACode" class="block text-sm font-medium text-gray-900">Code:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBACode" id="BNUBACode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBAAmper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBAAmper" id="BNUBAAmper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBAVolt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBAVolt" id="BNUBAVolt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBACCable" class="block text-xs font-medium text-gray-900">Change Cable:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBACCable" id="BNUBACCable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUBACTable" class="block text-xs font-medium text-gray-900">Change Table:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUBACTable" id="BNUBACTable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                            <div id="BSpareBat1" class="grid grid-cols-3 mr-2 ml-2 mt-1 items-center">
                                                <div id="label" class="">
                                                    <label for="BNUSB1Brand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                </div>
                                                <div id="input" class="col-span-2">
                                                    <input type="text" name="BNUSB1Brand" id="BNUSB1Brand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1BatType" class="block text-xs font-medium text-gray-900">Battery Type:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1BatType" id="BNUSB1BatType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1SerialNum" class="block text-xs font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1SerialNum" id="BNUSB1SerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1Code" class="block text-sm font-medium text-gray-900">Code:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1Code" id="BNUSB1Code" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1Amper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1Amper" id="BNUSB1Amper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1Volt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1Volt" id="BNUSB1Volt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1CCable" class="block text-xs font-medium text-gray-900">Change Cable:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1CCable" id="BNUSB1CCable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB1CTable" class="block text-xs font-medium text-gray-900">Change Table:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB1CTable" id="BNUSB1CTable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                            <div id="BSpareBat2" class="grid grid-cols-3 ml-2 mt-1 items-center">
                                                <div id="label" class="">
                                                    <label for="BNUSB2Brand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                </div>
                                                <div id="input" class="col-span-2">
                                                    <input type="text" name="BNUSB2Brand" id="BNUSB2Brand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2BatType" class="block text-xs font-medium text-gray-900">Battery Type:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2BatType" id="BNUSB2BatType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2SerialNum" class="block text-xs font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2SerialNum" id="BNUSB2SerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2Code" class="block text-sm font-medium text-gray-900">Code:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2Code" id="BNUSB2Code" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2Amper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2Amper" id="BNUSB2Amper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2Volt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2Volt" id="BNUSB2Volt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2CCable" class="block text-xs font-medium text-gray-900">Change Cable:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2CCable" id="BNUSB2CCable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="BNUSB2CTable" class="block text-xs font-medium text-gray-900">Change Table:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="BNUSB2CTable" id="BNUSB2CTable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- CHARGER DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="NChargerDetails" role="tabpanel" aria-labelledby="NChargerDetails-tab">
                                        <div class="grid grid-cols-7 items-center">
                                            <div id="label" class="">
                                                <label for="BNUCBrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <input type="text" name="BNUCBrand" id="BNUCBrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCModel" id="BNUCModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCSerialNum" id="BNUCSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCCode" class="block text-sm font-medium text-gray-900">Code:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCCode" id="BNUCCode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCAmper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCAmper" id="BNUCAmper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCVolt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCVolt" id="BNUCVolt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="BNUCInput" class="block text-sm font-medium text-gray-900">Input/Phase:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="BNUCInput" id="BNUCInput" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2">
                            <div id="DUbfoot" class="">
                                <div class="grid grid-cols-7 items-center">
                                    <div id="label" class="mb-2 ml-1">
                                        <label for="BNURemarks" class="block text-sm font-medium text-gray-900">Remarks:</label>
                                    </div>
                                    <div id="input" class="col-span-5 uppercase mb-2">
                                        <textarea rows="2" name="BNURemarks" id="BNURemarks" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b">
                        <button type="button" id="saveNewUnitH" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                        <button type="button" id="saveNewUnit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                        <button type="button" id="clearNewUnit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">CLEAR</button>
                        <button id="closeNewUnit" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- DELETE MODAL FOR BRAND NEW UNITS --}}
        <div id="modalDeleteBNU" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</h3>
                        <button type="button" id="deleteConfirmBNU"  data-modal-hide="modalDeleteBNU" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure.
                        </button>
                        <button data-modal-hide="modalDeleteBNU" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel.</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- TRANSFER MODAL FOR BRAND NEW UNITS --}}
        <div id="modalTransferBNU" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-2xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            TRANSFER TO WAREHOUSE
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalTransferBNU">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <form action="" id="formBNUT">
                            @csrf
                            <div class="grid grid-cols-5 items-center">
                                <div class="col-span-5 mb-2">
                                    <input type="hidden" id="BNUIDx" name="BNUIDx">
                                </div>
                                <div id="label" class="uppercase mb-2">
                                    <label for="BNUTransferDate" class="block text-sm font-medium text-gray-900">Transfer Date:</label>
                                </div>
                                <div class="col-span-2">
                                    <div class="relative max-w-sm">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="BNUTransferDate" id="BNUTransferDate">
                                    </div>
                                </div>
                                <div class="col-span-2"></div>
                                <div id="label" class="uppercase mt-5">
                                    <label for="BNUStatus" class="block text-sm font-medium text-gray-900">Status:</label>
                                </div>
                                <div id="input" class="col-span-2 uppercase mt-5">
                                    <select name="BNUStatus" id="BNUStatus" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                        <option value="" selected disabled></option>
                                        <option value="1">WAITING FOR REPAIR UNIT</option>
                                        <option value="2">UNDER REPAIR UNIT</option>
                                        <option value="3">USED GOOD UNIT</option>
                                        <option value="4">SERVICE UNIT</option>
                                        <option value="5">FOR SCRAP UNIT</option>
                                        <option value="6">FOR SALE UNIT</option>
                                        <option value="7">WAITING PARTS</option>
                                        <option value="8">WAITING BACK ORDER</option>
                                        <option value="9">WAITING SPARE BATT</option>
                                        <option value="10">STOCK UNIT</option>
                                        <option value="11">WAITING FOR MCI</option>
                                        <option value="12">WAITING FOR PDI</option>
                                        <option value="13">DONE PDI (WFD)</option>
                                    </select>
                                </div>
                                <div id="input" class="col-span-2">
                                </div>
                                <div id="label" class="uppercase mt-2">
                                    <label for="BNUArea" class="block text-sm font-medium text-gray-900">Area:</label>
                                </div>
                                <div id="input" class="col-span-2 uppercase mt-2">
                                    <select name="BNUArea" id="BNUArea" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                        <option value="" selected></option>
                                        @foreach ($section as $sections)
                                        <option value="{{$sections->id}}">{{$sections->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="input" class="col-span-2"></div>
                                <div id="label" class="uppercase mt-2">
                                    <label for="BNUBay" class="block text-sm font-medium text-gray-900">Bay:</label>
                                </div>
                                <div id="input" class="col-span-2 uppercase mt-2">
                                    <select name="BNUBay" id="BNUBay" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                        <option value="" selected></option>
                                        @foreach ($bay as $bays)
                                        <option value="{{$bays->id}}">{{$bays->area_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="input" class="col-span-2"></div>
                                <div id="label" class="uppercase mt-5">
                                    <label for="BNURemarksO" class="block text-sm font-medium text-gray-900">Unit Remarks:</label>
                                </div>
                                <div id="input" class="uppercase mt-5 col-span-2">
                                    <textarea rows="3" name="BNURemarksO" id="BNURemarksO" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 pointer-events-none uppercase"></textarea>
                                </div>
                                <div id="input" class="col-span-2"></div>
                                <div id="label" class="uppercase mt-5">
                                    <label for="BNURemarksT" class="block text-sm font-medium text-gray-900">Transfer Remarks:</label>
                                </div>
                                <div id="input" class="uppercase mt-5 col-span-2">
                                    <textarea rows="3" name="BNURemarksT" id="BNURemarksT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                        <button type="button" id="transferBNU" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">TRANSFER</button>
                        <button data-modal-hide="modalTransferBNU" type="button" id="closeBNTransfer" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ADD AND EDIT MODAL FOR PULL OUT UNITS --}}
        <div id="modalPOU" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-4xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 id="htitle" class="text-xl font-medium text-gray-900">
                            PULL OUT UNIT
                        </h3>
                        <button type="button" id="buttonCloseP" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalPOU">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-2 space-y-6">
                        <form action="" id="formPOU">
                            @csrf
                            <input type="hidden" id="POUIDe" name="POUIDe">
                            <div id="POUbhead" class="">
                                <div class="grid grid-cols-3">
                                    <div class="grid grid-cols-3 items-center">
                                        <div id="label" class="">
                                            <label for="POUUnitType" class="block text-sm font-medium text-gray-900">Unit Type:</label>
                                        </div>
                                        <div id="input" class="col-span-2 uppercase mr-1">
                                            <select name="POUUnitType" id="POUUnitType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center text-sm">
                                                <option value="1">DIESEL/GASOLINE/LPG</option>
                                                <option value="2">BATTERY</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class=""></div>
                                    <div class="grid grid-cols-3 items-center">
                                        <div id="label" class="">
                                            <label for="POUArrivalDate" class="block text-sm font-medium text-gray-900">Arrival Date:</label>
                                        </div>
                                        <div id="input" class="col-span-2">
                                            <div class="relative max-w-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </div>
                                                <input type="text"  datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="POUArrivalDate" id="POUArrivalDate">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="dataPOU" style="height: calc(100vh - 350px);" class="mt-2">
                                <div class="mb-4 border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="POUnit" data-tabs-toggle="#POUnitContent" role="tablist">
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="UnitDetails-tab" data-tabs-target="#UnitDetails" type="button" role="tab" aria-controls="UnitDetails" aria-selected="false">UNIT DETAILS</button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="OtherDetails-tab" data-tabs-target="#OtherDetails" type="button" role="tab" aria-controls="OtherDetails" aria-selected="false">OTHER DETAILS</button>
                                        </li>
                                        <li class="mr-2" role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="BatteryDetails-tab" data-tabs-target="#BatteryDetails" type="button" role="tab" aria-controls="BatteryDetails" aria-selected="false">BATTERY DETAILS</button>
                                        </li>
                                        <li role="presentation">
                                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="ChargerDetails-tab" data-tabs-target="#ChargerDetails" type="button" role="tab" aria-controls="ChargerDetails" aria-selected="false">CHARGER DETAILS</button>
                                        </li>
                                    </ul>
                                </div>
                                <div id="POUnitContent">
                                    {{-- UNIT DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="UnitDetails" role="tabpanel" aria-labelledby="UnitDetails-tab">
                                        <div class="grid grid-cols-7 items-center">
                                            <div id="label" class="">
                                                <label for="POUBrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <select name="POUBrand" id="POUBrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected disabled></option>
                                                    @foreach ($brand as $brands)
                                                    <option value="{{$brands->id}}">{{$brands->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="">
                                                <label for="POUClassification" class="block text-sm font-medium text-gray-900">Classification:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <select name="POUClassification" id="POUClassification" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected disabled></option>
                                                    <option value="1">CLASS A</option>
                                                    <option value="2">CLASS B</option>
                                                    <option value="3">CLASS C</option>
                                                    <option value="4">CLASS D</option>
                                                </select>
                                            </div>
                                            <div id="label" class="mt-2">
                                                <label for="POUModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="POUModel" id="POUModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="POUSerialNum" id="POUSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCode" class="block text-sm font-medium text-gray-900">Code:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="POUCode" id="POUCode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUMastType" class="block text-sm font-medium text-gray-900">Mast Type:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="POUMastType" id="POUMastType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div id="label" class="mt-2">
                                                <label for="POUForkSize" class="block text-sm font-medium text-gray-900">Fork Size:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="POUForkSize" id="POUForkSize" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class=""></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUMastHeight" class="block text-sm font-medium text-gray-900">Mast Height:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mt-2 mr-1">
                                                <input type="text" name="POUMastHeight" id="POUMastHeight" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-3 mt-2">
                                                <input type="checkbox" id="POUwAttachment" name="POUwAttachment" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="POUwAttachment" class="ml-2 text-sm font-medium text-gray-900">Unit with Attachment</label>
                                            </div>
                                            <div class=""></div>
                                            <div class="col-span-3 mt-2">
                                                <input type="checkbox" id="POUwAccesories" name="POUwAccesories" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="POUwAccesories" class="ml-2 text-sm font-medium text-gray-900">Unit with Accesories</label>
                                            </div>
                                            <div id="PAttachment" class="col-span-3 grid grid-cols-3 mt-2 items-center justify-self-center">
                                                <div class="col-span-3">
                                                    <label for="POUAttachment" class="block text-sm font-medium text-gray-900">Attachment:</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <label for="POUAttType" class="block text-sm font-medium text-gray-900">Type:</label>
                                                </div>
                                                <div class="">
                                                    <input type="text" name="POUAttType" id="POUAttType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div class="mt-1"></div>
                                                <div class="mt-1">
                                                    <label for="POUAttModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                                </div>
                                                <div class="mt-1">
                                                    <input type="text" name="POUAttModel" id="POUAttModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div class="mt-1"></div>
                                                <div class="mt-1">
                                                    <label for="POUAttSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div class="mt-1">
                                                    <input type="text" name="POUAttSerialNum" id="POUAttSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                            <div class=""></div>
                                            <div id="PAccesories" class="col-span-3 grid grid-cols-3 mt-2 items-start">
                                                <div class="col-span-3">
                                                    <label for="POUAccesories" class="block text-sm font-medium text-gray-900">Accesories:</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <input type="checkbox" id="POUAccISite" name="POUAccISite" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccISite" class="ml-2 text-sm font-medium text-gray-900">I-Site</label>
                                                </div>
                                                <div class="">
                                                    <input type="checkbox" id="POUAccLiftCam" name="POUAccLiftCam" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccLiftCam" class="ml-2 text-sm font-medium text-gray-900">Lift Cam</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <input type="checkbox" id="POUAccRedLight" name="POUAccRedLight" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccRedLight" class="ml-2 text-sm font-medium text-gray-900">Red Light</label>
                                                </div>
                                                <div class="">
                                                    <input type="checkbox" id="POUAccBlueLight" name="POUAccBlueLight" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccBlueLight" class="ml-2 text-sm font-medium text-gray-900">Blue Light</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="">
                                                    <input type="checkbox" id="POUAccFireExt" name="POUAccFireExt" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccFireExt" class="ml-2 text-xs font-medium text-gray-900">Fire Extinguisher</label>
                                                </div>
                                                <div class="">
                                                    <input type="checkbox" id="POUAccStLight" name="POUAccStLight" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccStLight" class="ml-2 text-xs font-medium text-gray-900">Strobe Light</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="col-span-2">
                                                    <input type="checkbox" id="POUAccOthers" name="POUAccOthers" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                    <label for="POUAccOthers" class="ml-2 text-sm font-medium text-gray-900">Others</label>
                                                </div>
                                                <div class=""></div>
                                                <div class="col-span-2 mt-1 relative">
                                                    <input type="text" id="POUAccOthersDetail" name="POUAccOthersDetail" class="block rounded-t-lg w-full text-sm text-gray-900 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Other Accesories" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- OTHER DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="OtherDetails" role="tabpanel" aria-labelledby="OtherDetails-tab">
                                        <div class="grid grid-cols-7 items-center">
                                            <div id="label" class="">
                                                <label for="POUTechnician1" class="block text-sm font-medium text-gray-900">Technician 1:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <select name="POUTechnician1" id="POUTechnician1" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected disabled></option>
                                                    @foreach ($technician as $technicians)
                                                    <option value="{{$technicians->id}}">{{$technicians->initials}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUTechnician2" class="block text-sm font-medium text-gray-900">Technician 2:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <select name="POUTechnician2" id="POUTechnician2" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                    <option value="" selected></option>
                                                    @foreach ($technician as $technicians)
                                                    <option value="{{$technicians->id}}">{{$technicians->initials}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-5">
                                                <label for="POUSalesman" class="block text-sm font-medium text-gray-900">Salesman:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-5">
                                                <input type="text" name="POUSalesman" id="POUSalesman" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-5">
                                                <label for="POUCustomer" class="block text-sm font-medium text-gray-900">Customer:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-5">
                                                <input type="text" name="POUCustomer" id="POUCustomer" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCustAddress" class="block text-sm font-medium text-gray-900">Customer Address:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCustAddress" id="POUCustAddress" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                        </div>
                                    </div>
                                    {{-- BATTERY DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="BatteryDetails" role="tabpanel" aria-labelledby="BatteryDetails-tab">
                                        <div class="grid grid-cols-3 items-center">
                                            <div class="col-span-1 mt-1">
                                                <label for="POUBatAttached" class="ml-2 text-sm font-medium text-gray-900">Battery Attached</label>
                                            </div>
                                            <div class="col-span-1 mt-1">
                                                <input type="checkbox" id="POUwBatSpare1" name="POUwBatSpare1" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="POUwBatSpare1" class="ml-2 text-sm font-medium text-gray-900">Spare Battery 1</label>
                                            </div>
                                            <div class="col-span-1 mt-1">
                                                <input type="checkbox" id="POUwBatSpare2" name="POUwBatSpare2" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                                <label for="POUwBatSpare2" class="ml-2 text-sm font-medium text-gray-900">Spare Battery 2</label>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-3">
                                            <div id="BatAttached" class="grid grid-cols-3 mr-2 mt-1 items-center">
                                                <div id="label" class="">
                                                    <label for="POUBABrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                </div>
                                                <div id="input" class="col-span-2">
                                                    <input type="text" name="POUBABrand" id="POUBABrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBABatType" class="block text-xs font-medium text-gray-900">Battery Type:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBABatType" id="POUBABatType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBASerialNum" class="block text-xs font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBASerialNum" id="POUBASerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBACode" class="block text-sm font-medium text-gray-900">Code:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBACode" id="POUBACode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBAAmper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBAAmper" id="POUBAAmper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBAVolt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBAVolt" id="POUBAVolt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBACCable" class="block text-xs font-medium text-gray-900">Change Cable:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBACCable" id="POUBACCable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUBACTable" class="block text-xs font-medium text-gray-900">Change Table:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUBACTable" id="POUBACTable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                            <div id="SpareBat1" class="grid grid-cols-3 mr-2 ml-2 mt-1 items-center">
                                                <div id="label" class="">
                                                    <label for="POUSB1Brand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                </div>
                                                <div id="input" class="col-span-2">
                                                    <input type="text" name="POUSB1Brand" id="POUSB1Brand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1BatType" class="block text-xs font-medium text-gray-900">Battery Type:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1BatType" id="POUSB1BatType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1SerialNum" class="block text-xs font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1SerialNum" id="POUSB1SerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1Code" class="block text-sm font-medium text-gray-900">Code:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1Code" id="POUSB1Code" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1Amper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1Amper" id="POUSB1Amper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1Volt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1Volt" id="POUSB1Volt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1CCable" class="block text-xs font-medium text-gray-900">Change Cable:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1CCable" id="POUSB1CCable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB1CTable" class="block text-xs font-medium text-gray-900">Change Table:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB1CTable" id="POUSB1CTable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                            <div id="SpareBat2" class="grid grid-cols-3 ml-2 mt-1 items-center">
                                                <div id="label" class="">
                                                    <label for="POUSB2Brand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                                </div>
                                                <div id="input" class="col-span-2">
                                                    <input type="text" name="POUSB2Brand" id="POUSB2Brand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2BatType" class="block text-xs font-medium text-gray-900">Battery Type:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2BatType" id="POUSB2BatType" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2SerialNum" class="block text-xs font-medium text-gray-900">Serial Number:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2SerialNum" id="POUSB2SerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2Code" class="block text-sm font-medium text-gray-900">Code:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2Code" id="POUSB2Code" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2Amper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2Amper" id="POUSB2Amper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2Volt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2Volt" id="POUSB2Volt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2CCable" class="block text-xs font-medium text-gray-900">Change Cable:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2CCable" id="POUSB2CCable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                                <div id="label" class="mt-1">
                                                    <label for="POUSB2CTable" class="block text-xs font-medium text-gray-900">Change Table:</label>
                                                </div>
                                                <div id="input" class="col-span-2 mt-1">
                                                    <input type="text" name="POUSB2CTable" id="POUSB2CTable" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- CHARGER DETAILS --}}
                                    <div class="hidden p-2 rounded-lg" id="ChargerDetails" role="tabpanel" aria-labelledby="ChargerDetails-tab">
                                        <div class="grid grid-cols-7 items-center">
                                            <div id="label" class="">
                                                <label for="POUCBrand" class="block text-sm font-medium text-gray-900">Brand:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1">
                                                <input type="text" name="POUCBrand" id="POUCBrand" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCModel" class="block text-sm font-medium text-gray-900">Model:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCModel" id="POUCModel" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCSerialNum" class="block text-sm font-medium text-gray-900">Serial Number:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCSerialNum" id="POUCSerialNum" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCCode" class="block text-sm font-medium text-gray-900">Code:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCCode" id="POUCCode" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCAmper" class="block text-sm font-medium text-gray-900">Amper:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCAmper" id="POUCAmper" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCVolt" class="block text-sm font-medium text-gray-900">Volt:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCVolt" id="POUCVolt" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                            <div id="label" class="mt-2">
                                                <label for="POUCInput" class="block text-sm font-medium text-gray-900">Input/Phase:</label>
                                            </div>
                                            <div id="input" class="col-span-2 mr-1 mt-2">
                                                <input type="text" name="POUCInput" id="POUCInput" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                            </div>
                                            <div class="col-span-4"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2">
                            <div id="DUbfoot" class="">
                                <div class="grid grid-cols-7 items-center">
                                    <div id="label" class="mb-2 ml-1">
                                        <label for="POURemarks" class="block text-sm font-medium text-gray-900">Remarks:</label>
                                    </div>
                                    <div id="input" class="col-span-5 uppercase mb-2">
                                        <textarea rows="2" name="POURemarks" id="POURemarks" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b">
                        <button type="button" id="savePullOutH" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                        <button type="button" id="savePullOut" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">SAVE</button>
                        <button type="button" id="clearPullOut" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">CLEAR</button>
                        <button data-modal-hide="modalPOU" id="closedPullOut" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- DELETE MODAL FOR PULL OUT UNITS --}}
        <div id="modalDeletePOU" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</h3>
                        <button type="button" id="deleteConfirmPOU"  data-modal-hide="modalDeletePOU" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure.
                        </button>
                        <button data-modal-hide="modalDeletePOU" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel.</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- TRANSFER MODAL FOR PULL OUT UNITS --}}
        <div id="modalTransferPOU" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-2xl md:h-auto">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            TRANSFER OF PULL OUT UNIT
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="modalTransferPOU">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <form action="" id="formPOUT">
                            @csrf
                            <div class="grid grid-cols-5 items-center">
                                <div class="col-span-5 mb-2">
                                    <input type="hidden" id="POUIDx" name="POUIDx">
                                </div>
                                <div id="label" class="uppercase mb-2">
                                    <label for="POUTransferDate" class="block text-sm font-medium text-gray-900">Transfer Date:</label>
                                </div>
                                <div class="col-span-2">
                                    <div class="relative max-w-sm">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <input type="text" datepicker datepicker-autohide datepicker-format="mm/dd/yyyy" value="{{ date('m/d/Y') }}" class="border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" name="POUTransferDate" id="POUTransferDate">
                                    </div>
                                </div>
                                <div class="col-span-2"></div>
                                <div id="label" class="uppercase mt-5">
                                    <label for="POUStatus" class="block text-sm font-medium text-gray-900">Status:</label>
                                </div>
                                <div id="input" class="col-span-2 uppercase mt-5">
                                    <select name="POUStatus" id="POUStatus" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                        <option value="" selected disabled></option>
                                        <option value="1">WAITING FOR REPAIR UNIT</option>
                                        <option value="2">UNDER REPAIR UNIT</option>
                                        <option value="3">USED GOOD UNIT</option>
                                        <option value="4">SERVICE UNIT</option>
                                        <option value="5">FOR SCRAP UNIT</option>
                                        <option value="6">FOR SALE UNIT</option>
                                        <option value="7">WAITING PARTS</option>
                                        <option value="8">WAITING BACK ORDER</option>
                                        <option value="9">WAITING SPARE BATT</option>
                                        <option value="10">STOCK UNIT</option>
                                        <option value="11">WAITING FOR MCI</option>
                                        <option value="12">WAITING FOR PDI</option>
                                        <option value="13">DONE PDI (WFD)</option>
                                    </select>
                                </div>
                                <div id="input" class="col-span-2">
                                </div>
                                <div id="label" class="uppercase mt-2">
                                    <label for="POUArea" class="block text-sm font-medium text-gray-900">Area:</label>
                                </div>
                                <div id="input" class="col-span-2 uppercase mt-2">
                                    <select name="POUArea" id="POUArea" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                        <option value="" selected></option>
                                        @foreach ($section as $sections)
                                        <option value="{{$sections->id}}">{{$sections->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="input" class="col-span-2"></div>
                                <div id="label" class="uppercase mt-2">
                                    <label for="POUBay" class="block text-sm font-medium text-gray-900">Bay:</label>
                                </div>
                                <div id="input" class="col-span-2 uppercase mt-2">
                                    <select name="POUBay" id="POUBay" class="block w-full p-1.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center uppercase">
                                        <option value="" selected></option>
                                        @foreach ($bay as $bays)
                                        <option value="{{$bays->id}}">{{$bays->area_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="input" class="col-span-2"></div>
                                <div id="label" class="uppercase mt-5">
                                    <label for="POURemarksO" class="block text-sm font-medium text-gray-900">Pull Out Remarks:</label>
                                </div>
                                <div id="input" class="uppercase mt-5 col-span-2">
                                    <textarea rows="3" name="POURemarksO" id="POURemarksO" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 pointer-events-none uppercase"></textarea>
                                </div>
                                <div id="input" class="col-span-2"></div>
                                <div id="label" class="uppercase mt-5">
                                    <label for="POURemarksT" class="block text-sm font-medium text-gray-900">Transfer Remarks:</label>
                                </div>
                                <div id="input" class="uppercase mt-5 col-span-2">
                                    <textarea rows="3" name="POURemarksT" id="POURemarksT" class="block w-full p-1 text-gray-900 border border-gray-300 rounded-lg sm:text-sm focus:ring-blue-500 focus:border-blue-500 uppercase"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                        <button type="button" id="transferPOU" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">TRANSFER</button>
                        <button data-modal-hide="modalTransferPOU" type="button" id="closeTransfer" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- DELETE MODAL FOR CONFIRM UNITS --}}
        <div id="modalDeleteCU" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</h3>
                        <button type="button" id="deleteConfirmCU" data-modal-hide="modalDeleteCU" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure.
                        </button>
                        <button data-modal-hide="modalDeleteCU" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel.</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- DELETE MODAL FOR DELIVERED UNITS --}}
        <div id="modalDeleteDU" data-modal-backdrop="static" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
            <div class="relative w-full h-full max-w-md md:h-auto">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="p-6 text-center">
                        <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</h3>
                        <button type="button" id="deleteConfirmDU" data-modal-hide="modalDeleteDU" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Yes, I'm sure.
                        </button>
                        <button data-modal-hide="modalDeleteDU" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">No, cancel.</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- SUCCESS MODAL --}}
        <div id="success-modal" class="fixed items-center top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="bg-green-200 rounded-lg shadow-xl border border-gray-200 w-80 mx-auto p-4">
                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-12 w-12">
                        <circle cx="12" cy="12" r="11" fill="#4CAF50"/>
                        <path fill="#FFFFFF" d="M9.25 15.25L5.75 11.75L4.75 12.75L9.25 17.25L19.25 7.25L18.25 6.25L9.25 15.25Z"/>
                        </svg>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Success!</h3>
                    <p class="text-sm text-gray-500">Your changes have been saved.</p>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button id="SCloseButton" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm" data-modal-hide="success-modal">Close</button>
                </div>
            </div>
        </div>
        {{-- FAILED MODAL --}}
        <div id="failed-modal" class="fixed items-center top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="bg-red-200 rounded-lg shadow-lg w-80 mx-auto p-4">
              <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-12 w-12">
                    <circle cx="12" cy="12" r="10" fill="#f44336"/>
                    <path d="M8.46 8.46L15.54 15.54M8.46 15.54L15.54 8.46" stroke="#fff" stroke-width="2"/>
                </svg>
              </div>
              <div class="mt-4 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Failed!</h3>
                <p class="text-xs text-gray-900">Your changes could not be saved. Please try again.</p>
              </div>
              <div class="mt-5 sm:mt-6">
                <button id="FCloseButton" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm" data-modal-hide="failed-modal">Close</button>
              </div>
            </div>
        </div>

    <script>
        $(document).ready(function () {
            // 
                jQuery(document).on( "click", "#SCloseButton", function(){
                    $("#success-modal").removeClass("flex");
                    $("#success-modal").addClass("hidden");
                });

            // 
                jQuery(document).on( "click", "#FCloseButton", function(){
                    $("#failed-modal").removeClass("flex");
                    $("#failed-modal").addClass("hidden");
                });

            //
                jQuery(document).on( "click", "#closedPullOut", function(){
                    $('input, select, textarea, checkbox, radio').prop('disabled', false);
                    $("#buttonCloseP").click();
                });
                
            // Clear Form on Add
                jQuery(document).on( "click", "#addPOUnit", function(){
                    if($('#POUIDe').val() != ''){
                        document.getElementById('formPOU').reset()
                        $('#POUIDe').val('');

                        $("#POUnit li:first-child button").click();
                        $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                        $('#PAttachment').addClass("disabled");
                        $('#PAccesories').addClass("disabled");
                        $('#POUAccOthersDetail').prop('disabled', true);
                        $('#POUTechnician1 option').prop('disabled', false);
                        $('#POUTechnician2 option').prop('disabled', false);
                        $('#SpareBat1').addClass("disabled");
                        $('#SpareBat2').addClass("disabled");

                        
                        $('input, select, textarea, checkbox').prop('disabled', false);
                        $("#savePullOut").show();
                        $("#clearPullOut").show();
                        var newHeading = "PULL OUT UNIT";
                        document.getElementById("htitle").innerHTML = newHeading;
                    }
                });

            // Automatic Hide of Battery and Charger Details
                if ($('#POUUnitType').val() == 1) {
                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                }else{
                    $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                }

            // Unit Type Option
                $("#POUUnitType").on("change", function() {
                    if ($('#POUUnitType').val() == 1) {
                        $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                    }else{
                        $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                    }

                    // Reset the tabs to the first one
                    $("#POUnit li:first-child button").click();
                });

            // Enable/Disable Attachment
                $('#PAttachment').addClass("disabled");
                $('#POUwAttachment').change(function() {
                    if($(this).is(":checked")) {
                        $('#PAttachment').removeClass("disabled");
                    } else {
                        $('#PAttachment').addClass("disabled");
                    }
                });
                
            // Enable/Disable Accesories
                $('#PAccesories').addClass("disabled");
                $('#POUwAccesories').change(function() {
                    if($(this).is(":checked")) {
                        $('#PAccesories').removeClass("disabled");
                    } else {
                        $('#PAccesories').addClass("disabled");
                    }
                });
            
            // Enable/Disable Others
                $('#POUAccOthersDetail').addClass("disabled");
                $("#POUAccOthers").on("change", function() {
                    if($(this).is(":checked")) {
                        $('#POUAccOthersDetail').removeClass("disabled");
                    } else {
                        $('#POUAccOthersDetail').addClass("disabled");
                    }
                });

            // Disable Value if Already Selected
                $('#POUTechnician1').change(function() {
                    var selectedValue = $(this).val();
                    $('#POUTechnician2 option').prop('disabled', false); // enable all options
                    if (selectedValue) {
                        $('#POUTechnician2 option[value="' + selectedValue + '"]').prop('disabled', true); // disable selected option
                    }
                });

                $('#POUTechnician2').change(function() {
                    var selectedValue = $(this).val();
                    $('#POUTechnician1 option').prop('disabled', false); // enable all options
                    if (selectedValue) {
                        $('#POUTechnician1 option[value="' + selectedValue + '"]').prop('disabled', true); // disable selected option
                    }
                });
            
            // Enable/Disable Spare 1 and Spare 2
                $('#SpareBat1').addClass("disabled");
                $('#POUwBatSpare1').change(function() {
                    if($(this).is(":checked")) {
                        $('#SpareBat1').removeClass("disabled");
                    } else {
                        $('#SpareBat1').addClass("disabled");
                    }
                });
                
                $('#SpareBat2').addClass("disabled");
                $('#POUwBatSpare2').change(function() {
                    if($(this).is(":checked")) {
                        $('#SpareBat2').removeClass("disabled");
                    } else {
                        $('#SpareBat2').addClass("disabled");
                    }
                });

            // Saving of Pull Out Unit
                $('#savePullOut').on( "click", function(){
                    if($('#POUUnitType').val() == 1){
                        if ($('#POUBrand').val() == '' || $('#POUClassification').val() == '' || $('#POUModel').val() == '' || $('#POUSerialNum').val() == '' || $('#POUCode').val() == '' || $('#POUMastType').val() == '' || $('#POUMastHeight').val() == '' || $('#POUForkSize').val() == '' || $('#POUTechnician1').val() == '' || $('#POURemarks').val() == '' ){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        } else {
                            $('#savePullOutH').click();
                        }
                    }else{
                        if ($('#POUBrand').val() == '' || $('#POUClassification').val() == '' || $('#POUModel').val() == '' || $('#POUSerialNum').val() == '' || $('#POUCode').val() == '' || $('#POUMastType').val() == '' || $('#POUMastHeight').val() == '' || $('#POUForkSize').val() == '' || $('#POUTechnician1').val() == '' || $('#POUBABrand').val() == '' || $('#POUBABatType').val() == '' || $('#POUBASerialNum').val() == '' || $('#POUBACode').val() == '' || $('#POUBAAmper').val() == '' || $('#POUBAVolt').val() == '' || $('#POUCBrand').val() == '' || $('#POUCModel').val() == '' || $('#POUCSerialNum').val() == '' || $('#POUCCode').val() == '' || $('#POUCAmper').val() == '' || $('#POUCVolt').val() == '' || $('#POUCInput').val() == '' || $('#POURemarks').val() == '' ){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        } else {
                            $('#savePullOutH').click();
                        }
                    }
                });

                $('#savePullOutH').on( "click", function(){
                    $.ajax({
                        url: "{{ route('r-workshop.report.savePullOut') }}",
                        type: "POST",
                        data: $("#formPOU").serialize(),
                        success: function(result) {
                            $("#POUnit li:first-child button").click();
                            $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                            $('#PAttachment').addClass("disabled");
                            $('#PAccesories').addClass("disabled");
                            $('#POUAccOthersDetail').prop('disabled', true);
                            $('#POUTechnician1 option').prop('disabled', false);
                            $('#POUTechnician2 option').prop('disabled', false);
                            $('#SpareBat1').addClass("disabled");
                            $('#SpareBat2').addClass("disabled");
                            document.getElementById('formPOU').reset()
                            $('#tableBPOU').html(result);
                            $('#tableBCU').load(location.href + ' #tableBCU>*','')
                            $('#tableBDU').load(location.href + ' #tableBDU>*','')
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            $("#closedPullOut").click();
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });

            // Clear Form Pull Out Unit
                $('#clearPullOut').on( "click", function(){
                    document.getElementById('formPOU').reset()
                    $('#POUIDe').val('');

                    $("#POUnit li:first-child button").click();
                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                    $('#PAttachment').addClass("disabled");
                    $('#PAccesories').addClass("disabled");
                    $('#POUAccOthersDetail').prop('disabled', true);
                    $('#POUTechnician1 option').prop('disabled', false);
                    $('#POUTechnician2 option').prop('disabled', false);
                    $('#SpareBat1').addClass("disabled");
                    $('#SpareBat2').addClass("disabled");
                });

            // View POU
                jQuery(document).on( "click", ".btnPOUView", function(){
                    $("#POUnit li:first-child button").click();
                    var id = $(this).data('id');
                    var utype = $(this).data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getPOUData') }}",
                        method:"GET",
                        dataType: 'json',
                        data:{id: id, utype: utype, _token: _token,},
                        success:function(result){
                            $('#POUIDe').val(result.POUnitIDx);
                                if(result.POUUnitType == 1) {
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                                }else{
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                                }
                            $('#POUArrivalDate').val(result.POUArrivalDate);
                            $('#POUBrand').val(result.POUBrand);
                            $('#POUClassification').val(result.POUClassification);
                            $('#POUModel').val(result.POUModel);
                            $('#POUSerialNum').val(result.POUSerialNum);
                            $('#POUCode').val(result.POUCode);
                            $('#POUMastType').val(result.POUMastType);
                            $('#POUMastHeight').val(result.POUMastHeight);
                            $('#POUForkSize').val(result.POUForkSize);
                                if(result.POUwAttachment == 1) {
                                    $('#POUwAttachment').prop('checked', true);
                                    $('#PAttachment').removeClass("disabled");
                                }else{
                                    $('#POUwAttachment').prop('checked', false);
                                    $('#PAttachment').addClass("disabled");
                                }
                            $('#POUAttType').val(result.POUAttType);
                            $('#POUAttModel').val(result.POUAttModel);
                            $('#POUAttSerialNum').val(result.POUAttSerialNum);
                                if(result.POUwAccesories == 1) {
                                    $('#POUwAccesories').prop('checked', true);
                                    $('#PAccesories').removeClass("disabled");
                                }else{
                                    $('#POUwAccesories').prop('checked', false);
                                    $('#PAccesories').addClass("disabled");
                                }
                                
                                if(result.POUAccISite == 1) {
                                    $('#POUAccISite').prop('checked', true);
                                }else{
                                    $('#POUAccISite').prop('checked', false);
                                }
                                
                                if(result.POUAccLiftCam == 1) {
                                    $('#POUAccLiftCam').prop('checked', true);
                                }else{
                                    $('#POUAccLiftCam').prop('checked', false);
                                }
                                
                                if(result.POUAccRedLight == 1) {
                                    $('#POUAccRedLight').prop('checked', true);
                                }else{
                                    $('#POUAccRedLight').prop('checked', false);
                                }
                                
                                if(result.POUAccBlueLight == 1) {
                                    $('#POUAccBlueLight').prop('checked', true);
                                }else{
                                    $('#POUAccBlueLight').prop('checked', false);
                                }
                                
                                if(result.POUAccFireExt == 1) {
                                    $('#POUAccFireExt').prop('checked', true);
                                }else{
                                    $('#POUAccFireExt').prop('checked', false);
                                }
                                
                                if(result.POUAccOthers == 1) {
                                    $('#POUAccOthers').prop('checked', true);
                                    $('#POUAccOthersDetail').prop('disabled',false);
                                }else{
                                    $('#POUAccOthers').prop('checked', false);
                                    $('#POUAccOthersDetail').prop('disabled',true);
                                }
                            $('#POUAccOthersDetail').val(result.POUAccOthersDetail);
                            $('#POUTechnician1').val(result.POUTechnician1);
                                var selectedValue1 = result.POUTechnician1;
                                $('#POUTechnician2 option').prop('disabled', false); // enable all options
                                if (selectedValue1) {
                                    $('#POUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUTechnician2').val(result.POUTechnician2);
                                var selectedValue2 = result.POUTechnician2;
                                $('#POUTechnician1 option').prop('disabled', false); // enable all options
                                if (selectedValue2) {
                                    $('#POUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUSalesman').val(result.POUSalesman);
                            $('#POUCustomer').val(result.POUCustomer);
                            $('#POUCustAddress').val(result.POUCustAddress);
                            $('#POUBABrand').val(result.POUBABrand);
                            $('#POUBABatType').val(result.POUBABatType);
                            $('#POUBASerialNum').val(result.POUBASerialNum);
                            $('#POUBACode').val(result.POUBACode);
                            $('#POUBAAmper').val(result.POUBAAmper);
                            $('#POUBAVolt').val(result.POUBAVolt);
                            $('#POUBACCable').val(result.POUBACCable);
                            $('#POUBACTable').val(result.POUBACTable);
                                if(result.POUwSpareBat1 == 1) {
                                    $('#POUwBatSpare1').prop('checked', true);
                                    $('#SpareBat1').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare1').prop('checked', false);
                                    $('#SpareBat1').addClass("disabled");
                                }
                            $('#POUSB1Brand').val(result.POUSB1Brand);
                            $('#POUSB1BatType').val(result.POUSB1BatType);
                            $('#POUSB1SerialNum').val(result.POUSB1SerialNum);
                            $('#POUSB1Code').val(result.POUSB1Code);
                            $('#POUSB1Amper').val(result.POUSB1Amper);
                            $('#POUSB1Volt').val(result.POUSB1Volt);
                            $('#POUSB1CCable').val(result.POUSB1CCable);
                            $('#POUSB1CTable').val(result.POUSB1CTable);
                                if(result.POUwSpareBat2 == 1) {
                                    $('#POUwBatSpare2').prop('checked', true);
                                    $('#SpareBat2').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare2').prop('checked', false);
                                    $('#SpareBat2').addClass("disabled");
                                }
                            $('#POUSB2Brand').val(result.POUSB2Brand);
                            $('#POUSB2BatType').val(result.POUSB2BatType);
                            $('#POUSB2SerialNum').val(result.POUSB2SerialNum);
                            $('#POUSB2Code').val(result.POUSB2Code);
                            $('#POUSB2Amper').val(result.POUSB2Amper);
                            $('#POUSB2Volt').val(result.POUSB2Volt);
                            $('#POUSB2CCable').val(result.POUSB2CCable);
                            $('#POUSB2CTable').val(result.POUSB2CTable);
                            $('#POUCBrand').val(result.POUCBrand);
                            $('#POUCModel').val(result.POUCModel);
                            $('#POUCSerialNum').val(result.POUCSerialNum);
                            $('#POUCCode').val(result.POUCCode);
                            $('#POUCAmper').val(result.POUCAmper);
                            $('#POUCVolt').val(result.POUCVolt);
                            $('#POUCInput').val(result.POUCInput);
                            $('#POURemarks').val(result.POURemarks);

                            $("#btnPOUViewH").click();
                            $('input, select, textarea, checkbox').prop('disabled', true);
                            $("#savePullOut").hide();
                            $("#clearPullOut").hide();
                        }
                    });
                });
            
            // Edit POU
                jQuery(document).on( "click", ".btnPOUEdit", function(){
                    $("#POUnit li:first-child button").click();
                    var id = $(this).data('id');
                    var utype = $(this).data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getPOUData') }}",
                        method:"GET",
                        dataType: 'json',
                        data:{id: id, utype: utype, _token: _token,},
                        success:function(result){
                            $('#POUIDe').val(id);
                                if(result.POUUnitType == 1) {
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                                }else{
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                                }
                            $('#POUArrivalDate').val(result.POUArrivalDate);
                            $('#POUBrand').val(result.POUBrand);
                            $('#POUClassification').val(result.POUClassification);
                            $('#POUModel').val(result.POUModel);
                            $('#POUSerialNum').val(result.POUSerialNum);
                            $('#POUCode').val(result.POUCode);
                            $('#POUMastType').val(result.POUMastType);
                            $('#POUMastHeight').val(result.POUMastHeight);
                            $('#POUForkSize').val(result.POUForkSize);
                                if(result.POUwAttachment == 1) {
                                    $('#POUwAttachment').prop('checked', true);
                                    $('#PAttachment').removeClass("disabled");
                                }else{
                                    $('#POUwAttachment').prop('checked', false);
                                    $('#PAttachment').addClass("disabled");
                                }
                            $('#POUAttType').val(result.POUAttType);
                            $('#POUAttModel').val(result.POUAttModel);
                            $('#POUAttSerialNum').val(result.POUAttSerialNum);
                                if(result.POUwAccesories == 1) {
                                    $('#POUwAccesories').prop('checked', true);
                                    $('#PAccesories').removeClass("disabled");
                                }else{
                                    $('#POUwAccesories').prop('checked', false);
                                    $('#PAccesories').addClass("disabled");
                                }
                                
                                if(result.POUAccISite == 1) {
                                    $('#POUAccISite').prop('checked', true);
                                }else{
                                    $('#POUAccISite').prop('checked', false);
                                }
                                
                                if(result.POUAccLiftCam == 1) {
                                    $('#POUAccLiftCam').prop('checked', true);
                                }else{
                                    $('#POUAccLiftCam').prop('checked', false);
                                }
                                
                                if(result.POUAccRedLight == 1) {
                                    $('#POUAccRedLight').prop('checked', true);
                                }else{
                                    $('#POUAccRedLight').prop('checked', false);
                                }
                                
                                if(result.POUAccBlueLight == 1) {
                                    $('#POUAccBlueLight').prop('checked', true);
                                }else{
                                    $('#POUAccBlueLight').prop('checked', false);
                                }
                                
                                if(result.POUAccFireExt == 1) {
                                    $('#POUAccFireExt').prop('checked', true);
                                }else{
                                    $('#POUAccFireExt').prop('checked', false);
                                }
                                
                                if(result.POUAccStLight == 1) {
                                    $('#POUAccStLight').prop('checked', true);
                                }else{
                                    $('#POUAccStLight').prop('checked', false);
                                }
                                
                                if(result.POUAccOthers == 1) {
                                    $('#POUAccOthers').prop('checked', true);
                                    $('#POUAccOthersDetail').removeClass('disabled');
                                }else{
                                    $('#POUAccOthers').prop('checked', false);
                                    $('#POUAccOthersDetail').addClass('disabled');
                                }
                            $('#POUAccOthersDetail').val(result.POUAccOthersDetail);
                            $('#POUTechnician1').val(result.POUTechnician1);
                                var selectedValue1 = result.POUTechnician1;
                                $('#POUTechnician2 option').prop('disabled', false); // enable all options
                                if (selectedValue1) {
                                    $('#POUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUTechnician2').val(result.POUTechnician2);
                                var selectedValue2 = result.POUTechnician2;
                                $('#POUTechnician1 option').prop('disabled', false); // enable all options
                                if (selectedValue2) {
                                    $('#POUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUSalesman').val(result.POUSalesman);
                            $('#POUCustomer').val(result.POUCustomer);
                            $('#POUCustAddress').val(result.POUCustAddress);
                            $('#POUBABrand').val(result.POUBABrand);
                            $('#POUBABatType').val(result.POUBABatType);
                            $('#POUBASerialNum').val(result.POUBASerialNum);
                            $('#POUBACode').val(result.POUBACode);
                            $('#POUBAAmper').val(result.POUBAAmper);
                            $('#POUBAVolt').val(result.POUBAVolt);
                            $('#POUBACCable').val(result.POUBACCable);
                            $('#POUBACTable').val(result.POUBACTable);
                                if(result.POUwSpareBat1 == 1) {
                                    $('#POUwBatSpare1').prop('checked', true);
                                    $('#SpareBat1').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare1').prop('checked', false);
                                    $('#SpareBat1').addClass("disabled");
                                }
                            $('#POUSB1Brand').val(result.POUSB1Brand);
                            $('#POUSB1BatType').val(result.POUSB1BatType);
                            $('#POUSB1SerialNum').val(result.POUSB1SerialNum);
                            $('#POUSB1Code').val(result.POUSB1Code);
                            $('#POUSB1Amper').val(result.POUSB1Amper);
                            $('#POUSB1Volt').val(result.POUSB1Volt);
                            $('#POUSB1CCable').val(result.POUSB1CCable);
                            $('#POUSB1CTable').val(result.POUSB1CTable);
                                if(result.POUwSpareBat2 == 1) {
                                    $('#POUwBatSpare2').prop('checked', true);
                                    $('#SpareBat2').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare2').prop('checked', false);
                                    $('#SpareBat2').addClass("disabled");
                                }
                            $('#POUSB2Brand').val(result.POUSB2Brand);
                            $('#POUSB2BatType').val(result.POUSB2BatType);
                            $('#POUSB2SerialNum').val(result.POUSB2SerialNum);
                            $('#POUSB2Code').val(result.POUSB2Code);
                            $('#POUSB2Amper').val(result.POUSB2Amper);
                            $('#POUSB2Volt').val(result.POUSB2Volt);
                            $('#POUSB2CCable').val(result.POUSB2CCable);
                            $('#POUSB2CTable').val(result.POUSB2CTable);
                            $('#POUCBrand').val(result.POUCBrand);
                            $('#POUCModel').val(result.POUCModel);
                            $('#POUCSerialNum').val(result.POUCSerialNum);
                            $('#POUCCode').val(result.POUCCode);
                            $('#POUCAmper').val(result.POUCAmper);
                            $('#POUCVolt').val(result.POUCVolt);
                            $('#POUCInput').val(result.POUCInput);
                            $('#POURemarks').val(result.POURemarks);

                            $("#btnPOUEditH").click();
                            $('input, select, textarea, checkbox').prop('disabled', false);
                            $("#savePullOut").show();
                            $("#clearPullOut").show();
                        }
                    });
                });
                
            // Delete POU
                jQuery(document).on( "click", ".btnPOUDelete", function(){
                    var id = $(this).data('id');
                    var unittype = $(this).data('unittype');

                    $('#btnPOUDeleteH').click();
                    $('#deleteConfirmPOU').data('id', id);
                    $('#deleteConfirmPOU').data('unittype', unittype);
                });

                jQuery(document).on( "click", "#deleteConfirmPOU", function(){
                    var id = $('#deleteConfirmPOU').data('id');
                    var unittype = $('#deleteConfirmPOU').data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.deletePOU') }}",
                        method:"POST",
                        data:{id: id, unittype: unittype, _token: _token,},
                        success:function(result){
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                            $('#tableBPOU').html(result);
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // If Area Change, Data from Bay also changes
                jQuery(document).on( "change", "#POUArea", function(){
                    var area = $(this).val();
                    var _token = $('input[name="_token"]').val();
                    // alert(area);

                    $.ajax({
                        url:"{{ route('r-workshop.report.getBay') }}",
                        method:"GET",
                        data:{area: area, _token: _token,},
                        success:function(result){
                            $('#POUBay').html(result);
                        }
                    });
                });
                
            // Clear Form if Button Click Transfer Unit
                jQuery(document).on( "click", "#btnPOUTransfer", function(){
                    $("#formPOUT").trigger('reset');
                    if($('#POUArea').val() == ''){
                        var area = $('#POUArea').val();
                        var _token = $('input[name="_token"]').val();

                        $.ajax({
                            url:"{{ route('r-workshop.report.getBay') }}",
                            method:"GET",
                            data:{area: area, _token: _token,},
                            success:function(result){
                                $('#POUBay').html(result);
                            }
                        });
                    }

                    var id = $(this).data('id');
                    var poremarks = $(this).data('poremarks');

                    $('#btnPOUTransferH').click();
                    $('#transferPOU').data('id',id);
                    $('#POUIDx').val(id);
                    $('#POURemarksO').val(poremarks);
                });

            // Transfer POU
                jQuery(document).on( "click", "#transferPOU", function(){
                    
                    $.ajax({
                        url:"{{ route('r-workshop.report.transferPullOut') }}",
                        method:"POST",
                        data: $("#formPOUT").serialize(),
                        success:function(result){
                            $('#tableBPOU').html(result);
                            $('#tableBCU').load(location.href + ' #tableBCU>*','');
                            $('#tableBWS').load(location.href + ' #tableBWS>*','');
                            $("#closeTransfer").click();
                            $("#btnSuccessH").click();
                        },
                        error: function(error){
                            $("#btnFailedH").click();
                        }
                    });
                });
            
            // Search Pull Out Unit
                $("#PTableSearch").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#tableBPOU tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });

                    $('#PUnitClassification').val('');
                });

            // Filter by Classification Pull Out Unit
                $("#PUnitClassification").on("change", function() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("PUnitClassification");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("tableBPOU");
                    tr = table.getElementsByTagName("tr");

                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[9];
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                    
                    $('#PTableSearch').val('');
                });

            // Edit CU
                jQuery(document).on( "click", ".btnCUEdit", function(){
                    $("#POUnit li:first-child button").click();
                    var id = $(this).data('id');
                    var utype = $(this).data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getPOUData') }}",
                        method:"GET",
                        dataType: 'json',
                        data:{id: id, utype: utype, _token: _token,},
                        success:function(result){
                            $('#POUIDe').val(result.POUnitIDx);
                                if(result.POUUnitType == 1) {
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                                }else{
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                                }
                            $('#POUArrivalDate').val(result.POUArrivalDate);
                            $('#POUBrand').val(result.POUBrand);
                            $('#POUClassification').val(result.POUClassification);
                            $('#POUModel').val(result.POUModel);
                            $('#POUSerialNum').val(result.POUSerialNum);
                            $('#POUCode').val(result.POUCode);
                            $('#POUMastType').val(result.POUMastType);
                            $('#POUMastHeight').val(result.POUMastHeight);
                            $('#POUForkSize').val(result.POUForkSize);
                                if(result.POUwAttachment == 1) {
                                    $('#POUwAttachment').prop('checked', true);
                                    $('#PAttachment').removeClass("disabled");
                                }else{
                                    $('#POUwAttachment').prop('checked', false);
                                    $('#PAttachment').addClass("disabled");
                                }
                            $('#POUAttType').val(result.POUAttType);
                            $('#POUAttModel').val(result.POUAttModel);
                            $('#POUAttSerialNum').val(result.POUAttSerialNum);
                                if(result.POUwAccesories == 1) {
                                    $('#POUwAccesories').prop('checked', true);
                                    $('#PAccesories').removeClass("disabled");
                                }else{
                                    $('#POUwAccesories').prop('checked', false);
                                    $('#PAccesories').addClass("disabled");
                                }
                                
                                if(result.POUAccISite == 1) {
                                    $('#POUAccISite').prop('checked', true);
                                }else{
                                    $('#POUAccISite').prop('checked', false);
                                }
                                
                                if(result.POUAccLiftCam == 1) {
                                    $('#POUAccLiftCam').prop('checked', true);
                                }else{
                                    $('#POUAccLiftCam').prop('checked', false);
                                }
                                
                                if(result.POUAccRedLight == 1) {
                                    $('#POUAccRedLight').prop('checked', true);
                                }else{
                                    $('#POUAccRedLight').prop('checked', false);
                                }
                                
                                if(result.POUAccBlueLight == 1) {
                                    $('#POUAccBlueLight').prop('checked', true);
                                }else{
                                    $('#POUAccBlueLight').prop('checked', false);
                                }
                                
                                if(result.POUAccFireExt == 1) {
                                    $('#POUAccFireExt').prop('checked', true);
                                }else{
                                    $('#POUAccFireExt').prop('checked', false);
                                }
                                
                                if(result.POUAccStLight == 1) {
                                    $('#POUAccStLight').prop('checked', true);
                                }else{
                                    $('#POUAccStLight').prop('checked', false);
                                }
                                
                                if(result.POUAccOthers == 1) {
                                    $('#POUAccOthers').prop('checked', true);
                                    $('#POUAccOthersDetail').prop('disabled',false);
                                }else{
                                    $('#POUAccOthers').prop('checked', false);
                                    $('#POUAccOthersDetail').prop('disabled',true);
                                }
                            $('#POUAccOthersDetail').val(result.POUAccOthersDetail);
                            $('#POUTechnician1').val(result.POUTechnician1);
                                var selectedValue1 = result.POUTechnician1;
                                $('#POUTechnician2 option').prop('disabled', false); // enable all options
                                if (selectedValue1) {
                                    $('#POUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUTechnician2').val(result.POUTechnician2);
                                var selectedValue2 = result.POUTechnician2;
                                $('#POUTechnician1 option').prop('disabled', false); // enable all options
                                if (selectedValue2) {
                                    $('#POUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUSalesman').val(result.POUSalesman);
                            $('#POUCustomer').val(result.POUCustomer);
                            $('#POUCustAddress').val(result.POUCustAddress);
                            $('#POUBABrand').val(result.POUBABrand);
                            $('#POUBABatType').val(result.POUBABatType);
                            $('#POUBASerialNum').val(result.POUBASerialNum);
                            $('#POUBACode').val(result.POUBACode);
                            $('#POUBAAmper').val(result.POUBAAmper);
                            $('#POUBAVolt').val(result.POUBAVolt);
                            $('#POUBACCable').val(result.POUBACCable);
                            $('#POUBACTable').val(result.POUBACTable);
                                if(result.POUwSpareBat1 == 1) {
                                    $('#POUwBatSpare1').prop('checked', true);
                                    $('#SpareBat1').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare1').prop('checked', false);
                                    $('#SpareBat1').addClass("disabled");
                                }
                            $('#POUSB1Brand').val(result.POUSB1Brand);
                            $('#POUSB1BatType').val(result.POUSB1BatType);
                            $('#POUSB1SerialNum').val(result.POUSB1SerialNum);
                            $('#POUSB1Code').val(result.POUSB1Code);
                            $('#POUSB1Amper').val(result.POUSB1Amper);
                            $('#POUSB1Volt').val(result.POUSB1Volt);
                            $('#POUSB1CCable').val(result.POUSB1CCable);
                            $('#POUSB1CTable').val(result.POUSB1CTable);
                                if(result.POUwSpareBat2 == 1) {
                                    $('#POUwBatSpare2').prop('checked', true);
                                    $('#SpareBat2').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare2').prop('checked', false);
                                    $('#SpareBat2').addClass("disabled");
                                }
                            $('#POUSB2Brand').val(result.POUSB2Brand);
                            $('#POUSB2BatType').val(result.POUSB2BatType);
                            $('#POUSB2SerialNum').val(result.POUSB2SerialNum);
                            $('#POUSB2Code').val(result.POUSB2Code);
                            $('#POUSB2Amper').val(result.POUSB2Amper);
                            $('#POUSB2Volt').val(result.POUSB2Volt);
                            $('#POUSB2CCable').val(result.POUSB2CCable);
                            $('#POUSB2CTable').val(result.POUSB2CTable);
                            $('#POUCBrand').val(result.POUCBrand);
                            $('#POUCModel').val(result.POUCModel);
                            $('#POUCSerialNum').val(result.POUCSerialNum);
                            $('#POUCCode').val(result.POUCCode);
                            $('#POUCAmper').val(result.POUCAmper);
                            $('#POUCVolt').val(result.POUCVolt);
                            $('#POUCInput').val(result.POUCInput);
                            $('#POURemarks').val(result.POURemarks);

                            $("#btnPOUEditH").click();
                            $('input, select, textarea, checkbox').prop('disabled', false);
                            $("#savePullOut").show();
                            $("#clearPullOut").show();

                            var newHeading = "EDIT UNIT";
                            document.getElementById("htitle").innerHTML = newHeading;
                        }
                    });
                });
            
            // View CU
                jQuery(document).on( "click", ".btnCUView", function(){
                    $("#POUnit li:first-child button").click();
                    var id = $(this).data('id');
                    var utype = $(this).data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getPOUData') }}",
                        method:"GET",
                        dataType: 'json',
                        data:{id: id, utype: utype, _token: _token,},
                        success:function(result){
                            $('#POUIDe').val(result.POUnitIDx);
                                if(result.POUUnitType == 1) {
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                                }else{
                                    $('#POUUnitType').val(result.POUUnitType);
                                    $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                                }
                            $('#POUArrivalDate').val(result.POUArrivalDate);
                            $('#POUBrand').val(result.POUBrand);
                            $('#POUClassification').val(result.POUClassification);
                            $('#POUModel').val(result.POUModel);
                            $('#POUSerialNum').val(result.POUSerialNum);
                            $('#POUCode').val(result.POUCode);
                            $('#POUMastType').val(result.POUMastType);
                            $('#POUMastHeight').val(result.POUMastHeight);
                            $('#POUForkSize').val(result.POUForkSize);
                                if(result.POUwAttachment == 1) {
                                    $('#POUwAttachment').prop('checked', true);
                                    $('#PAttachment').removeClass("disabled");
                                }else{
                                    $('#POUwAttachment').prop('checked', false);
                                    $('#PAttachment').addClass("disabled");
                                }
                            $('#POUAttType').val(result.POUAttType);
                            $('#POUAttModel').val(result.POUAttModel);
                            $('#POUAttSerialNum').val(result.POUAttSerialNum);
                                if(result.POUwAccesories == 1) {
                                    $('#POUwAccesories').prop('checked', true);
                                    $('#PAccesories').removeClass("disabled");
                                }else{
                                    $('#POUwAccesories').prop('checked', false);
                                    $('#PAccesories').addClass("disabled");
                                }
                                
                                if(result.POUAccISite == 1) {
                                    $('#POUAccISite').prop('checked', true);
                                }else{
                                    $('#POUAccISite').prop('checked', false);
                                }
                                
                                if(result.POUAccLiftCam == 1) {
                                    $('#POUAccLiftCam').prop('checked', true);
                                }else{
                                    $('#POUAccLiftCam').prop('checked', false);
                                }
                                
                                if(result.POUAccRedLight == 1) {
                                    $('#POUAccRedLight').prop('checked', true);
                                }else{
                                    $('#POUAccRedLight').prop('checked', false);
                                }
                                
                                if(result.POUAccBlueLight == 1) {
                                    $('#POUAccBlueLight').prop('checked', true);
                                }else{
                                    $('#POUAccBlueLight').prop('checked', false);
                                }
                                
                                if(result.POUAccFireExt == 1) {
                                    $('#POUAccFireExt').prop('checked', true);
                                }else{
                                    $('#POUAccFireExt').prop('checked', false);
                                }
                                
                                if(result.POUAccOthers == 1) {
                                    $('#POUAccOthers').prop('checked', true);
                                    $('#POUAccOthersDetail').prop('disabled',false);
                                }else{
                                    $('#POUAccOthers').prop('checked', false);
                                    $('#POUAccOthersDetail').prop('disabled',true);
                                }
                            $('#POUAccOthersDetail').val(result.POUAccOthersDetail);
                            $('#POUTechnician1').val(result.POUTechnician1);
                                var selectedValue1 = result.POUTechnician1;
                                $('#POUTechnician2 option').prop('disabled', false); // enable all options
                                if (selectedValue1) {
                                    $('#POUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUTechnician2').val(result.POUTechnician2);
                                var selectedValue2 = result.POUTechnician2;
                                $('#POUTechnician1 option').prop('disabled', false); // enable all options
                                if (selectedValue2) {
                                    $('#POUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#POUSalesman').val(result.POUSalesman);
                            $('#POUCustomer').val(result.POUCustomer);
                            $('#POUCustAddress').val(result.POUCustAddress);
                            $('#POUBABrand').val(result.POUBABrand);
                            $('#POUBABatType').val(result.POUBABatType);
                            $('#POUBASerialNum').val(result.POUBASerialNum);
                            $('#POUBACode').val(result.POUBACode);
                            $('#POUBAAmper').val(result.POUBAAmper);
                            $('#POUBAVolt').val(result.POUBAVolt);
                            $('#POUBACCable').val(result.POUBACCable);
                            $('#POUBACTable').val(result.POUBACTable);
                                if(result.POUwSpareBat1 == 1) {
                                    $('#POUwBatSpare1').prop('checked', true);
                                    $('#SpareBat1').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare1').prop('checked', false);
                                    $('#SpareBat1').addClass("disabled");
                                }
                            $('#POUSB1Brand').val(result.POUSB1Brand);
                            $('#POUSB1BatType').val(result.POUSB1BatType);
                            $('#POUSB1SerialNum').val(result.POUSB1SerialNum);
                            $('#POUSB1Code').val(result.POUSB1Code);
                            $('#POUSB1Amper').val(result.POUSB1Amper);
                            $('#POUSB1Volt').val(result.POUSB1Volt);
                            $('#POUSB1CCable').val(result.POUSB1CCable);
                            $('#POUSB1CTable').val(result.POUSB1CTable);
                                if(result.POUwSpareBat2 == 1) {
                                    $('#POUwBatSpare2').prop('checked', true);
                                    $('#SpareBat2').removeClass("disabled");
                                }else{
                                    $('#POUwBatSpare2').prop('checked', false);
                                    $('#SpareBat2').addClass("disabled");
                                }
                            $('#POUSB2Brand').val(result.POUSB2Brand);
                            $('#POUSB2BatType').val(result.POUSB2BatType);
                            $('#POUSB2SerialNum').val(result.POUSB2SerialNum);
                            $('#POUSB2Code').val(result.POUSB2Code);
                            $('#POUSB2Amper').val(result.POUSB2Amper);
                            $('#POUSB2Volt').val(result.POUSB2Volt);
                            $('#POUSB2CCable').val(result.POUSB2CCable);
                            $('#POUSB2CTable').val(result.POUSB2CTable);
                            $('#POUCBrand').val(result.POUCBrand);
                            $('#POUCModel').val(result.POUCModel);
                            $('#POUCSerialNum').val(result.POUCSerialNum);
                            $('#POUCCode').val(result.POUCCode);
                            $('#POUCAmper').val(result.POUCAmper);
                            $('#POUCVolt').val(result.POUCVolt);
                            $('#POUCInput').val(result.POUCInput);
                            $('#POURemarks').val(result.POURemarks);

                            $("#btnPOUViewH").click();
                            $('input, select, textarea, checkbox').prop('disabled', true);
                            $("#savePullOut").hide();
                            $("#clearPullOut").hide();

                            var newHeading = "VIEW UNIT";
                            document.getElementById("htitle").innerHTML = newHeading;
                        }
                    });
                });
            
            // Delete/Revert CU
                jQuery(document).on( "click", ".btnCUDelete", function(){
                    var id = $(this).data('id');
                    var cubay = $(this).data('cubay');

                    $('#btnCUDeleteH').click();
                    $('#deleteConfirmCU').data('id', id);
                    $('#deleteConfirmCU').data('cubay', cubay);
                });
                
                jQuery(document).on( "click", "#deleteConfirmCU", function(){
                    var id = $(this).data('id');
                    var cubay = $(this).data('cubay');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.deleteCU') }}",
                        method:"POST",
                        data:{id: id, cubay: cubay, _token: _token,},
                        success:function(result){
                            $('#tableBCU').html(result);
                            $('#tableBPOU').load(location.href + ' #tableBPOU>*','');
                            $('#tableBWS').load(location.href + ' #tableBWS>*','');
                            $("#success-modal").removeClass("hidden");
                            $("#success-modal").addClass("flex");
                        },
                        error: function(error){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        }
                    });
                });
            
            // Search Confirm Unit
                $("#CTableSearch").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#tableBCU tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });

                    $('#CUnitClassification').val('');
                });

            // Filter by Classification Confirm Unit
                $("#CUnitClassification").on("change", function() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("CUnitClassification");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("tableBCU");
                    tr = table.getElementsByTagName("tr");

                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[6];
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                    
                    $('#CTableSearch').val('');
                });

            // Search Workshop
                $("#WSTableSearch").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#tableBWS tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });

            // Filter by Classification Pull Out Unit
                $(".RadioBrand").on("change", function() {
                    alert('HI!');
                    // var input, filter, table, tr, td, i, txtValue;
                    // input = document.getElementById("PUnitClassification");
                    // filter = input.value.toUpperCase();
                    // table = document.getElementById("tableBPOU");
                    // tr = table.getElementsByTagName("tr");

                    // for (i = 0; i < tr.length; i++) {
                    //     td = tr[i].getElementsByTagName("td")[9];
                    //     if (td) {
                    //         txtValue = td.textContent || td.innerText;
                    //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    //             tr[i].style.display = "";
                    //         } else {
                    //             tr[i].style.display = "none";
                    //         }
                    //     }
                    // }
                    
                    // $('#PTableSearch').val('');
                });

            // Filter by Brand Workshop Unit
                $('input[name="RadioBrand"]').change(function() {
                    var unitBrand = $('input[name="RadioBrand"]:checked').val();
                    var _token = $('input[name="_token"]').val();

                    $('#WSTableSearch').val('');

                    $.ajax({
                        url: "{{ route('r-workshop.report.sortBrand') }}",
                        type: "GET",
                        data: {unitBrand: unitBrand, _token: _token},
                        success: function(result) {
                            $('#tableBWS').html(result);
                        }
                    });
                });

            // Filter by Status Pullout Unit
                $('input[name="pou-radio-unit"]').change(function() {
                    var unitStatus = $('input[name="pou-radio-unit"]:checked').val();
                    var _token = $('input[name="_token"]').val();

                    $('#PTableSearch').val('');
                    $('#PUnitClassification').val('');

                    $.ajax({
                        url: "{{ route('r-workshop.report.sortPullOut') }}",
                        type: "GET",
                        data: {unitStatus: unitStatus, _token: _token},
                        success: function(result) {
                            $('#tableBPOU').html(result);
                        }
                    });
                });
            
    // BRAND NEW UNIT
            // Close Modal Add and Edit
                jQuery(document).on( "click", "#closeNewUnit", function(){
                    $('input, select, textarea, checkbox, radio').prop('disabled', false);
                    $("#buttonCloseN").click();
                });

            // Search Brand New Unit
                $("#NTableSearch").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#tableBNewUnit tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });

            // Clear Form on Add - NEW UNIT
                jQuery(document).on( "click", "#addNewUnit", function(){
                    document.getElementById('formNewUnit').reset()
                    $('#BNUIDe').val('');

                    $("#NewUnit li:first-child button").click();
                    $("#NBatteryDetails-tab, #NChargerDetails-tab").hide();
                    $('#BAttachment').addClass("disabled");
                    $('#BAccesories').addClass("disabled");
                    $('#BNUAccOthersDetail').prop('disabled', true);
                    $('#BNUTechnician1 option').prop('disabled', false);
                    $('#BNUTechnician2 option').prop('disabled', false);
                    $('#BSpareBat1').addClass("disabled");
                    $('#BSpareBat2').addClass("disabled");

                    
                    $('input, select, textarea, checkbox').prop('disabled', false);
                    $("#saveNewUnit").show();
                    $("#clearNewUnit").show();
                });

            // Clear Form New Unit
                $('#clearNewUnit').on( "click", function(){
                    document.getElementById('formNewUnit').reset()
                    $('#BNUIDe').val('');

                    $("#NewUnit li:first-child button").click();
                    $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                    $('#BAttachment').addClass("disabled");
                    $('#BAccesories').addClass("disabled");
                    $('#BNUAccOthersDetail').prop('disabled', true);
                    $('#BNUTechnician1 option').prop('disabled', false);
                    $('#BNUTechnician2 option').prop('disabled', false);
                    $('#BSpareBat1').addClass("disabled");
                    $('#BSpareBat2').addClass("disabled");
                });

            // Automatic Hide of Battery and Charger Details
                if ($('#BNUnitType').val() == 1) {
                    $("#NBatteryDetails-tab, #NChargerDetails-tab").hide();
                }else{
                    $("#NBatteryDetails-tab, #NChargerDetails-tab").show();
                }

            // Unit Type Option
                $("#BNUnitType").on("change", function() {
                    if ($('#BNUnitType').val() == 1) {
                        $("#NBatteryDetails-tab, #NChargerDetails-tab").hide();
                    }else{
                        $("#NBatteryDetails-tab, #NChargerDetails-tab").show();
                    }

                    // Reset the tabs to the first one
                    $("#NewUnit li:first-child button").click();
                });

            // Enable/Disable Attachment
                $('#BAttachment').addClass("disabled");
                $('#BNUwAttachment').change(function() {
                    if($(this).is(":checked")) {
                        $('#BAttachment').removeClass("disabled");
                    } else {
                        $('#BAttachment').addClass("disabled");
                    }
                });
    
            // Enable/Disable Accesories
                $('#BAccesories').addClass("disabled");
                $('#BNUwAccesories').change(function() {
                    if($(this).is(":checked")) {
                        $('#BAccesories').removeClass("disabled");
                    } else {
                        $('#BAccesories').addClass("disabled");
                    }
                });

            // Enable/Disable Others
                $('#BNUAccOthersDetail').addClass('disabled');
                $("#BNUAccOthers").on("change", function() {
                    if($(this).is(":checked")) {
                        $('#BNUAccOthersDetail').removeClass('disabled');
                    } else {
                        $('#BNUAccOthersDetail').addClass('disabled');
                    }
                });

            // Disable Value if Already Selected
                $('#BNUTechnician1').change(function() {
                    var selectedValue = $(this).val();
                    $('#BNUTechnician2 option').prop('disabled', false); // enable all options
                    if (selectedValue) {
                        $('#BNUTechnician2 option[value="' + selectedValue + '"]').prop('disabled', true); // disable selected option
                    }
                });

                $('#BNUTechnician2').change(function() {
                    var selectedValue = $(this).val();
                    $('#BNUTechnician1 option').prop('disabled', false); // enable all options
                    if (selectedValue) {
                        $('#BNUTechnician1 option[value="' + selectedValue + '"]').prop('disabled', true); // disable selected option
                    }
                });

            // Enable/Disable Spare 1 and Spare 2
                $('#BSpareBat1').addClass("disabled");
                $('#BNUwBatSpare1').change(function() {
                    if($(this).is(":checked")) {
                        $('#BSpareBat1').removeClass("disabled");
                    } else {
                        $('#BSpareBat1').addClass("disabled");
                    }
                });
                
                $('#BSpareBat2').addClass("disabled");
                $('#BNUwBatSpare2').change(function() {
                    if($(this).is(":checked")) {
                        $('#BSpareBat2').removeClass("disabled");
                    } else {
                        $('#BSpareBat2').addClass("disabled");
                    }
                });

            // Saving of Brand New
                $('#saveNewUnit').on( "click", function(){
                    if($('#BNUnitType').val() == 1){
                        if ($('#BNUBrand').val() == '' || $('#BNUClassification').val() == '' || $('#BNUModel').val() == '' || $('#BNUSerialNum').val() == '' || $('#BNUCode').val() == '' || $('#BNUMastType').val() == '' || $('#BNUMastHeight').val() == '' || $('#BNUForkSize').val() == '' || $('#BNUTechnician1').val() == '' || $('#BNUCustomer').val() == '' || $('#BNUCustAddress').val() == '' || $('#BNURemarks').val() == '' ){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        } else {
                            $('#saveNewUnitH').click();
                        }
                    }else{
                        if ($('#BNUBrand').val() == '' || $('#BNUClassification').val() == '' || $('#BNUModel').val() == '' || $('#BNUSerialNum').val() == '' || $('#BNUCode').val() == '' || $('#BNUMastType').val() == '' || $('#BNUMastHeight').val() == '' || $('#BNUForkSize').val() == '' || $('#BNUTechnician1').val() == '' || $('#BNUCustomer').val() == '' || $('#BNUCustAddress').val() == '' || $('#BNUBABrand').val() == '' || $('#BNUBABatType').val() == '' || $('#BNUBASerialNum').val() == '' || $('#BNUBACode').val() == '' || $('#BNUBAAmper').val() == '' || $('#BNUBAVolt').val() == '' || $('#BNUCBrand').val() == '' || $('#BNUCModel').val() == '' || $('#BNUCSerialNum').val() == '' || $('#BNUCCode').val() == '' || $('#BNUCAmper').val() == '' || $('#BNUCVolt').val() == '' || $('#BNUCInput').val() == '' || $('#BNURemarks').val() == '' ){
                            $("#failed-modal").removeClass("hidden");
                            $("#failed-modal").addClass("flex");
                        } else {
                            $('#saveNewUnitH').click();
                        }
                    }
                });

                $('#saveNewUnitH').on( "click", function(){
                    $.ajax({
                        url: "{{ route('r-workshop.report.saveBrandNew') }}",
                        type: "POST",
                        data: $("#formNewUnit").serialize(),
                        success: function(result) {
                            $("#NewUnit li:first-child button").click();
                            $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                            $('#PAttachment').addClass("disabled");
                            $('#PAccesories').addClass("disabled");
                            $('#BNUAccOthersDetail').prop('disabled', true);
                            $('#BNUTechnician1 option').prop('disabled', false);
                            $('#BNUTechnician2 option').prop('disabled', false);
                            $('#SpareBat1').addClass("disabled");
                            $('#SpareBat2').addClass("disabled");
                            document.getElementById('formNewUnit').reset()
                            $('#tableBNewUnit').html(result);
                            $('#tableBCU').load(location.href + ' #tableBCU>*','')
                            $("#btnSuccessH").click();
                            $("#closeNewUnit").click();
                        },
                        error: function(error){
                            $("#btnFailedH").click();
                        }
                    });
                });

            // View Brand New
                jQuery(document).on( "click", ".btnBNUView", function(){
                    $("#NewUnit li:first-child button").click();
                    var id = $(this).data('id');
                    var utype = $(this).data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getBNUData') }}",
                        method:"GET",
                        dataType: 'json',
                        data:{id: id, utype: utype, _token: _token,},
                        success:function(result){
                            $('#BNUIDe').val(result.BNUnitIDx);
                                if(result.BNUnitType == 1) {
                                    $('#BNUnitType').val(result.BNUnitType);
                                    $("#NBatteryDetails-tab, #NChargerDetails-tab").hide();
                                }else{
                                    $('#BNUnitType').val(result.BNUnitType);
                                    $("#NBatteryDetails-tab, #NChargerDetails-tab").show();
                                }
                            $('#BNUArrivalDate').val(result.BNUArrivalDate);
                            $('#BNUBrand').val(result.BNUBrand);
                            $('#BNUClassification').val(result.BNUClassification);
                            $('#BNUModel').val(result.BNUModel);
                            $('#BNUSerialNum').val(result.BNUSerialNum);
                            $('#BNUCode').val(result.BNUCode);
                            $('#BNUMastType').val(result.BNUMastType);
                            $('#BNUMastHeight').val(result.BNUMastHeight);
                            $('#BNUForkSize').val(result.BNUForkSize);
                                if(result.BNUwAttachment == 1) {
                                    $('#BNUwAttachment').prop('checked', true);
                                    $('#PAttachment').removeClass("disabled");
                                }else{
                                    $('#BNUwAttachment').prop('checked', false);
                                    $('#PAttachment').addClass("disabled");
                                }
                            $('#BNUAttType').val(result.BNUAttType);
                            $('#BNUAttModel').val(result.BNUAttModel);
                            $('#BNUAttSerialNum').val(result.BNUAttSerialNum);
                                if(result.BNUwAccesories == 1) {
                                    $('#BNUwAccesories').prop('checked', true);
                                    $('#PAccesories').removeClass("disabled");
                                }else{
                                    $('#BNUwAccesories').prop('checked', false);
                                    $('#PAccesories').addClass("disabled");
                                }
                                
                                if(result.BNUAccISite == 1) {
                                    $('#BNUAccISite').prop('checked', true);
                                }else{
                                    $('#BNUAccISite').prop('checked', false);
                                }
                                
                                if(result.BNUAccLiftCam == 1) {
                                    $('#BNUAccLiftCam').prop('checked', true);
                                }else{
                                    $('#BNUAccLiftCam').prop('checked', false);
                                }
                                
                                if(result.BNUAccRedLight == 1) {
                                    $('#BNUAccRedLight').prop('checked', true);
                                }else{
                                    $('#BNUAccRedLight').prop('checked', false);
                                }
                                
                                if(result.BNUAccBlueLight == 1) {
                                    $('#BNUAccBlueLight').prop('checked', true);
                                }else{
                                    $('#BNUAccBlueLight').prop('checked', false);
                                }
                                
                                if(result.BNUAccFireExt == 1) {
                                    $('#BNUAccFireExt').prop('checked', true);
                                }else{
                                    $('#BNUAccFireExt').prop('checked', false);
                                }
                                
                                if(result.BNUAccOthers == 1) {
                                    $('#BNUAccOthers').prop('checked', true);
                                    $('#BNUAccOthersDetail').prop('disabled',false);
                                }else{
                                    $('#BNUAccOthers').prop('checked', false);
                                    $('#BNUAccOthersDetail').prop('disabled',true);
                                }
                            $('#BNUAccOthersDetail').val(result.BNUAccOthersDetail);
                            $('#BNUTechnician1').val(result.BNUTechnician1);
                                var selectedValue1 = result.BNUTechnician1;
                                $('#BNUTechnician2 option').prop('disabled', false); // enable all options
                                if (selectedValue1) {
                                    $('#BNUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#BNUTechnician2').val(result.BNUTechnician2);
                                var selectedValue2 = result.BNUTechnician2;
                                $('#BNUTechnician1 option').prop('disabled', false); // enable all options
                                if (selectedValue2) {
                                    $('#BNUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#BNUSalesman').val(result.BNUSalesman);
                            $('#BNUCustomer').val(result.BNUCustomer);
                            $('#BNUCustAddress').val(result.BNUCustAddress);
                            $('#BNUBABrand').val(result.BNUBABrand);
                            $('#BNUBABatType').val(result.BNUBABatType);
                            $('#BNUBASerialNum').val(result.BNUBASerialNum);
                            $('#BNUBACode').val(result.BNUBACode);
                            $('#BNUBAAmper').val(result.BNUBAAmper);
                            $('#BNUBAVolt').val(result.BNUBAVolt);
                            $('#BNUBACCable').val(result.BNUBACCable);
                            $('#BNUBACTable').val(result.BNUBACTable);
                                if(result.BNUwSpareBat1 == 1) {
                                    $('#BNUwBatSpare1').prop('checked', true);
                                    $('#SpareBat1').removeClass("disabled");
                                }else{
                                    $('#BNUwBatSpare1').prop('checked', false);
                                    $('#SpareBat1').addClass("disabled");
                                }
                            $('#BNUSB1Brand').val(result.BNUSB1Brand);
                            $('#BNUSB1BatType').val(result.BNUSB1BatType);
                            $('#BNUSB1SerialNum').val(result.BNUSB1SerialNum);
                            $('#BNUSB1Code').val(result.BNUSB1Code);
                            $('#BNUSB1Amper').val(result.BNUSB1Amper);
                            $('#BNUSB1Volt').val(result.BNUSB1Volt);
                            $('#BNUSB1CCable').val(result.BNUSB1CCable);
                            $('#BNUSB1CTable').val(result.BNUSB1CTable);
                                if(result.BNUwSpareBat2 == 1) {
                                    $('#BNUwBatSpare2').prop('checked', true);
                                    $('#SpareBat2').removeClass("disabled");
                                }else{
                                    $('#BNUwBatSpare2').prop('checked', false);
                                    $('#SpareBat2').addClass("disabled");
                                }
                            $('#BNUSB2Brand').val(result.BNUSB2Brand);
                            $('#BNUSB2BatType').val(result.BNUSB2BatType);
                            $('#BNUSB2SerialNum').val(result.BNUSB2SerialNum);
                            $('#BNUSB2Code').val(result.BNUSB2Code);
                            $('#BNUSB2Amper').val(result.BNUSB2Amper);
                            $('#BNUSB2Volt').val(result.BNUSB2Volt);
                            $('#BNUSB2CCable').val(result.BNUSB2CCable);
                            $('#BNUSB2CTable').val(result.BNUSB2CTable);
                            $('#BNUCBrand').val(result.BNUCBrand);
                            $('#BNUCModel').val(result.BNUCModel);
                            $('#BNUCSerialNum').val(result.BNUCSerialNum);
                            $('#BNUCCode').val(result.BNUCCode);
                            $('#BNUCAmper').val(result.BNUCAmper);
                            $('#BNUCVolt').val(result.BNUCVolt);
                            $('#BNUCInput').val(result.BNUCInput);
                            $('#BNURemarks').val(result.BNURemarks);

                            $("#btnBNUViewH").click();
                            $('input, select, textarea, checkbox').prop('disabled', true);
                            $("#saveNewUnit").hide();
                            $("#clearNewUnit").hide();
                        }
                    });
                });
            
            // Edit Brand New
                jQuery(document).on( "click", ".btnBNUEdit", function(){
                    $("#NewUnit li:first-child button").click();
                    var id = $(this).data('id');
                    var utype = $(this).data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getBNUData') }}",
                        method:"GET",
                        dataType: 'json',
                        data:{id: id, utype: utype, _token: _token,},
                        success:function(result){
                            $('#BNUIDe').val(id);
                                if(result.BNUnitType == 1) {
                                    $('#BNUnitType').val(result.BNUnitType);
                                    $("#NBatteryDetails-tab, #NChargerDetails-tab").hide();
                                }else{
                                    $('#BNUnitType').val(result.BNUnitType);
                                    $("#NBatteryDetails-tab, #NChargerDetails-tab").show();
                                }
                            $('#BNUArrivalDate').val(result.BNUArrivalDate);
                            $('#BNUBrand').val(result.BNUBrand);
                            $('#BNUClassification').val(result.BNUClassification);
                            $('#BNUModel').val(result.BNUModel);
                            $('#BNUSerialNum').val(result.BNUSerialNum);
                            $('#BNUCode').val(result.BNUCode);
                            $('#BNUMastType').val(result.BNUMastType);
                            $('#BNUMastHeight').val(result.BNUMastHeight);
                            $('#BNUForkSize').val(result.BNUForkSize);
                                if(result.BNUwAttachment == 1) {
                                    $('#BNUwAttachment').prop('checked', true);
                                    $('#PAttachment').removeClass("disabled");
                                }else{
                                    $('#BNUwAttachment').prop('checked', false);
                                    $('#PAttachment').addClass("disabled");
                                }
                            $('#BNUAttType').val(result.BNUAttType);
                            $('#BNUAttModel').val(result.BNUAttModel);
                            $('#BNUAttSerialNum').val(result.BNUAttSerialNum);
                                if(result.BNUwAccesories == 1) {
                                    $('#BNUwAccesories').prop('checked', true);
                                    $('#PAccesories').removeClass("disabled");
                                }else{
                                    $('#BNUwAccesories').prop('checked', false);
                                    $('#PAccesories').addClass("disabled");
                                }
                                
                                if(result.BNUAccISite == 1) {
                                    $('#BNUAccISite').prop('checked', true);
                                }else{
                                    $('#BNUAccISite').prop('checked', false);
                                }
                                
                                if(result.BNUAccLiftCam == 1) {
                                    $('#BNUAccLiftCam').prop('checked', true);
                                }else{
                                    $('#BNUAccLiftCam').prop('checked', false);
                                }
                                
                                if(result.BNUAccRedLight == 1) {
                                    $('#BNUAccRedLight').prop('checked', true);
                                }else{
                                    $('#BNUAccRedLight').prop('checked', false);
                                }
                                
                                if(result.BNUAccBlueLight == 1) {
                                    $('#BNUAccBlueLight').prop('checked', true);
                                }else{
                                    $('#BNUAccBlueLight').prop('checked', false);
                                }
                                
                                if(result.BNUAccFireExt == 1) {
                                    $('#BNUAccFireExt').prop('checked', true);
                                }else{
                                    $('#BNUAccFireExt').prop('checked', false);
                                }
                                
                                if(result.BNUAccStLight == 1) {
                                    $('#BNUAccStLight').prop('checked', true);
                                }else{
                                    $('#BNUAccStLight').prop('checked', false);
                                }
                                
                                if(result.BNUAccOthers == 1) {
                                    $('#BNUAccOthers').prop('checked', true);
                                    $('#BNUAccOthersDetail').prop('disabled',false);
                                }else{
                                    $('#BNUAccOthers').prop('checked', false);
                                    $('#BNUAccOthersDetail').prop('disabled',true);
                                }
                            $('#BNUAccOthersDetail').val(result.BNUAccOthersDetail);
                            $('#BNUTechnician1').val(result.BNUTechnician1);
                                var selectedValue1 = result.BNUTechnician1;
                                $('#BNUTechnician2 option').prop('disabled', false); // enable all options
                                if (selectedValue1) {
                                    $('#BNUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#BNUTechnician2').val(result.BNUTechnician2);
                                var selectedValue2 = result.BNUTechnician2;
                                $('#BNUTechnician1 option').prop('disabled', false); // enable all options
                                if (selectedValue2) {
                                    $('#BNUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                                }
                            $('#BNUSalesman').val(result.BNUSalesman);
                            $('#BNUCustomer').val(result.BNUCustomer);
                            $('#BNUCustAddress').val(result.BNUCustAddress);
                            $('#BNUBABrand').val(result.BNUBABrand);
                            $('#BNUBABatType').val(result.BNUBABatType);
                            $('#BNUBASerialNum').val(result.BNUBASerialNum);
                            $('#BNUBACode').val(result.BNUBACode);
                            $('#BNUBAAmper').val(result.BNUBAAmper);
                            $('#BNUBAVolt').val(result.BNUBAVolt);
                            $('#BNUBACCable').val(result.BNUBACCable);
                            $('#BNUBACTable').val(result.BNUBACTable);
                                if(result.BNUwSpareBat1 == 1) {
                                    $('#BNUwBatSpare1').prop('checked', true);
                                    $('#SpareBat1').removeClass("disabled");
                                }else{
                                    $('#BNUwBatSpare1').prop('checked', false);
                                    $('#SpareBat1').addClass("disabled");
                                }
                            $('#BNUSB1Brand').val(result.BNUSB1Brand);
                            $('#BNUSB1BatType').val(result.BNUSB1BatType);
                            $('#BNUSB1SerialNum').val(result.BNUSB1SerialNum);
                            $('#BNUSB1Code').val(result.BNUSB1Code);
                            $('#BNUSB1Amper').val(result.BNUSB1Amper);
                            $('#BNUSB1Volt').val(result.BNUSB1Volt);
                            $('#BNUSB1CCable').val(result.BNUSB1CCable);
                            $('#BNUSB1CTable').val(result.BNUSB1CTable);
                                if(result.BNUwSpareBat2 == 1) {
                                    $('#BNUwBatSpare2').prop('checked', true);
                                    $('#SpareBat2').removeClass("disabled");
                                }else{
                                    $('#BNUwBatSpare2').prop('checked', false);
                                    $('#SpareBat2').addClass("disabled");
                                }
                            $('#BNUSB2Brand').val(result.BNUSB2Brand);
                            $('#BNUSB2BatType').val(result.BNUSB2BatType);
                            $('#BNUSB2SerialNum').val(result.BNUSB2SerialNum);
                            $('#BNUSB2Code').val(result.BNUSB2Code);
                            $('#BNUSB2Amper').val(result.BNUSB2Amper);
                            $('#BNUSB2Volt').val(result.BNUSB2Volt);
                            $('#BNUSB2CCable').val(result.BNUSB2CCable);
                            $('#BNUSB2CTable').val(result.BNUSB2CTable);
                            $('#BNUCBrand').val(result.BNUCBrand);
                            $('#BNUCModel').val(result.BNUCModel);
                            $('#BNUCSerialNum').val(result.BNUCSerialNum);
                            $('#BNUCCode').val(result.BNUCCode);
                            $('#BNUCAmper').val(result.BNUCAmper);
                            $('#BNUCVolt').val(result.BNUCVolt);
                            $('#BNUCInput').val(result.BNUCInput);
                            $('#BNURemarks').val(result.BNURemarks);

                            $("#btnBNUEditH").click();
                            $('input, select, textarea, checkbox').prop('disabled', false);
                            $("#saveNewUnit").show();
                            $("#clearNewUnit").show();
                        }
                    });
                });
                
            // Delete Brand New
                jQuery(document).on( "click", ".btnBNUDelete", function(){
                    var id = $(this).data('id');
                    var unittype = $(this).data('unittype');

                    $('#btnBNUDeleteH').click();
                    $('#deleteConfirmBNU').data('id', id);
                    $('#deleteConfirmBNU').data('unittype', unittype);
                });

                jQuery(document).on( "click", "#deleteConfirmBNU", function(){
                    var id = $('#deleteConfirmBNU').data('id');
                    var unittype = $('#deleteConfirmBNU').data('unittype');
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.deleteBNU') }}",
                        method:"POST",
                        data:{id: id, unittype: unittype, _token: _token,},
                        success:function(result){
                            $("#btnSuccessH").click();
                            $('#tableBNewUnit').html(result);
                        },
                        error: function(error){
                            $("#btnFailedH").click();
                        }
                    });
                });
                
            // Clear Form if Button Click Transfer Unit
                jQuery(document).on( "click", "#btnBNUTransfer", function(){
                    $("#formBNUT").trigger('reset');
                    if($('#BNUArea').val() == ''){
                        var area = $('#BNUArea').val();
                        var _token = $('input[name="_token"]').val();

                        $.ajax({
                            url:"{{ route('r-workshop.report.getBay') }}",
                            method:"GET",
                            data:{area: area, _token: _token,},
                            success:function(result){
                                $('#POUBay').html(result);
                            }
                        });
                    }

                    var id = $(this).data('id');
                    var bnuremarks = $(this).data('uremarks');

                    $('#btnBNUTransferH').click();
                    $('#transferBNU').data('id',id);
                    $('#BNUIDx').val(id);
                    $('#BNURemarksO').val(bnuremarks);
                });
            
            // If Area Change, Data from Bay also changes
                jQuery(document).on( "change", "#BNUArea", function(){
                    var area = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url:"{{ route('r-workshop.report.getBay') }}",
                        method:"GET",
                        data:{area: area, _token: _token,},
                        success:function(result){
                            $('#BNUBay').html(result);
                        }
                    });
                });

            // Transfer Brand New
                jQuery(document).on( "click", "#transferBNU", function(){
                    
                    $.ajax({
                        url:"{{ route('r-workshop.report.transferNewUnit') }}",
                        method:"POST",
                        data: $("#formBNUT").serialize(),
                        success:function(result){
                            $('#tableBNewUnit').html(result);
                            $("#btnSuccessH").click();
                            $("#closeBNTransfer").click();
                        },
                        error: function(error){
                            $("#btnFailedH").click();
                        }
                    });
                });

    // DELIVERED UNIT
        // Search Confirm Unit
            $("#DTableSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableBDU tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

        // View Delivered Unit
            jQuery(document).on( "click", ".btnDUView", function(){
                $("#POUnit li:first-child button").click();
                var id = $(this).data('id');
                var utype = $(this).data('unittype');
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('r-workshop.report.getPOUData') }}",
                    method:"GET",
                    dataType: 'json',
                    data:{id: id, utype: utype, _token: _token,},
                    success:function(result){
                        $('#POUIDe').val(result.POUnitIDx);
                            if(result.POUUnitType == 1) {
                                $('#POUUnitType').val(result.POUUnitType);
                                $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                            }else{
                                $('#POUUnitType').val(result.POUUnitType);
                                $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                            }
                        $('#POUArrivalDate').val(result.POUArrivalDate);
                        $('#POUBrand').val(result.POUBrand);
                        $('#POUClassification').val(result.POUClassification);
                        $('#POUModel').val(result.POUModel);
                        $('#POUSerialNum').val(result.POUSerialNum);
                        $('#POUCode').val(result.POUCode);
                        $('#POUMastType').val(result.POUMastType);
                        $('#POUMastHeight').val(result.POUMastHeight);
                        $('#POUForkSize').val(result.POUForkSize);
                            if(result.POUwAttachment == 1) {
                                $('#POUwAttachment').prop('checked', true);
                                $('#PAttachment').removeClass("disabled");
                            }else{
                                $('#POUwAttachment').prop('checked', false);
                                $('#PAttachment').addClass("disabled");
                            }
                        $('#POUAttType').val(result.POUAttType);
                        $('#POUAttModel').val(result.POUAttModel);
                        $('#POUAttSerialNum').val(result.POUAttSerialNum);
                            if(result.POUwAccesories == 1) {
                                $('#POUwAccesories').prop('checked', true);
                                $('#PAccesories').removeClass("disabled");
                            }else{
                                $('#POUwAccesories').prop('checked', false);
                                $('#PAccesories').addClass("disabled");
                            }
                            
                            if(result.POUAccISite == 1) {
                                $('#POUAccISite').prop('checked', true);
                            }else{
                                $('#POUAccISite').prop('checked', false);
                            }
                            
                            if(result.POUAccLiftCam == 1) {
                                $('#POUAccLiftCam').prop('checked', true);
                            }else{
                                $('#POUAccLiftCam').prop('checked', false);
                            }
                            
                            if(result.POUAccRedLight == 1) {
                                $('#POUAccRedLight').prop('checked', true);
                            }else{
                                $('#POUAccRedLight').prop('checked', false);
                            }
                            
                            if(result.POUAccBlueLight == 1) {
                                $('#POUAccBlueLight').prop('checked', true);
                            }else{
                                $('#POUAccBlueLight').prop('checked', false);
                            }
                            
                            if(result.POUAccFireExt == 1) {
                                $('#POUAccFireExt').prop('checked', true);
                            }else{
                                $('#POUAccFireExt').prop('checked', false);
                            }
                            
                            if(result.POUAccOthers == 1) {
                                $('#POUAccOthers').prop('checked', true);
                                $('#POUAccOthersDetail').removeClass('disabled');
                            }else{
                                $('#POUAccOthers').prop('checked', false);
                                $('#POUAccOthersDetail').addClass('disabled');
                            }
                        $('#POUAccOthersDetail').val(result.POUAccOthersDetail);
                        $('#POUTechnician1').val(result.POUTechnician1);
                            var selectedValue1 = result.POUTechnician1;
                            $('#POUTechnician2 option').prop('disabled', false); // enable all options
                            if (selectedValue1) {
                                $('#POUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                            }
                        $('#POUTechnician2').val(result.POUTechnician2);
                            var selectedValue2 = result.POUTechnician2;
                            $('#POUTechnician1 option').prop('disabled', false); // enable all options
                            if (selectedValue2) {
                                $('#POUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                            }
                        $('#POUSalesman').val(result.POUSalesman);
                        $('#POUCustomer').val(result.POUCustomer);
                        $('#POUCustAddress').val(result.POUCustAddress);
                        $('#POUBABrand').val(result.POUBABrand);
                        $('#POUBABatType').val(result.POUBABatType);
                        $('#POUBASerialNum').val(result.POUBASerialNum);
                        $('#POUBACode').val(result.POUBACode);
                        $('#POUBAAmper').val(result.POUBAAmper);
                        $('#POUBAVolt').val(result.POUBAVolt);
                        $('#POUBACCable').val(result.POUBACCable);
                        $('#POUBACTable').val(result.POUBACTable);
                            if(result.POUwSpareBat1 == 1) {
                                $('#POUwBatSpare1').prop('checked', true);
                                $('#SpareBat1').removeClass("disabled");
                            }else{
                                $('#POUwBatSpare1').prop('checked', false);
                                $('#SpareBat1').addClass("disabled");
                            }
                        $('#POUSB1Brand').val(result.POUSB1Brand);
                        $('#POUSB1BatType').val(result.POUSB1BatType);
                        $('#POUSB1SerialNum').val(result.POUSB1SerialNum);
                        $('#POUSB1Code').val(result.POUSB1Code);
                        $('#POUSB1Amper').val(result.POUSB1Amper);
                        $('#POUSB1Volt').val(result.POUSB1Volt);
                        $('#POUSB1CCable').val(result.POUSB1CCable);
                        $('#POUSB1CTable').val(result.POUSB1CTable);
                            if(result.POUwSpareBat2 == 1) {
                                $('#POUwBatSpare2').prop('checked', true);
                                $('#SpareBat2').removeClass("disabled");
                            }else{
                                $('#POUwBatSpare2').prop('checked', false);
                                $('#SpareBat2').addClass("disabled");
                            }
                        $('#POUSB2Brand').val(result.POUSB2Brand);
                        $('#POUSB2BatType').val(result.POUSB2BatType);
                        $('#POUSB2SerialNum').val(result.POUSB2SerialNum);
                        $('#POUSB2Code').val(result.POUSB2Code);
                        $('#POUSB2Amper').val(result.POUSB2Amper);
                        $('#POUSB2Volt').val(result.POUSB2Volt);
                        $('#POUSB2CCable').val(result.POUSB2CCable);
                        $('#POUSB2CTable').val(result.POUSB2CTable);
                        $('#POUCBrand').val(result.POUCBrand);
                        $('#POUCModel').val(result.POUCModel);
                        $('#POUCSerialNum').val(result.POUCSerialNum);
                        $('#POUCCode').val(result.POUCCode);
                        $('#POUCAmper').val(result.POUCAmper);
                        $('#POUCVolt').val(result.POUCVolt);
                        $('#POUCInput').val(result.POUCInput);
                        $('#POURemarks').val(result.POURemarks);

                        $("#btnPOUViewH").click();
                        $('input, select, textarea, checkbox').prop('disabled', true);
                        $("#savePullOut").hide();
                        $("#clearPullOut").hide();
                        // $("#htitle").hide();
                        var newHeading = "VIEW UNIT";
                        document.getElementById("htitle").innerHTML = newHeading;
                    }
                });
            });

        // Edit DU
            jQuery(document).on( "click", ".btnDUEdit", function(){
                $("#POUnit li:first-child button").click();
                var id = $(this).data('id');
                var utype = $(this).data('unittype');
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('r-workshop.report.getPOUData') }}",
                    method:"GET",
                    dataType: 'json',
                    data:{id: id, utype: utype, _token: _token,},
                    success:function(result){
                        $('#POUIDe').val(result.POUnitIDx);
                            if(result.POUUnitType == 1) {
                                $('#POUUnitType').val(result.POUUnitType);
                                $("#BatteryDetails-tab, #ChargerDetails-tab").hide();
                            }else{
                                $('#POUUnitType').val(result.POUUnitType);
                                $("#BatteryDetails-tab, #ChargerDetails-tab").show();
                            }
                        $('#POUArrivalDate').val(result.POUArrivalDate);
                        $('#POUBrand').val(result.POUBrand);
                        $('#POUClassification').val(result.POUClassification);
                        $('#POUModel').val(result.POUModel);
                        $('#POUSerialNum').val(result.POUSerialNum);
                        $('#POUCode').val(result.POUCode);
                        $('#POUMastType').val(result.POUMastType);
                        $('#POUMastHeight').val(result.POUMastHeight);
                        $('#POUForkSize').val(result.POUForkSize);
                            if(result.POUwAttachment == 1) {
                                $('#POUwAttachment').prop('checked', true);
                                $('#PAttachment').removeClass("disabled");
                            }else{
                                $('#POUwAttachment').prop('checked', false);
                                $('#PAttachment').addClass("disabled");
                            }
                        $('#POUAttType').val(result.POUAttType);
                        $('#POUAttModel').val(result.POUAttModel);
                        $('#POUAttSerialNum').val(result.POUAttSerialNum);
                            if(result.POUwAccesories == 1) {
                                $('#POUwAccesories').prop('checked', true);
                                $('#PAccesories').removeClass("disabled");
                            }else{
                                $('#POUwAccesories').prop('checked', false);
                                $('#PAccesories').addClass("disabled");
                            }
                            
                            if(result.POUAccISite == 1) {
                                $('#POUAccISite').prop('checked', true);
                            }else{
                                $('#POUAccISite').prop('checked', false);
                            }
                            
                            if(result.POUAccLiftCam == 1) {
                                $('#POUAccLiftCam').prop('checked', true);
                            }else{
                                $('#POUAccLiftCam').prop('checked', false);
                            }
                            
                            if(result.POUAccRedLight == 1) {
                                $('#POUAccRedLight').prop('checked', true);
                            }else{
                                $('#POUAccRedLight').prop('checked', false);
                            }
                            
                            if(result.POUAccBlueLight == 1) {
                                $('#POUAccBlueLight').prop('checked', true);
                            }else{
                                $('#POUAccBlueLight').prop('checked', false);
                            }
                            
                            if(result.POUAccFireExt == 1) {
                                $('#POUAccFireExt').prop('checked', true);
                            }else{
                                $('#POUAccFireExt').prop('checked', false);
                            }
                            
                            if(result.POUAccStLight == 1) {
                                $('#POUAccStLight').prop('checked', true);
                            }else{
                                $('#POUAccStLight').prop('checked', false);
                            }
                            
                            if(result.POUAccOthers == 1) {
                                $('#POUAccOthers').prop('checked', true);
                                $('#POUAccOthersDetail').prop('disabled',false);
                            }else{
                                $('#POUAccOthers').prop('checked', false);
                                $('#POUAccOthersDetail').prop('disabled',true);
                            }
                        $('#POUAccOthersDetail').val(result.POUAccOthersDetail);
                        $('#POUTechnician1').val(result.POUTechnician1);
                            var selectedValue1 = result.POUTechnician1;
                            $('#POUTechnician2 option').prop('disabled', false); // enable all options
                            if (selectedValue1) {
                                $('#POUTechnician2 option[value="' + selectedValue1 + '"]').prop('disabled', true); // disable selected option
                            }
                        $('#POUTechnician2').val(result.POUTechnician2);
                            var selectedValue2 = result.POUTechnician2;
                            $('#POUTechnician1 option').prop('disabled', false); // enable all options
                            if (selectedValue2) {
                                $('#POUTechnician1 option[value="' + selectedValue2 + '"]').prop('disabled', true); // disable selected option
                            }
                        $('#POUSalesman').val(result.POUSalesman);
                        $('#POUCustomer').val(result.POUCustomer);
                        $('#POUCustAddress').val(result.POUCustAddress);
                        $('#POUBABrand').val(result.POUBABrand);
                        $('#POUBABatType').val(result.POUBABatType);
                        $('#POUBASerialNum').val(result.POUBASerialNum);
                        $('#POUBACode').val(result.POUBACode);
                        $('#POUBAAmper').val(result.POUBAAmper);
                        $('#POUBAVolt').val(result.POUBAVolt);
                        $('#POUBACCable').val(result.POUBACCable);
                        $('#POUBACTable').val(result.POUBACTable);
                            if(result.POUwSpareBat1 == 1) {
                                $('#POUwBatSpare1').prop('checked', true);
                                $('#SpareBat1').removeClass("disabled");
                            }else{
                                $('#POUwBatSpare1').prop('checked', false);
                                $('#SpareBat1').addClass("disabled");
                            }
                        $('#POUSB1Brand').val(result.POUSB1Brand);
                        $('#POUSB1BatType').val(result.POUSB1BatType);
                        $('#POUSB1SerialNum').val(result.POUSB1SerialNum);
                        $('#POUSB1Code').val(result.POUSB1Code);
                        $('#POUSB1Amper').val(result.POUSB1Amper);
                        $('#POUSB1Volt').val(result.POUSB1Volt);
                        $('#POUSB1CCable').val(result.POUSB1CCable);
                        $('#POUSB1CTable').val(result.POUSB1CTable);
                            if(result.POUwSpareBat2 == 1) {
                                $('#POUwBatSpare2').prop('checked', true);
                                $('#SpareBat2').removeClass("disabled");
                            }else{
                                $('#POUwBatSpare2').prop('checked', false);
                                $('#SpareBat2').addClass("disabled");
                            }
                        $('#POUSB2Brand').val(result.POUSB2Brand);
                        $('#POUSB2BatType').val(result.POUSB2BatType);
                        $('#POUSB2SerialNum').val(result.POUSB2SerialNum);
                        $('#POUSB2Code').val(result.POUSB2Code);
                        $('#POUSB2Amper').val(result.POUSB2Amper);
                        $('#POUSB2Volt').val(result.POUSB2Volt);
                        $('#POUSB2CCable').val(result.POUSB2CCable);
                        $('#POUSB2CTable').val(result.POUSB2CTable);
                        $('#POUCBrand').val(result.POUCBrand);
                        $('#POUCModel').val(result.POUCModel);
                        $('#POUCSerialNum').val(result.POUCSerialNum);
                        $('#POUCCode').val(result.POUCCode);
                        $('#POUCAmper').val(result.POUCAmper);
                        $('#POUCVolt').val(result.POUCVolt);
                        $('#POUCInput').val(result.POUCInput);
                        $('#POURemarks').val(result.POURemarks);

                        $("#btnPOUEditH").click();
                        $('input, select, textarea, checkbox').prop('disabled', false);
                        $("#savePullOut").show();
                        $("#clearPullOut").show();
                        var newHeading = "EDIT UNIT";
                        document.getElementById("htitle").innerHTML = newHeading;
                    }
                });
            });
        
        // Delete/Revert DU
            jQuery(document).on( "click", ".btnDUDelete", function(){
                var id = $(this).data('id');
                var cubay = $(this).data('cubay');
                var duid = $(this).data('duid');
                $('#btnDUDeleteH').click();
                $('#deleteConfirmDU').data('id', id);
                $('#deleteConfirmDU').data('cubay', cubay);
                $('#deleteConfirmDU').data('duid', duid);
            });
            
            jQuery(document).on( "click", "#deleteConfirmDU", function(){
                var id = $(this).data('id');
                var cubay = $(this).data('cubay');
                var duid = $(this).data('duid');
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{ route('r-workshop.report.deleteDU') }}",
                    method:"POST",
                    data:{id: id, cubay: cubay, duid: duid, _token: _token,},
                    success:function(result){
                        $('#tableBDU').html(result);
                        $('#tableBPOU').load(location.href + ' #tableBPOU>*','');
                        $('#tableBCU').load(location.href + ' #tableBCU>*','');
                        $("#success-modal").removeClass("hidden");
                        $("#success-modal").addClass("flex");
                    },
                    error: function(error){
                        $("#failed-modal").removeClass("hidden");
                        $("#failed-modal").addClass("flex");
                    }
                });
            });
        });

    // REPORT
        // If Area Change, Data from Bay also changes
        jQuery(document).on( "change", "#POUArea", function(){
                var area = $(this).val();
                var _token = $('input[name="_token"]').val();
                // alert(area);

                $.ajax({
                    url:"{{ route('r-workshop.report.getBay') }}",
                    method:"GET",
                    data:{area: area, _token: _token,},
                    success:function(result){
                        $('#POUBay').html(result);
                    }
                });
            });
        // Brand Report
        jQuery(document).on( "click", "#RGenBrand", function(){
            var UBrand = $('#RBrand').val();
            var fromDate = $('#RBrandFromDate').val();
            var toDate = $('#RBrandToDate').val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('r-workshop.report.generateBrandReport') }}",
                method: 'POST',
                data: {
                    UBrand: UBrand,
                    fromDate: fromDate,
                    toDate: toDate,
                    _token: _token
                },
                success: function(response) {
                    var link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURI(response);
                    link.target = '_blank';
                    link.download = 'Brand Report.csv';
                    link.click();
                },
            });
        });

        // Pull Out Unit Report
        jQuery(document).on("click", "#RGenPOU", function() {
            var fromDate = $('#RPOUFromDate').val();
            var toDate = $('#RPOUToDate').val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('r-workshop.report.generatePOUReport') }}",
                method: 'POST',
                data: {
                    fromDate: fromDate,
                    toDate: toDate,
                    _token: _token
                },
                success: function(response) {
                    var link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURI(response);
                    link.target = '_blank';
                    link.download = 'PullOutUnit Report.csv';
                    link.click();
                },
            });
        });
    </script>
    
</x-app-layout>