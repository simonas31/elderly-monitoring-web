<script setup>
import { cva } from "class-variance-authority";
import { computed } from "vue";

const model = defineModel()
const props = defineProps({
    id: String,
    name: String,
    placeholder: String,
    options: {
        type: Array,
        default: [],
    },
    default: {
        type: String,
        default: "",
    },
    customClasses: {
        type: String,
        default: "",
    }
})

const inputClass = computed(() => {
    //border-t-transparent
    return cva("", {
        variants: {
            type: {
                select: "peer h-full w-full border border-t-white focus:border-t-white border-2 border-gray-300 px-3 py-2.5 text-sm sm:text-base text-blue-gray-700 transition-all focus:border-2 focus:border-primary-600 focus:border-t-transparent focus:outline-0 shadow-lg"
            }
        }
    })({
        type: 'select'
    });
});

const labelClass = computed(() => {
    return cva("", {
        variants: {
            type: {
                select: "before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full text-[11px] leading-tight transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2 before:border-t-2 before:border-l-2 before:border-gray-300 before:transition-all before:border-gray-300 after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:border-t-2 after:border-r-2 after:border-gray-300 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-primary-600 peer-focus:after:border-t-2 peer-focus:after:border-primary-600"
            }
        }
    })({
        type: 'select'
    });
});

</script>
<template>
    <select :id="props.id"
            :class="[inputClass, props.customClasses]"
            :placeholder="props.placeholder"
            :name="props.name"
            v-model="model">
        <option v-for="(option, index) in props.options"
                :key="index"
                :value="option"
                :selected="index == 0">{{ option }}
        </option>
    </select>
    <label :class="[labelClass, 'selectLabel']">
        {{ props.placeholder }}
    </label>
</template>