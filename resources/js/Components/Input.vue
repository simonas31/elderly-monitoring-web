<script setup>
import { cva } from "class-variance-authority";
import { computed } from "vue";

const model = defineModel()
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
                text: "pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-primary-600 shadow-lg",
                password: "pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-primary-600 shadow-lg",
                file: "",
                number: "",
                date: "p-2 peer h-10 w-full border-b-2 border-gray-300 focus:outline-none focus:border-primary-600 shadow-lg",
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
                text: "absolute left-2 -top-4 text-sm peer-placeholder-shown:text-sm peer-placeholder-shown:sm:text-base peer-placeholder-shown:top-2.5 peer-placeholder-shown:sm:top-2 transition-all peer-focus:-top-4 peer-focus:sm:-top-4 peer-focus:sm:text-sm",
                password: "absolute left-2 -top-4 text-sm peer-placeholder-shown:text-sm peer-placeholder-shown:sm:text-base peer-placeholder-shown:top-2.5 peer-placeholder-shown:sm:top-2 transition-all peer-focus:-top-4 peer-focus:sm:-top-4 peer-focus:sm:text-sm",
                file: "",
                number: "",
                date: "absolute left-2 -top-4 text-sm peer-placeholder-shown:text-sm peer-placeholder-shown:sm:text-base peer-placeholder-shown:top-2.5 peer-placeholder-shown:sm:top-2 transition-all peer-focus:-top-4 peer-focus:text-sm"
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
           :placeholder="props.placeholder"
           v-model="model" />
    <label :for="props.name"
           :class="[labelClass, props.customClasses]">
        <slot />
        {{ props.placeholder }}
    </label>
</template>