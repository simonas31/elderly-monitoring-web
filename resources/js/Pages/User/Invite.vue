<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import { PaperAirplaneIcon } from "@heroicons/vue/20/solid";

const form = useForm({
    email: ref(null),
    name: ref(null),
    surname: ref(null),
    role_id: ref(null),
});

const formErrors = {
    email: ref(null),
    name: ref(null),
    surname: ref(null),
    role_id: ref(null),
}

const invite = () => {
    //validate form
    if (form.email == null) {
        formErrors.email.value = "Please enter invitation email address";
        return;
    } else {
        formErrors.email.value = null;
    }

    if (form.name == null) {
        formErrors.name.value = "Please enter invited person's name";
        return;
    } else {
        formErrors.name.value = null;
    }

    if (form.surname == null) {
        formErrors.surname.value = "Please enter invited person's surname";
        return;
    } else {
        formErrors.surname.value = null;
    }

    if (form.role_id == null) {
        formErrors.role_id.value = "Please select invited person's role";
        return;
    } else {
        formErrors.role_id.value = null;
    }

    form.post('/sendInvitation');
};

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 sm:mx-auto sm:w-2/3 xl:w-1/3">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="text-base sm:text-xl text-center header-border-bottom pb-3">
                            Invite another person to look after of elder
                        </div>
                        <div class="flex flex-col justify-center items-center w-full">
                            <div class="relative mt-10 w-[250px] sm:w-[300px]">
                                <Input autocomplete="off"
                                       id="email"
                                       name="email"
                                       type="text"
                                       placeholder="Email"
                                       :customClasses="'max-w-[400px]'"
                                       v-model="form.email" />
                                <p class="text-center">
                                    <span v-if="formErrors.email.value"
                                          class="text-rose-600">{{ formErrors.email.value }}</span>
                                </p>
                            </div>
                            <div class="relative mt-5 w-[250px] sm:w-[300px]">
                                <Input autocomplete="off"
                                       id="name"
                                       name="name"
                                       type="text"
                                       placeholder="Name"
                                       :customClasses="'max-w-[400px]'"
                                       v-model="form.name" />
                                <p class="text-center">
                                    <span v-if="formErrors.name.value"
                                          class="text-rose-600">{{ formErrors.name.value }}</span>
                                </p>
                            </div>
                            <div class="relative mt-5 w-[250px] sm:w-[300px]">
                                <Input autocomplete="off"
                                       id="surname"
                                       name="surname"
                                       type="text"
                                       placeholder="Surname"
                                       :customClasses="'max-w-[400px]'"
                                       v-model="form.surname" />
                                <p class="text-center">
                                    <span v-if="formErrors.surname.value"
                                          class="text-rose-600">{{ formErrors.surname.value }}</span>
                                </p>
                            </div>
                            <div class="relative mt-5 w-[250px] sm:w-[300px]">
                                <Select id="role_id"
                                        name="role_id"
                                        placeholder="Position"
                                        :options="['Relative', 'Caregiver']"
                                        v-model="form.role_id" />
                                <p class="text-center">
                                    <span v-if="formErrors.role_id.value"
                                          class="text-rose-600">{{ formErrors.role_id.value }}</span>
                                </p>
                            </div>
                            <div class="mx-auto mt-5">
                                <Button intent="primary"
                                        customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-3"
                                        @click="invite"
                                        :rightIcon="PaperAirplaneIcon">Invite</Button>
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
import Input from "@Components/Input.vue";
import Select from "@Components/Select.vue";

export default {
    components: { Layout, Button, Input, Select },
}
</script>