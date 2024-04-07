<script setup>
import { cva } from "class-variance-authority";
import { computed } from "vue";

const props = defineProps({
    leftIcon: Function,
    rightIcon: Function,
    loading: Boolean,
    as: {
        type: [String, Object],
        default: "button",
    },
    intent: {
        type: String,
        validator(value) {
            return ["primary", "secondary", "danger", "text"].includes(value);
        },
        default: "secondary",
    },
    customClasses: {
        type: String,
        default: "",
    }
});

const buttonClass = computed(() => {
    return cva("transition-all inline-flex items-center justify-center text-sm min-h-[32px] px-3 py-1 font-semibold", {
        variants: {
            intent: {
                primary: "bg-khaki text-white",
                secondary: "bg-black/5 hover:bg-black/10 text-gray-700",
                danger: "bg-red-600 text-white hover:bg-red-500",
                text: "text-gray-700 hover:bg-black/5",
            },
        },
    })({
        intent: props.intent,
    });
});

//Add loading indicator and disabled class
function disable(e) {
    // if (!localDisabled.value) {
    //     localDisabled.value = true;
    // } else {
    //     e.preventDefault();
    // }
}
</script>
<template>
    <component :is="props.as"
               :class="[buttonClass, props.customClasses, props.loading && 'disabled']"
               type="button">
        <svg v-if="props.loading"
             class="animate-spin h-5 w-5 absolute"
             xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24">
            <circle class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"></circle>
            <path class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>

        <component :is="props.leftIcon"
                   :class="['w-5 h-5 mr-2', props.loading && 'invisible']" />

        <span :class="[props.loading && 'invisible']">
            <slot />
        </span>

        <component :is="props.rightIcon"
                   :class="['w-5 h-5 ml-2', props.loading && 'invisible']" />
    </component>
</template>
