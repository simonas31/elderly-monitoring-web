<script setup>
import { ref } from "vue";
import { usePage, Link, useForm } from '@inertiajs/vue3'

const page = usePage();

const props = defineProps(['devices', 'auth']);

const deleteUser = (supervisor_id) => {
    form.user_id = supervisor_id;
    form.post("/deleteUser");
};

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto w-auto lg:w-[900px] sm:mx-auto">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col md:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="text-base sm:text-xl text-center header-border-bottom pb-3">
                            Registered Devices
                        </div>
                        <div class="flex justify-center pt-5 lg:mx-5">
                            <div class="bg-white shadow-lg">
                                <table class="w-full table-fixed">
                                    <thead class="bg-primary-300 border-gray-200">
                                        <tr>
                                            <th class="p-3 text-md font-semibold tracking-wide text-left">Device Name
                                            </th>
                                            <th class="p-3 text-md font-semibold tracking-wide text-left">Custom Device
                                                Name</th>
                                            <th class="p-3 text-md font-semibold tracking-wide text-left">Register Date
                                            </th>
                                            <th v-if="page.props.auth.user.role_id == 1">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="device in props.devices"
                                            class="py-2 hover:bg-primary-100">
                                            <td
                                                class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-left">
                                                {{ device.device_name }}
                                            </td>
                                            <td
                                                class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-left">
                                                {{ device.custom_device_name }}
                                            </td>
                                            <td
                                                class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-left">
                                                {{ (new
                                                    Date(device.created_at)).toLocaleString('en-GB', {
                                                        second: "2-digit",
                                                        hour: "2-digit", minute: "2-digit", day: "2-digit", month: "2-digit",
                                                        year: "2-digit"
                                                    })
                                                }}</td>
                                            <td v-show="page.props.auth.user.role_id == 1"
                                                class="text-center">
                                                <Button customClasses="sm:min-h-[40px] px-4 bg-rose-400 font-bold hover:bg-rose-600"
                                                        @click="deleteUser(supervisor.id)">
                                                    DELETE
                                                </Button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';
import Button from "@Components/Button.vue";

export default {
    components: { Layout, Button },
}
</script>