<script setup>
import { EnvelopeIcon } from "@heroicons/vue/20/solid";
import { Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

const login = async () => {
    form.post("/login");
};
</script>
<template>
    <Layout>
        <div class="container mx-auto">
            <form @submit.prevent="login">
                <Button intent="danger"
                        type="submit"
                        :leftIcon="EnvelopeIcon">Send</Button>
            </form>
        </div>

        <div class="container mx-auto mt-10 relative">
            <div class="relative">
                <input type="text"
                       id="your-input-id"
                       class="peer text-gray-700 font-normal outline outline-0 focus:outline-0 transition-all border focus:border-1 text-sm px-3 py-2.5 rounded-[7px] border-gray-200 focus:border-gray-900" />
                <label for="your-input-id"
                       class="absolute bg-white text-base text-gray-500 peer-focus:black duration-300 scale-75 top-[-13px] z-10 origin-[0] start-3 px-1">Email</label>
            </div>
        </div>
        <div class="container flex items-center justify-center mx-auto py-2 h-full">
            <div class="middle-of-the-screen bg-white">
                <img id="stream"
                     alt="image"
                     class="w-full h-full">
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "@Layouts/Layout.vue";
import Button from "@Components/Button.vue";

export default {
    components: { Layout, Button },
};
</script>