<script setup>
import {
    InformationCircleIcon,
    XMarkIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    XCircleIcon,
} from "@heroicons/vue/20/solid";
import { computed } from "vue";
import { cva } from "class-variance-authority";

const props = defineProps({
    intent: {
        type: String,
        validator(value) {
            return ["info", "warning", "success", "danger"].includes(value);
        },
        default: "info",
    },
    show: {
        type: Boolean,
        default: true,
    },
    onDismiss: Function,
});

if (props.show) {
    setTimeout(function () {
        dismiss();
    }, 10000);
}

const containerClass = computed(() => {
    return cva("absolute left-4 sm:left-auto top-5 right-4 leading-7 flex p-4 rounded-md space-x-3 sm:max-w-96 items-center", {
        variants: {
            intent: {
                info: "bg-blue-100",
                warning: "bg-yellow-100",
                success: "bg-primary-200",
                danger: "bg-red-100",
            },
        },
    })({
        intent: props.intent,
    });
});

const iconClass = computed(() => {
    return cva("w-6 h-6 text-blue-700", {
        variants: {
            intent: {
                info: "text-blue-700",
                warning: "text-yellow-700",
                success: "text-green-600",
                danger: "text-red-600",
            },
        },
    })({
        intent: props.intent,
    });
});

const messageClass = computed(() => {
    return cva("text-base whitespace-pre-line", {
        variants: {
            intent: {
                info: "text-blue-900",
                warning: "text-yellow-900",
                success: "text-green-900",
                danger: "text-red-900",
            },
        },
    })({
        intent: props.intent,
    });
});

const closeButtonClass = computed(() => {
    return cva("p-0.5 rounded -m-1 m-auto", {
        variants: {
            intent: {
                info: "text-blue-900/70 hover:text-blue-900 hover:bg-blue-200",
                warning: "text-yellow-900/70 hover:text-yellow-900 hover:bg-yellow-200",
                success: "text-green-900/70 hover:text-green-900 hover:bg-green-200",
                danger: "text-red-900/70 hover:text-red-900 hover:bg-red-200",
            },
        },
    })({
        intent: props.intent,
    });
});

const iconComponent = computed(() => {
    const icons = {
        success: CheckCircleIcon,
        warning: ExclamationTriangleIcon,
        danger: XCircleIcon,
        info: InformationCircleIcon,
    };

    return icons[props.intent];
});

function dismiss() {
    if (props.onDismiss) {
        props.onDismiss();
    }
}
</script>
<template>
    <transition leave-active-class="duration-300"
                leave-to-class="opacity-0">
        <div v-if="props.show"
             :class="containerClass">
            <div class="shrink-0">
                <component :is="iconComponent"
                           :class="iconClass" />
            </div>
            <div class="flex-1">
                <span :class="messageClass">
                    <slot></slot>
                </span>
            </div>
            <div class="shrink-0"
                 v-if="props.onDismiss">
                <button @click="dismiss"
                        :class="closeButtonClass">
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>
        </div>
    </transition>
</template>
