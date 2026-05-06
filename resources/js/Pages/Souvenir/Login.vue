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
                “澳理大小熊”購買專頁<br>MPU Bear Order Form
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
            <div class="mt-4">
                <InputLabel for="confirm" value="Acknoledge / 已知悉" />
                <Checkbox id="confirm" v-model:checked="form.confirm" class="" required
                />
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                <InputError class="mt-2" :message="form.errors.confirm" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in / 登入
                </PrimaryButton>
            </div>
        </form>

        <template #footer>
            <div class="max-w-94 bg-white px-2">
                <ul class="list-disc">
                    <li>
                        對象：澳門理工大學2025/2026學年準畢業生<br>
                        Eligibility: Prospective graduates of Macao Polytechnic University (Academic Year 2025/2026)
                    </li>
                    <li>
                        認購期：由即日起至2026年5月25日 23:00時止(數量有限，售完即止)<br>
                        Subscription Period: From now until 23:00 on 2026/05/25. (Limited quantities available; while stocks last)
                    </li>
                    <li>
                        領取期：2026年6月上旬，以電郵通知為準<br>
                        Pick-up Period: Early June 2026. (Exact details will be notified via email.)
                    </li>
                    <li>
                        每位準畢業生之帳號只能限購1隻<br>
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
