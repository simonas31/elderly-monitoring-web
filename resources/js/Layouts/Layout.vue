<script setup>
import { ref, watch } from "vue";

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
//used for default show alert message value
const showAlert = ref(true);

//used for watching show alert value change
//if changed to false, then showAlert value should be true always, but remove flash message because it will no longer be needed
watch(() => showAlert, (newShowAlert) => {
    if (!newShowAlert.value) {
        props.flash = null;
        showAlert.value = true;
    }
});


</script>
<template>
    <div class="flex flex-col min-h-screen">
        <Header></Header>
        <Alert v-if="$page.props.flash"
               :show="showAlert"
               :intent="$page.props.flash.type"
               :on-dismiss="() => (showAlert = false)">{{ $page.props.flash.message }}</Alert>
        <main class="flex-grow sm:m-auto text-black">
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
