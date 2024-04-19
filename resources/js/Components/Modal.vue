<script setup>
import { vOnClickOutside } from "@vueuse/components"

const props = defineProps({
    title: {
        type: String,
        default: 'Modal title'
    },
    show: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['closeModal']);
const closeModal = () => {
    emit('closeModal');
};
</script>
<template>
    <Teleport to="#modals">
        <Transition>
            <div v-if="show"
                 class="bg-gray-900/50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 justify-center items-center w-full md:inset-0 h-[calc(100%)] max-h-full flex">
                <div class="relative m-4 w-full max-w-md max-h-full"
                     v-on-click-outside="closeModal">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900">{{ title }}</h3>
                            <button type="button"
                                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                    @click="closeModal">
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
                        <!-- Modal body -->
                        <slot></slot>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.25s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>