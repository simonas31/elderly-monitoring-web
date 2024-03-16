<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { ComputerDesktopIcon } from "@heroicons/vue/16/solid";

const form = useForm({});

const logout = () => {
    form.post("/logout");
};

const disabled = ref(false);
const showSlidedown = ref(false);

function slideDown() {
    disabled.value = true
    setTimeout(() => {
        disabled.value = false
    }, 1000)

    showSlidedown.value = true;
}

</script>
<template>
    <header class="grid grid-cols-2 sm:grid-cols-3 items-center h-20 header-border-bottom">
        <Link href="/"
              class="flex space-x-2 mx-auto items-center">
        <span class="border rounded-xl header-svg-bg-gradient font-semibold sm:scale-100 w-9 h-9">
            <ComputerDesktopIcon />
        </span>
        <span class="text-lg font-semibold">Elder Watch</span>
        </Link>
        <div class="mx-auto hidden sm:flex">
            <div class="border border-white rounded-l-full rounded-r-full inline-flex header-middle-bg-color">
                <div class="flex justify-between my-2 px-5 space-x-4">
                    <Link href="/profile">Profile</Link>
                </div>
            </div>
        </div>
        <div class="hidden sm:flex space-x-2 mx-auto">
            <div>Logged in as: </div>
            <button class="border rounded-xl px-2 pb-0.5 login-btn-bg-gradient text-md"
                    @click="logout">Logout</button>
        </div>
        <div class="mx-auto pl-14 sm:hidden"
             :class="{ shake: disabled }">
            <svg width="40"
                 height="40"
                 class="scale-75"
                 @click="slideDown">
                <line x1="0"
                      y1="10"
                      x2="40"
                      y2="10"
                      stroke-linecap="round"
                      stroke-width="5"
                      stroke="white" />
                <line x1="0"
                      y1="20"
                      x2="40"
                      y2="20"
                      stroke-linecap="round"
                      stroke-width="5"
                      stroke="white" />
                <line x1="0"
                      y1="30"
                      x2="40"
                      y2="30"
                      stroke-linecap="round"
                      stroke-width="5"
                      stroke="white" />
            </svg>
        </div>
        <Slidedown :show="showSlidedown"
                   @close-slidedown="showSlidedown = false"
                   @logout="logout" />
    </header>
</template>
<script>
import Slidedown from "@Components/Slidedown.vue";

export default {
    components: {
        Slidedown
    }
};
</script>
<style scoped>
.shake {
    animation: shake 0.8s both;
    transform-origin: 80%;
}

@keyframes shake {

    10%,
    90% {
        transform: rotate(5deg);
    }

    20%,
    80% {
        transform: rotate(10deg);
    }

    30%,
    50%,
    70% {
        transform: rotate(-10deg);
    }

    40%,
    60% {
        transform: rotate(5deg);
    }
}
</style>