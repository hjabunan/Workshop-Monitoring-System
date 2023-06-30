<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class=" text-gray-900" style="display: flex; justify-content: center; align-items: center; height: 80vh;">
                    <div class="flex items-center justify-between p-6 border-b rounded-t h-8">
                        <h3 id="areaName" class="text-xl leading-8 font-semibold text-gray-900"></h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="saveModal">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                        </button>
                    </div>
                    <img src="{{ asset('images/workshop_layout.jpg') }}" alt="Image">
                </div>
                
            <div class="item">
                <div class="resizer ne"></div>
                <div class="resizer nw"></div>
                <div class="resizer se"></div>
                <div class="resizer sw"></div>
            </div>
            </div>
        </div>
    </div>
    <script>
        $($(document).ready(function () {
            const el = document.querySelector(".item");

            let isResizing = false;

            el.addEventListener("mousedown",mousedown);

            function mousedown(e){
                window.addEventListener('mousemove', mousemove);
                window.addEventListener('mouseup', mouseup);

                let prevX = e.clientX;
                let prevY = e.clientY;

                function mousemove(e){
                    if(!isResizing){
                        let newX = prevX - e.clientX;
                        let newY = prevY - e.clientY;

                        const rect = el.getBoundingClientRect();
                        
                        el.style.left = rect.left - newX + "px";
                        el.style.top = rect.top - newY + "px";

                        prevX = e.clientX;
                        prevY = e.clientY;
                    }
                }

                function mouseup(){
                    window.removeEventListener('mousemove', mousemove);
                    window.removeEventListener('mouseup', mouseup);
                }
            }

            const resizers = document.querySelectorAll(".resizer");
            let currentResizer;

            for(let resizer of resizers) {
                resizer.addEventListener("mousedown", mousedown);

                function mousedown(e) {
                    currentResizer = e.target;
                    isResizing = true;

                    let prevX = e.clientX;
                    let prevY = e.clientY;

                    window.addEventListener("mousemove", mousemove);
                    window.addEventListener('mouseup', mouseup);

                    function mousemove(e){
                        const rect = el.getBoundingClientRect();

                        if(currentResizer.classList.contains("se")){
                            el.style.width = rect.width - (prevX - e.clientX) + "px";
                            el.style.height = rect.height - (prevY - e.clientY) + "px";
                        }
                        else if(currentResizer.classList.contains('sw')){
                            
                        }

                        prevX = e.clientX;
                        prevY = e.clientY;
                    }

                    function mouseup(){
                        window.removeEventListener("mousemove", mousemove);
                        window.removeEventListener("mouseup", mouseup);
                        isResizing = false;

                    }
                }
            }
        });)
    </script>
</x-app-layout>
