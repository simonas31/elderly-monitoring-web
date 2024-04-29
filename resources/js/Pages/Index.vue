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
        <div class="my-12 container mx-auto">
            <div class="flex flex-col md:flex-row justify-center">
                <div class="max-w-[400px]">
                    <h3 class="text-primary-500 pt-5">Elder Watch</h3>
                    <br>
                    <p class="text-center md:text-left">
                        Elder Watch - system built for taking care of elders.
                    </p>
                    <p class="pt-2">
                        Watching over them and getting notification if incident happens.
                    </p>
                    <br>
                    <Link href="/login">
                    <Button intent="greenish"
                            customClasses="uppercase sm:min-h-[32px] sm:px-6 rounded bg-primary-200">
                        Login
                    </Button>
                    </Link>
                </div>

                <div class="max-w-[500px] mx-auto md:mx-1">
                    <img class="bg-no-repeat bg-cover bg-center rounded-md"
                         src="../../../storage/images/index-image.png"
                         alt="img">
                </div>
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