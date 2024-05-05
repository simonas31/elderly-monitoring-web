<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import { ArrowLongRightIcon, ArrowLongLeftIcon } from "@heroicons/vue/20/solid";

const roles = ['Relative', 'Caregiver'];

const form = useForm({
    name: ref(null),
    surname: ref(null),
    password: ref(null),
    confirm_password: ref(null),
    email: ref(null),
    date_of_birth: ref(null),
    phone_number: ref(null),
    profile_picture: ref(null),
    security_type: ref(null),
    parent_user_id: ref(null), // add user_id from invite request token
    role_id: ref(null),
});

const step1Errors = {
    name: ref(null),
    surname: ref(null),
    date_of_birth: ref(null),
    role_id: ref(null),
};

const step2Errors = {
    email: ref(null),
    phone_number: ref(null),
};

const step3Errors = {
    security_type: ref(null),
    password: ref(null),
    confirm_password: ref(null),
};

const errors = [
    step1Errors,
    step2Errors,
    step3Errors
];

const confirmPassword = ref(null);
const loadingPrev = ref(false);
const loadingNext = ref(false);
const isDropping = ref(false);
const dropped = ref(false);
const imageUrl = ref(null);
const currentStep = ref(0);
const requirementsMet = ref(false);

const register = async (e) => {
    e.preventDefault();
    if (!validateForm()) {
        return false;
    }

    let query = '';
    if (window.location.href.split('/register?')[1] != undefined) {
        query = '?' + window.location.href.split('/register?')[1];
    }
    form.post('/register' + query, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
        onSuccess: () => {
            // Do something on successful form submission
        },
    });
};

watch(() => form.password, (newPassword) => {
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

const validEmail = (email) => {
    var validRegex = /^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*$/;
    return email.match(validRegex);
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    let imgUrl = URL.createObjectURL(file);
    form.profile_picture = file;
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

function showTab(n) {
    fixStepIndicator(n)
}

function nextPrev(n) {
    var x = document.getElementsByClassName("step");

    if (n == 1 && !validateForm()) {
        return false;
    }
    n == 1 ? loadingNext.value = true : loadingPrev.value = true;

    if (currentStep.value + n >= 3) {
        return true;
    }
    currentStep.value += n;

    n == 1 ? loadingNext.value = false : loadingPrev.value = false;
    showTab(currentStep.value);
}

function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("step");
    let currentTab = 0;
    y = x[currentTab].getElementsByTagName("input");

    let select = x[currentTab].getElementsByTagName("select");
    let selectLabel = x[currentTab].getElementsByClassName("selectLabel");
    if (select.length > 0 && form.security_type == null && currentStep.value == 2) {
        errors[currentStep.value][select[0].name].value = "Security type is required";
        valid = false;
        selectLabel[0].classList.add("before:border-rose-300", "after:border-rose-300");
        select[0].classList.add("border-rose-300");
    } else if (select.length > 0 && form.role_id == null && currentStep.value == 0) {
        errors[currentStep.value][select[0].name].value = "Position is required";
        valid = false;
        selectLabel[0].classList.add("before:border-rose-300", "after:border-rose-300");
        select[0].classList.add("border-rose-300");
    } else if (select.length > 0 && form.security_type != null) {
        errors[currentStep.value][select[0].name].value = null;
        selectLabel[0].classList.add("before:border-primary-600", "after:border-primary-600");
        select[0].classList.remove("border-rose-300");
        select[0].classList.add("border-primary-600");
    }

    for (i = 0; i < y.length; i++) {
        if (!checkInputValidity(y[i], currentStep.value)) {
            y[i].className += " invalid";
            valid = false;
        } else if (y[i].classList.contains("invalid")) {
            y[i].classList.remove("invalid");
            y[i].classList.add("border-primary-600");
        } else {
            y[i].classList.add("border-primary-600");
        }
    }

    if (valid && !document.getElementsByClassName("stepIndicator")[currentStep.value].classList.contains("finish")) {
        document.getElementsByClassName("stepIndicator")[currentStep.value].className += " finish";
    }

    return valid;
}

function checkInputValidity(element, currentTab) {
    let valid = true;

    if (element.name == "profile_picture") {
        return true;
    }

    if (element.value == "") {
        errors[currentTab][element.name].value = element.placeholder + " is required";
        valid = false;
    } else if (element.name == "email" && !validEmail(element.value)) {
        errors[currentTab][element.name].value = "Invalid email";
        valid = false;
    } else if (element.name == "password" && !requirementsMet.value) {
        errors[currentTab][element.name].value = "Password does not meet requirements";
        valid = false;
    } else if (element.name == "confirm_password" && document.getElementById("password").value != element.value) {
        errors[currentTab][element.name].value = "Passwords do not match";
        valid = false;
    } else {
        element.classList.add("border-primary-600");
        errors[currentTab][element.name].value = null;
        form[element.name] = element.value;
    }

    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("stepIndicator");

    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    x[n].className += " active";
}

const prefilled = ref(false);

onMounted(() => {
    showTab(currentStep.value);

    if (window.location.href.split('/register/')[1] != undefined) {
        axios.post('/api/decrypt', {
            encryptedData: window.location.href.split('/register/')[1]
        })
            .then(response => {
                const decryptedData = response.data.decryptedData;
                prefilled.value = true;
                form.email = decryptedData.email;
                form.role_id = decryptedData.invited_user_role_id;
                form.name = decryptedData.invited_user_name;
                form.surname = decryptedData.invited_user_surname;
            })
            .catch(error => {
                // router.visit('/');
            });
    }
})

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5 text-sm sm:text-base">
            <div class="flex flex-col auth-width bg-secondary-300/40 m-auto shadow-lg text-black animate-h">
                <form id="signUpForm">
                    <!-- start step indicators -->
                    <div class="form-header flex gap-3 pt-4 pb-6 header-border-bottom text-xs sm:text-base text-center">
                        <span class="stepIndicator flex-1 pb-8 relative">Personal Info</span>
                        <span class="stepIndicator flex-1 pb-8 relative">Contanct Info</span>
                        <span class="stepIndicator flex-1 pb-8 relative">Security Info</span>
                    </div>

                    <!-- step 1 -->
                    <TransitionGroup tag="div"
                                     name="fade"
                                     mode="out-in">
                        <div :key="'step1'"
                             v-if="currentStep == 0"
                             class="step flex flex-col lg:flex-row mt-10 mb-6 mx-5">
                            <div class="w-full lg:w-1/2 lg:flex lg:items-center hidden">
                                <img class="bg-no-repeat bg-cover bg-center rounded-md"
                                     src="../../../../storage/images/register_page.png"
                                     alt="img">
                            </div>

                            <div class="w-full lg:w-1/2 mt-10 lg:mt-0 text-center">
                                <h1 class="text-xl sm:text-2xl text-center mb-7">Step 1: Personal Information</h1>
                                <div class="flex flex-col mx-auto space-y-6 w-4/5">
                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="name"
                                               name="name"
                                               type="text"
                                               placeholder="First Name"
                                               v-model="form.name"
                                               :customClasses="prefilled ? 'disabled pointer-events-none' : ''" />
                                        <span v-if="step1Errors.name.value"
                                              class="text-rose-600">{{ step1Errors.name.value }}</span>
                                    </div>
                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="surname"
                                               name="surname"
                                               type="text"
                                               placeholder="Last Name"
                                               v-model="form.surname"
                                               :customClasses="prefilled ? 'disabled pointer-events-none' : ''" />
                                        <span v-if="step1Errors.surname.value"
                                              class="text-rose-600">{{ step1Errors.surname.value }}</span>
                                    </div>

                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="date_of_birth"
                                               name="date_of_birth"
                                               type="date"
                                               placeholder="Date of Birth"
                                               v-model="form.date_of_birth" />
                                        <span v-if="step1Errors.date_of_birth.value"
                                              class="text-rose-600">{{ step1Errors.date_of_birth.value }}</span>
                                    </div>
                                    <div class="relative">
                                        <Select id="role_id"
                                                name="role_id"
                                                placeholder="Position"
                                                :options="roles"
                                                v-model="form.role_id"
                                                :customClasses="prefilled ? 'disabled pointer-events-none' : ''" />
                                        <span v-if="step1Errors.role_id.value"
                                              class="text-rose-600">{{ step1Errors.role_id.value }}</span>
                                    </div>

                                    <div class="flex flex-col items-center justify-center w-full"
                                         @dragover.prevent="onDragOver"
                                         @dragleave.prevent="onDragLeave"
                                         @drop.prevent="onDrop">
                                        <span class="">Profile picture (optional)</span>
                                        <label for="dropzone-file"
                                               class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white dark:hover:bg-bray-80 hover:bg-gray-100">
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
                                    </div>

                                    <div class="ml-auto">
                                        <Button intent="greenish"
                                                :loading="loadingNext"
                                                customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-3"
                                                @click="nextPrev(1)"
                                                :rightIcon="ArrowLongRightIcon">Continue</Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div :key="'step2'"
                             v-if="currentStep == 1"
                             class="step flex flex-col lg:flex-row mt-10 mb-6 sm:mx-5">
                            <div class="w-full lg:w-1/2">
                                <img class="bg-no-repeat bg-cover bg-center rounded-md"
                                     src="../../../../storage/images/register_page.png"
                                     alt="img">
                            </div>

                            <div class="w-full lg:w-1/2 mt-10 lg:mt-0 text-center">
                                <h1 class="text-xl sm:text-2xl text-center mb-7">Step 2: Contact Information</h1>
                                <div class="flex flex-col mx-auto space-y-6 w-4/5">
                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="email"
                                               name="email"
                                               type="text"
                                               placeholder="Email"
                                               :customClasses="prefilled ? 'disabled pointer-events-none' : ''"
                                               v-model="form.email" />
                                        <span v-if="step2Errors.email.value"
                                              class="text-rose-600">{{ step2Errors.email.value }}</span>
                                    </div>

                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="phone_number"
                                               name="phone_number"
                                               type="text"
                                               placeholder="Phone Number"
                                               v-model="form.phone_number" />
                                        <span v-if="step2Errors.phone_number.value"
                                              class="text-rose-600">{{ step2Errors.phone_number.value }}</span>
                                    </div>

                                    <div class="mx-auto w-full flex justify-between">
                                        <Button intent="greenish"
                                                customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-1"
                                                @click="nextPrev(-1)"
                                                :leftIcon="ArrowLongLeftIcon">Previous</Button>
                                        <Button intent="greenish"
                                                customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-3"
                                                @click="nextPrev(1)"
                                                :rightIcon="ArrowLongRightIcon">Continue</Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div :key="'step3'"
                             v-if="currentStep == 2"
                             class="step flex flex-col lg:flex-row mt-10 mb-6 sm:mx-5">
                            <div class="w-full lg:w-1/2">
                                <img class="bg-no-repeat bg-cover bg-center rounded-md"
                                     src="../../../../storage/images/register_page.png"
                                     alt="img">
                            </div>

                            <div class="w-full lg:w-1/2 mt-10 lg:mt-0 text-center">
                                <h1 class="text-xl sm:text-2xl text-center mb-7">Step 3: Security Information</h1>
                                <div class="flex flex-col mx-auto space-y-6 w-4/5">
                                    <div class="relative">
                                        <Select id="security_type"
                                                name="security_type"
                                                placeholder="Security Type"
                                                :options="['None', 'Email', 'SMS']"
                                                :default="'None'"
                                                v-model="form.security_type" />
                                        <span v-if="step3Errors.security_type.value"
                                              class="text-rose-600">{{ step3Errors.security_type.value }}</span>
                                    </div>
                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="password"
                                               name="password"
                                               type="password"
                                               placeholder="Password"
                                               v-model="form.password" />
                                        <span v-if="step3Errors.password.value"
                                              class="text-rose-600">{{ step3Errors.password.value }}</span>
                                    </div>

                                    <div class="relative">
                                        <Input autocomplete="off"
                                               id="confirm_password"
                                               name="confirm_password"
                                               type="password"
                                               placeholder="Confirm password"
                                               v-model="confirmPassword" />
                                        <span v-if="step3Errors.confirm_password.value"
                                              class="text-rose-600">{{ step3Errors.confirm_password.value }}</span>
                                    </div>

                                    <div class="mt-2 px-3 py-2 bg-primary-300">
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
                                    <div class="mx-auto flex w-full justify-between">
                                        <Button intent="greenish"
                                                customClasses="sm:min-h-[40px] px-4"
                                                @click="nextPrev(-1)"
                                                :leftIcon="ArrowLongLeftIcon">Previous</Button>
                                        <Button intent="greenish"
                                                customClasses="sm:min-h-[40px] px-4"
                                                id="submit"
                                                @click="register"
                                                :rightIcon="ArrowLongRightIcon">Submit</Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </form>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "@Layouts/Layout.vue";
import Button from "@Components/Button.vue";
import Input from "@Components/Input.vue";
import Select from "@Components/Select.vue";

export default {
    components: { Layout, Button, Input, Select },
};
</script>
<style scoped>
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity .3s;
}

.fade-enter-active {
    transition-delay: .4s;
}
</style>