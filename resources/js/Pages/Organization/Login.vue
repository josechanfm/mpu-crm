<template>
    <inertia-head>
        <title>Login</title>
    </inertia-head>
    <div class="h-screen w-screen bg-slate-100 flex">
        <div class="hidden md:block md:w-1/2 lg:w-2/3 h-full">
            <div class="h-full w-full bg-blue-500"></div>
        </div>
        <div class="max-w-lg w-full p-4 md:p-8 md:py-32 mx-auto flex flex-col justify-center md:justify-start">
            <h1 class="text-2xl font-medium mb-0 leading-loose">第一國際教育集團有限公司</h1>
            <p class="text-base mb-20 text-slate-600">課程管理系統</p>
            <p class="font-medium text-xl mb-20">系統登入</p>
            <a-form>
                <a-form-item name="email">
                    <a-input
                        placeholder="Email"
                        v-model:value="form.email"
                        size="large"
                    >
                        <template #prefix>
                            <UserOutlined/>
                        </template>
                    </a-input>
                </a-form-item>

                <a-form-item name="password">
                    <a-input-password placeholder="Password" size="large" v-model:value="form.password"
                                      @keydown.enter="login">
                        >
                        <template #prefix>
                            <LockOutlined/>
                        </template>
                    </a-input-password>
                </a-form-item>

                <a-form-item>
                    <a-checkbox name="remember" v-model:checked="form.remember">Remember Me</a-checkbox>

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
import { UserOutlined, LockOutlined } from '@ant-design/icons-vue'
export default {
    name: "Login",
    components: {
        UserOutlined,
        LockOutlined
    },
    setup() {
        const rules = {
            email: [
                {required: true, message: '请输入邮箱'},
                {type: 'email', message: '请输入正确的邮箱'}
            ],
            password: [
                {required: true, message: '请输入密码'}
            ]
        }

        return { rules }
    },
    data () {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false
            })
        }
    },
    methods: {
        login () {
            // console.log(this.form)
            
            // --- FortifyServiceProvider
            this.form.post('/manage/login')
        }
    }
}
</script>
