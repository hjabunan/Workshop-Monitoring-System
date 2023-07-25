const el = document.querySelector(".item");

let isResizing = false;

el.addEventListener("mousedown", mousedown);

function mousedown(e) {
    window.addEventListener("mousemove", mousemove);
    window.addEventListener("mouseup", mouseup);

    let prevX = e.clientX;
    let prevY = e.clientY;

    function mousemove(e) {
        if (!isResizing) {
        let newX = prevX - e.clientX;
        let newY = prevY - e.clientY;

        const rect = el.getBoundingClientRect();

        el.style.left = rect.left - newX + "px";
        el.style.top = rect.top - newY + "px";

        prevX = e.clientX;
        prevY = e.clientY;
        }
    }

    function mouseup() {
        window.removeEventListener("mousemove", mousemove);
        window.removeEventListener("mouseup", mouseup);

        var itemTop = $(".item").offset().top - 160;
        var itemLeft = $(".item").offset().left;
        var itemHeight = $(".item").height();
        var itemWidth = $(".item").width();

        var vh = $(window).height() - 205;
        var widthRatio = itemWidth / vh;
        var heightRatio = itemHeight / vh;
        var vw = $(window).width();
        var areaTop = (itemTop / vh) * 100;

        var areaLeft = ((vw / 2) - itemLeft) / vh;
        var leftRatio = ((vw / 2) - itemLeft) / vh;

        var areaHeight = (itemHeight / vh) * 100;
        var areaWidth = (itemWidth / vw) * 100;

        $('#areaTop').val(areaTop);
        $('#areaLeft').val(areaLeft);
        $('#areaHeight').val(itemHeight);
        $('#areaWidth').val(itemWidth);
        $('#areaWidthRatio').val(widthRatio);
        $('#areaHeightRatio').val(heightRatio);
        $('#areaLeftRatio').val(leftRatio);
    }
}

const resizers = document.querySelectorAll(".resizer");
let currentResizer;

for (let resizer of resizers) {
    resizer.addEventListener("mousedown", mousedown);

    function mousedown(e) {
        currentResizer = e.target;
        isResizing = true;

        let prevX = e.clientX;
        let prevY = e.clientY;

        window.addEventListener("mousemove", mousemove);
        window.addEventListener("mouseup", mouseup);

        function mousemove(e) {
            const rect = el.getBoundingClientRect();

            if (currentResizer.classList.contains("se")) {
                el.style.width = rect.width - (prevX - e.clientX) + "px";
                el.style.height = rect.height - (prevY - e.clientY) + "px";
            } else if (currentResizer.classList.contains("sw")) {
                el.style.width = rect.width + (prevX - e.clientX) + "px";
                el.style.height = rect.height - (prevY - e.clientY) + "px";
                el.style.left = rect.left - (prevX - e.clientX) + "px";
            } else if (currentResizer.classList.contains("ne")) {
                el.style.width = rect.width - (prevX - e.clientX) + "px";
                el.style.height = rect.height + (prevY - e.clientY) + "px";
                el.style.top = rect.top - (prevY - e.clientY) + "px";
            } else {
                el.style.width = rect.width + (prevX - e.clientX) + "px";
                el.style.height = rect.height + (prevY - e.clientY) + "px";
                el.style.top = rect.top - (prevY - e.clientY) + "px";
                el.style.left = rect.left - (prevX - e.clientX) + "px";
            }

            prevX = e.clientX;
            prevY = e.clientY;
        }

        function mouseup() {
            window.removeEventListener("mousemove", mousemove);
            window.removeEventListener("mouseup", mouseup);
            isResizing = false;

            var itemTop = $(".item").offset().top - 160;
            var itemLeft = $(".item").offset().left;
            var itemHeight = $(".item").height();
            var itemWidth = $(".item").width();

            var vh = $(window).height() - 205;
            var widthRatio = itemWidth / vh;
            var heightRatio = itemHeight / vh;
            var vw = $(window).width();
            var areaTop = (itemTop / vh) * 100;
            var areaLeft = (vw / 2) - itemLeft;
            var areaHeight = (itemHeight / vh) * 100;
            var areaWidth = (itemWidth / vw) * 100;

            $('#areaTop').val(areaTop);
            $('#areaLeft').val(areaLeft);
            $('#areaHeight').val(itemHeight);
            $('#areaWidth').val(itemWidth);
            $('#areaWidthRatio').val(widthRatio);
            $('#areaHeightRatio').val(heightRatio);
        }
    }
}