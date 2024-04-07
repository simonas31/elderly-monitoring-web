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
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5">
            intro
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