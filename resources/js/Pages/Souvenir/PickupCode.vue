<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    “澳理大小熊”購買專頁<br>
                    MPU Bear Order Form
                </h2>
                <div class="flex justify-end gap-2">
                    <button v-if="user" @click="logout"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center min-w-[100px]">
                        <span>Logout</span>
                        <span class="ml-1 text-red-100">/ 登出</span>
                    </button>
                    <button v-else @click="login"
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm shadow-sm hover:shadow-md flex items-center justify-center min-w-[100px]">
                        <span>Login</span>
                        <span class="ml-1 text-blue-100">/ 登入</span>
                    </button>
                </div>
            </div>
        </template>
        <div class="py-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-5">
                    <div v-if="user">
                        <h3 class="text-lg font-semibold">Student Id: {{ user.netid }}</h3>
                        <h3 class="text-lg font-semibold">Name: {{ user.name }}</h3>
                        <h3 class="text-lg font-semibold">Email: {{ user.email }}</h3>
                    </div>
                    <hr class="my-4"/>
                    <h4 class="text-lg font-semibold">Your Pickup Code:</h4>
                    <div v-if="pickupCode" class="flex flex-col items-center gap-4">
                        <a-qrcode
                            size="256"
                            error-level="H"
                            :value="pickupCode"
                            icon="/storage/images/mpu_logo.png"
                        />
                        <div>{{ pickupCode.length > 10 ? pickupCode.slice(0, 20) + '...' : pickupCode }} </div>
                        <a-button :href="route('souvenir')">Back to Main page</a-button>
                    </div>
            </div>
        </div>
    </BlankLayout>
</template>

<script>
import BlankLayout from '@/Layouts/BlankLayout.vue';
import { ShoppingCartOutlined } from '@ant-design/icons-vue';

export default {
    components: {
        BlankLayout,
        ShoppingCartOutlined,
    },
    props: {
        user: {
            type: Object,
            required: false,
        },
        pickupCode: {
            type: String,
            required: true,
        },
    },
    data() {
        return {};
    },
    methods: {
        login(){
            this.$inertia.get(route('souvenir.login'))
        },
        logout(){
            this.$inertia.post(route('souvenir.logout'), {}, {
                onSuccess: () => {
                    this.isLoggedIn = false; // Update login state
                    this.user = {}; // Clear user data
                },
                onError: (error) => {
                    console.error('Logout error:', error);
                },
            });
        },
    }
};
</script>

<style scoped>
</style>