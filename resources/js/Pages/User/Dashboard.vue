<script setup>
import { ref, onMounted, watch } from "vue";
import { useForm, router } from '@inertiajs/vue3'
import { VideoCameraIcon } from "@heroicons/vue/16/solid";

const props = defineProps(['videos', 'devices', 'selectedDevice', 'logs']);

const changeDeviceNameForm = useForm({
    device_name: ref(null),
    new_device_name: ref(null),
});

const changeDeviceNameErorrs = ref(null);
const months = ['January', 'February', 'March', 'April', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const devicesList = ref([]);
const showVideoModal = ref(false);
const showDeviceNameChangeModal = ref(false);
const videoUrl = ref(null);
const videoName = ref(null);
const activeChart = ref(1);
const daysInMonth = ref(28);

const customStyle = {
    'position': 'relative',
    'width': 'auto',
    'height': '400px'
}

const chartOptions1 = {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        x: {
            grid: {
                display: false  // Set display to false to hide grid lines
            },
            title: {
                display: true,
                text: 'Hours of the day'
            }
        },
    },
    y: {
        beginAtZero: true
    }
}

const chartOptions2 = {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        x: {
            grid: {
                display: false  // Set display to false to hide grid lines
            },
            title: {
                display: true,
                text: 'Days'
            }
        },
        y: {
            beginAtZero: true
        }
    }
}

const chartOptions3 = {
    responsive: true,
    maintainAspectRatio: true,
    scales: {
        x: {
            grid: {
                display: false  // Set display to false to hide grid lines
            },
            title: {
                display: true,
                text: 'Months'
            }
        },
        y: {
            beginAtZero: true
        }
    }
}

const chartDataHours = {
    labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
    datasets: [{
        label: '# of Activities Captured',
        data: props.logs.hours,
        backgroundColor: [
            'green'
        ],
        borderWidth: 1
    }]
};

const chartDataDays = {
    labels: [...Array.from(daysInMonth.value).keys()].map(i => i + 1),
    datasets: [{
        label: '# of Activities Captured',
        data: props.logs.days,
        backgroundColor: [
            'red',
        ],
        borderWidth: 1
    }]
};

const chartDataMonths = {
    labels: months,
    datasets: [{
        label: '# of Activities Captured',
        data: props.logs.months,
        backgroundColor: [
            'blue',
        ],
        borderWidth: 1
    }]
};

const toggleVideoModal = (url, name) => {
    showVideoModal.value = true;
    videoName.value = name;
    videoUrl.value = url;
};

const onDeviceChange = () => {
    changeDeviceNameForm.post('/dashboard');
};

const changeDeviceNameSubmit = () => {
    if (changeDeviceNameForm.new_device_name == null) {
        changeDeviceNameErorrs.value = "Device name is required";
        return;
    } else {
        changeDeviceNameErorrs.value = null;
    }

    showDeviceNameChangeModal.value = false;
    changeDeviceNameForm.post('/changeDeviceName', {
        onSuccess: (response) => {
            router.visit('/dashboard');
        }
    });
};

watch(() => props.selectedDevice, (newSelectedDevice) => {
    changeDeviceNameForm.device_name = newSelectedDevice;
    const found = devicesList.value.find(element => element.value == props.selectedDevice);
    if (found != undefined) {
        changeDeviceNameForm.new_device_name = found.label;
    }
});

onMounted(() => {
    props.devices.forEach(element => {
        devicesList.value.push({ value: element.device_name, label: element.custom_device_name });
    });

    if (devicesList.value.length > 0) {
        changeDeviceNameForm.device_name = devicesList.value[0].value;
        changeDeviceNameForm.new_device_name = devicesList.value[0].label;
    }

    daysInMonth.value = props.logs.days.length;
});

const switchChart = (chartId) => {
    activeChart.value = chartId;
};

//watch changes of devices select
</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-[1100px]">
            <div class="bg-secondary-300/40 m-auto px-2 shadow-lg">
                <div class="flex flex-col lg:flex-row text-black">
                    <div
                         class="container flex flex-col md:w-full my-4 pb-2 mx-auto justify-center text-sm sm:text-base">
                        <div class="flex flex-col sm:flex-row items-center mx-auto">
                            <div v-if="devicesList.length > 0"
                                 class="w-auto h-[170px] mb-4 sm:mb-0 sm:h-[200px] sm:mr-2 bg-white rounded pt-[50px] px-3 shadow-lg">
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
                                <div class="text-center pb-5">
                                    <Button intent="primary"
                                            @click="showDeviceNameChangeModal = true">
                                        Change Device Name
                                    </Button>
                                </div>
                            </div>
                            <div v-else
                                class="w-auto h-[170px] sm:h-[200px] sm:mr-2 bg-white rounded px-10 shadow-lg flex justify-center items-center">
                                <p class="font-bold">No device registered</p>
                            </div>
                            <div
                                 class="max-h-[200px] min-h-[150px] w-auto sm:w-[285px] bg-white rounded px-4 py-2 shadow-lg">
                                <p class="pl-2 pb-1 text-center">Device Captured Videos</p>
                                <hr class="h-px my-2 bg-gray-300 border-0">
                                <div class="max-h-[140px] overflow-auto">
                                    <div v-if="props.videos"
                                         v-for="video in props.videos"
                                         class="flex flex-row space-x-2 justify-center hover:bg-primary-300 hover:cursor-pointer hover:font-bold pl-4 pr-6 sm:px-0"
                                         @click="toggleVideoModal(video.url, video.name)">
                                        <VideoCameraIcon class="w-[20px] hover:bg-primary-400"></VideoCameraIcon>
                                        <p>{{ video.name }}</p>
                                    </div>
                                    <div v-else
                                         class="text-center pt-4 max-h-[200px] min-h-[150px]">
                                        <p class="font-bold">No video captured</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                             class="lg:mx-auto md:pl-8 md:pr-10 my-4 bg-white text-center rounded-lg justify-center shadow-lg">
                            <p>Activity Statistics</p>
                            <div class="space-x-2 my-3 space-y-2">
                                <Button intent="primary"
                                        @click="switchChart(1)">Today's statistics</Button>
                                <Button intent="primary"
                                        @click="switchChart(2)">Days's statistics</Button>
                                <Button intent="primary"
                                        @click="switchChart(3)">Month's statistics</Button>
                            </div>
                            <div v-if="activeChart == 1">
                                <Bar :data="chartDataHours"
                                     :style="customStyle"
                                     :options="chartOptions1"
                                     class="w-fit" />
                            </div>
                            <div v-else-if="activeChart == 2">
                                <Bar :data="chartDataDays"
                                     :style="customStyle"
                                     :options="chartOptions2"
                                     class="w-fit" />
                            </div>
                            <div v-else>
                                <Bar :data="chartDataMonths"
                                     :style="customStyle"
                                     :options="chartOptions3"
                                     class="w-fit" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :title="'Video playback'"
               :show="showVideoModal"
               @close-modal="showVideoModal = false">
            <div class="p-4 md:p-5 text-black">
                <div class="mb-4">
                    <video width="640"
                           height="480"
                           controls>
                        <source :src="videoUrl"
                                type='video/mp4' />
                    </video>
                </div>
            </div>
        </Modal>
        <Modal :title="'Change current device name'"
               :show="showDeviceNameChangeModal"
               @close-modal="showDeviceNameChangeModal = false">
            <div class="p-4 md:p-5 text-black">
                <div class="relative mb-4">
                    <Input autocomplete="off"
                           id="new_device_name"
                           name="new_device_name"
                           type="text"
                           placeholder="New device name"
                           v-model="changeDeviceNameForm.new_device_name"></Input>
                    <span v-if="changeDeviceNameErorrs"
                          class="text-rose-600">{{ changeDeviceNameErorrs }}</span>
                </div>
                <div>
                    <Button intent="primary"
                            @click="changeDeviceNameSubmit">
                        Change Device Name
                    </Button>
                </div>
            </div>
        </Modal>
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';
import Modal from "@Components/Modal.vue";
import Button from '@Components/Button.vue';
import Input from '@Components/Input.vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    components: { Layout, Modal, Button, Input, Bar },
}
</script>