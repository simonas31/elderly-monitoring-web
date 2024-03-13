<script setup>
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
                                               class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                               placeholder="Email address" />
                                        <label for="email"
                                               class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">Email
                                            Address</label>
                                    </div>
                                    <div class="relative">
                                        <input autocomplete="off"
                                               id="password"
                                               name="password"
                                               type="password"
                                               class="peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                               placeholder="Password" />
                                        <label for="password"
                                               class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm">Password</label>
                                    </div>
                                    <div class="relative">
                                        <div class="fill justify-between items-center mt-8 flex text-sm sm:text-base">
                                            <div class="block">
                                                <label for="rememberMe"
                                                       class="checkbox-container">
                                                    <input type="checkbox"
                                                           id="rememberMe"
                                                           name="rememberMe"
                                                           v-model="form.remember" />
                                                    <span class="checkmark"></span>
                                                    <p>Remember me</p>
                                                </label>
                                            </div>
                                            <Link href="/forgot-password">Forgot password?</Link>
                                        </div>
                                    </div>
                                    <div class="relative text-center pt-3">
                                        <Button intent="primary"
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