<script setup>
import { ref } from "vue";
import { usePage, Link, useForm } from '@inertiajs/vue3'


const form = useForm({
    'user_id': ref(null)
});

const page = usePage();

const props = defineProps(['supervisors', 'auth']);

const deleteUser = (supervisor_id) => {
    form.user_id = supervisor_id;
    form.post("/deleteUser");
};

const convertUserRole = (role_id) => {
    if (role_id == 1) {
        return "Admin";
    } else if (role_id == 2) {
        return "Relative";
    } else if (role_id == 3) {
        return "Nurse";
    }
}

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto w-auto sm:w-[600px] sm:mx-auto">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="text-base sm:text-xl text-center header-border-bottom pb-3">
                            {{ auth.user.role_id == 1 ? "System Users" : 'Elder supervisors' }}
                        </div>
                        <div class="flex justify-center pt-5 lg:mx-5">
                            <div class="bg-white shadow-lg">
                                <table class="w-full table-fixed">
                                    <thead class="bg-primary-300 border-gray-200">
                                        <tr>
                                            <th class="p-3 text-md font-semibold tracking-wide text-left">Full Name</th>
                                            <th class="p-3 text-md font-semibold tracking-wide text-left">Phone number
                                            </th>
                                            <th class="p-3 text-md font-semibold tracking-wide text-left">{{ page.props.auth.user.role_id == 1 ? 'Role' : "Elder's relationship" }}</th>
                                            <th v-if="page.props.auth.user.role_id == 1">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="supervisor in props.supervisors"
                                            class="py-2 hover:bg-primary-100">
                                            <td
                                                class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-left flex items-center justify-start">
                                                <img class="rounded-full scale-100 object-cover w-9 h-9"
                                                     :src="supervisor.profile_picture ? supervisor.profile_picture : 'https://t4.ftcdn.net/jpg/00/64/67/63/360_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg'">
                                                <p class="pl-2">
                                                    {{ supervisor.name + " " + supervisor.surname }}
                                                </p>
                                            </td>
                                            <td
                                                class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-left">
                                                {{ supervisor.phone_number }}</td>
                                            <td class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-left">
                                                {{ convertUserRole(supervisor.role_id) }}
                                            </td>
                                            <td class="p-3 text-md text-gray-700 text-ellipsis overflow-hidden text-center"
                                                v-if="page.props.auth.user.role_id == 1 && page.props.auth.user.id != supervisor.id">
                                                <Button customClasses="sm:min-h-[40px] px-4 bg-rose-400 font-bold hover:bg-rose-600"
                                                        @click="deleteUser(supervisor.id)">
                                                    DELETE
                                                </Button>
                                            </td>
                                            <td v-else-if="page.props.auth.user.role_id == 1"></td>
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