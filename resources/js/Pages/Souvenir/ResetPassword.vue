<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { message } from "ant-design-vue";

const props = defineProps({
    token: String,
    email: String,
});

const form = useForm({
    email: props.email,
    password: '',
    password_confirmation: '',
    token: props.token,
});

const submit = () => {
    form.post(route('souvenir.reset-password'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => {
            console.log('Password reset failed:', errors);
            message.error({
                content: () => "Password reset failed. Please check the errors.",
                class: 'custom-class',
                style: {
                marginTop: '30vh',
                },
            });
        },
    });
};
</script>

<template>
    <Head title="Reset Password" />
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="flex justify-center items-center pb-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                Reset Password / 重置密碼
            </h2>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email / 電子郵件" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    readonly
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="password" value="New Password / 新密碼" required />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm New Password / 確認新密碼" required />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
            <input type="hidden" v-model="form.token" />
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password / 重置密碼
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>