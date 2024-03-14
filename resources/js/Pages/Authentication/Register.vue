<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { ref, watch, onMounted } from "vue";
import { ArrowLongRightIcon, ArrowLongLeftIcon } from "@heroicons/vue/20/solid";

const form = useForm({
    username: ref(null),
    password: ref(null),
    email: ref(null),
    date_of_birth: ref(null),
    phone_number: ref(null),
    profile_picture: ref(null),
});

const confirmPassword = ref(null);
const errors = ref([]);
const loadingPrev = ref(false);
const loadingNext = ref(false);
var currentTab = 0;

const register = async (e) => {
    // if (!validateForm()) {
    //     return false;
    // }

    var x = document.getElementsByClassName("step");
    if (currentTab >= x.length && !validateForm()) {
        return false;
    } else if (currentTab >= x.length && validateForm()) {
        form.post('/register', {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: () => {
                // Do something on successful form submission
            },
        });
    }
};

watch(() => form.password, (newPassword) => {
    if (newPassword.length >= 8) {
        document.querySelector(".atleast").classList.remove("x-sign");
        document.querySelector(".atleast").classList.add("ok-sign");
    } else {
        document.querySelector(".atleast").classList.remove("ok-sign");
        document.querySelector(".atleast").classList.add("x-sign");
    }

    if (/[A-Z]/.test(newPassword)) {
        document.querySelector(".upper").classList.remove("x-sign");
        document.querySelector(".upper").classList.add("ok-sign");
    } else {
        document.querySelector(".upper").classList.remove("ok-sign");
        document.querySelector(".upper").classList.add("x-sign");
    }

    if (/[a-z]/.test(newPassword)) {
        document.querySelector(".lower").classList.remove("x-sign");
        document.querySelector(".lower").classList.add("ok-sign");
    } else {
        document.querySelector(".lower").classList.remove("ok-sign");
        document.querySelector(".lower").classList.add("x-sign");
    }

    if (/[0-9]/.test(newPassword)) {
        document.querySelector(".number").classList.remove("x-sign");
        document.querySelector(".number").classList.add("ok-sign");
    } else {
        document.querySelector(".number").classList.remove("ok-sign");
        document.querySelector(".number").classList.add("x-sign");
    }
});

watch(confirmPassword, () => {
    if (form.password !== confirmPassword.value) {
        document.getElementById("passwordsDontMatch").classList.remove("hidden");
    } else {
        document.getElementById("passwordsDontMatch").classList.add("hidden");
    }
});

const validEmail = (email) => {
    var validRegex =
        /^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@[a-zA-Z0-9]+(?:\.[a-zA-Z0-9]+)*$/;
    return email.match(validRegex);
};

const checkForm = (e) => {
    errors.value = [];
    if (!form.username) {
        errors.value.push("Username required.");
    }
    if (!form.email) {
        errors.value.push("Email required.");
    } else if (!validEmail(form.email)) {
        errors.value.push("Valid email required.");
    }
    if (!form.password) {
        errors.value.push("Password required.");
    }
    if (!form.date_of_birth) {
        errors.value.push("Birth date required.");
    }
    if (!form.phone_number) {
        errors.value.push("Phone number required.");
    }
    if (!form.profile_picture) {
        errors.value.push("Profile picture required.");
    }

    if (!errors.value.length) {
        return true;
    }

    e.preventDefault();
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    form.profile_picture = file;
};

function showTab(n) {
    var x = document.getElementsByClassName("step");
    x.item(n).style.display = "flex";
    fixStepIndicator(n)
}

function nextPrev(n) {
    var x = document.getElementsByClassName("step");

    if (n == 1 && !validateForm()) {
        return false;
    }
    n == 1 ? loadingNext.value = true : loadingPrev.value = true;

    if (currentTab + n >= x.length) {
        currentTab = currentTab + n;
        register();
        return true;
    } else {
        x[currentTab].style.display = "none";
    }
    currentTab = currentTab + n;

    n == 1 ? loadingNext.value = false : loadingPrev.value = false;
    showTab(currentTab);
}

function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("step");
    y = x[currentTab].getElementsByTagName("input");

    for (i = 0; i < y.length; i++) {
        if (y[i].value == "") {
            y[i].className += " invalid";
            valid = false;
        }
    }

    if (valid && !document.getElementsByClassName("stepIndicator")[currentTab].classList.contains("finish")) {
        document.getElementsByClassName("stepIndicator")[currentTab].className += " finish";
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

onMounted(() => {
    showTab(currentTab);
})

</script>
<template>
    <Layout>
        <div class="my-12 mx-4 xl:mx-auto xl:w-4/5 text-sm sm:text-base">
            <div class="flex flex-col auth-width bg-white m-auto shadow-lg text-black">
                <form @submit.prevent="register"
                      id="signUpForm">
                    <!-- start step indicators -->
                    <div class="form-header flex gap-3 pt-4 pb-6 border-b text-xs sm:text-base text-center">
                        <span class="stepIndicator flex-1 pb-8 relative">Personal Info</span>
                        <span class="stepIndicator flex-1 pb-8 relative">Contanct Info</span>
                        <span class="stepIndicator flex-1 pb-8 relative">Security Info</span>
                    </div>

                    <!-- step 1 -->
                    <div class="step flex flex-col lg:flex-row mt-10 mb-6 sm:mx-5">
                        <div class="w-full lg:w-1/2">
                            <img class="bg-no-repeat bg-cover bg-center"
                                 src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg"
                                 alt="img">
                        </div>

                        <div class="w-full lg:w-1/2 mt-10 lg:mt-0 text-center">
                            <h1 class="text-2xl sm:text-3xl text-center mb-7">Step 1: Personal Information</h1>
                            <div class="flex flex-col mx-auto space-y-6 w-4/5">
                                <div class="relative">
                                    <input autocomplete="off"
                                           id="firstname"
                                           name="firstname"
                                           type="text"
                                           class="pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                           placeholder="First Name" />
                                    <label for="firstname"
                                           class="absolute left-2 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-5 peer-focus:text-gray-600 peer-focus:text-sm">First
                                        Name</label>
                                </div>
                                <div class="relative">
                                    <input autocomplete="off"
                                           id="lastname"
                                           name="lastname"
                                           type="text"
                                           class="pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                           placeholder="Last Name" />
                                    <label for="lastname"
                                           class="absolute left-2 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-5 peer-focus:text-gray-600 peer-focus:text-sm">Last
                                        Name</label>
                                </div>

                                <div class="grid grid-cols-3 space-x-5">
                                    <div class="relative">
                                        <input autocomplete="off"
                                               id="month"
                                               name="month"
                                               type="text"
                                               class="pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                               placeholder="Month" />
                                        <label for="month"
                                               class="absolute left-2 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-5 peer-focus:text-gray-600 peer-focus:text-sm">Month</label>
                                    </div>

                                    <div class="relative">
                                        <input autocomplete="off"
                                               id="day"
                                               name="day"
                                               type="text"
                                               class="pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                               placeholder="Day" />
                                        <label for="day"
                                               class="absolute left-2 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-5 peer-focus:text-gray-600 peer-focus:text-sm">Day</label>
                                    </div>

                                    <div class="relative">
                                        <input autocomplete="off"
                                               id="year"
                                               name="year"
                                               type="text"
                                               class="pl-2 peer placeholder-transparent h-10 w-full border-b-2 border-gray-300 text-gray-900 focus:outline-none focus:borer-rose-600"
                                               placeholder="Year" />
                                        <label for="year"
                                               class="absolute left-2 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-440 peer-placeholder-shown:top-2 transition-all peer-focus:-top-5 peer-focus:text-gray-600 peer-focus:text-sm">Year</label>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <Button intent="primary"
                                            :loading="loadingNext"
                                            customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-3"
                                            id="nextBtn"
                                            @click="nextPrev(1)"
                                            :rightIcon="ArrowLongRightIcon">Continue</Button>
                                </div>
                            </div>
                        </div>

                        <div class="errors mb-4">
                            <p class="text-red-500 pb-1 hidden"
                               id="passwordsDontMatch">
                                Passwords don't match.
                            </p>
                            <p v-for="error in errors"
                               class="text-red-500 pb-1">
                                {{ error }}
                            </p>
                        </div>
                    </div>

                    <!-- step 2 -->
                    <div class="step">

                        <div class="mx-auto flex justify-between w-4/5">
                            <Button intent="primary"
                                    customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-1"
                                    id="prevBtn"
                                    @click="nextPrev(-1)"
                                    :leftIcon="ArrowLongLeftIcon">Previous</Button>
                            <Button intent="primary"
                                    customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-3"
                                    id="nextBtn"
                                    @click="nextPrev(1)"
                                    :rightIcon="ArrowLongRightIcon">Continue</Button>
                        </div>
                    </div>

                    <!-- step 3 -->
                    <div class="step">

                        <div class="mx-auto flex justify-between w-4/5">
                            <Button intent="primary"
                                    customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-1"
                                    id="prevBtn"
                                    @click="nextPrev(-1)"
                                    :leftIcon="ArrowLongLeftIcon">Previous</Button>
                            <Button intent="primary"
                                    customClasses="sm:min-h-[40px] px-4 space-x-0 sm:space-x-3"
                                    id="nextBtn"
                                    @click="nextPrev(1)"
                                    :rightIcon="ArrowLongRightIcon">Submit</Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>
<script>
import Layout from "@Layouts/Layout.vue";
import Button from "@Components/Button.vue";

export default {
    components: { Layout, Button },
};
</script>