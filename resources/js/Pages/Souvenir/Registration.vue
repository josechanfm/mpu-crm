<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { message, Modal  } from "ant-design-vue";
import { ref, reactive, watch, onUnmounted, computed } from 'vue'; // Add computed
import axios from 'axios';
import Dropdown from '../../Components/Dropdown.vue';

const props = defineProps({
    uuid: String,
    user: Object,
    faculties: Object,
    degrees: Object
});

const form = reactive({
    uuid: props.uuid,
    netId: props.user.netid,
    fullName: props.user.name,
    phone: props.user.phone,
    faculty: props.user.faculty_code,
    degree: props.user.degree_code,
    graduationYear: props.user.grad_year,
    email: props.user.email,
    password: null,
    password_confirmation: '',
    terms: false,
});

const errors = ref({});
const processing = ref(false);
const showModal = ref(false);
const verificationCode = ref('');
const netIdError = ref(''); // Add this
let netIdTimeout;

// Add computed property to check if netId is valid for submission
const isNetIdValid = computed(() => {
    // If user already has netId (disabled field), it's considered valid
    if (props.user.netid) return true;
    
    // Check if there's an error message
    if (errors.value.netId || netIdError.value) return false;
    
    // Check if netId meets requirements
    const netId = form.netId;
    if (!netId || netId.length !== 8) return false;
    if (netId[0].toUpperCase() !== 'P') return false;
    
    return true;
});

// Also disable submit if any required fields are invalid
const isFormValid = computed(() => {
    return isNetIdValid.value && 
           form.fullName && 
           form.phone && 
           form.faculty && 
           form.degree && 
           form.graduationYear && 
           form.email && 
           form.password && 
           form.password_confirmation === form.password &&
           form.terms;
});

const submit = async () => {
    // Prevent submission if netId is invalid
    if (!isNetIdValid.value) {
        message.error('Please fix the Student ID error before submitting');
        return;
    }
    
    processing.value = true;
    errors.value = {};
    try {
        const response = await axios.post(route('souvenir.register'), form);
        console.log('register response', response.data);
        message.success(response.data.message);
        showModal.value = true;
        verificationCode.value = '';
    } catch (error) {
        if (error.response && error.response.data.errors) {
            errors.value = Object.fromEntries(
                Object.entries(error.response.data.errors).map(([key, value]) => [key, Array.isArray(value) ? value[0] : value])
            );
            message.error('Registration failed. Please check the errors.');
        } else if (error.response && error.response.data.message) {
            message.error(error.response.data.message);
        } else {
            message.error('An unexpected error occurred. Please try again.');
        }
    } finally {
        processing.value = false;
    }
};

const verifyEmail = async () => {
    try {
        const response = await axios.post(route('souvenir.verifyEmail'), {
            uuid: props.uuid,
            code: verificationCode.value,
        });
        console.log(response.data);
        message.success(response.data.message);
        showModal.value = false;
        // Redirect to login or something
        window.location.href = route('souvenir.login');
    } catch (error) {
        if (error.response && error.response.data.message) {
            message.error(error.response.data.message);
        } else {
            message.error('Verification failed.');
        }
    }
};

const redirectToSouvenir = () => {
    window.location.href = '/souvenir';
};

const validateNetId = async (netId) => {
    console.log('to validate netid', netId);
    try {
        const response = await axios.post(route('souvenir.verifyNetid'), { netId });
        console.log('Net ID validation response:', response.data);
        if (response.data.isValid) {
            if (response.data.url) {
                Modal.info({
                    title: 'Student ID Verified',
                    content: response.data.message || 'Your Student ID is valid. Do you want to proceed to the next step?',
                    okText: 'Yes, Continue',
                    cancelText: 'Stay Here',
                    onOk: () => {
                        window.location.href = response.data.url;
                    },
                    onCancel: () => {
                        message.info('You can continue filling the form.');
                    }
                });
                return;
            }
            errors.value.netId = '';
            netIdError.value = '';
        } else {
            errors.value.netId = response.data.message || 'Invalid Student ID. Please enter a valid one.';
            netIdError.value = response.data.message || 'Invalid Student ID. Please enter a valid one.';
        }
    } catch (error) {
        errors.value.netId = 'Error validating Student ID. Please try again.';
        netIdError.value = 'Error validating Student ID. Please try again.';
    }
};

const handleNetIdBlur = () => {
    const newNetId = form.netId;
    
    // Clear previous errors
    errors.value.netId = '';
    netIdError.value = '';
    
    // Check if netId is null or empty
    if (!newNetId || newNetId.trim() === '') {
        errors.value.netId = 'Student ID is required / 學生編號為必填項';
        netIdError.value = 'Student ID is required / 學生編號為必填項';
        return;
    }
    
    // Check length
    if (newNetId.length !== 8) {
        errors.value.netId = 'Student ID should be 8 characters long / 學生編號應為8個字符';
        netIdError.value = 'Student ID should be 8 characters long / 學生編號應為8個字符';
        return;
    }
    
    // Check first character
    if (newNetId[0].toUpperCase() !== 'P') {
        errors.value.netId = 'Student ID should start with "P" / 學生編號應以"P"開頭';
        netIdError.value = 'Student ID should start with "P" / 學生編號應以"P"開頭';
        return;
    }
    
    // If all basic validations pass, validate against backend
    if (newNetId.length === 8 && newNetId[0].toUpperCase() === 'P') {
        validateNetId(newNetId);
    }
};
</script>

<template>
    <Head title="Register" />
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="flex justify-center items-center pb-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                User Registration / 用戶註冊
            </h2>
        </div>
        
        <!-- Add a validation summary -->
        <div v-if="!isNetIdValid && form.netId" class="mb-4 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
            ⚠️ Please fix the Student ID error before submitting
        </div>
        
        <form @submit.prevent="submit">

            <div>
                <InputLabel for="netId" value="Student ID of MPU / 學生編號" required />
                <a-input
                    type="input"
                    id="netId"
                    v-model:value="form.netId"
                    class="mt-1 block w-full"
                    :class="{ 'border-red-500': errors.netId }"
                    required
                    autofocus
                    :disabled="user.netid==null?false:true"
                    @blur="handleNetIdBlur"
                />
                <InputError class="mt-2" :message="errors.netId" />
            </div>
            <div class="mt-4">
                <InputLabel for="fullName" value="Full Name / 全名" required />
                <TextInput
                    id="fullName"
                    v-model="form.fullName"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="errors.fullName" />
            </div>
            <div class="mt-4">
                <InputLabel for="phone" value="Contact Number / 聯絡電話" required />
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="errors.phone" /> 
            </div>
            <div class="mt-4">
                <InputLabel for="faculty" value="Academic Unit / 學術單位" required />
                <a-select
                    v-model:value="form.faculty"
                    style="width: 100%"
                    placeholder="Select Academic Unit"
                    :options="faculties"
                    :disabled="user.faculty_code==null?false:true"
                />
                <InputError class="mt-2" :message="errors.faculty" />
            </div>
            <div class="mt-4">
                <InputLabel for="degree" value="Degree / 學位" required />
                <a-select
                    v-model:value="form.degree"
                    style="width: 100%"
                    placeholder="Select Degree"
                    :options="degrees"
                    :disabled="user.degree_code==null?false:true"
                />
                <InputError class="mt-2" :message="errors.degree" />
            </div>
            <div class="mt-4">
                <InputLabel for="graduationYear" value="Graduation Year / 畢業年份" required />
                <a-input
                    type="input"
                    id="graduationYear"
                    v-model:value="form.graduationYear"
                    class="mt-1 block w-full"
                    :disabled="user.grad_year==null?false:true"
                    required
                />
                <InputError class="mt-2" :message="errors.graduationYear" />
            </div>
            <div class="mt-4">
                <InputLabel for="email" value="Email / 電子郵件" required />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                />
                <div>(個人電郵，而非本校學生電郵 / Personal Email, not MPU Email)</div>
                <InputError class="mt-2" :message="errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="password" value="Password / 密碼" required />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="on"
                    required
                    @input="errors.password = ''"
                />
                <InputError class="mt-2" :message="errors.password" />
            </div>
            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password / 確認密碼" required />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="on"
                    required
                    @input="errors.password_confirmation = ''"
                />
                <InputError class="mt-2" :message="errors.password_confirmation" />
            </div>
            <div class="mt-4">
                <InputLabel for="terms" value="I verify and confirm that / 本人確認" required />
                <Checkbox id="terms" v-model:checked="form.terms" class="" required />
                <span class="ml-2 text-sm text-gray-600">The above information is accurate. I accept full responsibility for any missed updates due to incorrect contact details. I will proactively check collection arrangements with the relevant department. / 上述資料填寫無誤。倘因聯絡電郵及電話填寫有誤而導致最新消息無法通知本人，一切責任由本人承擔。本人亦應主動並提前向有關單位了解領取安排。</span>
                <InputError class="mt-2" :message="errors.terms" />
            </div>
            <div class="flex items-center justify-between mt-4 gap-2">
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-100"
                    @click="redirectToSouvenir"
                >
                    Close / 關閉
                </button>
                <PrimaryButton 
                    class="ml-4" 
                    :class="{ 'opacity-25': processing || !isNetIdValid }" 
                    :disabled="processing || !isNetIdValid"
                >
                    Register / 註冊
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>

    <Modal v-model:open="showModal" title="Email Verification" @ok="verifyEmail" okText="Verify" cancelText="Cancel">
        <p>Please enter the 6-digit verification code sent to your email.</p>
        <TextInput
            v-model="verificationCode"
            placeholder="Enter code"
            class="mt-2"
        />
    </Modal>
</template>