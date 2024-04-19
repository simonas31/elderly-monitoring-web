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
        <div class="my-12 mx-4 xl:mx-auto w-auto sm:w-[800px] sm:mx-auto">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="text-base sm:text-xl text-center header-border-bottom pb-3">
                            Registered Devices
                        </div>
                        <div class="flex justify-center pt-5">
                            <table class="table-auto">
                                <thead>
                                    <tr>
                                        <th class="px-2 sm:pr-10 text-center">Device Name</th>
                                        <th class="px-2 sm:pr-10 text-center">Custom Device Name</th>
                                        <th class="px-2 sm:pr-10 text-center">Register Date</th>
                                        <th v-if="page.props.auth.user.role_id == 1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="device in props.devices"
                                        class="py-2">
                                        <td class="text-center">
                                            {{ device.device_name }}
                                        </td>
                                        <td class="px-2 sm:pr-10 text-center">
                                            {{ device.custom_device_name }}
                                        </td>
                                        <td class="text-center px-2 sm:pr-10">{{ (new
                                            Date(device.created_at)).toLocaleString('en-GB', {
                                                second: "2-digit",
                                                hour: "2-digit", minute: "2-digit", day: "2-digit", month: "2-digit",
                                                year: "2-digit"
                                            })
                                            }}</td>
                                        <td v-show="page.props.auth.user.role_id == 1">
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
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';
import Button from "@Components/Button.vue";

export default {
    components: { Layout, Button },
}
</script>