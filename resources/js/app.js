import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
// import titleMixin from "./mixins";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const VueApp = createApp({ render: () => h(App, props) });
        console.log(el);
        // VueApp.use(plugin).mixin(titleMixin).mount(el);
        VueApp.use(plugin).mount("#app");
    },
});

// window.addEventListener("load", function () {
//     var websocket = new WebSocket(`ws://192.168.0.228:81/`);

//     websocket.onopen = function (event) {
//         console.log("Connection established");
//         websocket.send("getStream");
//     };

//     websocket.onclose = function (event) {
//         console.log("Connection died");
//     };

//     websocket.onerror = function (error) {
//         console.log("error");
//     };

//     websocket.onmessage = function (event) {
//         var urlCreator = window.URL || window.webkitURL;
//         let image = document.getElementById("stream");
//         image.src = urlCreator.createObjectURL(event.data);
//     };
// });
