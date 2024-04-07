<script setup>
import { ref } from "vue";

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="my-4 bg-white">
                            Folders with videos. Add select date to filter out data
                        </div>
                    </div>
                </div>
                <div class="w-1/3">
                    Activity
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';

export default {
    components: { Layout },
}
</script>