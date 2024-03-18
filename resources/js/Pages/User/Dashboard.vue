<script setup>
import { ref } from "vue";

const cameras = ref(['Pirma', 'Antra']);

const cameraOnChange = (e) => {
    console.log(e);
};
</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="relative sm:w-[300px] mx-auto">
                            <Select id="current_camera"
                                    name="current_camera"
                                    placeholder="Current Camera"
                                    :options="cameras.value"
                                    @change="cameraOnChange" />
                        </div>
                        <div class="my-4 bg-white">
                            <img id="stream"
                                 alt="image"
                                 class="w-[800px] h-[600px] max-w-[1024px] max-h-[720px]">
                        </div>
                    </div>
                    <div class="w-1/3">
                        Statistics
                    </div>
                </div>
                <div class="pb-8">
                    Other data
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';
import Select from "@Components/Select.vue";

export default {
    components: { Layout, Select },
}
</script>