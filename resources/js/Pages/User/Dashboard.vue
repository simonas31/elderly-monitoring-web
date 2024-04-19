<script setup>
import { ref, onMounted } from "vue";
import { useForm } from '@inertiajs/vue3'

const props = defineProps(['videos', 'devices']);

const changeDeviceNameForm = useForm({
    device_name: ref(null),
    new_device_name: ref(null),
});

const devicesList = ref([]);
const showVideoModal = ref(false);
const videoUrl = ref(null);
const videoName = ref(null);

const toggleVideoModal = (url, name) => {
    showVideoModal.value = true;
    videoName.value = name;
    videoUrl.value = url;
};

const onDeviceChange = () => {
    console.log(changeDeviceNameForm.device_name);
    
    // changeDeviceNameForm.post('/changeDeviceName');
};

onMounted(() => {
    props.devices.forEach(element => {
        devicesList.value.push({ value: element.device_name, label: element.custom_device_name });
    });
    devicesList.value.push({ value: 'EW123123123', label: 'EW123123123' });
});

//watch changes of devices select
</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div class="container flex flex-col md:flex-row my-4 pb-2 mx-auto text-sm sm:text-base">
                        <div class="">
                            <div class="relative mb-4 mx-auto max-w-[300px]">
                                <select id="devices"
                                        class="peer h-full w-full border-t-white focus:border-t-white border-2 border-gray-300 px-3 py-2.5 text-sm sm:text-base text-blue-gray-700 transition-all focus:border-2 focus:border-primary-600 focus:border-t-transparent focus:outline-0"
                                        placeholder="Devices"
                                        name="devices"
                                        @change="onDeviceChange"
                                        v-model="changeDeviceNameForm.device_name">
                                    <option v-for="(option, index) in devicesList"
                                            :key="option.value"
                                            :value="option.value"
                                            :selected="index == 0">{{ option.label }}
                                    </option>
                                </select>
                                <label
                                       class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full text-[11px] leading-tight transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2 before:border-t-2 before:border-l-2 before:border-gray-300 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:border-t-2 after:border-r-2 after:border-gray-300 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:border-primary-600 peer-focus:after:border-t-2 peer-focus:after:border-primary-600 selectLabel">
                                    Devices
                                </label>
                            </div>
                            <p class="pl-2 pb-1 text-center">Device Captured Videos</p>
                            <div class="max-h-[200px] min-w-[300px] overflow-auto bg-white rounded px-4 py-2">
                                <div v-for="video in props.videos"
                                     class=""
                                     @click="toggleVideoModal(video.url, video.name)">
                                    <p class="hover:text-blue-400 hover:cursor-pointer">{{ video.name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="my-4 bg-white">
                            Activity Statistics
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :title="'Rename'"
               :show="showVideoModal"
               @close-modal="showVideoModal = false">
            <div class="p-4 md:p-5 text-black">
                <form @submit.prevent="rename()"
                      class="mx-auto">
                    <div class="mb-4">
                        <video width="640"
                               height="480"
                               controls>
                            <source :src="videoUrl"
                                    type='video/mp4' />
                        </video>
                    </div>
                </form>
            </div>
        </Modal>
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';
import Modal from "@Components/Modal.vue";

export default {
    components: { Layout, Modal },
}
</script>