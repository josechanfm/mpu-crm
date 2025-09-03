<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
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
    netid: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('souvenir.loginChecker'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            console.log('Login failed:', errors);
            // Optionally, you can set a form error message
            // form.errors=errors;
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
</script>

<template>
    <Head title="Log in" />
    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="flex justify-center items-center pb-10">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                預購畢業生限量專屬紀念品<br>
                Pre-order of Graduation Gifts
            </h2>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="netid" value="Student ID / 學生編號" />
                <TextInput
                    id="netid"
                    v-model="form.netid"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.netid" />
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
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in / 登入
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
