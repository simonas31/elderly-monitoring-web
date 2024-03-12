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