<template>
    <BlankLayout title="Souvenir Order">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    預購畢業生限量專屬紀念品<br>
                    Pre-order of Graduation Gifts
                </h2>
                <div class="flex justify-end gap-2">
                    <div v-if="user" @click="logout">Logout<br>登出</div>
                    <div v-else @click="login">Login<br>登入</div>
                </div>
            </div>
        </template>
        <div class="py-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-5">
                    <div v-if="user">
                        <h3 class="text-lg font-semibold">{{ user.netid }}</h3>
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
                    console.log('Logout successful');
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