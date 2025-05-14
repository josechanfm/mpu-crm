<template>
    <inertia-head>
        <title>Login</title>
    </inertia-head>
    <div class="h-screen w-screen bg-slate-100 flex">
        <div class="hidden md:block md:w-1/2 lg:w-2/3 h-full">
            <div class="h-full w-full bg-blue-500"></div>
        </div>
        <div class="max-w-lg w-full p-4 md:p-8 md:py-32 mx-auto flex flex-col justify-center md:justify-start">
            <AuthenticationCardLogo class="mb-10"/>
            <h1 class="text-2xl font-medium mb-10 leading-loose">綜合服務管理系統</h1>
            <a-form ref="loginForm" :model="form" name="LoginForm">
                <a-form-item name="username" :rules="[{required:true,message:'請輸入帳號名稱'}]">
                    <a-input
                        placeholder="Username"
                        v-model:value="form.username"
                        size="large"
                    >
                        <template #prefix>
                            <UserOutlined/>
                        </template>
                    </a-input>
                </a-form-item>
                <InputError class="mt-2" :message="$page.props.errors.username" />

                <a-form-item name="password" :rules="[{required:true,message:'請輸入密碼'}]">
                    <a-input-password 
                        placeholder="Password" size="large" v-model:value="form.password"
                        @keydown.enter="login">
                    >
                        <template #prefix>
                            <LockOutlined/>
                        </template>
                    </a-input-password>
                </a-form-item>
                

                <a-form-item>
                    <a-checkbox name="remember" v-model:checked="form.remember">Remember Me</a-checkbox>
                    <a-checkbox name="local" v-model:checked="form.local">Local Account</a-checkbox>
                    <a href="/" class="float-right">前台</a>
                    <!--                    <inertia-link :href="route('password.request')" class="float-right">Forget Password?</inertia-link>-->
                </a-form-item>

                <a-form-item class="!mt-16">
                    <a-button @click="login" block type="primary" size="large">Login</a-button>
                </a-form-item>
            </a-form>
        </div>
    </div>
</template>

<script>
import { UserOutlined, LockOutlined } from '@ant-design/icons-vue';
import InputError from '@/Components/InputError.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

export default {
    name: "Login",
    components: {
        UserOutlined,
        LockOutlined,
        InputError,
        AuthenticationCardLogo
    },
    data () {
        return {
            form: this.$inertia.form({
                username: '',
                password: '',
                remember: false,
                local:false
            })
        }
    },
    methods: {
        login () {
            console.log(this.form)
            
            // --- FortifyServiceProvider

            this.form.post('/staff/login')
            
        }
    }
}
</script>
