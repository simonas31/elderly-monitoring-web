import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
            "@Components": path.resolve(__dirname, "./resources/js/Components"),
            "@Pages": path.resolve(__dirname, "./resources/js/Pages"),
            "@Layouts": path.resolve(__dirname, "./resources/js/Layouts"),
        },
    },
    server: {
        //https: true,
    },
    // server: {
    //     host: true
    // }
});
