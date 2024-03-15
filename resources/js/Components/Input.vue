<script setup>
import { cva } from "class-variance-authority";
import { computed } from "vue";

const props = defineProps({
    id: String,
    name: String,
    placeholder: String,
    autocomplete: String,
    accept: String,
    type: {
        type: String,
        default: "text"
    },
    customClasses: {
        type: String,
        default: "",
    }
})

const inputClass = computed(() => {
    return cva("", {
        variants: {
            type: {
                text: "pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:border-emerald-500",
                file: "",
                number: "",
                date: ""
            }
        }
    })({
        type: props.type
    });
});

const labelClass = computed(() => {
    return cva("", {
        variants: {
            type: {
                text: "absolute left-2 -top-4 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-gray-600 peer-focus:text-sm",
                file: "",
                number: "",
                date: ""
            }
        }
    })({
        type: props.type
    });
});

</script>
<template>
    <input :autocomplete="autocomplete"
           :id="props.id"
           :name="props.name"
           :type="props.type"
           :class="[inputClass, props.customClasses]"
           :placeholder="props.placeholder" />
    <label :for="props.name"
           :class="labelClass">
        <slot />
        {{ props.placeholder }}
    </label>
</template>