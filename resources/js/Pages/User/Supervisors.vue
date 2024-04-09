<script setup>
import { ref } from "vue";

const props = defineProps(['supervisors']);

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto w-auto sm:w-[500px] sm:mx-auto">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="text-base sm:text-xl text-center header-border-bottom pb-3">
                            Elder supervisors
                        </div>
                        <div class="flex justify-center pt-5">
                            <table class="table-auto">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="px-2 sm:pr-10">Full Name</th>
                                        <th>Phone number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="supervisor in props.supervisors"
                                        class="py-2">
                                        <td>
                                            <img class="rounded-full scale-100 object-cover w-9 h-9"
                                                 :src="supervisor.profile_picture">
                                        </td>
                                        <td class="px-2 sm:pr-10 text-center">
                                            {{ supervisor.name + " " + supervisor.surname }}
                                        </td>
                                        <td class="text-center">{{ supervisor.phone_number }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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