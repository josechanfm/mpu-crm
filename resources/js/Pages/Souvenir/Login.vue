<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { message } from "ant-design-vue";

defineProps({

});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const forgotForm = useForm({
    email: '',
});

const showForgot = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('souvenir.loginVarify'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            console.log('Login failed:', errors);
            message.error({
                content: () => "Username or password is incorrect, or you don't have permission",
                class: 'custom-class',
                style: {
                marginTop: '30vh',
                },
            });
        },
    });
};

const sendResetLink = () => {
    forgotForm.post(route('souvenir.sendResetPasswordToEmail'), {
        onSuccess: () => {
            message.success({
                content: () => 'If the email is registered, a password reset link has been sent. / 如果電子郵件已註冊，已發送密碼重置連結。',
                class: 'custom-class',
                style: {
                    marginTop: '30vh',
                },
            });
            forgotForm.reset();
            showForgot.value = false;
        },
        onError: (errors) => {
            console.log('Reset request failed:', errors);
        },
    });
};
</script>

<template>
    <Head title="Log in" />
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="flex justify-center items-center pb-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                “澳理大小熊”購買專頁<br>MPU Bear Order Form
            </h2>
        </div>

        <form v-if="!showForgot" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email / 電子郵件" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="password" value="Password / 密碼" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="mt-4">
                <InputLabel for="acknowledge" value="" />
                <Checkbox id="acknowledge" v-model:checked="form.acknowledge" class="" required
                />
                <span class="ml-2 text-sm text-gray-600">I agree to the terms and conditions / 我同意以下條款及細則</span>
                <InputError class="mt-2" :message="form.errors.acknowledge" />
            </div>
            <div class="flex justify-between items-center mt-4">
                <button type="button" @click.prevent="showForgot = true" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Forgot password / 忘記密碼
                </button>
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in / 登入
                </PrimaryButton>
            </div>
        </form>

        <form v-else @submit.prevent="sendResetLink">
            <div class="mb-4 text-sm text-gray-600">
                Please enter your login email. The reset link will be sent automatically to your registered email. / 請輸入您的登入電子郵件。重置連結將自動發送到您註冊的電子郵件帳戶。
            </div>
            <div>
                <InputLabel for="forgotEmail" value="Login Email / 登入電子郵件" required />
                <TextInput
                    id="forgotEmail"
                    type="email"
                    v-model="forgotForm.email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="forgotForm.errors.email" />
            </div>
            <div class="flex justify-between items-center mt-4">
                <button type="button" @click.prevent="showForgot = false" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Back to login / 返回登入
                </button>
                <PrimaryButton :class="{ 'opacity-25': forgotForm.processing }" :disabled="forgotForm.processing">
                    Send Reset Link / 發送重置連結
                </PrimaryButton>
            </div>
        </form>

        <template #footer>
            <div class="max-w-94 bg-white px-2">
                <ul class="list-disc">
                    <li>
                        對象：澳門理工大學2025/2026學年應屈畢業生<br>
                        Eligibility: Prospective graduates of Macao Polytechnic University (Academic Year 2025/2026)
                    </li>
                    <li>
                        購買期：由即日起至2026年6月1日 23:00時止(數量有限，售完即止)<br>
                        Subscription Period: From now until 23:00 on 2026/06/01. (Limited quantities available; while stocks last)
                    </li>
                    <li>
                        領取期：2026年6月5、8、9、10日(不設補領或寄送)，以電郵通知為準<br>
                        Pick-up Period: 5, 8, 9, 10 June 2026. (no pick-up re-arrangement nor shipping services will be offered.)
                    </li>
                    <li>
                        每位應屆畢業生之帳號只能限購1隻<br>
                        Limited to one bear per prospective graduate account
                    </li>
                    <li>
                        付款後，請點開收據及領取二維碼頁面，保存支付憑證<br>
                        After payment, please access the page displaying your receipt and pick-up QR code, and save this proof for collection
                    </li>
                </ul>
            </div>
            
        </template>

    </AuthenticationCard>


</template>
