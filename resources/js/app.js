import "./bootstrap";

window.addEventListener("load", function () {
    var websocket = new WebSocket(`ws://192.168.0.228:81/`);

    websocket.onopen = function (event) {
        console.log("Connection established");
        websocket.send("getStream");
    };

    websocket.onclose = function (event) {
        console.log("Connection died");
    };

    websocket.onerror = function (error) {
        console.log("error");
    };

    websocket.onmessage = function (event) {
        var urlCreator = window.URL || window.webkitURL;
        let image = document.getElementById("stream");
        image.src = urlCreator.createObjectURL(event.data);
    };
});
