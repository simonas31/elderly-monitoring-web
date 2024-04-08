<script setup>
import { ref, computed, watch } from "vue";
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    auth: {
        type: Object,
        default: undefined,
    },
    flash: {
        type: Object,
        default: undefined,
    },
});

const showAlert = ref(true);

watch(() => page.props, (newFlash) => {
    if (newFlash && !showAlert.value) {
        showAlert.value = true;
    }
});
</script>
<template>
    <div class="flex flex-col min-h-screen">
        <Header></Header>
        <Alert v-if="page.props.flash"
               :show="showAlert"
               :intent="page.props.flash.type"
               :on-dismiss="() => (showAlert = false)">{{ page.props.flash.message }}</Alert>
        <main class="flex-grow">
            <slot></slot>
        </main>
        <Footer></Footer>
    </div>
</template>
<script>
import Header from "@Components/Header.vue";
import Footer from "@Components/Footer.vue";
import Alert from "@Components/Alert.vue";

export default {
    components: {
        Header,
        Footer,
        Alert
    },
};
</script>
