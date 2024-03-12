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
        <div class="my-20 mx-4 auth-width text-sm sm:mx-auto sm:text-base">
            <div class="flex flex-col mx-auto bg-white shadow-lg text-black lg:flex-row">
                <div class="w-full py-5 px-4 sm:py-8 sm:px-8">
                    <div class="text-center mb-9">
                        <h1 class="text-2xl sm:text-3xl text-center mb-3">USER LOGIN</h1>
                        <h2>
                            Don't have an account?
                            <Link href="/register"
                                  class="text-blue-400 hover:text-blue-700">Register</Link>
                        </h2>
                    </div>
                    <div class="xxl:mx-20">
                        <form @submit.prevent="login">
                            <div>
                                <input type="email"
                                       id="email"
                                       class="border w-full input email"
                                       placeholder="Email"
                                       autocomplete="off"
                                       v-model="form.email" />
                            </div>
                            <div class="mt-8">
                                <!-- add ::before input password svg email icon -->
                                <input type="password"
                                       class="border w-full input password"
                                       placeholder="Password"
                                       autocomplete="on"
                                       v-model="form.password" />
                            </div>
                            <div class="fill justify-between items-center mt-8 flex">
                                <div>
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
                            <div class="mt-9">
                                <button class="border px-5 py-2 login-btn-bg-gradient text-lg sm:text-2xl w-full"
                                        type="submit">LOGIN</button>
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

export default {
    components: {
        Layout,
    },
};
</script>