<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import { LockClosedIcon, BellAlertIcon, PhoneIcon } from "@heroicons/vue/20/solid";
import { UserCircleIcon } from "@heroicons/vue/16/solid";

const props = defineProps(['user', 'tab']);

const passwordForm = useForm({
    current_password: ref(null),
    new_password: ref(null),
});

const profileForm = useForm({
    profile_picture: ref(null),
});

const securityForm = useForm({
    security_type: ref(null),
});

const notificationsForm = useForm({
    fall_alert: ref(null),
});

const phoneNumberForm = useForm({
    phone_number: ref(null),
});

const isDropping = ref(false);
const dropped = ref(false);
const imageUrl = ref(null);
const requirementsMet = ref(false);
const confirmPassword = ref(null);
const securityError = ref(null);
const passwordErrors = {
    password: ref(null),
    newPassword: ref(null),
    newPasswordConfirm: ref(null),
}


const toggleTab = (tab, e) => {
    let content = document.getElementById(tab);
    let contents = document.querySelectorAll("[role='tabpanel']");
    let tabs = document.querySelectorAll("[data-tab-target]");

    contents.forEach(content => {
        content.classList.add("hidden");
    });

    content.classList.remove("hidden");

    tabs.forEach(tab => {
        tab.classList.remove("active-tab");
    });

    e.srcElement.classList.add("active-tab");
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    let imgUrl = URL.createObjectURL(file);
    profileForm.profile_picture = file;
    imageUrl.value = imgUrl;

    isDropping.value = false;
    dropped.value = true;
};

const onDragOver = (e) => {
    e.preventDefault();
    isDropping.value = true;
};

const onDragLeave = (e) => {
    e.preventDefault();
    isDropping.value = false;
};

const onDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    isDropping.value = false;

    if (file.size / 10 >= 16) {
        alert("file is too large");
        return;
    }

    form.profile_picture = file;
    let imgUrl = URL.createObjectURL(file);
    imageUrl.value = imgUrl;

    dropped.value = true;
}

watch(() => passwordForm.new_password, (newPassword) => {
    requirementsMet.value = true;
    if (newPassword.length >= 8) {
        document.querySelector(".atleast").classList.remove("x-sign");
        document.querySelector(".atleast").classList.add("ok-sign");
    } else {
        document.querySelector(".atleast").classList.remove("ok-sign");
        document.querySelector(".atleast").classList.add("x-sign");
        requirementsMet.value = false;
    }

    if (/[A-Z]/.test(newPassword)) {
        document.querySelector(".upper").classList.remove("x-sign");
        document.querySelector(".upper").classList.add("ok-sign");
    } else {
        document.querySelector(".upper").classList.remove("ok-sign");
        document.querySelector(".upper").classList.add("x-sign");
        requirementsMet.value = false;
    }

    if (/[a-z]/.test(newPassword)) {
        document.querySelector(".lower").classList.remove("x-sign");
        document.querySelector(".lower").classList.add("ok-sign");
    } else {
        document.querySelector(".lower").classList.remove("ok-sign");
        document.querySelector(".lower").classList.add("x-sign");
        requirementsMet.value = false;
    }

    if (/[0-9]/.test(newPassword)) {
        document.querySelector(".number").classList.remove("x-sign");
        document.querySelector(".number").classList.add("ok-sign");
    } else {
        document.querySelector(".number").classList.remove("ok-sign");
        document.querySelector(".number").classList.add("x-sign");
        requirementsMet.value = false;
    }
});

const validatePassword = () => {
    if (passwordForm.current_password == null) {
        passwordErrors.password.value = "Current password cannot be empty";
        return;
    } else {
        passwordErrors.password.value = null;
    }

    if (!requirementsMet.value) {
        passwordErrors.newPassword.value = "Password did not meet the requirements";
        return;
    } else {
        passwordErrors.newPassword.value = null;
    }

    if (passwordForm.new_password != confirmPassword.value) {
        passwordErrors.newPasswordConfirm.value = "Passwords are not equal";
        return;
    } else {
        passwordErrors.newPasswordConfirm.value = null;
    }
    //post
    passwordForm.post('/changePassword');
};

const changeProfilePicture = () => {
    if (profileForm.profile_picture == null) {
        console.log(null);
        return;
    }
    profileForm.post('/changeProfilePicture', {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onSuccess: () => {
            // Do something on successful form submission
        },
    });
};

const updateSecurity = () => {
    if (securityForm.security_type == null) {
        securityError.value = "Security type cannot be empty";
        return;
    }

    securityForm.post('/changeSecurityType');
};

const updateNotifications = () => {
    notificationsForm.post('/toggleNotifications');
};

const updatePhoneNumber = () => {
    phoneNumberForm.post('/changePhoneNumber');
};

onMounted(() => {
    securityForm.security_type = props.user.security_type;
    notificationsForm.fall_alert = props.user.fall_notifications;
    phoneNumberForm.phone_number = props.user.phone_number;
    // if (props.user.fall_notifications) {
    //     var checkbox = document.getElementsByClassName("checkmark")[0];
    //     checkbox.classList.add('checked');
    //     console.log(checkbox);
    // }
});

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5">
            <div class="flex flex-col auth-width bg-secondary-300/40 m-auto shadow-lg text-black">
                <div class="my-4 pb-2 mx-auto text-sm sm:text-base">
                    <ul class="flex flex-wrap -mb-px text-center"
                        id="default-tab"
                        role="tablist">
                        <li class="me-2"
                            role="presentation">
                            <button class="inline-block p-4 border-b-2 hover:border-primary-500 hover:bg-primary-200 active-tab"
                                    id="profile-tab"
                                    data-tab-target="#profile"
                                    type="button"
                                    role="tab"
                                    aria-controls="profile"
                                    aria-selected="true"
                                    @click="toggleTab('profile', $event)">Profile</button>
                        </li>
                        <li class="me-2"
                            role="presentation">
                            <button class="inline-block p-4 border-b-2 hover:border-primary-500 hover:bg-primary-200"
                                    id="privacy-settings-tab"
                                    data-tab-target="#privacy-settings"
                                    type="button"
                                    role="tab"
                                    aria-controls="privacy-settings"
                                    aria-selected="false"
                                    @click="toggleTab('privacy-settings', $event)">Privacy Settings</button>
                        </li>
                        <!-- <li role="presentation">
                            <button class="inline-block p-4 border-b-2 hover:border-primary-500 hover:bg-primary-200"
                                    id="notifications-tab"
                                    data-tab-target="#notifications"
                                    type="button"
                                    role="tab"
                                    aria-controls="notifications"
                                    aria-selected="false"
                                    @click="toggleTab('notifications', $event)">Notifications</button>
                        </li> -->
                    </ul>
                </div>
                <div id="default-tab-content text-sm sm:text-base"
                     class="border-t-2 header-border-top">
                    <div class="p-4 m-4 rounded-lg"
                         id="profile"
                         role="tabpanel"
                         aria-labelledby="profile-tab">
                        <div class="flex flex-col sm:flex-row">
                            <div class="flex flex-col w-[200px] sm:w-[400px] max-w-[500px] items-center mx-auto"
                                 @dragover.prevent="onDragOver"
                                 @dragleave.prevent="onDragLeave"
                                 @drop.prevent="onDrop">
                                <span class="mb-5">Profile picture</span>
                                <label for="dropzone-file"
                                       class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg v-if="!isDropping && !dropped"
                                             class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                             aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg"
                                             fill="none"
                                             viewBox="0 0 20 16">
                                            <path stroke="currentColor"
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <div class="my-auto">
                                            <img v-if="imageUrl"
                                                 :src="imageUrl"
                                                 alt="Uploaded Image"
                                                 style="border: 1px solid #555; border-radius: 50%; width: 50px; height: 50px;" />
                                        </div>
                                        <p v-if="!isDropping && !dropped"
                                           class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p v-else-if="isDropping && !dropped"
                                           class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Drop image.</span>
                                        </p>
                                        <p v-else-if="dropped"
                                           class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Image uploaded successfully.</span>
                                        </p>


                                        <p v-if="!isDropping && !dropped"
                                           class="text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG (MAX. 16MB)
                                        </p>
                                    </div>
                                    <input id="dropzone-file"
                                           name="profile_picture"
                                           type="file"
                                           accept=".png, .jpg, .jpeg"
                                           class="hidden"
                                           @change="onFileChange" />
                                </label>
                                <Button intent="primary"
                                        customClasses="sm:min-h-[40px] px-4 mt-4"
                                        id="submit"
                                        @click="changeProfilePicture"
                                        :rightIcon="UserCircleIcon">Change Profile Picture</Button>
                            </div>
                            <div
                                 class="flex flex-col w-[200px] sm:w-[400px] max-w-[500px] mt-[40px] sm:mt-[100px] items-center mx-auto">
                                <div class="relative">
                                    <Input autocomplete="off"
                                           id="current_phone"
                                           name="current_phone"
                                           type="text"
                                           placeholder="Current Phone Number"
                                           v-model="phoneNumberForm.phone_number" />
                                </div>
                                <Button intent="primary"
                                        customClasses="sm:min-h-[40px] px-4 mt-4 mx-auto"
                                        id="submit"
                                        @click="updatePhoneNumber"
                                        :rightIcon="PhoneIcon">Change Phone Number</Button>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 my-4 sm:p-4 sm:m-4 rounded-lg hidden"
                         id="privacy-settings"
                         role="tabpanel"
                         aria-labelledby="privacy-settings-tab">
                        <div class="flex flex-col sm:flex-row text-sm sm:text-base mx-auto">
                            <div class="text-sm sm:text-base mx-5 sm:mx-auto sm:w-2/6">
                                <div class="relative">
                                    <Select id="security_type"
                                            name="security_type"
                                            placeholder="Security Type"
                                            :options="['None', 'Email', 'SMS']"
                                            v-model="securityForm.security_type" />
                                    <span v-if="securityError"
                                          class="text-rose-600">{{ securityError }}</span>
                                </div>
                                <Button intent="primary"
                                        customClasses="sm:min-h-[40px] px-4 my-5 w-full"
                                        id="submit"
                                        @click="updateSecurity"
                                        :rightIcon="LockClosedIcon">Update Security Type</Button>
                            </div>
                            <div
                                 class="flex flex-col text-sm sm:text-base mx-5 sm:mx-auto space-y-6 sm:w-1/2 max-w-[500px]">
                                <div class="relative">
                                    <Input autocomplete="off"
                                           id="current_password"
                                           name="current_password"
                                           type="password"
                                           placeholder="Current Password"
                                           v-model="passwordForm.current_password" />
                                    <span v-if="passwordErrors.password"
                                          class="text-rose-600">{{ passwordErrors.password.value }}</span>
                                </div>

                                <div class="relative">
                                    <Input autocomplete="off"
                                           id="new_password"
                                           name="new_password"
                                           type="password"
                                           placeholder="New Password"
                                           v-model="passwordForm.new_password" />
                                    <span v-if="passwordErrors.newPassword"
                                          class="text-rose-600">{{ passwordErrors.newPassword.value }}</span>
                                </div>

                                <div class="relative">
                                    <Input autocomplete="off"
                                           id="confirm_password"
                                           name="confirm_password"
                                           type="password"
                                           placeholder="New Password Confirm"
                                           v-model="confirmPassword" />
                                    <span v-if="passwordErrors.newPasswordConfirm"
                                          class="text-rose-600">{{ passwordErrors.newPasswordConfirm.value }}</span>
                                </div>

                                <div class="mt-2 px-3 py-2 bg-primary-300 text-center">
                                    <b>Password must contain:</b>
                                    <div class="sm:ml-2">
                                        <span class="x-sign atleast"></span>
                                        <span class="ml-1">At least 8 characters</span>
                                        <br />
                                        <span class="x-sign upper"></span>
                                        <span class="ml-1">One upper case letter (A-Z)</span>
                                        <br />
                                        <span class="x-sign lower"></span>
                                        <span class="ml-1">One lower case letter (a-z)</span>
                                        <br />
                                        <span class="x-sign number"></span>
                                        <span class="ml-1">One numeric character (0-9)</span>
                                        <br />
                                    </div>
                                </div>
                                <Button intent="primary"
                                        customClasses="sm:min-h-[40px] px-4"
                                        id="submit"
                                        @click="validatePassword"
                                        :rightIcon="LockClosedIcon">Change Password</Button>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 m-4 sm:p-4 sm:m-4 hidden"
                         id="notifications"
                         role="tabpanel"
                         aria-labelledby="notifications-tab">
                        <div class="container mx-auto max-w-fit text-center">
                            <div class="relative">
                                <div class="fill justify-between items-center mt-8 flex text-sm sm:text-base">
                                    <div class="block">
                                        <label for="fall_alert"
                                               class="checkbox-container">
                                            <input type="checkbox"
                                                   id="fall_alert"
                                                   name="fall_alert"
                                                   v-model="notificationsForm.fall_alert" />
                                            <span class="checkmark"></span>
                                            <p>Fall Alert Notification</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <Button intent="primary"
                                    customClasses="sm:min-h-[40px] px-4 mt-8 mb-4"
                                    id="submit"
                                    @click="updateNotifications"
                                    :rightIcon="BellAlertIcon">Update Notifications</Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from '@Layouts/Layout.vue';
import Input from '@Components/Input.vue';
import Button from "@Components/Button.vue";
import Select from "@Components/Select.vue";

export default {
    components: { Layout, Input, Button, Select },
}
</script>