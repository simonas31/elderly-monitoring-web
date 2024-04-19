<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    show: {
        type: Boolean,
        default: true,
    },
    user: Object
});

const emit = defineEmits(["closeSlidedown", "logout"]);

const closeSlidedown = () => {
    emit("closeSlidedown");
};

const logout = () => {
    emit("logout");
};
</script>
<template>
    <transition name="sidebarBackground">
        <div v-if="show"
             class="fixed inset-0 bg-gray-800 z-40 opacity-70"
             id="sidebarBackgroundId"
             @click="closeSlidedown()"></div>
    </transition>
    <Transition name="sidebar">
        <div v-if="show"
             class="fixed top-0 right-0 left-0 h-[400px] z-50 text-black">
            <div class="relative bg-white h-full">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl sm:text-xl font-semibold text-gray-900">Settings</h3>
                    <button type="button"
                            class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            @click="closeSlidedown">
                        <svg class="w-3 h-3"
                             aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 14 14">
                            <path stroke="currentColor"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only"></span>
                    </button>
                </div>
                <!-- Slidedown body -->
                <div class="flex items-center justify-center h-3/4">
                    <div class="p-5 text-center flex flex-col space-y-4">
                        <div>
                            <Link href="/dashboard">
                            Dashboard
                            </Link>
                        </div>
                        <div v-if="props.user.role_id == 1">
                            <Link href="/devices">
                            Devices
                            </Link>
                        </div>
                        <div v-if="props.user.parent_user_id == null">
                            <Link href="/invite">
                            Invite others
                            </Link>
                        </div>
                        <div>
                            <Link href="/supervisors">
                            {{ props.user.role_id == 1 ? "Users" : "Supervisors" }}
                            </Link>
                        </div>
                        <div>
                            <Link href="/settings">
                            Settings
                            </Link>
                        </div>
                        <button @click="logout"
                                class="border rounded-xl px-3 py-1 login-btn-bg-gradient text-md">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
<script>
export default {
    components: {},
};
</script>
<style scoped>
.sidebar-enter-active {
    animation: slide-sidebar 0.6s ease;
}

.sidebar-leave-active {
    animation: slide-sidebar 0.6s ease reverse;
}

.sidebarBackground-enter-active,
.sidebarBackground-leave-active {
    transition: opacity .6s;
}

.sidebarBackground-enter-from,
.sidebarBackground-leave-to {
    opacity: 0;
}

@keyframes sidebarBackground-opacity {
    from {
        opacity: 0;
    }

    to {
        opacity: 0;
    }
}

@keyframes slide-sidebar {
    from {
        transform: translateY(-100%);
    }

    to {
        transform: translateY(0);
    }
}
</style>
