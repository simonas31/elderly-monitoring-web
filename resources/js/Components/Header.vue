<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { ComputerDesktopIcon } from "@heroicons/vue/16/solid";

const form = useForm({});

const props = defineProps({
    auth: {
        type: Object,
        default: undefined
    }
});

const logout = () => {
    form.post("/logout");
};

const disabled = ref(false);
const showSlidedown = ref(false);
const dropdown = ref(false);

function slideDown() {
    disabled.value = true
    setTimeout(() => {
        disabled.value = false
    }, 1000)

    showSlidedown.value = true;
}

function toggleDropdown() {
    dropdown.value = !dropdown.value;
}

</script>
<template>
    <header class="flex flex-row justify-between w-full items-center h-20 header-border-bottom">
        <Link href="/"
              class="flex space-x-2 mx-auto items-center">
        <span class="border rounded-xl header-svg-bg-gradient font-semibold sm:scale-100 w-9 h-9">
            <ComputerDesktopIcon />
        </span>
        <span class="text-lg font-semibold">Elder Watch</span>
        </Link>
        <div class="hidden sm:flex space-x-2 mx-auto items-center bg-primary-300 relative p-1 pl-2 rounded-full hover:cursor-pointer"
             @click="toggleDropdown"
             v-if="props.auth.user">
            <div class="">{{ props.auth.user.name + " " + props.auth.user.surname }}</div>
            <div class="sm:scale-100 w-9 h-9">
                <img class="rounded-full sm:scale-100 object-cover w-9 h-9"
                     :src="props.auth ? props.auth.user['profile_picture'] : 'https://t4.ftcdn.net/jpg/00/64/67/63/360_F_64676383_LdbmhiNM6Ypzb3FM4PPuFP9rHe7ri8Ju.jpg'">
            </div>
            <div
                 :class="'rounded transition-all border-gray-300 bg-white p-4 absolute top-[50px] right-0 w-[200px] shadow-lg space-y-2 z-50 ' + (dropdown ? '' : 'hidden')">
                <div>
                    <Link href="/dashboard">
                    Dashboard
                    </Link>
                </div>
                <div>
                    <Link href="/settings">
                    Settings
                    </Link>
                </div>
                <hr>
                <div class="cursor-pointer"
                     @click="logout">Logout</div>
            </div>
        </div>
        <div class="hidden sm:flex space-x-2 mx-auto items-center rounded-full hover:cursor-pointer"
             v-if="!props.auth.user">
            <Button intent="primary"
                    customClasses="sm:min-h-[40px] px-4 rounded-full">
                <Link href="/dashboard">Login</Link>
            </Button>
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
                      class="header-svg-stroke-color" />
                <line x1="0"
                      y1="20"
                      x2="40"
                      y2="20"
                      class="header-svg-stroke-color" />
                <line x1="0"
                      y1="30"
                      x2="40"
                      y2="30"
                      class="header-svg-stroke-color" />
            </svg>
        </div>
        <Slidedown :show="showSlidedown"
                   @close-slidedown="showSlidedown = false"
                   @logout="logout" />
    </header>
</template>
<script>
import Slidedown from "@Components/Slidedown.vue";
import Button from "@Components/Button.vue";

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