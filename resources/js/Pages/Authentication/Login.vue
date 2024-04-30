<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const twoFa = ref(false);

const form = useForm({
    email: ref(null),
    password: ref(null),
    remember: ref(false),
    code: ref(null),
});

const login = async () => {
    form.post("/login", {
        onSuccess: (response) => {
            if (response.props.twoFA) {
                twoFa.value = true;
            }
        }
    });
};
</script>
<template>
    <Layout>
        <div class="flex flex-col justify-center sm:py-12 mt-16 sm:mt-10">
            <div class="relative py-3 sm:max-w-xl sm:mx-auto">
                <div
                     class="absolute inset-0 bg-gradient-to-r from-olive to-olive shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
                </div>
                <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20 container">
                    <div class="max-w-md mx-auto">
                        <div>
                            <h1 class="text-2xl font-semibold w-80">Login</h1>
                        </div>
                        <form @submit.prevent="login">
                            <div class="divide-y divide-gray-200 text-sm sm:text-base">
                                <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                                    <div class="relative">
                                        <input autocomplete="off"
                                               id="email"
                                               name="email"
                                               type="text"
                                               class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-emerald-500"
                                               placeholder="Email address"
                                               v-model="form.email" />
                                        <label for="email"
                                               class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">Email
                                            Address</label>
                                    </div>
                                    <div class="relative">
                                        <input autocomplete="off"
                                               id="password"
                                               name="password"
                                               type="password"
                                               class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-emerald-500"
                                               placeholder="Password"
                                               v-model="form.password" />
                                        <label for="password"
                                               class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">Password</label>
                                    </div>
                                    <div v-if="twoFa"
                                         class="relative">
                                        <input autocomplete="off"
                                               id="2fa"
                                               name="2fa"
                                               type="text"
                                               class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-emerald-500"
                                               placeholder="Password"
                                               v-model="form.code" />
                                        <label for="2fa"
                                               class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">2FA
                                            code</label>
                                    </div>
                                    <div class="relative text-center pt-3">
                                        <Button intent="primary"
                                                type="submit"
                                                customClasses="uppercase sm:min-h-[48px] px-5 sm:px-8">
                                            Login
                                        </Button>
                                    </div>
                                    <div class="text-center">
                                        <span>
                                            Don't have an account?
                                            <Link href="/register"
                                                  class="text-blue-400 hover:text-blue-700">Register</Link>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "@Layouts/Layout.vue";
import Button from "@Components/Button.vue";

export default {
    components: {
        Layout, Button
    },
};
</script>